<?php

namespace App\Filament\Resources\GoodsIssues\Pages;

use App\Filament\Resources\GoodsIssues\GoodsIssueResource;
use App\Models\Document;
use Filament\Resources\Pages\CreateRecord;

class CreateGoodsIssue extends CreateRecord
{
    protected static string $resource = GoodsIssueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['document_type'] = Document::TYPE_ISSUE;
        return $data;
    }
}
