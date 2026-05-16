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
use Carbon\Carbon;

class TestSeeder extends Seeder
{
    private array $shields = [
        'Club América'          => 'https://tmssl.akamaized.net/images/wappen/head/3631.png',
        'Chivas de Guadalajara' => 'https://tmssl.akamaized.net/images/wappen/head/6711.png',
        'Tigres UANL'           => 'https://tmssl.akamaized.net/images/wappen/head/7055.png',
        'Monterrey'             => 'https://tmssl.akamaized.net/images/wappen/head/2407.png',
        'Cruz Azul'             => 'https://tmssl.akamaized.net/images/wappen/head/3711.png',
        'Pachuca'               => 'https://tmssl.akamaized.net/images/wappen/head/4035.png',
        'Santos Laguna'         => 'https://tmssl.akamaized.net/images/wappen/head/1403.png',
        'León'                  => 'https://tmssl.akamaized.net/images/wappen/head/4941.png',
        'Toluca'                => 'https://tmssl.akamaized.net/images/wappen/head/1804.png',
        'Atlas'                 => 'https://tmssl.akamaized.net/images/wappen/head/8590.png',
        'Pumas UNAM'            => 'https://tmssl.akamaized.net/images/wappen/head/7633.png',
        'Necaxa'                => 'https://tmssl.akamaized.net/images/wappen/head/1146.png',
    ];

