<?php

namespace App\Http\Controllers\Seo;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Partner;
use Illuminate\Support\Str;

class LlmsController extends Controller
{
    public function index()
    {
        $body = <<<MD
            # Coliving Founders

            > An international network of community-driven colivings built on shared values, clear standards, and authentic human connection. Coliving Founders (COFO) connects independent coliving spaces across Europe under a shared vision of collaboration over competition.

            ## About

            Coliving Founders is a network created by founders, for founders. Instead of competing, we collaborate. Instead of scaling platforms, we strengthen communities.

            ## Pages

            MD;

        $body = $this->dedent($body);

        $body .= "- [Home](" . url('/') . "): The movement and vision behind Coliving Founders.\n";

        Page::where('published', true)->where('slug', '!=', 'home')->orderBy('sort_order')->get()
            ->each(function (Page $page) use (&$body) {
                $url = url($page->slug);
                $desc = $page->seoDescription() ?: '';
                $body .= "- [{$page->title}]({$url})" . ($desc ? ": {$desc}" : '') . "\n";
            });

        $body .= "\n## Partner Colivings\n\n";

        Partner::published()->get()->each(function (Partner $partner) use (&$body) {
            $url = route('partner.show', $partner);
            $body .= "- [{$partner->name}]({$url}): {$partner->location}. " . Str::limit($partner->description, 140) . "\n";
        });

        $body .= "\n## Contact\n\n- Email: info@colivingfounders.com\n- Phone: +39 388 446 2409\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function full()
    {
        $body = "# Coliving Founders — Full Content\n\n";
        $body .= "> Detailed content of every page in the Coliving Founders site, formatted for LLM consumption.\n\n";

        $pages = Page::where('published', true)->orderBy('sort_order')->with('publishedSections')->get();

        foreach ($pages as $page) {
            $url = $page->slug === 'home' ? url('/') : url($page->slug);
            $body .= "---\n\n## {$page->title}\n\n";
            $body .= "URL: {$url}\n";
            if ($desc = $page->seoDescription()) {
                $body .= "Description: {$desc}\n";
            }
            $body .= "\n";

            foreach ($page->publishedSections as $section) {
                $body .= $this->sectionToMarkdown($section);
            }
        }

        $body .= "---\n\n## Partner Colivings\n\n";
        Partner::published()->get()->each(function (Partner $partner) use (&$body) {
            $body .= "### {$partner->name}\n\n";
            $body .= "- Location: {$partner->location}\n";
            if ($partner->website) {
                $body .= "- Website: {$partner->website}\n";
            }
            if ($partner->rooms) {
                $body .= "- Rooms: {$partner->rooms}\n";
            }
            $body .= "- Page: " . route('partner.show', $partner) . "\n\n";
            $body .= "{$partner->description}\n\n";
        });

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function sectionToMarkdown($section): string
    {
        $c = $section->content ?? [];
        $out = '';

        switch ($section->type) {
            case 'hero':
                $title = trim(($c['title'] ?? '') . ' ' . ($c['title_highlight'] ?? ''));
                $out .= "### {$title}\n\n";
                if (! empty($c['subtitle'])) {
                    $out .= "{$c['subtitle']}\n\n";
                }
                break;

            case 'rich_text':
                if (! empty($c['title'])) {
                    $out .= "### {$c['title']}\n\n";
                }
                if (! empty($c['body_html'])) {
                    $out .= $this->htmlToMarkdown($c['body_html']) . "\n\n";
                }
                break;

            case 'bullet_list':
                if (! empty($c['title'])) {
                    $out .= "### {$c['title']}\n\n";
                }
                if (! empty($c['intro'])) {
                    $out .= "{$c['intro']}\n\n";
                }
                foreach (($c['items'] ?? []) as $item) {
                    $out .= "- {$item}\n";
                }
                $out .= "\n";
                break;

            case 'feature_grid':
            case 'steps':
                if (! empty($c['title'])) {
                    $out .= "### {$c['title']}\n\n";
                }
                foreach (($c['items'] ?? []) as $item) {
                    $title = $item['title'] ?? '';
                    $text = $item['text'] ?? '';
                    $out .= "- **{$title}**: {$text}\n";
                }
                $out .= "\n";
                break;

            case 'cta':
                $out .= "### {$c['title']}\n\n";
                if (! empty($c['text'])) {
                    $out .= "{$c['text']}\n\n";
                }
                if (! empty($c['button']['label'])) {
                    $out .= "→ {$c['button']['label']}: " . url($c['button']['href'] ?? '/') . "\n\n";
                }
                break;

            case 'founders':
                if (! empty($c['title'])) {
                    $out .= "### {$c['title']}\n\n";
                }
                foreach (($c['items'] ?? []) as $person) {
                    $out .= "- **{$person['name']}** — {$person['role']}\n";
                }
                $out .= "\n";
                break;

            case 'contact_info':
                $out .= "### Contact\n\n";
                if (! empty($c['email'])) {
                    $out .= "- Email: {$c['email']}\n";
                }
                if (! empty($c['phone'])) {
                    $out .= "- Phone: {$c['phone']}\n";
                }
                $out .= "\n";
                break;

            case 'partner_grid':
                $out .= "### " . ($c['title'] ?? 'Partner Spaces') . "\n\nSee Partner Colivings section below.\n\n";
                break;

            case 'image':
                if (! empty($c['caption'])) {
                    $out .= "*{$c['caption']}*\n\n";
                }
                break;

            case 'form':
                $out .= "### " . ($c['title'] ?? 'Form') . "\n\n";
                if (! empty($c['intro'])) {
                    $out .= "{$c['intro']}\n\n";
                }
                break;
        }

        return $out;
    }

    private function htmlToMarkdown(string $html): string
    {
        $text = strip_tags($html, '<br><strong><em><a>');
        $text = preg_replace('|<br\s*/?>|i', "\n", $text);
        $text = preg_replace('|<strong>(.*?)</strong>|is', '**$1**', $text);
        $text = preg_replace('|<em>(.*?)</em>|is', '*$1*', $text);
        $text = preg_replace('|<a [^>]*href="([^"]+)"[^>]*>(.*?)</a>|is', '[$2]($1)', $text);
        return trim(html_entity_decode($text, ENT_QUOTES | ENT_HTML5));
    }

    private function dedent(string $text): string
    {
        return preg_replace('/^ {12}/m', '', $text);
    }
}
