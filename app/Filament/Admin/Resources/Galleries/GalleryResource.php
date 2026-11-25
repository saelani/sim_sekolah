<?php

namespace App\Filament\Admin\Resources\Galleries;

use App\Filament\Admin\Resources\Galleries\Tables\GalleryTable;
use App\Filament\Admin\Resources\Galleries\Schemas\GalleryForm;
use App\Models\Gallery;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;


    protected static ?string $navigationLabel = 'Galeri';

    protected static ?int $navigationSort = 15;

    public static function getNavigationGroup(): ?string
    {
        return 'Media';
    }

    public static function form(Schema $schema): Schema
    {
        return GalleryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
