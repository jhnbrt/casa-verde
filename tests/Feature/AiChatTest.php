<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

// Keep the saved questions of these tests out of the real storage folder.
beforeEach(function () {
    config(['aichat.storage_path' => sys_get_temp_dir().'/cv-chat-'.uniqid()]);

    // The migrations copy the starting chat content into the database; start each test without it.
    HomeContent::whereIn('section', ['ai_chat', 'chat_faq', 'offer_popup'])->delete();
});

function chatBrain(array $overrides = []): HomeContent
{
    return HomeContent::create($overrides + [
        'section' => 'ai_chat',
        'title' => 'Casa Verde Assistant',
        'description' => 'Always mention the sunsets.',
        'sort_order' => 1,
        'active' => true,
    ]);
}

test('the assistant answers using the brain written in the admin', function () {
    config(['services.ai_provider' => 'anthropic', 'services.anthropic.key' => 'test-key']);
    chatBrain();

    Http::fake(['api.anthropic.com/*' => Http::response(['content' => [['type' => 'text', 'text' => 'Hello from the cliff!']]])]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi there']]])
        ->assertOk()
        ->assertJson(['reply' => 'Hello from the cliff!']);

    Http::assertSent(fn ($request) => str_contains($request['system'], 'Always mention the sunsets.')
        && $request->hasHeader('x-api-key', 'test-key'));
});

test('the assistant is off without an api key or when switched off in the admin', function () {
    config(['services.ai_provider' => 'anthropic', 'services.anthropic.key' => null]);
    chatBrain();

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi']]])->assertStatus(503);

    config(['services.ai_provider' => 'anthropic', 'services.anthropic.key' => 'test-key']);
    HomeContent::where('section', 'ai_chat')->update(['active' => false]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi']]])->assertStatus(503);
});

test('a visitor cannot go over the daily message limit', function () {
    config(['services.ai_provider' => 'anthropic', 'services.anthropic.key' => 'test-key']);
    chatBrain(['price' => 1]);

    Http::fake(['api.anthropic.com/*' => Http::response(['content' => [['type' => 'text', 'text' => 'Hi!']]])]);

    $payload = ['messages' => [['role' => 'user', 'content' => 'Hello']]];

    $this->postJson('/ai-chat', $payload)->assertOk();
    $this->postJson('/ai-chat', $payload)->assertStatus(429);
});

test('the active counter offer is shared with the assistant', function () {
    config(['services.ai_provider' => 'anthropic', 'services.anthropic.key' => 'test-key']);
    chatBrain();

    HomeContent::create([
        'section' => 'offer_popup',
        'title' => 'Book direct and save',
        'description' => 'Ten percent off for direct bookings.',
        'features' => ['SAVE10'],
        'sort_order' => 1,
        'active' => true,
    ]);

    Http::fake(['api.anthropic.com/*' => Http::response(['content' => [['type' => 'text', 'text' => 'Sure!']]])]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Is it expensive?']]])->assertOk();

    Http::assertSent(fn ($request) => str_contains($request['system'], 'SAVE10'));
});

test('the offer popup only appears on the site once it is switched on', function () {
    HomeContent::create(['section' => 'offer_popup', 'title' => 'Wait, a gift!', 'sort_order' => 1, 'active' => false]);

    $this->get('/villas')->assertOk()->assertDontSee('data-cvw-offer', false);

    HomeContent::where('section', 'offer_popup')->update(['active' => true]);

    $this->get('/villas')->assertOk()->assertSee('data-cvw-offer', false)->assertSee('Wait, a gift!');
});

test('the assistant can answer through OpenAI instead', function () {
    config(['services.ai_provider' => 'openai', 'services.openai.key' => 'sk-test', 'services.openai.model' => 'test-model']);
    chatBrain();

    Http::fake(['api.openai.com/*' => Http::response(['choices' => [['message' => ['role' => 'assistant', 'content' => 'Hello from OpenAI!']]]])]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi there']]])
        ->assertOk()
        ->assertJson(['reply' => 'Hello from OpenAI!']);

    Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer sk-test')
        && $request['model'] === 'test-model'
        && $request['messages'][0]['role'] === 'system'
        && str_contains($request['messages'][0]['content'], 'Always mention the sunsets.')
        && $request['messages'][1]['content'] === 'Hi there');
});

test('the chat stays off when the chosen provider has no key', function () {
    config(['services.ai_provider' => 'openai', 'services.openai.key' => null, 'services.anthropic.key' => 'test-key']);
    chatBrain();

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi']]])->assertStatus(503);
});

test('quick answers work with no AI key at all', function () {
    config(['services.ai_provider' => 'gemini', 'services.gemini.key' => null, 'services.openai.key' => null, 'services.anthropic.key' => null]);
    Http::fake();

    HomeContent::create(['section' => 'chat_faq', 'title' => 'Rates', 'description' => 'From PHP 6,500 per night.', 'features' => ['price', 'how much', 'magkano'], 'sort_order' => 1, 'active' => true]);
    HomeContent::create(['section' => 'chat_faq', 'title' => 'Hello', 'description' => 'Hello there!', 'features' => ['hi', 'hello'], 'sort_order' => 2, 'active' => true]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi, how much is a villa?']]])
        ->assertOk()->assertJson(['reply' => 'From PHP 6,500 per night.']);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Do you allow pets?']]])
        ->assertOk()->assertJsonPath('reply', config('aichat.not_sure'));

    Http::assertNothingSent();
});

test('the assistant can answer through Gemini', function () {
    config(['services.ai_provider' => 'gemini', 'services.gemini.key' => 'g-test', 'services.gemini.model' => 'test-model']);
    chatBrain();

    Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['candidates' => [['content' => ['parts' => [['text' => 'Hello from Gemini!']]]]]])]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hi there']]])
        ->assertOk()->assertJson(['reply' => 'Hello from Gemini!']);

    Http::assertSent(fn ($request) => str_contains($request->url(), 'models/test-model:generateContent')
        && $request->hasHeader('x-goog-api-key', 'g-test')
        && str_contains($request['systemInstruction']['parts'][0]['text'], 'Always mention the sunsets.')
        && $request['contents'][0]['role'] === 'user');
});

test('a quick answer covers for the AI when it is busy', function () {
    config(['services.ai_provider' => 'gemini', 'services.gemini.key' => 'g-test']);
    chatBrain();
    HomeContent::create(['section' => 'chat_faq', 'title' => 'Rates', 'description' => 'From PHP 6,500 per night.', 'features' => ['price'], 'sort_order' => 1, 'active' => true]);

    Http::fake(['generativelanguage.googleapis.com/*' => Http::response(['error' => ['message' => 'quota']], 429)]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'What is the price?']]])
        ->assertOk()->assertJson(['reply' => 'From PHP 6,500 per night.']);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Do you allow pets?']]])
        ->assertStatus(502);
});

test('the OpenAI reasoning setting is only sent when it is configured', function () {
    config(['services.ai_provider' => 'openai', 'services.openai.key' => 'sk-test', 'services.openai.model' => 'gpt-6-luna', 'services.openai.reasoning_effort' => 'none']);
    chatBrain();

    Http::fake(['api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => 'Hi!']]]])]);

    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hello']]])->assertOk();
    Http::assertSent(fn ($request) => $request['reasoning_effort'] === 'none' && $request['max_completion_tokens'] === 1200);

    config(['services.openai.reasoning_effort' => null]);
    $this->postJson('/ai-chat', ['messages' => [['role' => 'user', 'content' => 'Hello again']]])->assertOk();
    Http::assertSent(fn ($request) => ! isset($request['reasoning_effort']) && $request['messages'][1]['content'] === 'Hello again');
});
