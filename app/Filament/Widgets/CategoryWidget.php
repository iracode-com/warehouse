<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use SolutionForest\FilamentTree\Widgets\Tree;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;

class CategoryWidget extends Tree
{
    protected static string $model = Category::class;

    protected static int $maxDepth = 3;

    protected ?string $treeTitle = 'دسته بندی ها';

    protected bool $enableTreeTitle = true;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('نام دسته‌بندی')
                ->required()
                ->maxLength(255),
                
            TextInput::make('code')
                ->label('کد دسته‌بندی')
                ->required()
                ->maxLength(255),
                
            Textarea::make('description')
                ->label('توضیحات')
                ->rows(3),
        ];
    }

    protected function getViewFormSchema(): array
    {
        return [
            TextEntry::make('name')
                ->label('نام دسته‌بندی'),
                
            TextEntry::make('code')
                ->label('کد دسته‌بندی'),
                
            TextEntry::make('description')
                ->label('توضیحات'),
        ];
    }

    protected function getTreeToolbarActions(): array
    {
        return [
            //
        ];
    }

    // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE
    // public function getTreeRecordIcon(?\Illuminate\Database\Eloquent\Model $record = null): ?string
    // {
    //     return null;
    // }

    // CUSTOMIZE ACTION OF EACH RECORD, CAN DELETE 
    // protected function getTreeActions(): array
    // {
    //     return [
    //         Action::make('helloWorld')
    //             ->action(function () {
    //                 Notification::make()->success()->title('Hello World')->send();
    //             }),
    //         // ViewAction::make(),
    //         // EditAction::make(),
    //         ActionGroup::make([
    //             
    //             ViewAction::make(),
    //             EditAction::make(),
    //         ]),
    //         DeleteAction::make(),
    //     ];
    // }
    // OR OVERRIDE FOLLOWING METHODS
    //protected function hasDeleteAction(): bool
    //{
    //    return true;
    //}
    //protected function hasEditAction(): bool
    //{
    //    return true;
    //}
    //protected function hasViewAction(): bool
    //{
    //    return true;
    //}
}
