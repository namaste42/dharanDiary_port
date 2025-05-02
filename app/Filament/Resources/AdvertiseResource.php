<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdvertiseResource\Pages;
use App\Filament\Resources\AdvertiseResource\RelationManagers;
use App\Models\Advertise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AdvertiseResource extends Resource
{
    protected static ?string $model = Advertise::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->unique(Advertise::class, 'slug', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('advertises')
                    ->required(),
                Forms\Components\DatePicker::make('expire_date'),
                Forms\Components\TextInput::make('redirect_url')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'sidebar' => 'Sidebar',
                        'sidebar_top' => 'Sidebar Top',
                        'main_content' => 'Main Content',
                    ])
                    ->required()
                    // ->reactive()
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation === 'create') {
                            $set('location', $state);
                        }
                    })
                    ->default('header'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('expire_date')
                //     ->date()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('expire_date')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return 'No Expiration';
                        }

                        return \Carbon\Carbon::parse($state)->isPast()
                            ? 'Expired'
                            : 'Active';
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('redirect_url')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertises::route('/'),
            'create' => Pages\CreateAdvertise::route('/create'),
            'edit' => Pages\EditAdvertise::route('/{record}/edit'),
        ];
    }
}
