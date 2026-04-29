<?php

namespace App\Livewire;

use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Section;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class PageEditor extends Component
{
    use WithFileUploads;

    public int $pageId;

    public ?int $editingSectionId = null;

    public array $editingContent = [];

    public array $editingStyle = [];

    public $pendingImage;

    public string $pendingImageCollection = 'image';

    public bool $editingSeo = false;

    public array $seoForm = [];

    public bool $editingNav = false;

    public array $navForm = [];

    public array $navDeletedIds = [];

    public bool $editingPartnersList = false;

    public ?int $editingPartnerId = null;

    public bool $partnerIsNew = false;

    public array $partnerForm = [];

    public function mount(Page $page): void
    {
        $this->pageId = $page->id;
    }

    #[Computed]
    public function page(): Page
    {
        return Page::with(['sections' => fn ($q) => $q->orderBy('sort_order')])
            ->findOrFail($this->pageId);
    }

    public function startEditing(int $sectionId): void
    {
        $section = $this->ensureOwnedSection($sectionId);

        $this->editingSectionId = $section->id;
        $this->editingContent = $section->content ?? [];
        $this->editingStyle = $section->style ?? [];
        $this->pendingImage = null;
    }

    public function cancelEditing(): void
    {
        $this->editingSectionId = null;
        $this->editingContent = [];
        $this->editingStyle = [];
        $this->pendingImage = null;
    }

    public function saveEditing(): void
    {
        if (! $this->editingSectionId) {
            return;
        }

        $section = $this->ensureOwnedSection($this->editingSectionId);

        $section->update([
            'content' => $this->editingContent,
            'style' => $this->editingStyle ?: null,
        ]);

        $this->cancelEditing();
        unset($this->page);
    }

    public function uploadImage(string $collection = 'image'): void
    {
        if (! $this->editingSectionId || ! $this->pendingImage) {
            return;
        }

        $this->validate([
            'pendingImage' => 'required|image|max:5120',
        ]);

        $section = $this->ensureOwnedSection($this->editingSectionId);
        $section->clearMediaCollection($collection);
        $section->addMedia($this->pendingImage->getRealPath())
            ->usingFileName($this->pendingImage->getClientOriginalName())
            ->toMediaCollection($collection);

        $this->pendingImage = null;
        unset($this->page);
    }

    public function removeImage(string $collection = 'image'): void
    {
        if (! $this->editingSectionId) {
            return;
        }

        $section = $this->ensureOwnedSection($this->editingSectionId);
        $section->clearMediaCollection($collection);
        unset($this->page);
    }

    public function deleteSection(int $sectionId): void
    {
        $section = $this->ensureOwnedSection($sectionId);
        $section->delete();
        $this->cancelEditing();
        unset($this->page);
    }

    public function duplicateSection(int $sectionId): void
    {
        $section = $this->ensureOwnedSection($sectionId);

        $clone = $section->replicate();
        $clone->sort_order = $section->sort_order + 1;
        $clone->save();

        Section::where('page_id', $this->pageId)
            ->where('id', '!=', $clone->id)
            ->where('sort_order', '>=', $clone->sort_order)
            ->increment('sort_order');

        unset($this->page);
    }

    public function moveSection(int $sectionId, string $direction): void
    {
        $section = $this->ensureOwnedSection($sectionId);

        $neighbor = Section::where('page_id', $this->pageId)
            ->when(
                $direction === 'up',
                fn ($q) => $q->where('sort_order', '<', $section->sort_order)->orderByDesc('sort_order'),
                fn ($q) => $q->where('sort_order', '>', $section->sort_order)->orderBy('sort_order')
            )
            ->first();

        if (! $neighbor) {
            return;
        }

        [$a, $b] = [$section->sort_order, $neighbor->sort_order];
        $section->update(['sort_order' => $b]);
        $neighbor->update(['sort_order' => $a]);

        unset($this->page);
    }

    public function addSection(string $type, ?int $afterSectionId = null): void
    {
        $defaults = $this->defaultContentFor($type);

        $insertAfterOrder = $afterSectionId
            ? ($this->ensureOwnedSection($afterSectionId)->sort_order)
            : Section::where('page_id', $this->pageId)->max('sort_order');

        $newOrder = ($insertAfterOrder ?? -1) + 1;

        Section::where('page_id', $this->pageId)
            ->where('sort_order', '>=', $newOrder)
            ->increment('sort_order');

        Section::create([
            'page_id' => $this->pageId,
            'type' => $type,
            'sort_order' => $newOrder,
            'content' => $defaults,
            'style' => null,
        ]);

        unset($this->page);
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Section::where('page_id', $this->pageId)
                ->where('id', $id)
                ->update(['sort_order' => $index]);
        }
        unset($this->page);
    }

    public function addItem(string $path): void
    {
        $items = data_get($this->editingContent, $path, []);
        if (! is_array($items)) {
            $items = [];
        }
        $items[] = $this->defaultItemTemplate($path);
        data_set($this->editingContent, $path, $items);
    }

    private function defaultItemTemplate(string $path): mixed
    {
        $section = $this->editingSectionId ? Section::find($this->editingSectionId) : null;
        $type = $section?->type;

        return match (true) {
            $type === 'hero' && $path === 'ctas' => ['label' => 'Click me', 'href' => '/', 'style' => 'primary'],
            $type === 'feature_grid' && $path === 'items' => ['title' => 'New feature', 'text' => 'Describe it.'],
            $type === 'steps' && $path === 'items' => ['title' => 'New step', 'text' => 'Describe it.'],
            $type === 'founders' && $path === 'items' => ['name' => 'Name', 'role' => 'Role', 'partner_slug' => ''],
            $type === 'bullet_list' && $path === 'items' => 'New item',
            default => '',
        };
    }

    public function removeItem(string $path, int $index): void
    {
        $items = data_get($this->editingContent, $path, []);
        if (! is_array($items) || ! array_key_exists($index, $items)) {
            return;
        }
        array_splice($items, $index, 1);
        data_set($this->editingContent, $path, $items);
    }

    public function moveItem(string $path, int $index, string $direction): void
    {
        $items = data_get($this->editingContent, $path, []);
        if (! is_array($items)) {
            return;
        }
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if (! array_key_exists($target, $items)) {
            return;
        }
        [$items[$index], $items[$target]] = [$items[$target], $items[$index]];
        data_set($this->editingContent, $path, array_values($items));
    }

    public function startEditingPartners(): void
    {
        $this->editingPartnersList = true;
        $this->editingPartnerId = null;
        $this->partnerIsNew = false;
    }

    public function cancelEditingPartners(): void
    {
        $this->editingPartnersList = false;
        $this->editingPartnerId = null;
        $this->partnerIsNew = false;
        $this->partnerForm = [];
        $this->pendingImage = null;
    }

    public function editPartner(int $id): void
    {
        $partner = Partner::findOrFail($id);
        $this->editingPartnerId = $partner->id;
        $this->partnerIsNew = false;
        $this->partnerForm = [
            'name' => $partner->name,
            'slug' => $partner->slug,
            'location' => $partner->location,
            'description' => $partner->description,
            'website' => $partner->website ?: '',
            'rooms' => $partner->rooms,
            'published' => (bool) $partner->published,
        ];
        $this->pendingImage = null;
    }

    public function addNewPartner(): void
    {
        $this->editingPartnerId = null;
        $this->partnerIsNew = true;
        $this->partnerForm = [
            'name' => '',
            'slug' => '',
            'location' => '',
            'description' => '',
            'website' => '',
            'rooms' => null,
            'published' => true,
        ];
        $this->pendingImage = null;
    }

    public function cancelPartnerForm(): void
    {
        $this->editingPartnerId = null;
        $this->partnerIsNew = false;
        $this->partnerForm = [];
        $this->pendingImage = null;
    }

    public function savePartner(): void
    {
        $data = $this->validate([
            'partnerForm.name' => 'required|string|max:160',
            'partnerForm.slug' => 'nullable|string|max:160|alpha_dash',
            'partnerForm.location' => 'required|string|max:160',
            'partnerForm.description' => 'required|string|max:2000',
            'partnerForm.website' => 'nullable|url|max:240',
            'partnerForm.rooms' => 'nullable|integer|min:1|max:9999',
            'partnerForm.published' => 'boolean',
        ]);

        $form = $data['partnerForm'];
        $form['slug'] = $form['slug'] ?: Str::slug($form['name']);

        if ($this->editingPartnerId) {
            $partner = Partner::findOrFail($this->editingPartnerId);
            $partner->update($form);
        } else {
            $form['sort_order'] = (Partner::max('sort_order') ?? 0) + 1;
            $partner = Partner::create($form);
            $this->editingPartnerId = $partner->id;
            $this->partnerIsNew = false;
        }

        if ($this->pendingImage) {
            $this->validate(['pendingImage' => 'image|max:5120']);
            $partner->clearMediaCollection('logo');
            $partner->addMedia($this->pendingImage->getRealPath())
                ->usingFileName($this->pendingImage->getClientOriginalName())
                ->toMediaCollection('logo');
            $this->pendingImage = null;
        }

        $this->cancelPartnerForm();
    }

    public function removePartnerLogo(int $id): void
    {
        $partner = Partner::findOrFail($id);
        $partner->clearMediaCollection('logo');
    }

    public function deletePartner(int $id): void
    {
        Partner::where('id', $id)->delete();
        if ($this->editingPartnerId === $id) {
            $this->cancelPartnerForm();
        }
    }

    public function movePartner(int $id, string $direction): void
    {
        $partner = Partner::findOrFail($id);
        $neighbor = Partner::when(
            $direction === 'up',
            fn ($q) => $q->where('sort_order', '<', $partner->sort_order)->orderByDesc('sort_order'),
            fn ($q) => $q->where('sort_order', '>', $partner->sort_order)->orderBy('sort_order')
        )->first();

        if (! $neighbor) {
            return;
        }

        [$a, $b] = [$partner->sort_order, $neighbor->sort_order];
        $partner->update(['sort_order' => $b]);
        $neighbor->update(['sort_order' => $a]);
    }

    public function startEditingNav(): void
    {
        $this->navForm = NavigationItem::orderBy('sort_order')->get()
            ->map(fn ($i) => [
                'id' => $i->id,
                'label' => $i->label,
                'href' => $i->href,
                'published' => (bool) $i->published,
                'open_in_new_tab' => (bool) $i->open_in_new_tab,
            ])->all();
        $this->navDeletedIds = [];
        $this->editingNav = true;
    }

    public function cancelEditingNav(): void
    {
        $this->editingNav = false;
        $this->navForm = [];
        $this->navDeletedIds = [];
    }

    public function addNavItemRow(): void
    {
        $this->navForm[] = [
            'id' => null,
            'label' => 'New item',
            'href' => '/',
            'published' => true,
            'open_in_new_tab' => false,
        ];
    }

    public function removeNavItemRow(int $index): void
    {
        if (! array_key_exists($index, $this->navForm)) {
            return;
        }
        $row = $this->navForm[$index];
        if (! empty($row['id'])) {
            $this->navDeletedIds[] = $row['id'];
        }
        array_splice($this->navForm, $index, 1);
    }

    public function moveNavItemRow(int $index, string $direction): void
    {
        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if (! array_key_exists($index, $this->navForm) || ! array_key_exists($target, $this->navForm)) {
            return;
        }
        [$this->navForm[$index], $this->navForm[$target]] = [$this->navForm[$target], $this->navForm[$index]];
        $this->navForm = array_values($this->navForm);
    }

    public function saveNav(): void
    {
        if (! empty($this->navDeletedIds)) {
            NavigationItem::whereIn('id', $this->navDeletedIds)->delete();
        }

        foreach ($this->navForm as $i => $row) {
            $payload = [
                'label' => trim($row['label'] ?? ''),
                'href' => trim($row['href'] ?? '/'),
                'sort_order' => $i,
                'published' => (bool) ($row['published'] ?? true),
                'open_in_new_tab' => (bool) ($row['open_in_new_tab'] ?? false),
            ];

            if ($payload['label'] === '' || $payload['href'] === '') {
                continue;
            }

            if (! empty($row['id'])) {
                NavigationItem::where('id', $row['id'])->update($payload);
            } else {
                NavigationItem::create($payload);
            }
        }

        $this->cancelEditingNav();
    }

    public function startEditingSeo(): void
    {
        $this->seoForm = $this->page->seo ?? [];
        $this->editingSeo = true;
    }

    public function cancelEditingSeo(): void
    {
        $this->editingSeo = false;
        $this->seoForm = [];
    }

    public function saveSeo(): void
    {
        $page = Page::findOrFail($this->pageId);
        $page->seo = array_filter($this->seoForm, fn ($v) => $v !== null && $v !== '');
        $page->save();
        $this->editingSeo = false;
        unset($this->page);
    }

    private function ensureOwnedSection(int $sectionId): Section
    {
        $section = Section::findOrFail($sectionId);
        abort_if($section->page_id !== $this->pageId, 403);
        return $section;
    }

    public function availableBlockTypes(): array
    {
        return [
            'hero' => ['label' => 'Hero'],
            'rich_text' => ['label' => 'Rich Text'],
            'bullet_list' => ['label' => 'Bullet List'],
            'feature_grid' => ['label' => 'Feature Grid'],
            'partner_grid' => ['label' => 'Partner Grid'],
            'steps' => ['label' => 'Steps'],
            'cta' => ['label' => 'CTA Banner'],
            'founders' => ['label' => 'Founders'],
            'image' => ['label' => 'Image'],
            'contact_info' => ['label' => 'Contact Info'],
            'form' => ['label' => 'Form'],
        ];
    }

    public function blockSupportsImage(string $type, string $collection = 'image'): bool
    {
        return match ([$type, $collection]) {
            ['hero', 'bg'] => true,
            ['image', 'image'] => true,
            default => false,
        };
    }

    private function defaultContentFor(string $type): array
    {
        return match ($type) {
            'hero' => [
                'title' => 'New hero title',
                'subtitle' => 'Add a subtitle that explains what this page is about.',
                'ctas' => [],
            ],
            'rich_text' => [
                'title' => 'Section title',
                'body_html' => '<p>Add your text here…</p>',
            ],
            'bullet_list' => [
                'title' => 'List title',
                'items' => ['First item', 'Second item', 'Third item'],
            ],
            'feature_grid' => [
                'title' => 'Features',
                'columns' => 3,
                'items' => [
                    ['title' => 'Feature 1', 'text' => 'Describe it…'],
                    ['title' => 'Feature 2', 'text' => 'Describe it…'],
                    ['title' => 'Feature 3', 'text' => 'Describe it…'],
                ],
            ],
            'partner_grid' => [
                'title' => 'Partner Spaces',
            ],
            'steps' => [
                'title' => 'How it works',
                'items' => [
                    ['title' => 'Step 1', 'text' => 'Describe it…'],
                    ['title' => 'Step 2', 'text' => 'Describe it…'],
                ],
            ],
            'cta' => [
                'title' => 'Ready to get started?',
                'text' => 'A short, encouraging line.',
                'button' => ['label' => 'Get in touch', 'href' => '/contact'],
            ],
            'founders' => [
                'title' => 'The founders',
                'items' => [['name' => 'Name', 'role' => 'Role']],
            ],
            'image' => [
                'caption' => '',
                'alt' => '',
            ],
            'contact_info' => [
                'email' => 'info@colivingfounders.com',
                'phone' => '+39 388 446 2409',
            ],
            'form' => [
                'form_id' => 'contact',
                'title' => 'Get in touch',
            ],
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.page-editor');
    }
}
