<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\Section;
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
