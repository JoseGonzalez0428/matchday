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

class WorldCupSeeder extends Seeder
{
    private array $flags = [
        'México'           => 'mx',
        'Sudáfrica'        => 'za',
        'Corea del Sur'    => 'kr',
        'Chequia'          => 'cz',
        'Canadá'           => 'ca',
        'Suiza'            => 'ch',
        'Qatar'            => 'qa',
        'Bosnia y Herzegovina' => 'ba',
        'Brasil'           => 'br',
        'Marruecos'        => 'ma',
        'Haití'            => 'ht',
        'Escocia'          => 'gb-sct',
        'Estados Unidos'   => 'us',
        'Paraguay'         => 'py',
        'Australia'        => 'au',
        'Turquía'          => 'tr',
        'Alemania'         => 'de',
        'Curazao'          => 'cw',
        'Costa de Marfil'  => 'ci',
        'Ecuador'          => 'ec',
        'Países Bajos'     => 'nl',
        'Japón'            => 'jp',
        'Suecia'           => 'se',
        'Túnez'            => 'tn',
        'Bélgica'          => 'be',
        'Egipto'           => 'eg',
        'Irán'             => 'ir',
        'Nueva Zelanda'    => 'nz',
        'España'           => 'es',
        'Cabo Verde'       => 'cv',
        'Arabia Saudita'   => 'sa',
        'Uruguay'          => 'uy',
        'Francia'          => 'fr',
        'Senegal'          => 'sn',
        'Noruega'          => 'no',
        'Irak'             => 'iq',
        'Argentina'        => 'ar',
        'Argelia'          => 'dz',
        'Austria'          => 'at',
        'Jordania'         => 'jo',
        'Portugal'         => 'pt',
        'RD Congo'         => 'cd',
        'Uzbekistán'       => 'uz',
        'Colombia'         => 'co',
        'Inglaterra'       => 'gb-eng',
        'Croacia'          => 'hr',
        'Ghana'            => 'gh',
        'Panamá'           => 'pa',
    ];
    // Grupos oficiales FIFA World Cup 2026
    private array $groups = [
        'A' => ['México', 'Sudáfrica', 'Corea del Sur', 'Chequia'],
        'B' => ['Canadá', 'Suiza', 'Qatar', 'Bosnia y Herzegovina'],
        'C' => ['Brasil', 'Marruecos', 'Haití', 'Escocia'],
        'D' => ['Estados Unidos', 'Paraguay', 'Australia', 'Turquía'],
        'E' => ['Alemania', 'Curazao', 'Costa de Marfil', 'Ecuador'],
        'F' => ['Países Bajos', 'Japón', 'Suecia', 'Túnez'],
        'G' => ['Bélgica', 'Egipto', 'Irán', 'Nueva Zelanda'],
        'H' => ['España', 'Cabo Verde', 'Arabia Saudita', 'Uruguay'],
        'I' => ['Francia', 'Senegal', 'Noruega', 'Irak'],
        'J' => ['Argentina', 'Argelia', 'Austria', 'Jordania'],
        'K' => ['Portugal', 'RD Congo', 'Uzbekistán', 'Colombia'],
        'L' => ['Inglaterra', 'Croacia', 'Ghana', 'Panamá'],
    ];

