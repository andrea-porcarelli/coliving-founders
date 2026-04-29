<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPartners();
        $this->seedHome();
        $this->seedPartnersPage();
        $this->seedCompanies();
        $this->seedAbout();
        $this->seedContact();
    }

    private function seedPartners(): void
    {
        $partners = [
            [
                'slug' => 'beetcommunity',
                'name' => 'Beetcommunity Coliving',
                'location' => 'Palermo, Italy',
                'description' => 'A vibrant coliving in the heart of Palermo, blending Mediterranean culture with a strong founder-led community.',
                'website' => 'https://beetcommunity.com',
                'rooms' => null,
                'sort_order' => 1,
                'logo' => 'beetcommunity.png',
            ],
            [
                'slug' => 'pomar',
                'name' => 'Pomar Coliving',
                'location' => 'Algarve, Portugal',
                'description' => 'A nature-rooted coliving on the Algarve coast, focused on slow living, surf, and meaningful work.',
                'website' => 'https://pomarcoliving.com',
                'rooms' => null,
                'sort_order' => 2,
                'logo' => 'pomar.png',
            ],
            [
                'slug' => 'cactus',
                'name' => 'Cactus Coliving',
                'location' => 'Tenerife, Canary Islands',
                'description' => 'A volcanic-island coliving for remote workers and creators, surrounded by the Atlantic and year-round sunshine.',
                'website' => 'https://cactuscoliving.com',
                'rooms' => null,
                'sort_order' => 3,
                'logo' => 'cactus.png',
            ],
        ];

        $assetDir = database_path('seed-assets');

        foreach ($partners as $data) {
            $logo = $data['logo'] ?? null;
            unset($data['logo']);

            $partner = Partner::updateOrCreate(['slug' => $data['slug']], $data);

            if ($logo && file_exists($assetDir . DIRECTORY_SEPARATOR . $logo)) {
                if ($partner->getMedia('logo')->isEmpty()) {
                    $partner->addMedia($assetDir . DIRECTORY_SEPARATOR . $logo)
                        ->preservingOriginal()
                        ->toMediaCollection('logo');
                }
            }
        }
    }

    private function seedHome(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'sort_order' => 1,
                'seo' => [
                    'title' => 'Coliving Founders — A movement of community-driven colivings',
                    'description' => 'An international network of community-driven colivings built on shared values, clear standards, and authentic human connection.',
                    'schema_type' => 'WebSite',
                ],
            ]
        );

        $page->sections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'content' => [
                    'title' => 'Coliving is not accommodation.',
                    'title_highlight' => "It's a movement.",
                    'subtitle' => 'An international network of community-driven colivings built on shared values, clear standards, and authentic human connection.',
                    'ctas' => [
                        ['label' => 'Explore our Network', 'href' => '/coliving-partners', 'style' => 'primary'],
                        ['label' => 'Become a Partner', 'href' => '#become-a-partner', 'style' => 'secondary'],
                    ],
                ],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'title' => 'About the Project',
                    'body_html' => '<p>Coliving Founders is an international network of independent coliving spaces, created by founders who believe in collaboration over competition.</p><p>We connect authentic, community-driven colivings under a shared vision: to elevate the industry through quality standards, mutual support, and meaningful experiences.</p>',
                    'align' => 'center',
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'title' => 'Vision',
                    'body_html' => '<p>We believe in a world where coliving transcends accommodation, becoming a movement defined by shared values, authentic connections, and unwavering support for those who create it.</p>',
                ],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'Mission',
                    'intro' => 'We are building a global ecosystem of coliving spaces united by:',
                    'items' => [
                        'Clear and shared standards',
                        'Active collaboration between founders',
                        'A strong focus on community and human connection',
                    ],
                ],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'Goals',
                    'items' => [
                        'Elevate the coliving industry through excellence',
                        'Create a collaborative ecosystem for founders',
                        'Protect authentic coliving values',
                        'Promote meaningful partnerships',
                        'Expand a global network by 2030',
                        'Organize traveling meetups',
                    ],
                ],
            ],
            [
                'type' => 'feature_grid',
                'content' => [
                    'title' => 'Why COFO',
                    'columns' => 3,
                    'items' => [
                        ['title' => 'For Guests', 'text' => 'Reliable quality and authentic experiences across different locations.'],
                        ['title' => 'For Founders', 'text' => 'A support network to grow, share, and improve together.'],
                        ['title' => 'For the Industry', 'text' => 'A collective effort to define and protect the meaning of coliving.'],
                    ],
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'cta',
                'content' => [
                    'title' => 'Join the movement',
                    'button' => ['label' => 'Become a Partner', 'href' => '#become-a-partner'],
                ],
                'style' => ['bg' => 'brand'],
            ],
        ];

        $this->insertSections($page, $sections);
    }

    private function seedPartnersPage(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'coliving-partners'],
            [
                'title' => 'Coliving Partners',
                'sort_order' => 2,
                'seo' => [
                    'title' => 'Our Coliving Network — Coliving Founders',
                    'description' => 'Explore a curated network of independent colivings across Europe, each built around community, quality, and authentic experiences.',
                ],
            ]
        );

        $page->sections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'content' => [
                    'title' => 'Our Coliving Network',
                    'subtitle' => 'Explore a curated network of independent colivings across Europe, each built around community, quality, and authentic experiences. Every space in the network shares the same commitment: creating environments where people can live, work, and connect in a meaningful way.',
                ],
                'style' => ['compact' => true],
            ],
            [
                'type' => 'partner_grid',
                'content' => [
                    'title' => 'Partner Spaces',
                ],
            ],
            [
                'type' => 'cta',
                'content' => [
                    'title' => 'Want to join the network?',
                    'text' => 'We are always looking for authentic coliving spaces that share our values.',
                    'button' => ['label' => 'Become a Partner', 'href' => '#become-a-partner'],
                ],
                'style' => ['bg' => 'brand'],
            ],
        ];

        $this->insertSections($page, $sections);
    }

    private function seedCompanies(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'for-companies'],
            [
                'title' => 'For Companies',
                'sort_order' => 3,
                'seo' => [
                    'title' => 'For Companies — Workation solutions for modern teams',
                    'description' => 'Flexible living and working solutions for modern teams — combining productivity, well-being, and real human connection.',
                ],
            ]
        );

        $page->sections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'content' => [
                    'title' => 'Remote work,',
                    'title_highlight' => 'without compromise.',
                    'subtitle' => 'Flexible living and working solutions for modern teams — combining productivity, well-being, and real human connection.',
                ],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'The problem',
                    'intro' => 'Hybrid and remote work often fail to deliver:',
                    'items' => [
                        'Temporary housing solutions compromise productivity and security',
                        'Company culture weakens with distributed teams',
                        'Employees feel isolated and disengaged',
                    ],
                ],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'title' => 'Our solution',
                    'body_html' => '<p>Coliving Founders offers a structured solution: certified coliving spaces, a single agreement, and a consistent experience across multiple locations.</p>',
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'title' => 'What is coliving',
                    'body_html' => '<p>Coliving integrates living and working into one seamless experience — designed for flexibility, well-being, and connection.</p>',
                ],
            ],
            [
                'type' => 'feature_grid',
                'content' => [
                    'title' => 'Features',
                    'columns' => 3,
                    'items' => [
                        ['title' => 'Private Spaces', 'text' => 'Fully equipped rooms or apartments, ready to move in.'],
                        ['title' => 'Shared Spaces', 'text' => 'Coworking areas, kitchens, outdoor spaces, and more.'],
                        ['title' => 'Community', 'text' => 'Events, networking, and local experiences.'],
                    ],
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'Benefits for companies',
                    'items' => [
                        'Improve employee retention and engagement',
                        'Reduce operational complexity',
                        'Attract top talent with flexible solutions',
                        'Strengthen company culture',
                    ],
                ],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'Benefits for employees',
                    'items' => [
                        'Better work-life balance',
                        'Built-in community',
                        'Flexibility to move and adapt',
                    ],
                ],
            ],
            [
                'type' => 'steps',
                'content' => [
                    'title' => 'How it works',
                    'items' => [
                        ['title' => 'Request', 'text' => 'Tell us your needs.'],
                        ['title' => 'Match', 'text' => 'We select the best locations.'],
                        ['title' => 'Stay', 'text' => 'Your team moves in seamlessly.'],
                        ['title' => 'Monitor', 'text' => 'You receive insights and reports.'],
                    ],
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'form',
                'content' => [
                    'form_id' => 'workation',
                    'title' => 'Request a Workation Plan',
                    'intro' => 'Tell us about your team and we will design a solution.',
                ],
            ],
        ];

        $this->insertSections($page, $sections);
    }

    private function seedAbout(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About',
                'sort_order' => 4,
                'seo' => [
                    'title' => 'About — Coliving Founders',
                    'description' => 'A network created by founders, for founders. Instead of competing, we collaborate. Instead of scaling platforms, we strengthen communities.',
                ],
            ]
        );

        $page->sections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'content' => [
                    'title' => 'A network created by founders,',
                    'title_highlight' => 'for founders.',
                    'subtitle' => 'Coliving Founders is a network created by founders, for founders.',
                ],
                'style' => ['compact' => true],
            ],
            [
                'type' => 'founders',
                'content' => [
                    'title' => 'The founders',
                    'items' => [
                        ['name' => 'Marco Traina', 'role' => 'Founder of Beetcommunity Coliving, Palermo', 'partner_slug' => 'beetcommunity'],
                        ['name' => 'Maria Mayordomo Yglesias', 'role' => 'Founder of Cactus Coliving, Tenerife', 'partner_slug' => 'cactus'],
                        ['name' => 'Claire Cipriano', 'role' => 'Founder of Pomar Coliving, Algarve', 'partner_slug' => 'pomar'],
                    ],
                ],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'title' => 'The project',
                    'body_html' => '<p>We built Coliving Founders to connect independent spaces under a shared vision.</p><p>Instead of competing, we collaborate. Instead of scaling platforms, we strengthen communities.</p>',
                ],
                'style' => ['bg' => 'tint'],
            ],
            [
                'type' => 'bullet_list',
                'content' => [
                    'title' => 'What we do',
                    'items' => [
                        'Define shared standards',
                        'Support founders',
                        'Facilitate collaboration',
                        'Promote authentic coliving',
                    ],
                ],
            ],
            [
                'type' => 'rich_text',
                'content' => [
                    'body_html' => '<p class="text-2xl font-display">This is not just a network.<br>It\'s a collective effort to shape the future of coliving.</p>',
                    'align' => 'center',
                ],
                'style' => ['bg' => 'brand'],
            ],
        ];

        $this->insertSections($page, $sections);
    }

    private function seedContact(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact',
                'sort_order' => 5,
                'seo' => [
                    'title' => 'Contact — Coliving Founders',
                    'description' => 'For any inquiry, collaboration, or information, feel free to contact us.',
                ],
            ]
        );

        $page->sections()->delete();

        $sections = [
            [
                'type' => 'hero',
                'content' => [
                    'title' => 'Get in touch.',
                    'subtitle' => 'For any inquiry, collaboration, or information, feel free to contact us.',
                ],
                'style' => ['compact' => true],
            ],
            [
                'type' => 'contact_info',
                'content' => [
                    'email' => 'info@colivingfounders.com',
                    'phone' => '+39 388 446 2409',
                ],
            ],
            [
                'type' => 'form',
                'content' => [
                    'form_id' => 'contact',
                    'title' => 'Send us a message',
                ],
                'style' => ['bg' => 'tint'],
            ],
        ];

        $this->insertSections($page, $sections);
    }

    private function insertSections(Page $page, array $sections): void
    {
        foreach ($sections as $i => $section) {
            $page->sections()->create([
                'type' => $section['type'],
                'sort_order' => $i,
                'content' => $section['content'],
                'style' => $section['style'] ?? null,
            ]);
        }
    }
}
