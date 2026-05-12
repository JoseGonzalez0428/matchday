<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\Player;
use App\Models\TournamentMatch;
use App\Models\Goal;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuarios capitanes ────────────────────────────────
        $captains = [];
        $captainData = [
            ['name' => 'Carlos Herrera',  'email' => 'carlos@matchday.test'],
            ['name' => 'Luis Martínez',   'email' => 'luis@matchday.test'],
            ['name' => 'Pedro Sánchez',   'email' => 'pedro@matchday.test'],
            ['name' => 'Miguel Torres',   'email' => 'miguel@matchday.test'],
            ['name' => 'Juan García',     'email' => 'juan@matchday.test'],
            ['name' => 'Roberto López',   'email' => 'roberto@matchday.test'],
            ['name' => 'Andrés Ramírez',  'email' => 'andres@matchday.test'],
            ['name' => 'Diego Flores',    'email' => 'diego@matchday.test'],
        ];

        foreach ($captainData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole('captain');
            $captains[] = $user;
        }

        // ── Torneo ────────────────────────────────────────────
        $tournament = Tournament::firstOrCreate(
            ['name' => 'Copa MatchDay 2026'],
            [
                'edition'    => 2026,
                'format'     => 'groups_knockout',
                'status'     => 'active',
                'starts_at'  => '2026-06-01',
                'ends_at'    => '2026-06-30',
                'created_by' => 1,
            ]
        );

        // ── Grupos ────────────────────────────────────────────
        $groupNames = ['A', 'B'];
        $groups = [];
        foreach ($groupNames as $name) {
            $groups[] = Group::firstOrCreate([
                'tournament_id' => $tournament->id,
                'name'          => $name,
            ]);
        }

        // ── Equipos y jugadores ───────────────────────────────
        $teamsData = [
            ['name' => 'Águilas FC',      'country' => 'México',    'captain' => $captains[0]],
            ['name' => 'Tigres del Sur',  'country' => 'México',    'captain' => $captains[1]],
            ['name' => 'Real Potosí',     'country' => 'México',    'captain' => $captains[2]],
            ['name' => 'Deportivo Norte', 'country' => 'México',    'captain' => $captains[3]],
            ['name' => 'Leones FC',       'country' => 'México',    'captain' => $captains[4]],
            ['name' => 'Club Halcones',   'country' => 'México',    'captain' => $captains[5]],
            ['name' => 'Atlético Sur',    'country' => 'México',    'captain' => $captains[6]],
            ['name' => 'FC Bravo',        'country' => 'México',    'captain' => $captains[7]],
        ];

        $positions = ['GK', 'DEF', 'DEF', 'DEF', 'DEF', 'MID', 'MID', 'MID', 'FWD', 'FWD', 'FWD'];
        $teams = [];

        foreach ($teamsData as $i => $data) {
            $team = Team::firstOrCreate(
                ['name' => $data['name']],
                ['country' => $data['country'], 'captain_id' => $data['captain']->id]
            );

            // Asignar al grupo correspondiente (4 equipos por grupo)
            $group = $groups[$i < 4 ? 0 : 1];
            if (!$group->teams()->where('team_id', $team->id)->exists()) {
                $group->teams()->attach($team->id);
            }

            // Crear jugadores si no tiene
            if ($team->players()->count() === 0) {
                foreach ($positions as $j => $position) {
                    Player::create([
                        'team_id'     => $team->id,
                        'name'        => "Jugador " . ($j + 1) . " - " . $team->name,
                        'dorsal'      => $j + 1,
                        'position'    => $position,
                        'nationality' => 'Mexicana',
                    ]);
                }
            }

            $teams[] = $team;
        }

        // ── Partidos fase de grupos ───────────────────────────
        $matchDate = \Carbon\Carbon::parse('2026-06-01');

        foreach ($groups as $group) {
            $groupTeams = $group->teams()->get();
            $n = $groupTeams->count();

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $exists = TournamentMatch::where('home_team_id', $groupTeams[$i]->id)
                        ->where('away_team_id', $groupTeams[$j]->id)
                        ->where('tournament_id', $tournament->id)
                        ->exists();

                    if (!$exists) {
                        $homeScore = rand(0, 4);
                        $awayScore = rand(0, 3);

                        $match = TournamentMatch::create([
                            'tournament_id' => $tournament->id,
                            'group_id'      => $group->id,
                            'home_team_id'  => $groupTeams[$i]->id,
                            'away_team_id'  => $groupTeams[$j]->id,
                            'home_score'    => $homeScore,
                            'away_score'    => $awayScore,
                            'played_at'     => $matchDate->copy()->addDays(rand(0, 20))->setHour(rand(16, 20)),
                            'stage'         => 'group',
                            'status'        => 'finished',
                        ]);

                        // Goles del equipo local
                        $homePlayers = $groupTeams[$i]->players()
                            ->whereIn('position', ['MID', 'FWD'])->get();
                        for ($g = 0; $g < $homeScore; $g++) {
                            Goal::create([
                                'match_id'  => $match->id,
                                'player_id' => $homePlayers->random()->id,
                                'minute'    => rand(1, 90),
                                'type'      => 'regular',
                            ]);
                        }

                        // Goles del equipo visitante
                        $awayPlayers = $groupTeams[$j]->players()
                            ->whereIn('position', ['MID', 'FWD'])->get();
                        for ($g = 0; $g < $awayScore; $g++) {
                            Goal::create([
                                'match_id'  => $match->id,
                                'player_id' => $awayPlayers->random()->id,
                                'minute'    => rand(1, 90),
                                'type'      => 'regular',
                            ]);
                        }
                    }
                }
            }
        }

        // ── Partido pendiente (para probar próximo partido) ───
        TournamentMatch::firstOrCreate(
        [
            'tournament_id' => $tournament->id,
            'home_team_id'  => $teams[0]->id,
            'away_team_id'  => $teams[5]->id,
            'stage'         => 'semi',
        ],
            [
                'group_id'   => null,
                'played_at'  => \Carbon\Carbon::now()->addDays(3)->setHour(18),
                'status'     => 'scheduled',
                'home_score' => null,
                'away_score' => null,
            ]
        );

        $this->command->info('✅ TestSeeder ejecutado exitosamente.');
        $this->command->info('   - 1 torneo activo: Copa MatchDay 2026');
        $this->command->info('   - 2 grupos (A y B) con 4 equipos cada uno');
        $this->command->info('   - 8 equipos con 11 jugadores cada uno');
        $this->command->info('   - Todos los partidos de grupos jugados con goles');
        $this->command->info('   - 1 partido de cuartos pendiente');
        $this->command->info('   - 8 capitanes: carlos@matchday.test ... diego@matchday.test');
        $this->command->info('   - Password de todos: password');
    }
}