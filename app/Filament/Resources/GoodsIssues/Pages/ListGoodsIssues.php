<?php

namespace App\Filament\Resources\GoodsIssues\Pages;

use App\Filament\Resources\GoodsIssues\GoodsIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoodsIssues extends ListRecords
{
    protected static string $resource = GoodsIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
