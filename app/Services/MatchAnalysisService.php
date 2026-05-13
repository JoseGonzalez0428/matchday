<?php

namespace App\Services;

use App\Models\Team;
use App\Models\TournamentMatch;
use Illuminate\Support\Facades\Http;

class MatchAnalysisService
{
    public function analyze(Team $team, TournamentMatch $nextMatch): string
    {
        $isHome   = $nextMatch->home_team_id === $team->id;
        $opponent = $isHome ? $nextMatch->awayTeam : $nextMatch->homeTeam;

        $stats         = $this->getTeamStats($team);
        $recentResults = $this->getRecentResults($team, 5);

        $prompt = "Eres un analista deportivo experto en fútbol. " .
            "Analiza el siguiente próximo partido y da una predicción CORTA de exactamente 2 párrafos " .
            "en español, con tono profesional y motivador. No uses markdown ni asteriscos.\n\n" .
            "EQUIPO: {$team->name}\n" .
            "ROL: " . ($isHome ? 'Local' : 'Visitante') . "\n" .
            "RIVAL: {$opponent->name}\n\n" .
            "ESTADÍSTICAS ACTUALES:\n" .
            "- Partidos jugados: {$stats['played']}\n" .
            "- Victorias: {$stats['won']} | Empates: {$stats['drawn']} | Derrotas: {$stats['lost']}\n" .
            "- Goles a favor: {$stats['gf']} | Goles en contra: {$stats['gc']}\n" .
            "- Puntos: {$stats['points']}\n\n" .
            "ÚLTIMOS 5 RESULTADOS: {$recentResults}\n\n" .
            "FECHA DEL PARTIDO: {$nextMatch->played_at->format('d/m/Y H:i')}";

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type'  => 'application/json',
            'X-goog-api-key' => config('services.gemini.key'),
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => 1200,
                'temperature'     => 0.7,
            ]
        ]);

        if ($response->failed()) {
            return 'El análisis no está disponible en este momento.';
        }

        return $response->json('candidates.0.content.parts.0.text', 
            'Sin análisis disponible.');
    }

    private function getRecentResults(Team $team, int $n): string
    {
        $matches = TournamentMatch::where('status', 'finished')
            ->where(fn($q) => $q->where('home_team_id', $team->id)
                ->orWhere('away_team_id', $team->id))
            ->latest('played_at')
            ->take($n)
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        if ($matches->isEmpty()) return 'Sin partidos jugados aún.';

        return $matches->map(function ($m) use ($team) {
            $isHome   = $m->home_team_id === $team->id;
            $myGoals  = $isHome ? $m->home_score : $m->away_score;
            $oppGoals = $isHome ? $m->away_score : $m->home_score;
            $opponent = $isHome ? $m->awayTeam->name : $m->homeTeam->name;
            $result   = $myGoals > $oppGoals ? 'V' : ($myGoals < $oppGoals ? 'D' : 'E');
            return "{$result} {$myGoals}-{$oppGoals} vs {$opponent}";
        })->implode(', ');
    }

    private function getTeamStats(Team $team): array
    {
        $finished = TournamentMatch::where('status', 'finished')
            ->where(fn($q) => $q->where('home_team_id', $team->id)
                ->orWhere('away_team_id', $team->id))
            ->get();

        $stats = ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'gc' => 0, 'points' => 0];

        foreach ($finished as $m) {
            $isHome = $m->home_team_id === $team->id;
            $myG    = $isHome ? $m->home_score : $m->away_score;
            $oppG   = $isHome ? $m->away_score : $m->home_score;

            $stats['played']++;
            $stats['gf'] += $myG;
            $stats['gc'] += $oppG;

            if ($myG > $oppG)       { $stats['won']++;   $stats['points'] += 3; }
            elseif ($myG === $oppG) { $stats['drawn']++; $stats['points']++; }
            else                    { $stats['lost']++; }
        }

        return $stats;
    }
}