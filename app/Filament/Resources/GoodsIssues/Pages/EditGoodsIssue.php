<?php

namespace App\Filament\Resources\GoodsIssues\Pages;

use App\Filament\Resources\GoodsIssues\GoodsIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGoodsIssue extends EditRecord
{
    protected static string $resource = GoodsIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