    // Jugadores estrella por selección
    private array $players = [
        'México'           => [['Guillermo Ochoa','GK',1],['Héctor Moreno','DEF',15],['Jorge Sánchez','DEF',2],['Edson Álvarez','MID',6],['Héctor Herrera','MID',16],['Andrés Guardado','MID',18],['Hirving Lozano','FWD',22],['Raúl Jiménez','FWD',9],['Henry Martín','FWD',21],['Roberto Alvarado','MID',25],['César Montes','DEF',3]],
        'Brasil'           => [['Alisson','GK',1],['Marquinhos','DEF',4],['Éder Militão','DEF',3],['Casemiro','MID',5],['Vinícius Jr','FWD',7],['Neymar','FWD',10],['Rodrygo','FWD',11],['Raphinha','FWD',17],['Lucas Paquetá','MID',8],['Endrick','FWD',9],['Danilo','DEF',2]],
        'Argentina'        => [['E. Martínez','GK',23],['N. Otamendi','DEF',19],['Lisandro Martínez','DEF',25],['De Paul','MID',7],['Mac Allister','MID',20],['Lionel Messi','FWD',10],['Lautaro Martínez','FWD',22],['J. Álvarez','FWD',9],['Di María','FWD',11],['Molina','DEF',26],['Acuña','DEF',8]],
        'Francia'          => [['Maignan','GK',16],['Theo Hernández','DEF',22],['Upamecano','DEF',5],['Tchouaméni','MID',8],['Griezmann','FWD',7],['Mbappé','FWD',10],['Dembélé','FWD',11],['Camavinga','MID',6],['Benzema','FWD',9],['Saliba','DEF',12],['Kanté','MID',13]],
        'España'           => [['Unai Simón','GK',1],['Carvajal','DEF',2],['Laporte','DEF',14],['Pedri','MID',26],['Gavi','MID',8],['Rodri','MID',16],['Yamal','FWD',19],['Morata','FWD',7],['Nico Williams','FWD',10],['Ferran Torres','FWD',11],['Dani Olmo','MID',6]],
        'Inglaterra'       => [['Pickford','GK',1],['Alexander-Arnold','DEF',66],['Maguire','DEF',5],['Declan Rice','MID',4],['Bellingham','MID',22],['Kane','FWD',9],['Saka','FWD',7],['Foden','FWD',11],['Rashford','FWD',10],['Trippier','DEF',12],['Walker','DEF',2]],
        'Portugal'         => [['Rui Patrício','GK',1],['Rúben Dias','DEF',4],['Pepe','DEF',3],['Bruno Fernandes','MID',8],['Bernardo Silva','MID',10],['Cristiano Ronaldo','FWD',7],['Rafael Leão','FWD',11],['João Félix','FWD',11],['Vitinha','MID',13],['Nuno Mendes','DEF',22],['Gonçalo Ramos','FWD',9]],
        'Alemania'         => [['Neuer','GK',1],['Rüdiger','DEF',2],['Süle','DEF',15],['Kimmich','MID',6],['Müller','FWD',13],['Havertz','FWD',7],['Musiala','FWD',10],['Gnabry','FWD',11],['Wirtz','MID',8],['Goretzka','MID',18],['Schlotterbeck','DEF',5]],
        'Países Bajos'     => [['Flekken','GK',1],['De Vrij','DEF',6],['Dumfries','DEF',22],['De Jong','MID',21],['Gakpo','FWD',11],['Van Dijk','DEF',4],['Depay','FWD',10],['Bergwijn','FWD',7],['Frimpong','DEF',12],['Reijnders','MID',8],['Weghorst','FWD',9]],
        'Bélgica'          => [['Courtois','GK',1],['Alderweireld','DEF',4],['De Bruyne','MID',7],['Lukaku','FWD',9],['Hazard','FWD',10],['Tielemans','MID',8],['Meunier','DEF',2],['Carrasco','FWD',11],['Vertonghen','DEF',5],['Witsel','MID',6],['Batshuayi','FWD',23]],
        'Marruecos'        => [['Bono','GK',1],['Hakimi','DEF',2],['Saiss','DEF',5],['Amrabat','MID',4],['Ziyech','MID',7],['En-Nesyri','FWD',9],['Boufal','FWD',11],['Ounahi','MID',8],['Mazraoui','DEF',3],['Sabiri','MID',10],['Aguerd','DEF',6]],
        'Senegal'          => [['E. Mendy','GK',1],['Koulibaly','DEF',3],['Gueye','MID',4],['Mané','FWD',10],['Diallo','FWD',11],['Sarr','FWD',7],['Diatta','FWD',19],['Kouyaté','MID',8],['Cissé','MID',5],['Diédhiou','FWD',9],['Badji','DEF',2]],
        'Estados Unidos'   => [['Turner','GK',1],['Dest','DEF',2],['Zimmerman','DEF',5],['McKennie','MID',8],['Musah','MID',4],['Pulisic','FWD',10],['Weah','FWD',21],['Reyna','MID',7],['Ferreira','FWD',9],['Aaronson','MID',11],['Robinson','DEF',3]],
        'Canadá'           => [['Borjan','GK',18],['Johnston','DEF',12],['Vitória','DEF',3],['Eustáquio','MID',7],['Davies','DEF',19],['David','FWD',9],['Laryea','MID',11],['Buchanan','FWD',10],['Hoilett','FWD',17],['Osorio','MID',21],['Henry','DEF',5]],
        'Uruguay'          => [['Rochet','GK',1],['Godín','DEF',3],['Araújo','DEF',4],['Valverde','MID',14],['Bentancur','MID',6],['Núñez','FWD',11],['Suárez','FWD',9],['Cavani','FWD',21],['De Arrascaeta','MID',10],['Vidal','MID',8],['Giménez','DEF',2]],
        'Japón'            => [['Gonda','GK',1],['Tomiyasu','DEF',5],['Yoshida','DEF',22],['Endo','MID',7],['Kubo','FWD',14],['Doan','FWD',8],['Minamino','FWD',10],['Kamada','MID',11],['Ueda','FWD',9],['Ito','FWD',23],['Nagatomo','DEF',3]],
        'Corea del Sur'    => [['Kim Seung-gyu','GK',1],['Kim Min-jae','DEF',4],['Son Heung-min','FWD',7],['Lee Jae-sung','MID',10],['Hwang Hee-chan','FWD',9],['Hwang In-beom','MID',8],['Jung Woo-young','MID',6],['Na Sang-ho','FWD',17],['Kim Young-gwon','DEF',19],['Lee Kang-in','MID',11],['Cho Gue-sung','FWD',18]],
        'Australia'        => [['Ryan','GK',1],['Behich','DEF',2],['Souttar','DEF',19],['Mooy','MID',13],['McGree','MID',8],['Irvine','MID',6],['Hrustic','MID',10],['Leckie','FWD',7],['Duke','FWD',9],['Goodwin','FWD',11],['Atkinson','DEF',5]],
        'Irán'             => [['Hosseini','GK',1],['Pouraliganji','DEF',4],['Mohammadi','DEF',19],['Ezatolahi','MID',8],['Jahanbakhsh','FWD',7],['Taremi','FWD',9],['Gholizadeh','FWD',10],['Karimi','MID',6],['Shajari','MID',11],['Cheshmi','DEF',3],['Hajsafi','DEF',5]],
        'Arabia Saudita'   => [['Al-Owais','GK',1],['Al-Bulaihi','DEF',6],['Kadesh','DEF',19],['Al-Dawsari','FWD',10],['Al-Shehri','FWD',9],['Kanno','MID',8],['Al-Malki','MID',11],['Al-Faraj','MID',4],['Bahebri','FWD',7],['Al-Buraikan','FWD',17],['Al-Shahrani','DEF',3]],
        'Ecuador'          => [['Domínguez','GK',1],['Preciado','DEF',2],['Hincapié','DEF',3],['Plata','FWD',7],['Valencia','FWD',9],['Caicedo','MID',4],['Franco','MID',8],['Sarmiento','FWD',11],['Torres','FWD',10],['Arboleda','DEF',5],['Estupiñán','DEF',16]],
        'Colombia'         => [['Vargas','GK',1],['Dávinson Sánchez','DEF',2],['Zapata','DEF',5],['J. Rodríguez','MID',10],['Cuadrado','MID',11],['Falcao','FWD',9],['L. Díaz','FWD',7],['Borré','FWD',19],['Arias','DEF',3],['Uribe','MID',8],['Lerma','MID',6]],
        'Suiza'            => [['Sommer','GK',1],['Schär','DEF',5],['Akanji','DEF',4],['Xhaka','MID',10],['Shaqiri','FWD',23],['Seferovic','FWD',9],['Embolo','FWD',11],['Zakaria','MID',6],['Widmer','DEF',3],['Vargas','FWD',7],['Freuler','MID',8]],
        'Turquía'          => [['Çakır','GK',1],['Çelik','DEF',2],['Soyuncu','DEF',4],['Calhanoglu','MID',10],['Yazici','FWD',11],['Tosun','FWD',9],['Karaman','FWD',7],['Müldür','DEF',3],['Tufan','MID',8],['Aktürkoğlu','FWD',17],['Yokuslu','MID',6]],
        'Noruega'          => [['Nyland','GK',1],['Ryerson','DEF',2],['Ostigard','DEF',5],['Ødegaard','MID',8],['Haaland','FWD',9],['Sörloth','FWD',11],['Thorstvedt','MID',10],['Ajer','DEF',6],['Berg','FWD',7],['Pedersen','DEF',3],['Normann','MID',13]],
        'Croacia'          => [['Livaković','GK',1],['Ćaleta-Car','DEF',6],['Gvardiol','DEF',24],['Modrić','MID',10],['Kovačić','MID',8],['Brozović','MID',11],['Perisić','FWD',4],['Kramarić','FWD',9],['Vlašić','FWD',7],['Budimir','FWD',16],['Juranović','DEF',2]],
        'Sudáfrica'        => [['Williams','GK',1],['Xulu','DEF',5],['Mvala','MID',4],['Dolly','FWD',10],['Mofokeng','FWD',7],['Tau','FWD',11],['Zwane','MID',8],['Mothobi','MID',6],['Mothiba','FWD',9],['Lakay','FWD',17],['Modiba','DEF',3]],
        'Egipto'           => [['El-Hadary','GK',1],['Hegazy','DEF',3],['Farouk','DEF',5],['Salah','FWD',10],['Trezeguet','FWD',7],['El-Neny','MID',4],['Mostafa','FWD',9],['Elneny','MID',8],['Ashour','MID',6],['Kahraba','FWD',11],['Amro','DEF',2]],
        'Argelia'          => [['Benayada','GK',1],['Mandi','DEF',3],['Benlamri','DEF',6],['Mahrez','FWD',10],['Benrahma','FWD',11],['Bensebaini','DEF',5],['Belaili','FWD',7],['Tahrat','MID',8],['Slimani','FWD',9],['Guedioura','MID',4],['Atal','MID',14]],
        'Ghana'            => [['Wollacott','GK',1],['Amartey','DEF',5],['Djiku','DEF',3],['Partey','MID',4],['Kudus','FWD',10],['Ayew','FWD',9],['Caleb Ekuban','FWD',19],['Baba','DEF',2],['Fatawu','FWD',11],['Semenyo','FWD',7],['Schlupp','MID',8]],
        'Paraguay'         => [['Silva','GK',1],['Balbuena','DEF',3],['Alonso','MID',4],['Almirón','MID',10],['Sanabria','FWD',9],['Gómez','FWD',7],['Romero','DEF',5],['Villasanti','MID',8],['Enciso','FWD',11],['Espínola','DEF',2],['Bobadilla','MID',6]],
        'Qatar'            => [['Al-Sheeb','GK',1],['Pedro Miguel','DEF',2],['Al-Rawi','DEF',5],['Al-Haydos','MID',10],['Al-Moez Ali','FWD',19],['Boualem Khoukhi','MID',6],['Assim Madibo','MID',8],['Al-Ahrak','FWD',9],['Hassan Al-Haydos','FWD',11],['Tarek Salman','DEF',3],['Al-Naemi','MID',7]],
        'Haití'            => [['Voltaire','GK',1],['Andrés','DEF',5],['Florival','MID',8],['Pierrot','FWD',9],['Nazon','FWD',10],['Sannon','MID',6],['Bien-Aimé','MID',4],['Laguerre','FWD',7],['Ogenel','DEF',3],['Anglade','MID',11],['Désiré','FWD',17]],
        'Escocia'          => [['Gordon','GK',1],['Alexander','DEF',2],['Hanley','DEF',6],['McTominay','MID',8],['Armstrong','MID',10],['Adams','FWD',9],['Christie','MID',11],['McGinn','MID',7],['Tierney','DEF',3],['Dykes','FWD',20],['Patterson','DEF',15]],
        'Curazao'          => [['Dos Santos','GK',1],['Sulvaran','DEF',5],['Hasselbaink','FWD',7],['Fer','MID',8],['Çorlu','FWD',10],['Bacuna','MID',6],['van Aanholt','DEF',3],['Meyer','MID',11],['Martina','DEF',2],['Gontom','FWD',9],['Clasie','MID',4]],
        'Costa de Marfil'  => [['Gbohouo','GK',16],['Kodjia','FWD',9],['Pepe','FWD',7],['Franck Kessie','MID',4],['Zaha','FWD',11],['Gradel','FWD',8],['Sangaré','MID',6],['Bailly','DEF',3],['Aurier','DEF',2],['Deli','DEF',5],['Cornet','FWD',10]],
        'Cabo Verde'       => [['Vozinha','GK',1],['Landel','DEF',5],['Jamiro','MID',10],['Ryan Mendes','FWD',7],['Garry Rodrigues','FWD',11],['Marco Soares','MID',8],['Stopira','DEF',3],['Platiny','MID',6],['Tavares','FWD',9],['Andrezinho','DEF',2],['Leandro','MID',4]],
        'Bosnia y Herzegovina' => [['Piric','GK',1],['Bicakcic','DEF',5],['Kolasinac','DEF',3],['Pjanic','MID',8],['Visca','FWD',10],['Dzeko','FWD',9],['Gojak','MID',11],['Krunic','MID',7],['Besic','MID',6],['Cimirot','MID',4],['Lulic','DEF',2]],
        'Nueva Zelanda'    => [['Sail','GK',1],['Boxall','DEF',5],['Wood','FWD',9],['Payne','MID',8],['Just','MID',10],['Gould','DEF',3],['Lewis','MID',6],['de Vries','FWD',11],['Farquharson','DEF',2],['Bell','MID',7],['Cacace','DEF',16]],
        'Austria'          => [['Pentz','GK',1],['Trauner','DEF',5],['Posch','DEF',3],['Schlager','MID',8],['Sabitzer','MID',10],['Arnautovic','FWD',7],['Baumgartner','MID',11],['Grillitsch','MID',6],['Lienhart','DEF',4],['Trimmel','DEF',2],['Gregoritsch','FWD',9]],
        'Jordania'         => [['Abu Zeid','GK',1],['Awawdeh','DEF',5],['Al-Rawabdeh','MID',8],['Baha Faisal','FWD',9],['Al-Haddad','FWD',7],['Al-Dardour','MID',10],['Hani','MID',6],['Obeidat','DEF',3],['Al-Khalaiwa','FWD',11],['Mustafa','DEF',4],['Abu Aisheh','MID',14]],
        'RD Congo'         => [['Kasanda','GK',1],['Mbemba','DEF',5],['Kayembe','MID',8],['Batshuayi','FWD',9],['Bakambu','FWD',10],['Bolasie','FWD',7],['Tino Kadewere','FWD',11],['Masuaku','DEF',3],['Luyindama','DEF',4],['Mpoku','MID',6],['Mossi','MID',14]],
        'Uzbekistán'       => [['Nematov','GK',1],['Ashurmatov','DEF',5],['Kholmatov','MID',8],['Shomurodov','FWD',9],['Masharipov','FWD',10],['Tursunov','FWD',7],['Alijonov','MID',6],['Yusupov','MID',11],['Iskanderov','DEF',3],['Ergashev','DEF',4],['Jaloliddinov','MID',14]],
        'Irak'             => [['Bashar','GK',1],['Ali Adnan','DEF',3],['Hamza','DEF',5],['Amjed','MID',8],['Hussein','FWD',9],['Mohanad','FWD',10],['Karrar','MID',6],['Rebin','FWD',7],['Safaa','MID',11],['Mahdi','DEF',4],['Aymen','FWD',17]],
        'Panamá'           => [['Penedo','GK',1],['Murillo','DEF',3],['Escobar','DEF',5],['Cooper','MID',8],['Godoy','MID',10],['Arroyo','FWD',9],['Fajardo','FWD',7],['Blackman','MID',6],['Davis','DEF',2],['Mosquera','FWD',11],['Carrasquilla','MID',4]],
        'Túnez'            => [['Ben Mustapha','GK',1],['Meriah','DEF',5],['Maaloul','DEF',3],['Khazri','FWD',10],['Sliti','FWD',7],['Ben Yedder','FWD',9],['Skhiri','MID',4],['Laïdouni','MID',8],['Drager','DEF',2],['Jaziri','FWD',11],['Ben Romdhane','MID',6]],
        'Suecia'           => [['Olsen','GK',1],['Lustig','DEF',2],['Lindelöf','DEF',6],['Ekdal','MID',4],['Larsson','MID',8],['Ibrahimovic','FWD',10],['Forsberg','MID',10],['Isak','FWD',9],['Kulusevski','FWD',11],['Claesson','FWD',7],['Danielson','DEF',3]],
        'Inglarerra'       => [['Pickford','GK',1],['Alexander-Arnold','DEF',66],['Maguire','DEF',5],['Declan Rice','MID',4],['Bellingham','MID',22],['Kane','FWD',9],['Saka','FWD',7],['Foden','FWD',11],['Rashford','FWD',10],['Trippier','DEF',12],['Walker','DEF',2]],
    ];

