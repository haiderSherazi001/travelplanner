<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $bio = '';
    public string $travel_style = '';
    
    // We use a different variable name for the uploaded file so it doesn't conflict with the text path in the DB
    public $avatar_file; 

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone ?? '';
        $this->bio = Auth::user()->bio ?? '';
        $this->travel_style = Auth::user()->travel_style ?? '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'travel_style' => ['nullable', 'string', 'max:50'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user->fill([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'travel_style' => $this->travel_style,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->avatar_file) {
            $user->avatar = $this->avatar_file->store('avatars', 'public');
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        
        <!-- Avatar Upload -->
        <div class="flex items-center gap-6">
            <div class="shrink-0">
                @if ($avatar_file)
                    <img src="{{ $avatar_file->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-full border border-gray-200">
                @else
                    <img src="{{ auth()->user()->avatar_url }}" class="h-16 w-16 object-cover rounded-full border border-gray-200">
                @endif
            </div>
            <div>
                <x-input-label for="avatar_file" :value="__('Change Profile Picture')" />
                <input id="avatar_file" type="file" wire:model="avatar_file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar_file')" />
                <div wire:loading wire:target="avatar_file" class="text-xs text-indigo-600 mt-1 font-semibold">Uploading preview...</div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Phone Number -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number (Optional)')" />
            <x-text-input wire:model="phone" id="phone" name="phone" type="text" class="mt-1 block w-full" placeholder="+1 234 567 8900" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Travel Style -->
        <div>
            <x-input-label for="travel_style" :value="__('Travel Style')" />
            <select wire:model="travel_style" id="travel_style" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select a style...</option>
                <option value="Thrill-seeker">Thrill-seeker</option>
                <option value="Relaxation Expert">Relaxation Expert</option>
                <option value="Foodie">Foodie</option>
                <option value="Culture Explorer">Culture Explorer</option>
                <option value="Budget Backpacker">Budget Backpacker</option>
                <option value="Luxury Traveler">Luxury Traveler</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('travel_style')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Short Bio')" />
            <textarea wire:model="bio" id="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="A little about yourself..."></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>