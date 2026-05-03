<?php

namespace App\Mail;

use App\Models\TournamentMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MatchResultMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TournamentMatch $match,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Resultado: {$this->match->homeTeam->name} {$this->match->home_score} - {$this->match->away_score} {$this->match->awayTeam->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.match-result',
        );
    }
}