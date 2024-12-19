<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input)
    {
        Validator::make(
            $input,
            [
                'name' => ['required', 'string', 'max:255', 'regex:/^\S*$/', 'min:5'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'profile_name' => ['required', 'string', 'max:255', 'min:3'],
                'no_identity' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^\d+$/',
                    'min:5'
                ],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            ],
            [
                'name.regex' => 'The profile name must not contain spaces.',
                'no_identity.regex' => 'NIP must contain only numbers.',
                'no_identity.min' => 'NIP must be at least 5 characters long.',
            ]
        )
            ->sometimes('no_identity', 'required', function ($input) use ($user) {
                return $user->role === 'auditor';
            })
            ->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $input['photo']->store('profile_photos', 'public');

            $user->forceFill([
                'profile_photo_path' => $path
            ])->save();
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => strtolower($input['name']),
                'email' => strtolower($input['email']),
                'no_identity' => $input['no_identity'],
                'profile_name' => $input['profile_name'],
            ])->save();
        }

        return redirect()->route('profile.show');
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
