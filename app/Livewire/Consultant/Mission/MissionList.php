<?php

namespace App\Livewire\Consultant\Mission;

use App\Enums\MissionStatus;
use App\Models\Mission;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MissionList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    /** @var array<int> */
    #[Url]
    public array $selectedTags = [];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedTags(): void
    {
        $this->resetPage();
    }

    public function toggleTag(int $tagId): void
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
        } else {
            $this->selectedTags[] = $tagId;
        }
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedTags = [];
        $this->resetPage();
    }

    #[Computed]
    public function availableTags(): Collection
    {
        return Tag::query()
            ->whereHas('missions', function ($query) {
                $query->where('status', MissionStatus::Active);
            })
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        $missions = Mission::query()
            ->with(['commercial', 'tags'])
            ->where('status', MissionStatus::Active)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'ilike', "%{$this->search}%")
                        ->orWhere('description', 'ilike', "%{$this->search}%")
                        ->orWhere('location', 'ilike', "%{$this->search}%");
                });
            })
            ->when(! empty($this->selectedTags), function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->whereIn('tags.id', $this->selectedTags);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.consultant.mission.mission-list', [
            'missions' => $missions,
        ]);
    }
}
