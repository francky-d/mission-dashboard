<?php

namespace App\Livewire\Consultant\Profile;

use App\Models\ConsultantProfile;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    #[Validate('nullable|string|max:2000')]
    public string $bio = '';

    #[Validate('nullable|integer|min:0|max:50')]
    public ?int $experienceYears = 0;

    #[Validate('nullable|file|mimes:pdf|max:5120')]
    public $cv;

    public ?string $existingCvUrl = null;

    /** @var array<int> */
    public array $selectedTags = [];

    public function mount(): void
    {
        $user = Auth::user();
        $profile = $user->consultantProfile;

        if ($profile) {
            $this->bio = $profile->bio ?? '';
            $this->experienceYears = $profile->experience_years ?? 0;
            $this->existingCvUrl = $profile->cv_url;
            $this->selectedTags = $profile->tags->pluck('id')->toArray();
        }
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        $profile = $user->consultantProfile ?? new ConsultantProfile(['user_id' => $user->id]);

        $profile->bio = $this->bio;
        $profile->experience_years = $this->experienceYears;

        if ($this->cv) {
            // Delete old CV if exists
            if ($profile->cv_url) {
                Storage::disk('public')->delete($profile->cv_url);
            }

            $path = $this->cv->store('cvs', 'public');
            $profile->cv_url = $path;
            $this->existingCvUrl = $path;
        }

        $profile->save();

        // Sync tags
        $profile->tags()->sync($this->selectedTags);

        $this->dispatch('profile-updated');
    }

    public function deleteCv(): void
    {
        $user = Auth::user();
        $profile = $user->consultantProfile;

        if ($profile && $profile->cv_url) {
            Storage::disk('public')->delete($profile->cv_url);
            $profile->cv_url = null;
            $profile->save();
            $this->existingCvUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.consultant.profile.edit-profile', [
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }
}
