<?php

namespace App\Mail;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\TournamentMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TournamentWonMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tournament $tournament,
        public Team $team,
        public TournamentMatch $final
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏆 ¡Campeones! — ' . $this->tournament->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tournament-won',
        );
    }
}