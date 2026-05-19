@extends('layouts.admin')

@section('title', $tournament->name)

@section('content')

{{-- Contenedor General con un Ancho Máximo Confortable --}}
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header del Torneo --}}
    <div class="flex flex-col xl:flex-row xl:justify-between xl:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 font-bold text-xs uppercase tracking-wider px-2.5 py-1 rounded-md border border-green-100">
                🏆 Torneo Oficial
            </span>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 mt-2">{{ $tournament->name }}</h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Edición {{ $tournament->edition }} <span class="text-gray-200 mx-1">•</span> Format: {{ ucfirst($tournament->format) }}
            </p>
        </div>

        {{-- Barra de Herramientas / Botonera Adaptativa --}}
        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Ver Bracket --}}
            <a href="{{ route('admin.tournaments.bracket', $tournament) }}"
               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                <span>📊</span> Ver Bracket
            </a>

            {{-- Simulación Inteligente --}}
            @if($tournament->matches()->where('status', 'scheduled')->exists() && config('app.ai_analysis_enabled'))
                <button type="button" onclick="document.getElementById('simulate-confirm-modal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center gap-2 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                    <span>🤖</span> Simular con IA
                </button>
            @endif

            {{-- Documentos PDF --}}
            <div class="inline-flex rounded-xl shadow-sm bg-gray-50 border border-gray-200 p-0.5">
                <a href="{{ route('admin.tournaments.pdf.standings', $tournament) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 hover:bg-white rounded-lg transition-all" title="Exportar Posiciones">
                    📄 Posiciones
                </a>
                <a href="{{ route('admin.tournaments.pdf.fixture', $tournament) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 hover:bg-white rounded-lg transition-all" title="Exportar Calendario">
                    📄 Calendario
                </a>
            </div>

            {{-- Editar Datos --}}
            <a href="{{ route('admin.tournaments.edit', $tournament) }}"
               class="inline-flex items-center justify-center border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-sm">
                ⚙️ Editar
            </a>

            {{-- Flujo de Fixture y Fases --}}
            @if(!$tournament->matches()->exists())
                <form method="POST" action="{{ route('admin.tournaments.fixture', $tournament) }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                        ⚡ Generar Fixture
                    </button>
                </form>
            @else
                @php
                    $finalMatch = $tournament->matches()->where('stage', 'final')->where('status', 'finished')->first();
                @endphp

                @if($finalMatch)
                    @php
                        $champion = !is_null($finalMatch->home_penalties)
                            ? ($finalMatch->home_penalties > $finalMatch->away_penalties ? $finalMatch->homeTeam : $finalMatch->awayTeam)
                            : ($finalMatch->home_score > $finalMatch->away_score ? $finalMatch->homeTeam : $finalMatch->awayTeam);
                    @endphp
                    @if($tournament->status === 'finished')
                        <button onclick="document.getElementById('champion-modal').classList.remove('hidden')"
                                class="inline-flex items-center justify-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                            ⭐ Ver Campeón
                        </button>
                    @endif
                    @if($tournament->status !== 'finished')
                        <form method="POST" action="{{ route('admin.tournaments.next-round', $tournament) }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                                ✅ Concluir Torneo
                            </button>
                        </form>
                    @endif
                @else
                    <form method="POST" action="{{ route('admin.tournaments.next-round', $tournament) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                            ⏩ Siguiente Fase
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    {{-- Info General en Módulos de KPI --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Estado del Torneo</p>
                <p class="text-base font-black mt-0.5
                    {{ $tournament->status === 'active' ? 'text-emerald-700' : '' }}
                    {{ $tournament->status === 'draft' ? 'text-amber-600' : '' }}
                    {{ $tournament->status === 'finished' ? 'text-gray-500' : '' }}">
                    {{ \App\Helpers\StatusHelper::tournament($tournament->status) }}
                </p>
            </div>
            <span class="w-2.5 h-2.5 rounded-full 
                {{ $tournament->status === 'active' ? 'bg-emerald-500 animate-pulse' : '' }}
                {{ $tournament->status === 'draft' ? 'bg-amber-500' : '' }}
                {{ $tournament->status === 'finished' ? 'bg-gray-300' : '' }}"></span>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Fecha de Apertura</p>
            <p class="text-base font-black text-gray-700 mt-0.5">{{ $tournament->starts_at->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Fecha de Clausura</p>
            <p class="text-base font-black text-gray-700 mt-0.5">
                {{ $tournament->ends_at ? $tournament->ends_at->format('d/m/Y') : 'En curso' }}
            </p>
        </div>
    </div>

    {{-- Configuración Inicial de Grupos (Solo en Draft) --}}
    @if($tournament->status === 'draft')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 pb-4 mb-4 gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">Fase Regular: Grupos y Equipos</h2>
                <p class="text-xs text-gray-400">Genera los grupos base y vincula los equipos inscritos.</p>
            </div>
            {{-- Formulario Nuevo Grupo --}}
            <form method="POST" action="{{ route('admin.tournaments.groups.store', $tournament) }}" class="flex items-center gap-2 w-full sm:w-auto">
                @csrf
                <input type="text" name="name" placeholder="Ej. A" maxlength="2"
                       class="border border-gray-200 rounded-xl px-3 py-1.5 text-sm w-24 text-center font-bold uppercase focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm whitespace-nowrap">
                    ➕ Nuevo Grupo
                </button>
            </form>
        </div>

        @if($tournament->groups->isEmpty())
            <div class="text-center py-8 bg-slate-50/50 border border-dashed rounded-2xl">
                <p class="text-sm text-gray-400 font-medium">No se han estructurado grupos de juego aún. Comienza agregando uno arriba.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($tournament->groups as $group)
                <div class="border border-gray-100 bg-slate-50/40 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2 mb-3">
                            <h3 class="font-black text-green-800 text-base tracking-tight">Grupo {{ $group->name }}</h3>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white px-2 py-0.5 rounded border">{{ $group->teams->count() }} Equipos</span>
                        </div>

                        {{-- Equipos Asignados --}}
                        <div class="space-y-1">
                            @forelse($group->teams as $team)
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-gray-100 shadow-sm transition-all hover:border-gray-200">
                                <span class="text-xs font-semibold text-gray-700">{{ $team->name }}</span>
                                <button type="button"
                                        onclick="confirmDelete('{{ route('admin.tournaments.groups.teams.destroy', [$tournament, $group, $team]) }}', '¿Quitar a {{ $team->name }} del Grupo {{ $group->name }}?')"
                                        class="text-rose-500 hover:text-rose-700 font-bold text-[11px] hover:bg-rose-50 px-2 py-1 rounded transition-colors">
                                    Quitar
                                </button>
                            </div>
                            @empty
                                <p class="text-gray-400 text-xs italic py-2">Sin equipos asignados en este sector.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Añadir Integrante --}}
                    <form method="POST" action="{{ route('admin.tournaments.groups.teams.store', [$tournament, $group]) }}" class="flex gap-2 mt-4 pt-3 border-t border-gray-100">
                        @csrf
                        <select name="team_id" required class="flex-1 bg-white border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs font-medium focus:ring-1 focus:ring-green-500 focus:outline-none">
                            <option value="">Selecciona un equipo...</option>
                            @foreach(\App\Models\Team::orderBy('name')->get() as $availableTeam)
                                <option value="{{ $availableTeam->id }}">{{ $availableTeam->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-white hover:bg-green-50 text-green-700 font-bold border border-green-200 px-3 py-1.5 rounded-xl text-xs transition-all shadow-sm">
                            Agregar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    {{-- Secciones de Resultados: Tablas de Clasificaciones --}}
    @if(!empty($standings))
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4 border-b border-gray-50 pb-3">
            <span class="text-xl">📊</span>
            <h2 class="text-lg font-bold text-gray-800 tracking-tight">Tablas de Clasificación General</h2>
        </div>
        
        <div class="space-y-3">
            @foreach($standings as $groupName => $teams)
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm bg-white">
                <button onclick="toggleSection('standings-{{ $groupName }}')"
                        class="w-full flex justify-between items-center px-4 py-3.5 bg-slate-50/80 hover:bg-slate-100/80 font-bold text-gray-700 text-sm transition-all text-left">
                    <span class="flex items-center gap-2 text-green-800 font-black">Grupo {{ $groupName }}</span>
                    <span id="standings-{{ $groupName }}-icon" class="text-xs text-gray-400 bg-white px-2 py-0.5 rounded border font-mono">∨</span>
                </button>
                
                <div id="standings-{{ $groupName }}" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-gray-500 uppercase tracking-wider font-bold text-[10px] border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-center w-12">Pos</th>
                                    <th class="px-4 py-3">Equipo</th>
                                    <th class="px-3 py-3 text-center">PJ</th>
                                    <th class="px-3 py-3 text-center text-emerald-600">G</th>
                                    <th class="px-3 py-3 text-center text-amber-600">E</th>
                                    <th class="px-3 py-3 text-center text-rose-600">P</th>
                                    <th class="px-3 py-3 text-center">GF</th>
                                    <th class="px-3 py-3 text-center">GC</th>
                                    <th class="px-3 py-3 text-center">DG</th>
                                    <th class="px-4 py-3 text-center font-black bg-slate-100/50 text-gray-800 w-16">Pts</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($teams as $pos => $row)
                                <tr class="hover:bg-slate-50/50 transition-colors {{ $pos < 2 ? 'bg-emerald-50/30' : '' }}">
                                    <td class="px-4 py-3 text-center font-bold text-gray-400">
                                        @if($pos < 2)
                                            <span class="inline-block bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded text-[10px] font-bold">#{{ $pos + 1 }}</span>
                                        @else
                                            #{{ $pos + 1 }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-bold text-gray-700">{{ $row['team']->name }}</td>
                                    <td class="px-3 py-3 text-center font-medium text-gray-600">{{ $row['played'] }}</td>
                                    <td class="px-3 py-3 text-center font-semibold text-emerald-700">{{ $row['won'] }}</td>
                                    <td class="px-3 py-3 text-center font-semibold text-amber-700">{{ $row['drawn'] }}</td>
                                    <td class="px-3 py-3 text-center font-semibold text-rose-700">{{ $row['lost'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-500">{{ $row['gf'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-500">{{ $row['gc'] }}</td>
                                    <td class="px-3 py-3 text-center font-mono font-bold {{ $row['gd'] > 0 ? 'text-emerald-600' : ($row['gd'] < 0 ? 'text-rose-600' : 'text-gray-400') }}">
                                        {{ $row['gd'] > 0 ? '+' : '' }}{{ $row['gd'] }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-black bg-slate-100/30 text-green-800 text-sm">{{ $row['points'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Módulo de Gestión y Listado de Partidos por Fases --}}
    {{-- Partidos --}}
    @if($tournament->matches()->exists())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4 border-b border-gray-50 pb-3">
            <span class="text-xl">⚽</span>
            <h2 class="text-lg font-bold text-gray-800 tracking-tight">Calendario e Historial de Encuentros</h2>
        </div>

        @php
            $stageOrder = ['group' => 1, 'round32' => 2, 'round16' => 3, 'quarter' => 4, 'semi' => 5, 'final' => 6];
            $allMatches = $tournament->matches()
                ->with(['homeTeam','awayTeam','group'])
                ->get()
                ->sortBy(fn($m) => [$stageOrder[$m->stage] ?? 99, $m->played_at]);

            // Separación de partidos: Fase de grupos vs Fases eliminatorias
            $groupStageMatches = $allMatches->filter(fn($m) => $m->stage === 'group')->groupBy(fn($m) => 'Grupo ' . ($m->group->name ?? '?'));
            $knockoutMatches = $allMatches->filter(fn($m) => $m->stage !== 'group')->groupBy('stage');
        @endphp

        <div class="space-y-3">
            
            {{-- ── CONTENEDOR MAESTRO: FASE DE GRUPOS ── --}}
            @if($groupStageMatches->isNotEmpty())
            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm bg-white">
                {{-- Botón Principal de la Fase de Grupos --}}
                <button onclick="toggleSection('stage-fase-de-grupos')"
                        class="w-full flex justify-between items-center px-4 py-4 bg-green-700 hover:bg-green-800 font-bold text-white text-sm transition-all text-left">
                    <span class="flex items-center gap-2 font-black text-base">📋 Fase de Grupos</span>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] bg-green-900/40 border border-green-600 font-bold px-2 py-0.5 rounded text-green-200 uppercase tracking-wider">
                            {{ $groupStageMatches->sum->count() }} partidos
                        </span>
                        <span id="stage-fase-de-grupos-icon" class="text-xs text-green-200 bg-green-900/30 px-2 py-0.5 rounded border border-green-600 font-mono">∨</span>
                    </div>
                </button>

                {{-- Contenido Interno de la Fase de Grupos --}}
                <div id="stage-fase-de-grupos" class="hidden p-4 bg-slate-50/50 space-y-3">
                    @foreach($groupStageMatches as $groupName => $matches)
                    <div class="border border-gray-100 rounded-xl overflow-hidden shadow-xs bg-white">
                        {{-- Botón de cada Grupo Individual --}}
                        <button onclick="toggleSection('matches-{{ Str::slug($groupName) }}')"
                                class="w-full flex justify-between items-center px-4 py-3 bg-slate-50 hover:bg-slate-100 font-bold text-gray-700 text-xs transition-all text-left">
                            <span class="font-black text-gray-800">🔍 {{ $groupName }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] bg-white border font-bold px-2 py-0.5 rounded text-gray-400 uppercase tracking-wider">{{ $matches->count() }} partidos</span>
                                <span id="matches-{{ Str::slug($groupName) }}-icon" class="text-xs text-gray-400 bg-white px-2 py-0.5 rounded border font-mono">∨</span>
                            </div>
                        </button>

                        {{-- Partidos del Grupo Correspondiente --}}
                        <div id="matches-{{ Str::slug($groupName) }}" class="hidden p-3 divide-y divide-gray-100/70 bg-white">
                            @foreach($matches as $match)
                            @php
                                $homeWins = $match->status === 'finished' && (
                                    !is_null($match->home_penalties)
                                        ? $match->home_penalties > $match->away_penalties
                                        : $match->home_score > $match->away_score
                                );
                                $awayWins = $match->status === 'finished' && !$homeWins && $match->status === 'finished';
                                $matchUrl = $match->status === 'finished'
                                    ? route('admin.matches.show', $match) . '?from=tournament&id=' . $tournament->id
                                    : route('admin.matches.edit', $match) . '?from=tournament&id=' . $tournament->id;
                            @endphp
                            
                            <div onclick="window.location='{{ $matchUrl }}'"
                                class="grid grid-cols-1 md:grid-cols-7 items-center bg-white border border-gray-100 rounded-xl px-4 py-3 my-1.5 hover:bg-green-50/40 gap-3 cursor-pointer transition-all shadow-sm group">
                                
                                <div class="md:col-span-3 flex items-center justify-start md:justify-end gap-2 order-2 md:order-1">
                                    <span class="text-xs sm:text-sm font-semibold tracking-tight text-left md:text-right {{ $homeWins ? 'text-green-800 font-black' : 'text-gray-700 font-medium' }}">
                                        {{ $match->homeTeam?->name ?? 'Clasificado pendiente' }}
                                    </span>
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $homeWins ? 'bg-green-500' : 'bg-transparent' }}"></span>
                                </div>

                                <div class="md:col-span-1 flex flex-col items-center justify-center order-1 md:order-2 bg-slate-50 group-hover:bg-white border rounded-xl p-1 md:py-1.5">
                                    @if($match->status === 'finished')
                                        <div class="flex items-center gap-1.5 font-mono text-base font-black">
                                            <span class="{{ $homeWins ? 'text-green-700' : 'text-gray-400' }}">{{ $match->home_score }}</span>
                                            <span class="text-gray-300 font-normal">—</span>
                                            <span class="{{ $awayWins ? 'text-green-700' : 'text-gray-400' }}">{{ $match->away_score }}</span>
                                        </div>
                                        @if(!is_null($match->home_penalties))
                                            <span class="text-[9px] font-bold text-blue-600 tracking-wide uppercase mt-0.5">
                                                ({{ $match->home_penalties }}-{{ $match->away_penalties }} Pen)
                                            </span>
                                        @endif
                                        <span class="text-[9px] font-bold tracking-widest text-emerald-700 uppercase bg-emerald-50 px-1.5 py-0.5 rounded-md mt-1">Final</span>
                                    @else
                                        <div class="text-gray-400 text-xs font-black tracking-widest uppercase">VS</div>
                                        <span class="text-[9px] font-mono text-gray-400 mt-0.5">{{ $match->played_at->format('d/m H:i') }}</span>
                                        <span class="text-[9px] font-bold tracking-widest text-blue-700 uppercase bg-blue-50 px-1.5 py-0.5 rounded-md mt-1 animate-pulse">Prog</span>
                                    @endif
                                </div>

                                <div class="md:col-span-3 flex items-center justify-start gap-2 order-3">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $awayWins ? 'bg-green-500' : 'bg-transparent' }}"></span>
                                    <span class="text-xs sm:text-sm font-semibold tracking-tight text-left {{ $awayWins ? 'text-green-800 font-black' : 'text-gray-700 font-medium' }}">
                                        {{ $match->awayTeam?->name ?? 'Clasificado pendiente' }}
                                    </span>
                                </div>

                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── FASES ELIMINATORIAS (Se mantienen directas fuera del contenedor de grupos) ── --}}
            @foreach($knockoutMatches as $stageName => $matches)
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm bg-white">
                <button onclick="toggleSection('matches-{{ $stageName }}')"
                        class="w-full flex justify-between items-center px-4 py-3.5 bg-slate-50/80 hover:bg-slate-100/80 font-bold text-gray-700 text-sm transition-all text-left">
                    <span class="font-black text-gray-800">
                        {{ match($stageName) {
                            'round32' => '⚔️ Ronda de 32',
                            'round16' => '⚡ Octavos de Final',
                            'quarter' => '🛡️ Cuartos de Final',
                            'semi'    => '🔥 Semifinales',
                            'final'   => '🏆 Gran Final',
                            default   => '⚽ ' . $stageName
                        } }}
                    </span>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] bg-white border font-bold px-2 py-0.5 rounded text-gray-400 uppercase tracking-wider">{{ $matches->count() }} partidos</span>
                        <span id="matches-{{ $stageName }}-icon" class="text-xs text-gray-400 bg-white px-2 py-0.5 rounded border font-mono">∧</span>
                    </div>
                </button>

                <div id="matches-{{ $stageName }}" class="p-4 bg-slate-50/30 divide-y divide-gray-100/70">
                    @foreach($matches as $match)
                    @php
                        $homeWins = $match->status === 'finished' && (
                            !is_null($match->home_penalties)
                                ? $match->home_penalties > $match->away_penalties
                                : $match->home_score > $match->away_score
                        );
                        $awayWins = $match->status === 'finished' && !$homeWins && $match->status === 'finished';
                        $matchUrl = $match->status === 'finished'
                            ? route('admin.matches.show', $match) . '?from=tournament&id=' . $tournament->id
                            : route('admin.matches.edit', $match) . '?from=tournament&id=' . $tournament->id;
                    @endphp
                    
                    <div onclick="window.location='{{ $matchUrl }}'"
                        class="grid grid-cols-1 md:grid-cols-7 items-center bg-white border border-gray-100 rounded-xl px-4 py-3 my-1.5 hover:bg-green-50/40 gap-3 cursor-pointer transition-all shadow-sm group">
                        
                        <div class="md:col-span-3 flex items-center justify-start md:justify-end gap-2 order-2 md:order-1">
                            <span class="text-xs sm:text-sm font-semibold tracking-tight text-left md:text-right {{ $homeWins ? 'text-green-800 font-black' : 'text-gray-700 font-medium' }}">
                                {{ $match->homeTeam?->name ?? 'Clasificado pendiente' }}
                            </span>
                            <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $homeWins ? 'bg-green-500' : 'bg-transparent' }}"></span>
                        </div>

                        <div class="md:col-span-1 flex flex-col items-center justify-center order-1 md:order-2 bg-slate-50 group-hover:bg-white border rounded-xl p-1 md:py-1.5">
                            @if($match->status === 'finished')
                                <div class="flex items-center gap-1.5 font-mono text-base font-black">
                                    <span class="{{ $homeWins ? 'text-green-700' : 'text-gray-400' }}">{{ $match->home_score }}</span>
                                    <span class="text-gray-300 font-normal">—</span>
                                    <span class="{{ $awayWins ? 'text-green-700' : 'text-gray-400' }}">{{ $match->away_score }}</span>
                                </div>
                                @if(!is_null($match->home_penalties))
                                    <span class="text-[9px] font-bold text-blue-600 tracking-wide uppercase mt-0.5">
                                        ({{ $match->home_penalties }}-{{ $match->away_penalties }} Pen)
                                    </span>
                                @endif
                                <span class="text-[9px] font-bold tracking-widest text-emerald-700 uppercase bg-emerald-50 px-1.5 py-0.5 rounded-md mt-1">Final</span>
                            @else
                                <div class="text-gray-400 text-xs font-black tracking-widest uppercase">VS</div>
                                <span class="text-[9px] font-mono text-gray-400 mt-0.5">{{ $match->played_at->format('d/m H:i') }}</span>
                                <span class="text-[9px] font-bold tracking-widest text-blue-700 uppercase bg-blue-50 px-1.5 py-0.5 rounded-md mt-1 animate-pulse">Prog</span>
                            @endif
                        </div>

                        <div class="md:col-span-3 flex items-center justify-start gap-2 order-3">
                            <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $awayWins ? 'bg-green-500' : 'bg-transparent' }}"></span>
                            <span class="text-xs sm:text-sm font-semibold tracking-tight text-left {{ $awayWins ? 'text-green-800 font-black' : 'text-gray-700 font-medium' }}">
                                {{ $match->awayTeam?->name ?? 'Clasificado pendiente' }}
                            </span>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>
    @endif

