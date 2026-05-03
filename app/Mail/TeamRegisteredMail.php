<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TeamRegisteredMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Team $team,
        public Tournament $tournament,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "¡Tu equipo {$this->team->name} está inscrito en {$this->tournament->name}!",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.team-registered',
        );
    }
}