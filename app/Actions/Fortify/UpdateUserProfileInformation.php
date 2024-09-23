<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Log::info($input);
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => ['string', 'max:64'],
            'description' => ['string', 'max:256', 'nullable'],
            'skill' => ['string', 'max:512', 'nullable'],
            'x_link' => ['string', 'url:http,https', 'nullable'],
            'website' => ['string', 'url:http,https', 'nullable'],
            'created_game' => ['string', 'max:512', 'nullable'],
            'contributed_game' => ['string', 'max:512', 'nullable'],
            'image' => ['string', 'nullable']
        ])->validateWithBag('updateProfileInformation');

        if(!array_key_exists('image', $input)) {
            $input['image'] = $user->image;
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'role' => $input['role'],
                'description' => $input['description'],
                'image' => $input['image'],
                'skill' => $input['skill'],
                'x_link' => $input['x_link'],
                'website' => $input['website'],
                'created_game' => $input['created_game'],
                'contributed_game' => $input['contributed_game'],
            ])->save();
        }
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