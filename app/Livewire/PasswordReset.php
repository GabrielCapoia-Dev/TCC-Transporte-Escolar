<?php

namespace App\Livewire;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SimplePage;
use Filament\Infolists\Components\Section;
use Filament\Notifications\Notification;

class PasswordReset extends SimplePage
{
    protected static string $view = 'livewire.password-reset';

    protected static ?string $title = 'Redefinir Senha';

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required(),

            TextInput::make('password')
                ->label('Senha')
                ->helperText('Senha gerada pelo administrador.')
                ->password()
                ->required()
                ->revealable(),

            TextInput::make('new_password')
                ->label('Nova Senha')
                ->password()
                ->required()
                ->revealable(),

            TextInput::make('new_password_confirmation')
                ->label('Confirmação de Nova Senha')
                ->password()
                ->required()
                ->revealable(),
            Actions::make([
                Actions\Action::make('submit')
                    ->submit('send')
                    ->label('Enviar'),
                Actions\Action::make('back')
                    ->label('Voltar para Login')
                    ->link()
                    ->url(filament()->getLoginUrl())
            ])
            ->fullWidth()

        ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();
        
        Notification::make()
            ->title('Senha Redefinida')
            ->body('Sua senha foi redefinida com sucesso.')
            ->success()
            ->send();

            
        $this->form->fill();
        $this->redirect(filament()->getLoginUrl());
        
    }
}