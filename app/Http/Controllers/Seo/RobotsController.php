<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;

class RobotsController extends Controller
{
    public function __invoke()
    {
        $sitemap = url('/sitemap.xml');

        $body = <<<TXT
            # Coliving Founders — robots.txt
            # AI crawlers are explicitly allowed: we want our content to be discoverable
            # by AI assistants and search engines alike.

            User-agent: *
            Disallow: /login
            Disallow: /logout
            Disallow: /livewire/

            # AI crawlers
            User-agent: GPTBot
            Allow: /

            User-agent: ClaudeBot
            Allow: /

            User-agent: Claude-Web
            Allow: /

            User-agent: PerplexityBot
            Allow: /

            User-agent: Google-Extended
            Allow: /

            User-agent: Bingbot
            Allow: /

            User-agent: CCBot
            Allow: /

            Sitemap: {$sitemap}
            TXT;

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
