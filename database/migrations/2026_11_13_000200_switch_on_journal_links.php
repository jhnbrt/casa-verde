<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The Journal page now exists (/blog), so the header ("Discover" dropdown, next to Reviews) and footer links
     * added by the previous migration are switched on.
     */
    public function up(): void
    {
        HomeContent::whereIn('section', ['navigation', 'footer_explore'])
            ->whereRaw('lower(trim(title)) = ?', ['journal'])
            ->update(['active' => true, 'button_url' => '/blog']);
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
