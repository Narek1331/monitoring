<?php

namespace App\Traits\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait GetHelper
{
    /**
     * Get the base Eloquent query builder instance, adding user-specific filtering.
     *
     * This method modifies the query by adding a `user_id` filter if the user is authenticated.
     *
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user) {
            $query->where('user_id', $user->main_user_id ?? $user->id);
        }

        return $query;
    }
}
