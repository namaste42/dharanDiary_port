<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author'] = Auth::user()->name;
        return $data;
    }

    // protected function getRedirectUrl(): string
    // {
    //     return static::$resource::getUrl('index');
    // }
}
