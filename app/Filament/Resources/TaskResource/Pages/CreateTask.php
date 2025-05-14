<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\User\StoreHelper;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class CreateTask extends CreateRecord
{
    use StoreHelper;

    protected static string $resource = TaskResource::class;

    public function getTitle(): string
    {
        return 'Создать';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Сохранить');
    }

    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Сохранить и создать ещё');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $data['user_id'] = $user->id;

        $referer = request()->headers->get('referer');

        if (! $referer) {
            return $data;
        }

        $queryString = parse_url($referer, PHP_URL_QUERY);

        if (! $queryString) {
            return $data;
        }

        parse_str($queryString, $queryParams);

        if (filter_var($queryParams['sample'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $data['sample'] = true;
        }

        return $data;
    }

}