    public function run(): void
    {
        $this->command->info('🌍 Creando torneo FIFA World Cup 2026...');

        // ── Admin ya existe del RoleSeeder ────────────────
        $admin = User::where('email', 'admin@matchday.test')->first();

        // ── Torneo ────────────────────────────────────────
        $tournament = Tournament::firstOrCreate(
            ['name' => 'FIFA World Cup 2026'],
            [
                'edition'    => 2026,
                'format'     => 'groups_knockout',
                'status'     => 'active',
                'starts_at'  => '2026-06-11',
                'ends_at'    => '2026-07-19',
                'created_by' => $admin->id,
            ]
        );

        // ── Crear equipos, capitanes y jugadores ──────────
        $teamModels = [];
        $captainIndex = 0;

        foreach ($this->groups as $groupName => $teamNames) {
            foreach ($teamNames as $teamName) {
                if (isset($teamModels[$teamName])) continue;

                // Crear capitán
                $email = strtolower(str_replace([' ', 'ú', 'é', 'á', 'í', 'ó', 'ñ', 'ü'], ['_', 'u', 'e', 'a', 'i', 'o', 'n', 'u'], $teamName)) . '@wc2026.test';
                $captain = User::firstOrCreate(
                    ['email' => $email],
                    ['name' => 'DT ' . $teamName, 'password' => Hash::make('password')]
                );
                $captain->assignRole('captain');

                // Crear equipo
                $flagCode = $this->flags[$teamName] ?? null;
                $flagUrl  = $flagCode
                    ? "https://flagcdn.com/w80/{$flagCode}.png"
                    : null;

                // Descargar y guardar la bandera localmente
                $shieldUrl = null;
                if ($flagUrl) {
                    try {
                        $contents = file_get_contents($flagUrl);
                        if ($contents) {
                            $filename = 'shields/flag_' . $flagCode . '.png';
                            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                            $shieldUrl = $filename;
                        }
                    } catch (\Exception $e) {
                        // Si falla la descarga, continuar sin bandera
                    }
                }

                $team = Team::firstOrCreate(
                    ['name' => $teamName],
                    ['country' => $teamName, 'captain_id' => $captain->id, 'shield_url' => $shieldUrl]
                );

                // Crear jugadores
                $playerData = $this->players[$teamName] ?? null;
                if ($playerData && $team->players()->count() === 0) {
                    foreach ($playerData as [$name, $position, $dorsal]) {
                        Player::firstOrCreate(
                            ['team_id' => $team->id, 'dorsal' => $dorsal],
                            ['name' => $name, 'position' => $position, 'nationality' => $teamName]
                        );
                    }
                }

                $teamModels[$teamName] = $team;
                $captainIndex++;
            }
        }

        // ── Crear grupos y asignar equipos ────────────────
        $groupModels = [];
        foreach ($this->groups as $groupName => $teamNames) {
            $group = Group::firstOrCreate([
                'tournament_id' => $tournament->id,
                'name'          => $groupName,
            ]);

            foreach ($teamNames as $teamName) {
                $team = $teamModels[$teamName];
                if (!$group->teams()->where('team_id', $team->id)->exists()) {
                    $group->teams()->attach($team->id);
                }
            }

            $groupModels[$groupName] = $group;
        }

        // ── Generar partidos de grupos con resultados ─────
        $this->command->info('⚽ Generando partidos de grupos...');
        $startDate = Carbon::parse('2026-06-11');
        $dayOffset = 0;
        $matchCount = 0;

        foreach ($groupModels as $groupName => $group) {
            $groupTeams = $group->teams()->with('players')->get()->values();
            $n = $groupTeams->count();

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $exists = TournamentMatch::where('tournament_id', $tournament->id)
                        ->where('home_team_id', $groupTeams[$i]->id)
                        ->where('away_team_id', $groupTeams[$j]->id)
                        ->exists();

                    if ($exists) continue;

                    $homeScore = rand(0, 3);
                    $awayScore = rand(0, 3);

                    $match = TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'group_id'      => $group->id,
                        'home_team_id'  => $groupTeams[$i]->id,
                        'away_team_id'  => $groupTeams[$j]->id,
                        'home_score'    => $homeScore,
                        'away_score'    => $awayScore,
                        'played_at'     => $startDate->copy()->addDays($dayOffset)->setHour($matchCount % 3 === 0 ? 12 : ($matchCount % 3 === 1 ? 15 : 18)),
                        'stage'         => 'group',
                        'status'        => 'finished',
                    ]);

