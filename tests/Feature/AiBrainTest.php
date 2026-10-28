<?php

use App\Models\HomeContent;
use App\Models\User;
use App\Support\AiBrain;
use App\Support\AiConnection;
use App\Support\AiProvider;
use App\Support\ChatLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->brainDir = sys_get_temp_dir().'/cv-brain-'.uniqid();

    // The migrations copy the starting chat content into the database; start each test without it.
    HomeContent::whereIn('section', ['ai_chat', 'chat_faq', 'offer_popup'])->delete();
    config([
        'aichat.storage_path' => $this->brainDir,
        'services.ai_provider' => 'gemini',
        'services.gemini.key' => 'g-test',
        'services.gemini.model' => 'test-model',
    ]);
});

afterEach(function () {
    File::deleteDirectory($this->brainDir);
});

function geminiSays(string $text): void
{
    Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => $text]]]]]])]);
}

function adminUser(): User
{
    return User::factory()->create(['is_admin' => true]);
}

test('knowledge is kept in a file: add, change, switch off and delete', function () {
    $entry = AiBrain::add('Check-in & policies', 'Check-in is from 2 PM.');
    AiBrain::add('Dining', 'Breakfast is served from 7 to 10 AM.');

    expect(AiBrain::counts())->toBe(['total' => 2, 'active' => 2]);
    expect(file_exists($this->brainDir.'/knowledge.json'))->toBeTrue();

    expect(AiBrain::update($entry['id'], 'Check-in & policies', 'Check-in is from 3 PM.', true))->toBeTrue();
    expect(AiBrain::promptText())->toContain('Check-in is from 3 PM.')->not->toContain('2 PM');

    AiBrain::toggle($entry['id']);
    expect(AiBrain::promptText())->not->toContain('Check-in')->toContain('Breakfast');

    expect(AiBrain::delete($entry['id']))->toBeTrue();
    expect(AiBrain::counts()['total'])->toBe(1);
});

test('the prompt text is grouped by topic and never cut off mid list', function () {
    AiBrain::add('Dining', 'Dining fact.');
    AiBrain::add('Rooms & villas', 'Villa fact.');

    $text = AiBrain::promptText();

    expect(strpos($text, 'ROOMS & VILLAS'))->toBeLessThan(strpos($text, 'DINING'));
    expect(AiBrain::promptText(10))->toBe('');
    expect(AiBrain::promptText(15))->toBe("ROOMS & VILLAS\n- Villa fact.");
});

test('unknown topics become Other and html is stripped', function () {
    $entry = AiBrain::add('Nonsense', '<b>Pets</b> are welcome.');

    expect($entry['topic'])->toBe('Other');
    expect($entry['text'])->toBe('Pets are welcome.');
});

test('the chat uses the knowledge, hides the unsure marker and saves the question', function () {
    AiBrain::add('Check-in & policies', 'Check-in is from 2 PM.');
    geminiSays('Sorry, I do not have that detail. Please ask our team. [[UNSURE]]');

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Do you have a gym?']]])
        ->assertOk()
        ->assertJson(['reply' => 'Sorry, I do not have that detail. Please ask our team.']);

    Http::assertSent(fn ($request) => str_contains($request['systemInstruction']['parts'][0]['text'], 'Check-in is from 2 PM.'));

    $rows = ChatLog::recent();
    expect($rows)->toHaveCount(1);
    expect($rows[0]['question'])->toBe('Do you have a gym?');
    expect($rows[0]['unsure'])->toBeTrue();
    expect(ChatLog::needsKnowledgeCount())->toBe(1);

    ChatLog::markReviewed($rows[0]['id']);
    expect(ChatLog::needsKnowledgeCount())->toBe(0);
});

test('only admins can open AI Brain', function () {
    $this->get('/admin/ai-brain')->assertRedirect(route('admin.login'));

    $this->actingAs(User::factory()->create(['is_admin' => false]))->get('/admin/ai-brain')->assertForbidden();
});

