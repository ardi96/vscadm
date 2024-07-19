<?php

namespace App\Filament\Portal\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class Chatbot extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static string $view = 'filament.portal.pages.chatbot';


    public $question;
    public $response;

    public function mount()
    {
        $this->question = '';
        $this->response = '';
    }

    public function submit()
    {
        // Here you can add the logic to generate a response to the question.
        // For demonstration, we'll just echo the question back.
        $this->response = "You asked: " . $this->question;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('question')
                ->label('Your Question')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('response', null)),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Chatbot';
    }

}
