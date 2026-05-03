<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\StandingsService;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function fixture(Tournament $tournament)
    {
        $matches = $tournament->matches()
            ->with(['homeTeam', 'awayTeam', 'group'])
            ->orderBy('played_at')
            ->get()
            ->groupBy('group.name');

        $pdf = Pdf::loadView('pdf.fixture', compact('tournament', 'matches'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("fixture-{$tournament->name}.pdf");
    }

    public function standings(Tournament $tournament, StandingsService $service)
    {
        $standings = $service->calculate($tournament);

        $pdf = Pdf::loadView('pdf.standings', compact('tournament', 'standings'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("standings-{$tournament->name}.pdf");
    }
}