<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Partner;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Schema;

class SchemaBuilder
{
    public function organization(): BaseType
    {
        return Schema::organization()
            ->name('Coliving Founders')
            ->alternateName('COFO')
            ->url(url('/'))
            ->logo(url('/og/logo.png'))
            ->description('An international network of community-driven colivings built on shared values, clear standards, and authentic human connection.')
            ->email('info@colivingfounders.com')
            ->telephone('+39 388 446 2409')
            ->sameAs([
                'https://colivingfounders.com',
            ]);
    }

    public function webSite(): BaseType
    {
        return Schema::webSite()
            ->name('Coliving Founders')
            ->url(url('/'))
            ->inLanguage('en')
            ->publisher($this->organization());
    }

    public function webPage(Page $page): BaseType
    {
        $type = data_get($page->seo, 'schema_type') ?: 'WebPage';

        $factory = match ($type) {
            'WebSite' => fn () => $this->webSite(),
            'AboutPage' => fn () => Schema::aboutPage(),
            'ContactPage' => fn () => Schema::contactPage(),
            'CollectionPage' => fn () => Schema::collectionPage(),
            default => fn () => Schema::webPage(),
        };

        $node = $factory()
            ->name($page->seoTitle())
            ->url($this->canonicalForPage($page))
            ->inLanguage('en')
            ->isPartOf($this->webSite());

        if ($desc = $page->seoDescription()) {
            $node->description($desc);
        }

        return $node;
    }

    public function lodgingBusiness(Partner $partner): BaseType
    {
        $node = Schema::lodgingBusiness()
            ->name($partner->name)
            ->description($partner->description)
            ->url(route('partner.show', $partner))
            ->address(Schema::postalAddress()->addressLocality($partner->location));

        if ($partner->website) {
            $node->sameAs($partner->website);
        }

        if ($logo = $partner->logoUrl('card')) {
            $node->image($logo)->logo($logo);
        }

        return $node;
    }

    public function person(string $name, ?string $jobTitle = null, ?string $worksForUrl = null): BaseType
    {
        $node = Schema::person()->name($name);
        if ($jobTitle) {
            $node->jobTitle($jobTitle);
        }
        if ($worksForUrl) {
            $node->worksFor(Schema::organization()->url($worksForUrl));
        }
        return $node;
    }

    public function breadcrumbs(array $items): BaseType
    {
        $list = [];
        foreach ($items as $i => [$name, $url]) {
            $list[] = Schema::listItem()
                ->position($i + 1)
                ->name($name)
                ->item($url);
        }
        return Schema::breadcrumbList()->itemListElement($list);
    }

    public function canonicalForPage(Page $page): string
    {
        $custom = data_get($page->seo, 'canonical');
        if ($custom) {
            return $custom;
        }
        return $page->slug === 'home' ? url('/') : url($page->slug);
    }

    public function renderAll(array $nodes): string
    {
        $scripts = '';
        foreach ($nodes as $node) {
            if ($node instanceof BaseType) {
                $scripts .= $node->toScript();
            }
        }
        return $scripts;
    }
}