                    $this->createGoals($match, $groupTeams[$i], $homeScore);
                    $this->createGoals($match, $groupTeams[$j], $awayScore);

                    $matchCount++;
                    if ($matchCount % 6 === 0) $dayOffset++;
                }
            }
        }

        $this->command->info('✅ FIFA World Cup 2026 creado exitosamente.');
        $this->command->info('   - 12 grupos con 4 selecciones cada uno');
        $this->command->info('   - 48 selecciones con jugadores reales');
        $this->command->info("   - {$matchCount} partidos de grupos generados");
        $this->command->info('   - Listo para generar Ronda de 32');
        $this->command->info('');
        $this->command->info('👉 Inicia sesión como admin@matchday.test / password');
        $this->command->info('👉 Ve al torneo FIFA World Cup 2026 y genera la siguiente fase');
    }

    private function createGoals(TournamentMatch $match, Team $team, int $count): void
    {
        $scorers = $team->players()->whereIn('position', ['MID', 'FWD'])->get();
        if ($scorers->isEmpty() || $count === 0) return;

        $usedMinutes = [];
        for ($g = 0; $g < $count; $g++) {
            do { $minute = rand(1, 90); } while (in_array($minute, $usedMinutes));
            $usedMinutes[] = $minute;

            Goal::create([
                'match_id'  => $match->id,
                'player_id' => $scorers->random()->id,
                'minute'    => $minute,
                'type'      => rand(0, 4) === 0 ? 'penalty' : 'regular',
            ]);
        }
    }
}