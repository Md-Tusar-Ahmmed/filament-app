<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manage Post';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    
    


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Post Details')
                    ->collapsible()
                    ->schema([

                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('title')->required()
                            ->live(debounce: true)
                            ->afterStateUpdated(
                                function (string $operation, $state, Forms\Set $set) {
                                    $set('slug', Str::slug($state));
                                }
                            ),
                        TextInput::make('slug')->required()->unique(ignoreRecord: true),


                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required()
                                    ->live(debounce: true)
                                    ->afterStateUpdated(
                                        function (string $operation, $state, Forms\Set $set) {
                                            $set('slug', Str::slug($state));
                                        }
                                    ),

                                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                            ]),

                        MarkdownEditor::make('content')->required()->columnSpanFull(),

                        Repeater::make('Custom Data')
                            ->relationship('customField')
                            ->schema([
                                TextInput::make('customText1')->required()->columns(1),
                                TextInput::make('customText2')->required(),

                            ])->columnSpan(2)->columns(2)
                    ])->columnSpan(2)->columns(2),

                Section::make('meta')
                    ->schema([
                        FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),

                        Select::make('tags_id')
                            ->relationship('tags', 'tags')
                            ->required()
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('tags')
                                    ->required(),
                            ]),
                        Checkbox::make('published'),

                    ])->columnSpan(1)->columns(1),



            ])->columns(3);
    }

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->limit(20)
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Post Author')
                    ->limit(20)
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->limit(15)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                ImageColumn::make('thumbnail')
                    ->disk('public')
                    ->toggleable(),
                TextColumn::make('tags.tags')
                    ->limit(15)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                CheckboxColumn::make('published')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Posted On')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            
            ->filters([
                TernaryFilter::make('published'),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
 
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
