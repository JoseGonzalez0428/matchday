<?php

namespace App\Services;

use App\Models\TournamentMatch;
use Illuminate\Support\Facades\Http;

class MatchPredictionService
{
    public function predict(TournamentMatch $match): array
    {
        $home = $match->homeTeam;
        $away = $match->awayTeam;
        $stage = \App\Helpers\StatusHelper::stage($match->stage);

        // Estadísticas del torneo actual
        $homeStats = $this->getTeamStats($match->tournament_id, $home->id);
        $awayStats = $this->getTeamStats($match->tournament_id, $away->id);

        $prompt = "Eres un analista de fútbol. Responde SOLO con JSON válido, sin texto adicional, sin markdown.\n\n" .
            "Predice: {$home->name} vs {$away->name} ({$stage})\n" .
            "Stats {$home->name}: {$homeStats['played']}PJ {$homeStats['gf']}GF {$homeStats['gc']}GC {$homeStats['points']}pts\n" .
            "Stats {$away->name}: {$awayStats['played']}PJ {$awayStats['gf']}GF {$awayStats['gc']}GC {$awayStats['points']}pts\n\n" .
            "Formato exacto (analysis máximo 15 palabras):\n" .
            "{\"home_score\":0,\"away_score\":0,\"analysis\":\"texto corto\",\"winner\":\"equipo\"}";

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type'   => 'application/json',
            'X-goog-api-key' => config('services.gemini.key'),
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent", [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'maxOutputTokens' => 600,
                'temperature'     => 0.8,
            ]
        ]);

        if ($response->failed()) {
            return ['error' => 'No se pudo obtener la predicción.'];
        }

        $text = $response->json('candidates.0.content.parts.0.text', '{}');
        \Illuminate\Support\Facades\Log::info('Gemini response: ' . $text);

        // Limpiar formato markdown
        $text = preg_replace('/```json\s*/i', '', $text);
        $text = preg_replace('/```\s*/i', '', $text);
        $text = trim($text);

        // Extraer JSON si viene con texto adicional
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $text = $matches[0];
        }

        try {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE && $decoded) {
                return $decoded;
            }
            return ['error' => 'La IA no devolvió un formato válido.'];
        } catch (\Exception $e) {
            return ['error' => 'Error al procesar la predicción.'];
        }
    }

    private function getTeamStats(int $tournamentId, int $teamId): array
    {
        $matches = \App\Models\TournamentMatch::where('tournament_id', $tournamentId)
            ->where('status', 'finished')
            ->where(fn($q) => $q->where('home_team_id', $teamId)->orWhere('away_team_id', $teamId))
            ->get();

        $stats = ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'gc' => 0, 'points' => 0];

        foreach ($matches as $m) {
            $isHome = $m->home_team_id === $teamId;
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