</div>

{{-- MODALES DINÁMICOS REDISEÑADOS --}}

{{-- Modal Campeón --}}
@if(isset($champion))
<div id="champion-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center transition-all" onclick="this.classList.add('hidden')">
    <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl p-8 text-center max-w-sm mx-4 transform transition-all border border-yellow-100" onclick="event.stopPropagation()">
        
        <button onclick="document.getElementById('champion-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors font-mono">✕</button>
        
        <div class="relative inline-block mb-3">
            <span class="text-6xl block drop-shadow-md animate-bounce">🏆</span>
        </div>
        <h2 class="text-2xl font-black text-amber-600 tracking-tight">¡Campeón del Torneo!</h2>
        <p class="text-gray-400 text-xs font-medium">{{ $tournament->name }} • {{ $tournament->edition }}</p>

        <div class="my-6">
            @if($champion->shield_url)
                <img src="{{ Storage::url($champion->shield_url) }}" class="w-24 h-24 rounded-full object-cover border-4 border-amber-400 shadow-xl mx-auto p-1 bg-white">
            @else
                <div class="w-24 h-24 rounded-full bg-amber-50 border-4 border-amber-400 flex items-center justify-center text-amber-700 font-black text-3xl shadow-lg mx-auto">
                    {{ strtoupper(substr($champion->name, 0, 2)) }}
                </div>
            @endif
            <h3 class="text-2xl font-black text-gray-800 mt-4 tracking-tight">{{ $champion->name }}</h3>
            <p class="text-sm font-mono font-bold text-amber-500 bg-amber-50 rounded-xl px-4 py-1.5 inline-block mt-2 shadow-inner">
                Resultado final: {{ $finalMatch->home_score }} - {{ $finalMatch->away_score }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Subcampeón: <span class="font-semibold text-gray-500">{{ $champion->id === $finalMatch->homeTeam->id ? $finalMatch->awayTeam->name : $finalMatch->homeTeam->name }}</span>
            </p>
        </div>

        <button onclick="document.getElementById('champion-modal').classList.add('hidden')" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-md">
            ¡Celebrar con el equipo! 🎉
        </button>
    </div>
</div>

@if(session('show_champion'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('champion-modal').classList.remove('hidden');
    });
</script>
@endif
@endif

{{-- Modal Límite de IA / Fallback --}}
@if(session('simulate_failed'))
<div id="simulate-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-xs"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full border border-orange-100">
        
        <div class="text-center mb-4">
            <div class="text-4xl mb-2">⚠️</div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Capacidad de Cómputo de IA Excedida</h2>
            <p class="text-xs text-gray-500 mt-1">
                La IA resolvió con éxito <span class="font-bold text-green-600">{{ session('simulate_success') }} cruces</span>, pero 
                <span class="font-bold text-orange-600">{{ session('simulate_failed') }} partidos</span> arrojaron timeout o cuotas saturadas.
            </p>
        </div>

        <div class="bg-slate-50 border border-gray-100 rounded-xl p-3 mb-4 max-h-36 overflow-y-auto shadow-inner">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Partidos sin resolver:</p>
            @foreach(explode('|', session('simulate_failed_matches')) as $matchName)
                <div class="flex items-center gap-2 py-1 text-xs text-gray-600 border-b border-gray-100/50 last:border-0 font-medium">
                    <span class="text-orange-500">⏱️</span>
                    <span>{{ $matchName }}</span>
                </div>
            @endforeach
        </div>

        <p class="text-center text-xs text-gray-500 mb-5 leading-relaxed">
            ¿Prefieres resolver de forma automatizada los <strong>{{ session('simulate_failed') }} compromisos restantes</strong> mediante algoritmos tradicionales aleatorios?
        </p>

        <div class="flex flex-col sm:flex-row gap-2">
            <form method="POST" action="{{ route('admin.tournaments.simulate', session('tournament_id')) }}" class="flex-1">
                @csrf
                <input type="hidden" name="use_random" value="1">
                <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white py-2.5 rounded-xl text-xs font-bold hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all text-center">
                    🎲 Simulación Aleatoria
                </button>
            </form>
            <button onclick="document.getElementById('simulate-modal').classList.add('hidden')"
                    class="flex-1 border border-gray-200 text-gray-500 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all text-center">
                Ignorar y Resolver Luego
            </button>
        </div>
    </div>
</div>
@endif

{{-- Modal Confirmación de Simulación IA --}}
<div id="simulate-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-xs"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full border border-purple-100" onclick="event.stopPropagation()">
        
        <button onclick="document.getElementById('simulate-confirm-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors font-mono">✕</button>

        <div class="text-center mb-5">
            <div class="text-4xl mb-2">🤖</div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Lanzar Simulación Integral</h2>
            <p class="text-xs text-gray-400 mt-1 leading-relaxed">
                Gemini AI analizará las plantillas, el historial técnico y las variables competitivas de cada llave pendiente.
            </p>
        </div>

        @php
            $pendingCount = $tournament->matches()->where('status', 'scheduled')->count();
            $currentStage = $tournament->matches()->where('status', 'scheduled')->first()?->stage ?? 'group';
        @endphp

        <div class="bg-purple-50 border border-purple-100 rounded-xl p-3.5 mb-4 shadow-inner text-center sm:text-left">
            <div class="flex flex-col sm:flex-row items-center gap-2.5">
                <span class="text-2xl bg-white p-1 rounded-lg border shadow-xs">📊</span>
                <div>
                    <p class="text-xs font-black text-purple-900">{{ $pendingCount }} Encuentros Agendados</p>
                    <p class="text-[10px] text-purple-500 font-medium mt-0.5">Fase actual: {{ \App\Helpers\StatusHelper::stage($currentStage) }}</p>
                </div>
            </div>
        </div>

        <p class="text-[10px] text-gray-400 text-center mb-5 leading-relaxed">
            *Nota: Este proceso realiza llamadas asíncronas y puede demorar unos minutos según el tamaño del fixture.
        </p>

        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.tournaments.simulate', $tournament) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition-all">
                    Iniciar Motor de IA
                </button>
            </form>
            <button onclick="document.getElementById('simulate-confirm-modal').classList.add('hidden')"
                    class="flex-1 border border-gray-200 text-gray-500 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all">
                Cerrar
            </button>
        </div>
    </div>
</div>

{{-- Scripts Base de Colapsables --}}
<script>
function toggleSection(id) {
    const section = document.getElementById(id);
    const icon = document.getElementById(id + '-icon');
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.textContent = '∧';
    } else {
        section.classList.add('hidden');
        icon.textContent = '∨';
    }
}
</script>

@endsection