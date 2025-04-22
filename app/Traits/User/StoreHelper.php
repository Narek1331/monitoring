<?php

namespace App\Traits\User;

use Illuminate\Support\Facades\Auth;

trait StoreHelper
{
    /**
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        return $data;
    }
}
