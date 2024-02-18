<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Notifications\InvitationUser;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make(Str::random(10));

        return $data;
    }

    public function afterCreate()
    {
        $user = User::find($this->record->id);
        $token = Password::getRepository()->create($user);

        $user->notify(new InvitationUser($token));
    }
}
