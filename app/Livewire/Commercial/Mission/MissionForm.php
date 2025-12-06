<?php

namespace App\Livewire\Commercial\Mission;

use App\Enums\MissionStatus;
use App\Models\Mission;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MissionForm extends Component
{
    #[Locked]
    public ?Mission $mission = null;

    public string $title = '';

    public string $description = '';

    public string $location = '';

    /** @var array<int> */
    public array $selectedTags = [];

    public string $newTagName = '';

    public function mount(?Mission $mission = null): void
    {
        if ($mission && $mission->exists) {
            $this->mission = $mission;
            $this->title = $mission->title;
            $this->description = $mission->description ?? '';
            $this->location = $mission->location ?? '';
            $this->selectedTags = $mission->tags->pluck('id')->toArray();
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:255'],
            'selectedTags' => ['array'],
            'selectedTags.*' => ['exists:tags,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 5000 caractères.',
            'location.required' => 'Le lieu est obligatoire.',
            'location.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
        ];
    }

    public function toggleTag(int $tagId): void
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_values(array_diff($this->selectedTags, [$tagId]));
        } else {
            $this->selectedTags[] = $tagId;
        }
    }

    public function addTag(): void
    {
        $this->validate([
            'newTagName' => ['required', 'string', 'min:2', 'max:50'],
        ], [
            'newTagName.required' => 'Le nom de la compétence est obligatoire.',
            'newTagName.min' => 'Le nom doit contenir au moins 2 caractères.',
            'newTagName.max' => 'Le nom ne peut pas dépasser 50 caractères.',
        ]);

        $normalizedName = Str::title(trim($this->newTagName));

        // Check if tag already exists (case-insensitive)
        $existingTag = Tag::whereRaw('LOWER(name) = ?', [Str::lower($normalizedName)])->first();

        if ($existingTag) {
            // Select the existing tag if not already selected
            if (! in_array($existingTag->id, $this->selectedTags)) {
                $this->selectedTags[] = $existingTag->id;
            }
        } else {
            // Create new tag
            $tag = Tag::create(['name' => $normalizedName]);
            $this->selectedTags[] = $tag->id;
        }

        $this->newTagName = '';
    }

    public function save(): void
    {
        $this->validate();

        if ($this->mission) {
            $this->mission->update([
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
            ]);

            $this->mission->tags()->sync($this->selectedTags);

            session()->flash('success', 'Mission mise à jour avec succès.');
        } else {
            $mission = Mission::create([
                'commercial_id' => Auth::id(),
                'title' => $this->title,
                'description' => $this->description,
                'location' => $this->location,
                'status' => MissionStatus::Active,
            ]);

            $mission->tags()->sync($this->selectedTags);

            session()->flash('success', 'Mission créée avec succès.');
        }

        $this->redirect(route('commercial.missions.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.commercial.mission.mission-form', [
            'tags' => Tag::orderBy('name')->get(),
            'isEditing' => $this->mission !== null,
        ]);
    }
}
