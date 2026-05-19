@extends('layouts.admin')

@section('title', 'Cargar Resultado')

@section('content')

{{-- Contenedor Centralizado de la Vista --}}
<div class="max-w-3xl mx-auto">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 mb-6 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">📅</span> Cargar Resultado
            </h1>
            <p class="text-sm font-medium text-gray-500 mt-1 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                <span class="text-gray-700 font-bold">{{ $match->homeTeam->name }}</span> 
                <span class="text-gray-300">vs</span> 
                <span class="text-gray-700 font-bold">{{ $match->awayTeam->name }}</span>
                <span class="text-gray-300">•</span>
                <span class="inline-flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded text-xs text-gray-600 font-mono">
                    {{ $match->played_at->format('d/m/Y H:i') }} hs
                </span>
            </p>
        </div>
        
        @if(config('app.ai_analysis_enabled'))
        <button type="button" id="predict-btn" onclick="getPrediction()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all transform active:scale-95">
            <span>🤖</span> Predecir resultado
        </button>
        @endif
    </div>

    {{-- Widget de predicción IA Premium --}}
    @if(config('app.ai_analysis_enabled'))
    <div id="prediction-widget" class="hidden bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-100 rounded-2xl p-5 mb-6 shadow-sm transition-all">
        <div class="flex items-center justify-between gap-3 border-b border-purple-200/50 pb-3 mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🤖</span>
                <h3 class="font-bold text-purple-900 tracking-tight">Análisis Predictivo</h3>
            </div>
            <span class="text-[10px] font-bold tracking-wider uppercase bg-purple-200/60 text-purple-700 px-2.5 py-1 rounded-full border border-purple-300/40">
                Powered by Gemini AI
            </span>
        </div>
        
        {{-- Loader --}}
        <div id="prediction-loading" class="flex items-center gap-3 text-purple-600 text-sm font-medium p-4 justify-center">
            <svg class="animate-spin h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Procesando estadísticas del partido...
        </div>

        {{-- Render de Resultados --}}
        <div id="prediction-result" class="hidden">
            <div class="bg-white/80 border border-purple-200/60 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 mb-4">
                <div class="flex-1 text-center md:text-right font-bold text-gray-700 text-base">{{ $match->homeTeam->name }}</div>
                
                <div class="flex flex-col items-center bg-purple-600 text-white px-6 py-2 rounded-xl shadow-md min-w-[120px]">
                    <span class="text-2xl font-black tracking-tight"><span id="pred-home">0</span> — <span id="pred-away">0</span></span>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-purple-200 mt-0.5">Predicción</span>
                </div>
                
                <div class="flex-1 text-center md:text-left font-bold text-gray-700 text-base">{{ $match->awayTeam->name }}</div>
            </div>
            
            <div class="bg-white border border-purple-100 rounded-xl p-3 text-sm text-gray-600 leading-relaxed shadow-inner mb-4" id="pred-analysis"></div>
            
            <button type="button" onclick="applyPrediction()"
                    class="w-full bg-white hover:bg-purple-600 border border-purple-300 text-purple-700 hover:text-white font-bold py-2 rounded-xl text-sm transition-all shadow-sm">
                Usar esta predicción como resultado
            </button>
        </div>
        
        <div id="prediction-error" class="hidden text-red-600 bg-red-50 border border-red-200 p-3 rounded-xl text-sm font-medium"></div>
    </div>
    @endif

    {{-- Bloque de Formulario Principal Centrado --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('admin.matches.update', $match) }}?from={{ request('from') }}&id={{ request('id') }}" class="p-6">
            @csrf
            @method('PUT')

            {{-- Grid del Marcador Global Compacto y Simétrico --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 bg-slate-50/60 border border-gray-100 p-6 rounded-2xl mb-6">
                {{-- Local --}}
                <div class="w-full sm:flex-1 text-center">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
                        Goles {{ $match->homeTeam->name }}
                    </label>
                    <input type="number" name="home_score" min="0" max="99" placeholder="0"
                           value="{{ old('home_score', $match->home_score) }}"
                           class="w-full bg-white border border-gray-200 text-center font-black text-2xl rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('home_score') border-red-500 ring-2 ring-red-100 @enderror">
                    @error('home_score')
                        <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center justify-center gap-1">❌ {{ $message }}</p>
                    @enderror
                </div>

                {{-- Separador Visual --}}
                <div class="text-gray-300 font-black text-xl hidden sm:block pt-5">—</div>

                {{-- Visitante --}}
                <div class="w-full sm:flex-1 text-center">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
                        Goles {{ $match->awayTeam->name }}
                    </label>
                    <input type="number" name="away_score" min="0" max="99" placeholder="0"
                           value="{{ old('away_score', $match->away_score) }}"
                           class="w-full bg-white border border-gray-200 text-center font-black text-2xl rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('away_score') border-red-500 ring-2 ring-red-100 @enderror">
                    @error('away_score')
                        <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center justify-center gap-1">❌ {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Penales (solo eliminatorias directas) --}}
            @if(in_array($match->stage, ['round32', 'quarter', 'semi', 'final']))
            <div id="penalties-section" class="{{ ($match->home_score === $match->away_score && $match->status === 'finished') ? '' : 'hidden' }} bg-blue-50/60 border border-blue-100 rounded-2xl p-5 mb-6 shadow-inner text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="text-lg">🎯</span>
                    <h3 class="font-bold text-blue-800 text-sm tracking-tight">Definición por Penales Obligatoria</h3>
                </div>
                <p class="text-blue-600 text-xs mb-4">El partido se encuentra empatado. Registra la tanda reglamentaria.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md mx-auto">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ $match->homeTeam->name }}</label>
                        <input type="number" name="home_penalties" min="0" max="99" placeholder="0"
                               value="{{ old('home_penalties', $match->home_penalties) }}"
                               class="w-full bg-white border border-gray-200 font-bold rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ $match->awayTeam->name }}</label>
                        <input type="number" name="away_penalties" min="0" max="99" placeholder="0"
                               value="{{ old('away_penalties', $match->away_penalties) }}"
                               class="w-full bg-white border border-gray-200 font-bold rounded-lg px-3 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const homeScore = document.querySelector('input[name="home_score"]');
                const awayScore = document.querySelector('input[name="away_score"]');
                const penaltiesSection = document.getElementById('penalties-section');

                function checkDraw() {
                    const home = parseInt(homeScore.value) || 0;
                    const away = parseInt(awayScore.value) || 0;
                    if (home === away && (homeScore.value !== '' && awayScore.value !== '')) {
                        penaltiesSection.classList.remove('hidden');
                    } else {
                        penaltiesSection.classList.add('hidden');
                    }
                }

                homeScore.addEventListener('input', checkDraw);
                awayScore.addEventListener('input', checkDraw);
                checkDraw();
            });
            </script>
            @endif

            {{-- Detalle de Goles Uno a Uno --}}
            <div class="mb-6 border-t border-gray-100 pt-5">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-center sm:text-left gap-3 mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 tracking-tight text-base">Cronología de Goles</h3>
                        <p class="text-xs text-gray-400">Asigna minutos, autores y tipos de anotación.</p>
                    </div>
                    <div>
                        <button type="button" onclick="addGoal()" id="add-goal-btn"
                                class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 text-green-700 font-semibold border border-green-200 px-4 py-2 rounded-xl text-xs shadow-sm transition-all">
                            ➕ Agregar gol
                        </button>
                    </div>
                </div>

                <div id="goals-container" class="space-y-3">
                    @foreach($match->goals as $i => $goal)
                    <div class="goal-row grid grid-cols-1 md:grid-cols-4 gap-3 p-3.5 border border-gray-200/70 rounded-xl bg-slate-50/50 shadow-sm relative items-end">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Jugador</label>
                            <select name="goals[{{ $i }}][player_id]" class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none">
                                <option value="">Sin especificar</option>
                                <optgroup label="{{ $match->homeTeam->name }} (Local)">
                                    @foreach($match->homeTeam->players as $player)
                                        <option value="{{ $player->id }}" {{ $goal->player_id == $player->id ? 'selected' : '' }}>
                                            N° {{ $player->dorsal }} — {{ $player->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="{{ $match->awayTeam->name }} (Visitante)">
                                    @foreach($match->awayTeam->players as $player)
                                        <option value="{{ $player->id }}" {{ $goal->player_id == $player->id ? 'selected' : '' }}>
                                            N° {{ $player->dorsal }} — {{ $player->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Minuto</label>
                            <input type="number" name="goals[{{ $i }}][minute]" value="{{ $goal->minute }}" min="1" max="120" placeholder="Ej. 45"
                                   class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none font-medium">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Tipo de Anotación</label>
                            <select name="goals[{{ $i }}][type]" class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none">
                                <option value="regular" {{ $goal->type === 'regular' ? 'selected' : '' }}>Jugada Regular</option>
                                <option value="penalty" {{ $goal->type === 'penalty' ? 'selected' : '' }}>Tiro Penal</option>
                                <option value="own_goal" {{ $goal->type === 'own_goal' ? 'selected' : '' }}>Gol en Contra (Autogol)</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Alertas de Validación en Frontend --}}
            <div id="validation-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm font-medium">
                <div class="flex items-center gap-2 mb-1">
                    <span>⚠️</span>
                    <span>Inconsistencia en los datos</span>
                </div>
                <span id="validation-detail" class="text-xs text-red-600 block pl-6"></span>
            </div>

            {{-- Footer de Acciones Centrado --}}
            <div class="flex flex-col sm:flex-row justify-center gap-3 border-t border-gray-100 pt-5">
                <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-8 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                    Guardar resultado
                </button>
                <a href="{{ route('admin.matches.show', $match) }}" class="w-full sm:w-auto px-8 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</div>

{{-- Scripts Generales de Manipulación Dinámica --}}
<script>
let goalIndex = {{ $match->goals->count() }};
const homePlayers = @json($match->homeTeam->players);
const awayPlayers = @json($match->awayTeam->players);
const homeName = "{{ $match->homeTeam->name }}";
const awayName = "{{ $match->awayTeam->name }}";

function getScores() {
    return {
        home: parseInt(document.querySelector('input[name="home_score"]').value) || 0,
        away: parseInt(document.querySelector('input[name="away_score"]').value) || 0,
    };
}

function updateAddButton() {
    const { home, away } = getScores();
    const total = home + away;
    const goalRows = document.querySelectorAll('.goal-row');
    const btn = document.getElementById('add-goal-btn');
    if (goalRows.length >= total) {
        btn.disabled = true;
        btn.classList.add('opacity-40', 'cursor-not-allowed');
        btn.classList.remove('hover:bg-green-100', 'text-green-700');
        btn.classList.add('text-gray-400', 'bg-gray-100', 'border-gray-200');
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-40', 'cursor-not-allowed', 'text-gray-400', 'bg-gray-100', 'border-gray-200');
        btn.classList.add('hover:bg-green-100', 'text-green-700');
    }
}

function removeGoal(btn) {
    btn.closest('.goal-row').remove();
    updateAddButton();
}

function addGoal() {
    const { home, away } = getScores();
    const total = home + away;
    const goalRows = document.querySelectorAll('.goal-row');

    if (goalRows.length >= total) return;

    const container = document.getElementById('goals-container');
    const div = document.createElement('div');
    div.className = 'goal-row grid grid-cols-1 md:grid-cols-4 gap-3 p-3.5 border border-gray-200 rounded-xl bg-slate-50/50 shadow-sm relative items-end';

    let localOptions = '';
    let visitanteOptions = '';
    
    homePlayers.forEach(p => {
        localOptions += `<option value="${p.id}">N° ${p.dorsal} — ${p.name}</option>`;
    });
    awayPlayers.forEach(p => {
        visitanteOptions += `<option value="${p.id}">N° ${p.dorsal} — ${p.name}</option>`;
    });

    div.innerHTML = `
        <div>
            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Jugador</label>
            <select name="goals[${goalIndex}][player_id]" class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none">
                <option value="">Sin especificar</option>
                <optgroup label="${homeName} (Local)">
                    ${localOptions}
                </optgroup>
                <optgroup label="${awayName} (Visitante)">
                    ${visitanteOptions}
                </optgroup>
            </select>
        </div>
        <div>
            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Minuto</label>
            <input type="number" name="goals[${goalIndex}][minute]" min="1" max="120" placeholder="Ej. 45"
                   class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none font-medium">
        </div>
        <div>
            <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Tipo</label>
            <select name="goals[${goalIndex}][type]" class="w-full bg-white border border-gray-200 rounded-lg px-2.5 py-1.5 text-sm focus:ring-1 focus:ring-green-500 focus:outline-none">
                <option value="regular">Jugada Regular</option>
                <option value="penalty">Tiro Penal</option>
                <option value="own_goal">Gol en Contra (Autogol)</option>
            </select>
        </div>
        <div class="flex justify-end">
            <button type="button" onclick="removeGoal(this)"
                    class="w-full md:w-auto text-red-600 hover:text-white text-xs border border-red-200 rounded-lg px-3 py-2 hover:bg-red-600 transition-all font-semibold shadow-sm bg-white">
                Eliminar fila
            </button>
        </div>
    `;
    container.appendChild(div);
    goalIndex++;
    updateAddButton();
}

document.addEventListener('DOMContentLoaded', function() {
    // Agregar botón eliminar nativo a las filas ya persistidas en BD
    document.querySelectorAll('.goal-row').forEach(row => {
        const deleteDiv = document.createElement('div');
        deleteDiv.className = 'flex justify-end';
        deleteDiv.innerHTML = `
            <button type="button" onclick="removeGoal(this)"
                    class="w-full md:w-auto text-red-600 hover:text-white text-xs border border-red-200 rounded-lg px-3 py-2 hover:bg-red-600 transition-all font-semibold shadow-sm bg-white">
                Eliminar fila
            </button>
        `;
        row.appendChild(deleteDiv);
    });

    document.querySelector('input[name="home_score"]').addEventListener('input', updateAddButton);
    document.querySelector('input[name="away_score"]').addEventListener('input', updateAddButton);

    updateAddButton();

    // Validaciones preventivas antes del submit HTML
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const { home: homeScore, away: awayScore } = getScores();
        const totalScore = homeScore + awayScore;
        const goalRows = document.querySelectorAll('.goal-row');
        const totalGoals = goalRows.length;

        const homePlayerIds = @json($match->homeTeam->players->pluck('id')->values());
        const awayPlayerIds = @json($match->awayTeam->players->pluck('id')->values());

        let homeGoals = 0;
        let awayGoals = 0;

        goalRows.forEach(row => {
            const playerId = parseInt(row.querySelector('select[name*="player_id"]').value);
            const type = row.querySelector('select[name*="type"]').value;

            if (!playerId) return;

            if (type === 'own_goal') {
                if (homePlayerIds.includes(playerId)) awayGoals++;
                else if (awayPlayerIds.includes(playerId)) homeGoals++;
            } else {
                if (homePlayerIds.includes(playerId)) homeGoals++;
                else if (awayPlayerIds.includes(playerId)) awayGoals++;
            }
        });

        const errorDiv = document.getElementById('validation-error');
        const detailSpan = document.getElementById('validation-detail');
        let errors = [];

        if (totalGoals > totalScore || homeGoals > homeScore || awayGoals > awayScore) {
            errors.push('La cantidad de jugadores con goles asignados o el tipo de anotación excede el marcador global establecido.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
            detailSpan.innerHTML = '<ul class="list-disc list-inside">' + errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        errorDiv.classList.add('hidden');
    });
});
</script>

{{-- Async Query Script de IA --}}
@if(config('app.ai_analysis_enabled'))
<script>
let predHomeScore = 0;
let predAwayScore = 0;

async function getPrediction() {
    const btn = document.getElementById('predict-btn');
    const widget = document.getElementById('prediction-widget');
    const loading = document.getElementById('prediction-loading');
    const result = document.getElementById('prediction-result');
    const error = document.getElementById('prediction-error');

    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span> Analizando...';
    widget.classList.remove('hidden');
    loading.classList.remove('hidden');
    result.classList.add('hidden');
    error.classList.add('hidden');

    try {
        const response = await fetch('{{ route('admin.matches.predict', $match) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.error) {
            error.textContent = data.error;
            error.classList.remove('hidden');
            loading.classList.add('hidden');
        } else {
            predHomeScore = data.home_score ?? 0;
            predAwayScore = data.away_score ?? 0;

            document.getElementById('pred-home').textContent = predHomeScore;
            document.getElementById('pred-away').textContent = predAwayScore;
            document.getElementById('pred-analysis').textContent = data.analysis ?? '';

            loading.classList.add('hidden');
            result.classList.remove('hidden');
        }
    } catch (e) {
        error.textContent = 'Error crítico al conectar con el motor de IA.';
        error.classList.remove('hidden');
        loading.classList.add('hidden');
    }

    btn.disabled = false;
    btn.innerHTML = '<span>🤖</span> Predecir resultado';
}

function applyPrediction() {
    document.querySelector('input[name="home_score"]').value = predHomeScore;
    document.querySelector('input[name="away_score"]').value = predAwayScore;

    document.querySelector('input[name="home_score"]').dispatchEvent(new Event('input'));
    document.querySelector('input[name="away_score"]').dispatchEvent(new Event('input'));

    document.getElementById('prediction-widget').classList.add('hidden');
}
</script>
@endif

@endsection