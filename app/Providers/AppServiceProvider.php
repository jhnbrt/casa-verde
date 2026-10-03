<?php

namespace App\Providers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every public page includes the footer, so it gets its content here
        // instead of each controller having to pass it.
        View::composer('layouts.footer', function ($view): void {
            $view->with('footer', $this->footerContent());
        });
    }

    /**
     * Footer content: the admin-managed "footer_*" entries over the defaults in
     * config/footer.php. A section with no entries at all uses the defaults; a
     * section whose entries are all switched off is hidden.
     *
     * @return array<string, mixed>
     */
    protected function footerContent(): array
    {
        $defaults = config('footer');

        try {
            $rows = HomeContent::query()
                ->whereIn('section', [
                    'footer_cta', 'footer_brand', 'footer_explore', 'footer_links', 'footer_contact',
                    'footer_social', 'footer_newsletter', 'footer_legal', 'footer_bottom',
                ])
                ->orderBy('sort_order')
                ->get()
                ->groupBy('section');

            $logo = HomeContent::query()->where('section', 'site')->value('image');
        } catch (Throwable) {
            return $this->footerFromDefaults($defaults);
        }

        // Active rows of a section, or null when the section has never been filled in.
        $active = fn (string $section): ?Collection => $rows->has($section)
            ? $rows->get($section)->where('active', true)->values()
            : null;

        $links = fn (string $section, array $fallback): array => ($list = $active($section)) === null
            ? $fallback
            : $list->map(fn (HomeContent $row): array => [
                'title' => (string) $row->title,
                'url' => (string) $row->button_url,
                'icon' => (string) $row->icon,
            ])->all();

        $cta = $active('footer_cta');
        $brand = $active('footer_brand')?->first();
        $newsletter = $active('footer_newsletter')?->first();
        $bottom = $active('footer_bottom')?->first();

        return [
            'cta' => $cta === null
                ? $defaults['cta']
                : ($cta->first() ? [
                    'title' => $cta->first()->title ?: $defaults['cta']['title'],
                    'button_text' => $cta->first()->button_text,
                    'button_url' => $cta->first()->button_url ?: $defaults['cta']['button_url'],
                ] : null),
            'brand' => [
                'title' => $brand?->title ?: $defaults['brand']['title'],
                'subtitle' => $brand?->subtitle ?: $defaults['brand']['subtitle'],
                'image' => $logo ?: 'images/logo.png',
            ],
            'explore' => $links('footer_explore', $defaults['explore']),
            'useful' => $links('footer_links', $defaults['useful']),
            'contact' => $links('footer_contact', $defaults['contact']),
            'social' => $links('footer_social', $defaults['social']),
            'newsletter' => [
                'title' => $newsletter ? $newsletter->title : $defaults['newsletter']['title'],
                'text' => $newsletter ? $newsletter->description : $defaults['newsletter']['text'],
            ],
            'legal' => $links('footer_legal', $defaults['legal']),
            'bottom' => $bottom?->title ?: $defaults['bottom'],
        ];
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function footerFromDefaults(array $defaults): array
    {
        return [
            'cta' => $defaults['cta'],
            'brand' => $defaults['brand'] + ['image' => 'images/logo.png'],
            'explore' => $defaults['explore'],
            'useful' => $defaults['useful'],
            'contact' => $defaults['contact'],
            'social' => $defaults['social'],
            'newsletter' => $defaults['newsletter'],
            'legal' => $defaults['legal'],
            'bottom' => $defaults['bottom'],
        ];
    }
}
