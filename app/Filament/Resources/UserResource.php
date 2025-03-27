<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Validation\Rules\Password;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->unique('users', 'email', fn($record) => $record)
                    ->required(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->revealable()
                    ->rule(Password::default())
                    ->required(fn($record) => $record === null)
                    ->dehydrateStateUsing(fn($state, $record) => $state ? Hash::make($state) : $record->password),

                Select::make('roles')
                    ->multiple() // Allow multiple roles
                    ->preload() // Load options immediately
                    ->searchable() // Enable search
                    ->relationship('roles', 'name') // Define the relationship
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->unique('roles', 'name', fn($record) => $record)
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data, $livewire): int {
                        $role = Role::create(['name' => $data['name']]);
                        $user = $livewire->getMountedTableActionRecord();

                        $user->roles()->attach($role->id, [
                            'model_type' => User::class,
                        ]);

                        return $role->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')

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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