test('the AI Brain page shows what the AI knows and what guests asked', function () {
    AiBrain::add('Dining', 'Breakfast is served from 7 to 10 AM.');
    ChatLog::record('Do you allow pets?', 'Please ask our team.', 'ai', true, 'abc12345');

    $this->actingAs(adminUser())->get('/admin/ai-brain')
        ->assertOk()
        ->assertSee('AI Brain')
        ->assertSee('Teach the AI');

    $this->actingAs(adminUser())->get('/admin/ai-brain?tab=knowledge')->assertOk()->assertSee('Breakfast is served from 7 to 10 AM.');
    $this->actingAs(adminUser())->get('/admin/ai-brain?tab=questions')->assertOk()->assertSee('Do you allow pets?')->assertSee('Could not answer');
});

test('an admin can add, switch off, edit and delete knowledge by hand', function () {
    $admin = adminUser();

    $this->actingAs($admin)->post('/admin/ai-brain/entries', ['topic' => 'Dining', 'text' => 'The restaurant opens at 6 PM.'])
        ->assertRedirect(route('admin.ai-brain.index', ['tab' => 'knowledge']));

    $id = AiBrain::all()[0]['id'];

    $this->actingAs($admin)->put("/admin/ai-brain/entries/{$id}", ['topic' => 'Dining', 'text' => 'The restaurant opens at 5 PM.']);
    expect(AiBrain::all()[0]['text'])->toBe('The restaurant opens at 5 PM.');

    $this->actingAs($admin)->post("/admin/ai-brain/entries/{$id}/toggle");
    expect(AiBrain::all()[0]['active'])->toBeFalse();

    $this->actingAs($admin)->delete("/admin/ai-brain/entries/{$id}");
    expect(AiBrain::all())->toBe([]);
});

test('teaching turns plain words into knowledge cards and nothing is saved until the owner agrees', function () {
    geminiSays("```json\n".json_encode([
        'entries' => [
            ['topic' => 'Check-in & policies', 'text' => 'Check-in is from 2 PM and check-out is until 11 AM.'],
            ['topic' => 'Made up topic', 'text' => 'Airport transfers can be arranged on request.'],
        ],
        'note' => 'No price was given for transfers.',
    ])."\n```");

    $admin = adminUser();

    $this->actingAs($admin)->postJson('/admin/ai-brain/teach', ['message' => 'Check in 2pm, checkout 11am. Airport transfer available.'])
        ->assertOk()
        ->assertJsonPath('entries.0.topic', 'Check-in & policies')
        ->assertJsonPath('entries.1.topic', 'Other')
        ->assertJsonPath('note', 'No price was given for transfers.');

    expect(AiBrain::all())->toBe([]);

    $this->actingAs($admin)->postJson('/admin/ai-brain/drafts', ['entries' => [['topic' => 'Dining', 'text' => 'Breakfast is served until 10 AM.']]])
        ->assertOk()->assertJson(['saved' => 1]);

    expect(AiBrain::counts()['total'])->toBe(1);
});

test('teaching needs the AI to be connected', function () {
    config(['services.gemini.key' => null, 'services.openai.key' => null, 'services.anthropic.key' => null]);

    $this->actingAs(adminUser())->postJson('/admin/ai-brain/teach', ['message' => 'Check-in is at 2 PM.'])->assertStatus(503);
});

test('the test chat answers like the website and flags what the AI is unsure about', function () {
    AiBrain::add('Check-in & policies', 'Check-in is from 2 PM.');
    geminiSays('I am not sure about that. [[UNSURE]]');

    $this->actingAs(adminUser())->postJson('/admin/ai-brain/test', ['messages' => [['role' => 'user', 'content' => 'Is there a gym?']]])
        ->assertOk()
        ->assertJson(['reply' => 'I am not sure about that.', 'unsure' => true]);

    Http::assertSent(fn ($request) => str_contains($request['systemInstruction']['parts'][0]['text'], 'Check-in is from 2 PM.'));
    expect(ChatLog::recent())->toBe([]);
});

test('the AI Brain menu link shows how many questions wait for an answer', function () {
    ChatLog::record('Do you allow pets?', 'Please ask our team.', 'ai', true, 'abc12345');

    $this->actingAs(adminUser())->get('/admin')->assertOk()->assertSee('AI Brain');
});