    public function run(): void
    {
        // ── CAPITANES ─────────────────────────────────────────
        $captainData = [
            ['name' => 'André Jardine',      'email' => 'jardine@matchday.test'],
            ['name' => 'Diego Cocca',        'email' => 'cocca@matchday.test'],
            ['name' => 'Veljko Paunović',    'email' => 'paunovic@matchday.test'],
            ['name' => 'Renato Paiva',       'email' => 'paiva@matchday.test'],
            ['name' => 'Pedro Caixinha',     'email' => 'caixinha@matchday.test'],
            ['name' => 'Martín Anselmi',     'email' => 'anselmi@matchday.test'],
            ['name' => 'Guillermo Almada',   'email' => 'almada@matchday.test'],
            ['name' => 'Víctor Manuel Vucetich', 'email' => 'vucetich@matchday.test'],
            ['name' => 'Ignacio Ambriz',     'email' => 'ambriz@matchday.test'],
            ['name' => 'Robert Dante Siboldi', 'email' => 'siboldi@matchday.test'],
            ['name' => 'Eduardo Fentanes',   'email' => 'fentanes@matchday.test'],
            ['name' => 'Alexis Vega DT',     'email' => 'vegadt@matchday.test'],
            ['name' => 'Jaime Lozano',       'email' => 'lozano@matchday.test'],
            ['name' => 'Miguel Herrera',     'email' => 'herrera@matchday.test'],
            ['name' => 'Memo Vázquez',       'email' => 'vazquez@matchday.test'],
            ['name' => 'Carlos Bustos',      'email' => 'bustos@matchday.test'],
        ];

        $captains = [];
        foreach ($captainData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole('captain');
            $captains[] = $user;
        }

        // ── EQUIPOS LIGA MX ───────────────────────────────────
        $teamsData = [
            [
                'name' => 'Club América', 'country' => 'México',
                'captain' => $captains[0],
                'players' => [
                    ['name' => 'Guillermo Ochoa',    'dorsal' => 13, 'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Néstor Araujo',      'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Sebastián Cáceres',  'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Uruguaya'],
                    ['name' => 'Jorge Sánchez',      'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Miguel Layún',       'dorsal' => 16, 'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Richard Sánchez',    'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Paraguaya'],
                    ['name' => 'Álvaro Fidalgo',     'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Española'],
                    ['name' => 'Diego Valdés',       'dorsal' => 20, 'position' => 'MID', 'nationality' => 'Chilena'],
                    ['name' => 'Henry Martín',       'dorsal' => 21, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Jonathan Rodríguez', 'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Uruguaya'],
                    ['name' => 'Alejandro Zendejas', 'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                ]
            ],
            [
                'name' => 'Chivas de Guadalajara', 'country' => 'México',
                'captain' => $captains[1],
                'players' => [
                    ['name' => 'Miguel Jiménez',     'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Gilberto Sepúlveda', 'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Antonio Briseño',    'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Jesús Angulo',       'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Fernando Beltrán',   'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Érick Gutiérrez',    'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Roberto Alvarado',   'dorsal' => 25, 'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Alexis Vega',        'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Chicharito',         'dorsal' => 14, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Cade Cowell',        'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Estadounidense'],
                    ['name' => 'Alan Pulido',        'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                ]
            ],
            [
                'name' => 'Tigres UANL', 'country' => 'México',
                'captain' => $captains[2],
                'players' => [
                    ['name' => 'Nahuel Guzmán',      'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Argentina'],
                    ['name' => 'Hugo Ayala',         'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Carlos Salcedo',     'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Luis Rodríguez',     'dorsal' => 22, 'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Guido Pizarro',      'dorsal' => 5,  'position' => 'MID', 'nationality' => 'Argentina'],
                    ['name' => 'Sebastián Córdova',  'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Juan Pablo Vigón',   'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'André-Pierre Gignac','dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Francesa'],
                    ['name' => 'Nicolás López',      'dorsal' => 17,  'position' => 'FWD', 'nationality' => 'Uruguaya'],
                    ['name' => 'Nico Ibáñez',        'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Jesús Dueñas',       'dorsal' => 6,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                ]
            ],
            [
                'name' => 'Monterrey', 'country' => 'México',
                'captain' => $captains[3],
                'players' => [
                    ['name' => 'Esteban Andrada',    'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Argentina'],
                    ['name' => 'Héctor Moreno',      'dorsal' => 15, 'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Stefan Medina',      'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Gerardo Arteaga',    'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Jordi Cortizo',      'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Española'],
                    ['name' => 'Rodrigo Aguirre',    'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Uruguaya'],
                    ['name' => 'Germán Berterame',   'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Sergio Canales',     'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Española'],
                    ['name' => 'Brandon Vázquez',    'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Luis Romo',          'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Maximiliano Meza',   'dorsal' => 20, 'position' => 'MID', 'nationality' => 'Argentina'],
                ]
            ],
            [
                'name' => 'Cruz Azul', 'country' => 'México',
                'captain' => $captains[4],
                'players' => [
                    ['name' => 'Kevin Mier',         'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Colombiana'],
                    ['name' => 'Adrián Aldrete',     'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Alexis Gutiérrez',   'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Chilena'],
                    ['name' => 'Juan Escobar',       'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Carlos Rodríguez',   'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Erik Lira',          'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Uriel Antuna',       'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Gonzalo Carneiro',   'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Uruguaya'],
                    ['name' => 'Alexis Vega',        'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Rafinha',            'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Brasileña'],
                    ['name' => 'Lorenzo Faravelli',  'dorsal' => 20, 'position' => 'MID', 'nationality' => 'Argentina'],
                ]
            ],
            [
                'name' => 'Pachuca', 'country' => 'México',
                'captain' => $captains[5],
                'players' => [
                    ['name' => 'Óscar Ustari',       'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Argentina'],
                    ['name' => 'Óscar Murillo',      'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Gustavo Cabral',     'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Argentina'],
                    ['name' => 'Érick Sánchez',      'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Víctor Guzmán',      'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Óscar González',     'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Salomón Rondón',     'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Venezolana'],
                    ['name' => 'Oussama Idrissi',    'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Marroquí'],
                    ['name' => 'Avilés Hurtado',     'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Colombiana'],
                    ['name' => 'Diego Barbosa',      'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Jordi Reyna',        'dorsal' => 20, 'position' => 'MID', 'nationality' => 'Venezolana'],
                ]
            ],
            [
                'name' => 'Santos Laguna', 'country' => 'México',
                'captain' => $captains[6],
                'players' => [
                    ['name' => 'Carlos Acevedo',     'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Eduardo Coudet',     'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Omar Campos',        'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Félix Torres',       'dorsal' => 5,  'position' => 'DEF', 'nationality' => 'Ecuatoriana'],
                    ['name' => 'Matheus Doria',      'dorsal' => 6,  'position' => 'DEF', 'nationality' => 'Brasileña'],
                    ['name' => 'Diego de Buen',      'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Ezequiel Cerutti',   'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Argentina'],
                    ['name' => 'Harold Preciado',    'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Colombiana'],
                    ['name' => 'Bruninho',           'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Brasileña'],
                    ['name' => 'Bryan Lozano',       'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Eduardo Aguirre',    'dorsal' => 20, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                ]
            ],
            [
                'name' => 'León', 'country' => 'México',
                'captain' => $captains[7],
                'players' => [
                    ['name' => 'Rodolfo Cota',       'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Stiven Barreiro',    'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'William Tesillo',    'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Víctor Dávila',      'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Chilena'],
                    ['name' => 'Ángel Mena',         'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Ecuatoriana'],
                    ['name' => 'Jean Meneses',       'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Chilena'],
                    ['name' => 'Iván Moreno',        'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Lucas Di Yorio',     'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Fidel Ambriz',       'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'José Iván Rodríguez','dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Santiago Colombatto','dorsal' => 5,  'position' => 'MID', 'nationality' => 'Argentina'],
                ]
            ],
            [
                'name' => 'Toluca', 'country' => 'México',
                'captain' => $captains[8],
                'players' => [
                    ['name' => 'Tiago Volpi',        'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Brasileña'],
                    ['name' => 'Andrés Sanchez',     'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Jean Paul Martínez', 'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Cristian Borja',     'dorsal' => 6,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Pedro Alexis Canelo','dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Rubens Sambueza',    'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Argentina'],
                    ['name' => 'Paulinho',           'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Brasileña'],
                    ['name' => 'Alexis Canelo',      'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Michael Estrada',    'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Ecuatoriana'],
                    ['name' => 'Rodrigo Salinas',    'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Camilo Sanvezzo',    'dorsal' => 20, 'position' => 'FWD', 'nationality' => 'Colombiana'],
                ]
            ],
            [
                'name' => 'Atlas', 'country' => 'México',
                'captain' => $captains[9],
                'players' => [
                    ['name' => 'Camilo Vargas',      'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Colombiana'],
                    ['name' => 'Aldo Rocha',         'dorsal' => 5,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Jesús Angulo',       'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Julián Quiñones',    'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Colombiana'],
                    ['name' => 'Luciano Acosta',     'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Argentina'],
                    ['name' => 'Anderson Santamaría','dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Peruana'],
                    ['name' => 'Jairo Torres',       'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Ígor Lichnovsky',    'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Chilena'],
                    ['name' => 'Diego Barbosa',      'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Colombiana'],
                    ['name' => 'Ángel Zaldívar',     'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Mexicana'],
                    ['name' => 'Martín Nervo',       'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Argentina'],
                ]
            ],
            [
                'name' => 'Pumas UNAM', 'country' => 'México',
                'captain' => $captains[10],
                'players' => [
                    ['name' => 'Alfredo Talavera',   'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Alan Mozo',          'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Gustavo Del Prete',  'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Diogo de Oliveira',  'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Brasileña'],
                    ['name' => 'Adrián González',    'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Lucas Rodríguez',    'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Argentina'],
                    ['name' => 'Higor Platiny',      'dorsal' => 10, 'position' => 'MID', 'nationality' => 'Brasileña'],
                    ['name' => 'Efraín Velarde',     'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Pablo Bennevendo',   'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Washington Corozo',  'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Ecuatoriana'],
                    ['name' => 'Jorge Ruvalcaba',    'dorsal' => 5,  'position' => 'MID', 'nationality' => 'Mexicana'],
                ]
            ],
            [
                'name' => 'Necaxa', 'country' => 'México',
                'captain' => $captains[11],
                'players' => [
                    ['name' => 'Luis Malagón',       'dorsal' => 1,  'position' => 'GK',  'nationality' => 'Mexicana'],
                    ['name' => 'Juan Delgado',       'dorsal' => 7,  'position' => 'FWD', 'nationality' => 'Ecuatoriana'],
                    ['name' => 'Rodrigo Aguirre',    'dorsal' => 9,  'position' => 'FWD', 'nationality' => 'Uruguaya'],
                    ['name' => 'Emmanuel Irarragorri','dorsal' => 10,'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Kevin Álvarez',      'dorsal' => 2,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Óscar Méndez',       'dorsal' => 3,  'position' => 'DEF', 'nationality' => 'Mexicana'],
                    ['name' => 'Facundo Batista',    'dorsal' => 4,  'position' => 'DEF', 'nationality' => 'Argentina'],
                    ['name' => 'Rodrigo Noya',       'dorsal' => 6,  'position' => 'MID', 'nationality' => 'Argentina'],
                    ['name' => 'Alexis Peña',        'dorsal' => 8,  'position' => 'MID', 'nationality' => 'Mexicana'],
                    ['name' => 'Ramiro Rocca',       'dorsal' => 11, 'position' => 'FWD', 'nationality' => 'Argentina'],
                    ['name' => 'Efraín Orona',       'dorsal' => 20, 'position' => 'FWD', 'nationality' => 'Mexicana'],
                ]
            ],
        ];

        $teams = [];
        foreach ($teamsData as $i => $data) {
            // Descargar escudo
            $shieldUrl = null;
            $shieldSrc = $this->shields[$data['name']] ?? null;
            if ($shieldSrc) {
                try {
                    $contents = @file_get_contents($shieldSrc);
                    if ($contents) {
                        $filename = 'shields/lmx_' . \Illuminate\Support\Str::slug($data['name']) . '.png';
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                        $shieldUrl = $filename;
                    }
                } catch (\Exception $e) {
                    // continuar sin escudo
                }
            }

            $team = Team::firstOrCreate(
                ['name' => $data['name']],
                ['country' => $data['country'], 'captain_id' => $data['captain']->id, 'shield_url' => $shieldUrl]
            );

            if ($team->players()->count() === 0) {
                foreach ($data['players'] as $playerData) {
                    Player::create([
                        'team_id'     => $team->id,
                        'name'        => $playerData['name'],
                        'dorsal'      => $playerData['dorsal'],
                        'position'    => $playerData['position'],
                        'nationality' => $playerData['nationality'],
                    ]);
                }
            }

            $teams[] = $team;
        }

        // ════════════════════════════════════════════════════
        // TORNEO 1: Copa MatchDay 2026 (4 grupos de 3 → cuartos)
        // Estado: Fase de grupos completa, cuartos generados
        // ════════════════════════════════════════════════════
        $t1 = Tournament::firstOrCreate(
            ['name' => 'Copa MatchDay 2026'],
            [
                'edition'    => 2026,
                'format'     => 'groups_knockout',
                'status'     => 'active',
                'starts_at'  => '2026-04-01',
                'ends_at'    => '2026-06-30',
                'created_by' => 1,
            ]
        );

        $t1Groups = [];
        foreach (['A', 'B', 'C', 'D'] as $i => $name) {
            $group = Group::firstOrCreate(['tournament_id' => $t1->id, 'name' => $name]);
            // 3 equipos por grupo
            $groupTeams = array_slice($teams, $i * 3, 3);
            foreach ($groupTeams as $team) {
                if (!$group->teams()->where('team_id', $team->id)->exists()) {
                    $group->teams()->attach($team->id);
                }
            }
            $t1Groups[] = $group;
        }

        // Generar partidos de grupos con resultados
        $this->generateGroupMatches($t1, $t1Groups, '2026-04-01');

        // ════════════════════════════════════════════════════
        // TORNEO 2: Liga Regia (2 grupos de 4 → semifinales)
        // Estado: En semifinales
        // ════════════════════════════════════════════════════
        $t2 = Tournament::firstOrCreate(
            ['name' => 'Liga Regia 2026'],
            [
                'edition'    => 2026,
                'format'     => 'groups_knockout',
                'status'     => 'active',
                'starts_at'  => '2026-03-01',
                'ends_at'    => '2026-05-31',
                'created_by' => 1,
            ]
        );

        $t2Groups = [];
        foreach (['A', 'B'] as $i => $name) {
            $group = Group::firstOrCreate(['tournament_id' => $t2->id, 'name' => $name]);
            $groupTeams = array_slice($teams, $i * 4, 4);
            foreach ($groupTeams as $team) {
                if (!$group->teams()->where('team_id', $team->id)->exists()) {
                    $group->teams()->attach($team->id);
                }
            }
            $t2Groups[] = $group;
        }

        $this->generateGroupMatches($t2, $t2Groups, '2026-03-01');

        // ════════════════════════════════════════════════════
        // TORNEO 3: Clásico MX (2 grupos de 3 → semifinales)
        // Estado: En draft, sin fixture
        // ════════════════════════════════════════════════════
        $t3 = Tournament::firstOrCreate(
            ['name' => 'Clásico MX 2026'],
            [
                'edition'    => 2026,
                'format'     => 'groups_knockout',
                'status'     => 'draft',
                'starts_at'  => '2026-07-01',
                'ends_at'    => '2026-08-31',
                'created_by' => 1,
            ]
        );

        foreach (['A', 'B'] as $i => $name) {
            $group = Group::firstOrCreate(['tournament_id' => $t3->id, 'name' => $name]);
            $groupTeams = [$teams[0], $teams[2], $teams[4 + $i * 2]];
            foreach ($groupTeams as $team) {
                if (!$group->teams()->where('team_id', $team->id)->exists()) {
                    $group->teams()->attach($team->id);
                }
            }
        }

        $this->command->info('✅ TestSeeder ejecutado exitosamente.');
        $this->command->info('');
        $this->command->info('📋 TORNEOS:');
        $this->command->info('   1. Copa MatchDay 2026 — 4 grupos de 3 equipos, fase de grupos completa');
        $this->command->info('   2. Liga Regia 2026 — 2 grupos de 4 equipos, fase de grupos completa');
        $this->command->info('   3. Clásico MX 2026 — 2 grupos de 3 equipos, en draft (sin fixture)');
        $this->command->info('');
        $this->command->info('⚽ EQUIPOS: 12 equipos Liga MX con plantillas reales');
        $this->command->info('');
        $this->command->info('👤 CREDENCIALES:');
        $this->command->info('   Admin: admin@matchday.test / password');
        $this->command->info('   Capitanes: jardine@matchday.test ... bustos@matchday.test / password');
    }

    private function generateGroupMatches(Tournament $tournament, array $groups, string $startDate): void
    {
        $date = Carbon::parse($startDate);
        $dayOffset = 0;
        $matchCount = 0;

        foreach ($groups as $group) {
            $groupTeams = $group->teams()->with('players')->get()->values();
            $n = $groupTeams->count();

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $exists = TournamentMatch::where('tournament_id', $tournament->id)
                        ->where('home_team_id', $groupTeams[$i]->id)
                        ->where('away_team_id', $groupTeams[$j]->id)
                        ->exists();

                    if ($exists) continue;

                    $homeScore = rand(0, 4);
                    $awayScore = rand(0, 3);

                    $match = TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'group_id'      => $group->id,
                        'home_team_id'  => $groupTeams[$i]->id,
                        'away_team_id'  => $groupTeams[$j]->id,
                        'home_score'    => $homeScore,
                        'away_score'    => $awayScore,
                        'played_at'     => $date->copy()->addDays($dayOffset)->setHour($matchCount % 2 === 0 ? 17 : 20),
                        'stage'         => 'group',
                        'status'        => 'finished',
                    ]);

                    // Goles locales
                    $this->createGoals($match, $groupTeams[$i], $homeScore, $match->id);
                    // Goles visitantes
                    $this->createGoals($match, $groupTeams[$j], $awayScore, $match->id);

                    $matchCount++;
                    if ($matchCount % 4 === 0) $dayOffset++;
                }
            }
        }
    }

    private function createGoals(TournamentMatch $match, Team $team, int $count, int $matchId): void
    {
        $scorers = $team->players()
            ->whereIn('position', ['MID', 'FWD'])
            ->get();

        if ($scorers->isEmpty()) return;

        $usedMinutes = [];
        for ($g = 0; $g < $count; $g++) {
            do {
                $minute = rand(1, 90);
            } while (in_array($minute, $usedMinutes));
            $usedMinutes[] = $minute;

            $types = ['regular', 'regular', 'regular', 'penalty'];
            Goal::create([
                'match_id'  => $matchId,
                'player_id' => $scorers->random()->id,
                'minute'    => $minute,
                'type'      => $types[array_rand($types)],
            ]);
        }
    }
}