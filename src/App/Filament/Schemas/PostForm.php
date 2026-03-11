<?php

namespace Lines\Skeleton\App\Filament\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->components([
                    self::title(),
                    self::body(),
                ])
                    ->columnSpan(3)
                    ->columnOrder([
                        'default' => 2,
                        'lg' => 1,
                    ]),
                Section::make()->components([
                    Toggle::make('should_publish')
                        ->label('Publish')
                        ->live()
                        ->saved(false),
                    self::published(),
                ])
                    ->columnSpan(1)
                    ->columnOrder([
                        'default' => 1,
                        'lg' => 2,
                    ]),
            ]);
    }

    private static function title(): TextInput
    {
        return TextInput::make('title')
            ->autofocus()
            ->label('Title')
            ->inputMode('text')
            ->minLength(8)
            ->maxLength(128)
            ->placeholder(fake()->realTextBetween(8, 32))
            ->required()
            ->trim();
    }

    private static function body(): MarkdownEditor
    {
        return MarkdownEditor::make('body')
            ->label('Content')
            ->placeholder(fake()->realText(256))
            ->required();
    }

    private static function published(): DatePicker
    {
        return DatePicker::make('published_at')
            ->label('Publish on')
            ->native(false)
            ->closeOnDateSelection()
            ->displayFormat('d-m-Y')
            ->default(now())
            ->formatStateUsing(fn ($s) => $s ?? now())
            ->locale('nl')
            ->required(fn (Get $get): bool => $get('should_publish'))
            ->visible(fn (Get $get): bool => $get('should_publish'));
    }
}