test('a key saved in the admin is stored encrypted and wins over the .env file', function () {
    config(['services.gemini.key' => 'env-key-from-dotenv']);

    expect(AiProvider::key())->toBe('env-key-from-dotenv')->and(AiProvider::source())->toBe('env');

    AiConnection::save('gemini', "  'AIzaSy-NEW-KEY-123456'  ", 'gemini-test-model');

    expect(AiProvider::key())->toBe('AIzaSy-NEW-KEY-123456');
    expect(AiProvider::model())->toBe('gemini-test-model');
    expect(AiProvider::source())->toBe('admin');
    expect(AiConnection::mask(AiProvider::key()))->toBe('•••• •••• 3456');
    expect(file_get_contents($this->brainDir.'/connection.json'))->not->toContain('AIzaSy-NEW-KEY-123456');

    AiConnection::remove('gemini');

    expect(AiProvider::key())->toBe('env-key-from-dotenv');
});

test('an empty key field keeps the saved key', function () {
    AiConnection::save('openai', 'sk-first-key-0001', null);
    AiConnection::save('openai', '', 'some-model');

    expect(AiConnection::key('openai'))->toBe('sk-first-key-0001');
    expect(AiProvider::name())->toBe('openai');
});

test('an admin can paste a new key and it is checked, and the page never shows the key', function () {
    $admin = adminUser();
    config(['services.gemini.key' => null]);
    geminiSays('OK');

    $this->actingAs($admin)->post('/admin/ai-brain/connection', ['provider' => 'gemini', 'api_key' => 'AIzaSy-SECRET-VALUE-9876', 'model' => ''])
        ->assertRedirect(route('admin.ai-brain.index', ['tab' => 'connection', 'check' => 1]));

    expect(AiConnection::key('gemini'))->toBe('AIzaSy-SECRET-VALUE-9876');

    $this->actingAs($admin)->get('/admin/ai-brain?tab=connection')
        ->assertOk()
        ->assertSee('9876')
        ->assertDontSee('AIzaSy-SECRET-VALUE-9876');

    $this->actingAs($admin)->postJson('/admin/ai-brain/connection/check')
        ->assertOk()
        ->assertJsonPath('message', 'Connected. Gemini (test-model) is answering.');
});

test('saving needs a key when there is none anywhere', function () {
    config(['services.gemini.key' => null, 'services.openai.key' => null, 'services.anthropic.key' => null]);

    $this->actingAs(adminUser())->post('/admin/ai-brain/connection', ['provider' => 'gemini', 'api_key' => ''])
        ->assertSessionHasErrors('api_key');
});

test('a rejected key raises a warning that goes away when the key is replaced', function () {
    config(['services.gemini.key' => 'old-expired-key']);
    Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['error' => ['code' => 400, 'message' => 'API key expired. Please renew the API key.']], 400)]);

    $admin = adminUser();

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi there']]])->assertStatus(502);

    expect(AiConnection::needsAttention())->toBeTrue();

    $this->actingAs($admin)->get('/admin/ai-brain')->assertOk()->assertSee('The AI needs attention');
    $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('The AI key needs to be renewed');

    AiConnection::save('gemini', 'brand-new-working-key', null);

    expect(AiConnection::needsAttention())->toBeFalse();

    $this->actingAs($admin)->get('/admin/ai-brain')->assertOk()->assertDontSee('The AI needs attention');
});

test('a key with no credit left is reported as a billing problem', function () {
    config(['services.openai.key' => 'sk-test', 'services.ai_provider' => 'openai']);
    Http::fake(['api.openai.com/*' => Http::response(['error' => ['code' => 'insufficient_quota', 'message' => 'You exceeded your current quota, please check your plan and billing details.']], 429)]);

    $this->actingAs(adminUser())->postJson('/admin/ai-brain/connection/check')
        ->assertStatus(502)
        ->assertJsonPath('message', fn ($message) => str_contains($message, 'no credit left'));

    expect(AiConnection::needsAttention())->toBeTrue();
});

test('removing the saved key switches back to the .env key', function () {
    config(['services.gemini.key' => 'env-key']);
    AiConnection::save('gemini', 'saved-key-in-admin', null);

    $this->actingAs(adminUser())->delete('/admin/ai-brain/connection')->assertRedirect();

    expect(AiProvider::key())->toBe('env-key')->and(AiProvider::source())->toBe('env');
});
