<?php

namespace App\Filament\Pages;

use App\Mail\contactMail;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Button;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;

class Contact extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.contact';

    
    public $email;
    public $subject;
    public $msg;

    public function form(Form $form): Form
    {
        return $form->schema([
                    TextInput::make('email')
                        ->email()
                        ->required(),

                    TextInput::make('subject')
                        ->required(),

                    MarkdownEditor::make('msg')
                        ->label('Message')
                        ->required(),
                    
                    
        ]);
    }


    public function getFormActions():array    {
        return[
        ];

    }

    public function submit(){

        $toEmail = $this->email;
        $subject = $this->subject;
        $msg = $this->msg;

        Mail::to($toEmail)->send(new contactMail($msg, $subject));

        Notification::make()
            ->title('Message Sent')
            ->success()
            ->send();

        $this->reset();
    }
}
