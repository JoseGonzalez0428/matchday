@extends('layouts.admin')

@section('title', $tournament->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">🏆 {{ $tournament->name }}</h1>
        <p class="text-gray-500 mt-1">{{ $tournament->edition }} · {{ ucfirst($tournament->format) }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.tournaments.bracket', $tournament) }}"
            class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
            🏆 Ver Bracket
        </a>
        @if($tournament->matches()->where('status', 'scheduled')->exists() && config('app.ai_analysis_enabled'))
        <form method="POST" action="{{ route('admin.tournaments.simulate', $tournament) }}"
            onsubmit="return confirm('¿Simular todos los partidos pendientes con IA? Esto puede tardar varios minutos.')">
            @csrf
            <button type="button"
                    onclick="document.getElementById('simulate-confirm-modal').classList.remove('hidden')"
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700">
                🤖 Simular con IA
            </button>
        </form>
        @endif
        <a href="{{ route('admin.tournaments.pdf.standings', $tournament) }}"
           class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
            📄 PDF Standings
        </a>
        <a href="{{ route('admin.tournaments.pdf.fixture', $tournament) }}"
           class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
            📄 PDF Fixture
        </a>
        <a href="{{ route('admin.tournaments.edit', $tournament) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Editar
        </a>
        @if(!$tournament->matches()->exists())
        <form method="POST" action="{{ route('admin.tournaments.fixture', $tournament) }}">
            @csrf
            <button type="submit"
                    class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
                ⚡ Generar Fixture
            </button>
        </form>
        @else
            @php
                $finalMatch = $tournament->matches()->where('stage', 'final')->where('status', 'finished')->first();
            @endphp

            @if($finalMatch)
                {{-- Torneo terminado, mostrar campeón --}}
                @php
                    $champion = !is_null($finalMatch->home_penalties)
                        ? ($finalMatch->home_penalties > $finalMatch->away_penalties ? $finalMatch->homeTeam : $finalMatch->awayTeam)
                        : ($finalMatch->home_score > $finalMatch->away_score ? $finalMatch->homeTeam : $finalMatch->awayTeam);
                @endphp
                <button onclick="document.getElementById('champion-modal').classList.remove('hidden')"
                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
                    🏆 Ver Campeón
                </button>
                @if($tournament->status !== 'finished')
                <form method="POST" action="{{ route('admin.tournaments.next-round', $tournament) }}">
                    @csrf
                    <button type="submit"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
                        ✅ Finalizar torneo
                    </button>
                </form>
                @endif
            @else
                <form method="POST" action="{{ route('admin.tournaments.next-round', $tournament) }}">
                    @csrf
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        ⚡ Generar siguiente fase
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>

{{-- Info general --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Estado</p>
        <p class="text-lg font-bold
            {{ $tournament->status === 'active' ? 'text-green-700' : '' }}
            {{ $tournament->status === 'draft' ? 'text-yellow-600' : '' }}
            {{ $tournament->status === 'finished' ? 'text-gray-500' : '' }}">
            {{ \App\Helpers\StatusHelper::tournament($tournament->status) }}
        </p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Inicio</p>
        <p class="text-lg font-bold text-gray-700">{{ $tournament->starts_at->format('d/m/Y') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Cierre</p>
        <p class="text-lg font-bold text-gray-700">
            {{ $tournament->ends_at ? $tournament->ends_at->format('d/m/Y') : '—' }}
        </p>
    </div>
</div>

{{-- Gestión de grupos --}}
@if($tournament->status === 'draft')
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">Grupos y Equipos</h2>
        {{-- Formulario nuevo grupo --}}
        <form method="POST" action="{{ route('admin.tournaments.groups.store', $tournament) }}"
              class="flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="Ej: A"
                   maxlength="2"
                   class="border rounded-lg px-3 py-2 text-sm w-20 focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="submit"
                    class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
                + Grupo
            </button>
        </form>
    </div>

    @if($tournament->groups->isEmpty())
        <p class="text-gray-400 text-sm">No hay grupos creados. Agrega al menos uno para comenzar.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($tournament->groups as $group)
            <div class="border rounded-xl p-4">
                <h3 class="font-bold text-green-700 mb-3">Grupo {{ $group->name }}</h3>

                {{-- Equipos del grupo --}}
                @forelse($group->teams as $team)
                <div class="flex items-center justify-between py-2 border-b last:border-0">
                    <span class="text-sm font-medium">{{ $team->name }}</span>
                    <button type="button"
                            onclick="confirmDelete('{{ route('admin.tournaments.groups.teams.destroy', [$tournament, $group, $team]) }}', '¿Quitar a {{ $team->name }} del Grupo {{ $group->name }}?')"
                            class="text-red-500 hover:text-red-700 text-xs">
                        Quitar
                    </button>
                </div>
                @empty
                    <p class="text-gray-400 text-xs mb-3">Sin equipos asignados.</p>
                @endforelse

                {{-- Agregar equipo al grupo --}}
                <form method="POST"
                      action="{{ route('admin.tournaments.groups.teams.store', [$tournament, $group]) }}"
                      class="flex gap-2 mt-3">
                    @csrf
                    <select name="team_id"
                            class="flex-1 border rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Selecciona un equipo</option>
                        @foreach(\App\Models\Team::all() as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm hover:bg-green-200">
                        + Agregar
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endif

{{-- Tabla de posiciones --}}
@if(!empty($standings))
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Tabla de Posiciones</h2>
    @foreach($standings as $groupName => $teams)
    <div class="mb-6">
        <h3 class="font-bold text-green-700 mb-2">Grupo {{ $groupName }}</h3>
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-green-50 text-green-800">
                <tr>
                    <th class="text-left px-3 py-2">#</th>
                    <th class="text-left px-3 py-2">Equipo</th>
                    <th class="px-3 py-2">PJ</th>
                    <th class="px-3 py-2">G</th>
                    <th class="px-3 py-2">E</th>
                    <th class="px-3 py-2">P</th>
                    <th class="px-3 py-2">GF</th>
                    <th class="px-3 py-2">GC</th>
                    <th class="px-3 py-2">DG</th>
                    <th class="px-3 py-2 font-bold">Pts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $pos => $row)
                <tr class="border-t {{ $pos < 2 ? 'bg-green-50' : '' }}">
                    <td class="px-3 py-2 text-gray-500">{{ $pos + 1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $row['team']->name }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['played'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['won'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['drawn'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['lost'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['gf'] }}</td>
                    <td class="px-3 py-2 text-center">{{ $row['gc'] }}</td>
                    <td class="px-3 py-2 text-center">
                        {{ $row['gd'] > 0 ? '+' : '' }}{{ $row['gd'] }}
                    </td>
                    <td class="px-3 py-2 text-center font-bold text-green-700">{{ $row['points'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    </div>
    @endforeach
</div>
@endif

{{-- Partidos --}}
@if($tournament->matches()->exists())
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Partidos</h2>
    @php
        $stageOrder = ['group' => 1, 'round32' => 2, 'round16' => 3, 'quarter' => 4, 'semi' => 5, 'final' => 6];
        $allMatches = $tournament->matches()
            ->with(['homeTeam','awayTeam','group'])
            ->get()
            ->sortBy(fn($m) => [$stageOrder[$m->stage] ?? 99, $m->played_at])
            ->groupBy(fn($m) => $m->stage === 'group' ? 'Grupo ' . ($m->group->name ?? '?') : $m->stage);
    @endphp
    @foreach($allMatches as $groupName => $matches)
    <div class="mb-4">
        <h3 class="font-bold text-green-700 mb-2">
            {{ match($groupName) {
                'round32' => 'Ronda de 32',
                'round16' => 'Octavos de final',
                'quarter' => 'Cuartos de final',
                'semi'    => 'Semifinales',
                'final'   => 'Final',
                default   => $groupName
            } }}
        </h3>
        @foreach($matches as $match)
        <div class="flex flex-wrap items-center justify-between border rounded-lg px-4 py-3 mb-2 hover:bg-gray-50 gap-2">
            <span class="font-medium w-1/3 text-right">{{ $match->homeTeam?->name ?? '(Equipo eliminado)' }}</span>
            <span class="mx-4 text-gray-500 text-sm">
                @if($match->status === 'finished')
                    <div class="text-center">
                        <span class="font-bold text-gray-800">{{ $match->home_score }} - {{ $match->away_score }}</span>
                        @if(!is_null($match->home_penalties))
                            <span class="text-xs text-blue-600 block">({{ $match->home_penalties }}-{{ $match->away_penalties }} pen)</span>
                        @endif
                    </div>
                @else
                    <span class="text-xs">{{ $match->played_at->format('d/m H:i') }}</span>
                @endif
            </span>
            <span class="font-medium w-1/3 text-left">{{ $match->awayTeam?->name ?? '(Equipo eliminado)' }}</span>
            @if($match->status === 'finished')
                <a href="{{ route('admin.matches.show', $match) }}?from=tournament&id={{ $tournament->id }}"
                class="text-xs text-green-700 hover:underline ml-4">
                    Ver
                </a>
            @else
                <a href="{{ route('admin.matches.edit', $match) }}?from=tournament&id={{ $tournament->id }}"
                class="text-xs text-blue-600 hover:underline ml-4">
                    Cargar resultado
                </a>
            @endif
        </div>
        @endforeach
    </div>
    @endforeach
</div>
@endif

{{-- Modal Campeón --}}
@if(isset($champion))
    <div id="champion-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center"
        onclick="this.classList.add('hidden')">
        <div class="absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-10 text-center max-w-sm mx-4 transform transition-all"
            onclick="event.stopPropagation()">

            <button onclick="document.getElementById('champion-modal').classList.add('hidden')"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">✕</button>

            <div class="text-6xl mb-4">🏆</div>
            <h2 class="text-2xl font-bold text-yellow-600 mb-2">¡Campeón!</h2>
            <p class="text-gray-500 text-sm mb-6">{{ $tournament->name }} {{ $tournament->edition }}</p>

            @if($champion->shield_url)
                <img src="{{ Storage::url($champion->shield_url) }}"
                    class="w-28 h-28 rounded-full object-cover border-4 border-yellow-400 shadow-lg mx-auto mb-4">
            @else
                <div class="w-28 h-28 rounded-full bg-yellow-100 border-4 border-yellow-400
                            flex items-center justify-center text-yellow-700 font-bold text-4xl
                            shadow-lg mx-auto mb-4">
                    {{ strtoupper(substr($champion->name, 0, 2)) }}
                </div>
            @endif

            <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $champion->name }}</h3>
            <p class="text-yellow-500 font-medium">
                {{ $finalMatch->home_score }} - {{ $finalMatch->away_score }}
            </p>
            <p class="text-gray-400 text-sm mt-1">
                vs {{ $champion->id === $finalMatch->homeTeam->id ? $finalMatch->awayTeam->name : $finalMatch->homeTeam->name }}
            </p>

            <button onclick="document.getElementById('champion-modal').classList.add('hidden')"
                    class="mt-6 bg-yellow-500 text-white px-8 py-2 rounded-lg hover:bg-yellow-600 font-medium">
                ¡Celebrar! 🎉
            </button>
        </div>
    </div>

    {{-- Mostrar automáticamente si se acaba de finalizar --}}
    @if(session('show_champion'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('champion-modal').classList.remove('hidden');
        });
    </script>
    @endif
@endif

    {{-- Modal simulación con fallback aleatorio --}}
    @if(session('simulate_failed'))
    <div id="simulate-modal"
        class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 w-full">
            
            <div class="text-center mb-6">
                <div class="text-5xl mb-3">⚠️</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Límite de IA alcanzado</h2>
                <p class="text-gray-500 text-sm">
                    La IA procesó <span class="font-bold text-green-600">{{ session('simulate_success') }} partidos</span> 
                    correctamente, pero 
                    <span class="font-bold text-orange-500">{{ session('simulate_failed') }} partidos</span> 
                    no pudieron ser predichos.
                </p>
            </div>

            {{-- Lista de partidos fallidos --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6 max-h-40 overflow-y-auto">
                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Partidos pendientes:</p>
                @foreach(explode('|', session('simulate_failed_matches')) as $matchName)
                    <div class="flex items-center gap-2 py-1 border-b border-gray-100 last:border-0">
                        <span class="text-orange-400 text-xs">⚽</span>
                        <span class="text-sm text-gray-700">{{ $matchName }}</span>
                    </div>
                @endforeach
            </div>

            <p class="text-center text-sm text-gray-500 mb-6">
                ¿Deseas simular los <strong>{{ session('simulate_failed') }} partidos restantes</strong> 
                con resultados aleatorios?
            </p>

            <div class="flex gap-3">
                {{-- Botón simular aleatorio --}}
                <form method="POST" 
                    action="{{ route('admin.tournaments.simulate', session('tournament_id')) }}"
                    class="flex-1">
                    @csrf
                    <input type="hidden" name="use_random" value="1">
                    <button type="submit"
                            class="w-full bg-orange-500 text-white py-3 rounded-xl font-medium hover:bg-orange-600 transition-colors">
                        🎲 Simular aleatoriamente
                    </button>
                </form>

                {{-- Botón cancelar --}}
                <button onclick="document.getElementById('simulate-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-600 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">
                También puedes esperar unos minutos y volver a intentar con IA.
            </p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('simulate-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    });
    </script>
    @endif

    {{-- Modal confirmación simulación IA --}}
    <div id="simulate-confirm-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center"
        onclick="this.classList.add('hidden')">
        <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 w-full"
            onclick="event.stopPropagation()">

            <button onclick="document.getElementById('simulate-confirm-modal').classList.add('hidden')"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl">✕</button>

            <div class="text-center mb-6">
                <div class="text-5xl mb-3">🤖</div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Simular con IA</h2>
                <p class="text-gray-500 text-sm">
                    Gemini AI analizará cada partido pendiente y predecirá el resultado.
                    Este proceso puede tardar varios minutos.
                </p>
            </div>

            @php
                $pendingCount = $tournament->matches()->where('status', 'scheduled')->count();
                $currentStage = $tournament->matches()->where('status', 'scheduled')->first()?->stage ?? 'group';
            @endphp

            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⚽</span>
                    <div>
                        <p class="font-bold text-purple-700">{{ $pendingCount }} partidos pendientes</p>
                        <p class="text-sm text-purple-500">
                            Fase: {{ \App\Helpers\StatusHelper::stage($currentStage) }}
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center mb-6">
                Si la IA falla en algunos partidos, se te preguntará si deseas completarlos aleatoriamente.
            </p>

            <div class="flex gap-3">
                <form method="POST"
                    action="{{ route('admin.tournaments.simulate', $tournament) }}"
                    class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full bg-purple-600 text-white py-3 rounded-xl font-medium hover:bg-purple-700 transition-colors">
                        🤖 Simular con IA
                    </button>
                </form>
                <button onclick="document.getElementById('simulate-confirm-modal').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-600 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
@endsection