<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Partner;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(url('/'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        );

        Page::where('published', true)
            ->where('slug', '!=', 'home')
            ->orderBy('sort_order')
            ->get()
            ->each(function (Page $page) use ($sitemap) {
                $sitemap->add(
                    Url::create(url($page->slug))
                        ->setPriority(0.8)
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
            });

        Partner::published()->get()->each(function (Partner $partner) use ($sitemap) {
            $sitemap->add(
                Url::create(route('partner.show', $partner))
                    ->setPriority(0.7)
                    ->setLastModificationDate($partner->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
