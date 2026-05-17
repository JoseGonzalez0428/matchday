@extends('layouts.admin')

@section('title', 'Cargar Resultado')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-green-800">📅 Cargar Resultado</h1>
            <p class="text-gray-500 mt-1">
                {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} ·
                {{ $match->played_at->format('d/m/Y H:i') }}
            </p>
        </div>
        @if(config('app.ai_analysis_enabled'))
        <button type="button" id="predict-btn"
                onclick="getPrediction()"
                class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 flex items-center gap-2">
            🤖 Predecir resultado
        </button>
        @endif
    </div>

    {{-- Widget de predicción IA --}}
    @if(config('app.ai_analysis_enabled'))
    <div id="prediction-widget" class="hidden bg-purple-50 border border-purple-200 rounded-xl p-5 mb-6">
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xl">🤖</span>
            <h3 class="font-bold text-purple-700">Predicción IA</h3>
            <span class="ml-auto text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">
                Powered by Gemini AI
            </span>
        </div>
        <div id="prediction-loading" class="text-purple-500 text-sm">
            Analizando el partido...
        </div>
        <div id="prediction-result" class="hidden">
            <div class="flex items-center justify-center gap-6 mb-3">
                <div class="text-center">
                    <p class="font-bold text-gray-800">{{ $match->homeTeam->name }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-700">
                        <span id="pred-home">0</span> — <span id="pred-away">0</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Resultado predicho</p>
                </div>
                <div class="text-center">
                    <p class="font-bold text-gray-800">{{ $match->awayTeam->name }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg p-3 text-sm text-gray-600 mb-3" id="pred-analysis"></div>
            <button type="button" onclick="applyPrediction()"
                    class="w-full bg-purple-600 text-white py-2 rounded-lg text-sm hover:bg-purple-700">
                Usar esta predicción como resultado
            </button>
        </div>
        <div id="prediction-error" class="hidden text-red-500 text-sm"></div>
    </div>
    @endif
    <p class="text-gray-500 mt-1">
        {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }} ·
        {{ $match->played_at->format('d/m/Y H:i') }}
    </p>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.matches.update', $match) }}?from={{ request('from') }}&id={{ request('id') }}">
        @csrf
        @method('PUT')

        {{-- Resultado --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Goles — {{ $match->homeTeam->name }} (Local)
                </label>
                <input type="number" name="home_score" min="0"
                       value="{{ old('home_score', $match->home_score) }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                       @error('home_score') border-red-500 @enderror">
                @error('home_score')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Goles — {{ $match->awayTeam->name }} (Visitante)
                </label>
                <input type="number" name="away_score" min="0"
                       value="{{ old('away_score', $match->away_score) }}"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                       @error('away_score') border-red-500 @enderror">
                @error('away_score')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Penales (solo eliminatorias) --}}
        @if(in_array($match->stage, ['round32', 'quarter', 'semi', 'final']))
        <div id="penalties-section" class="{{ ($match->home_score === $match->away_score && $match->status === 'finished') ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <h3 class="font-bold text-blue-700 mb-3">⚽ Resultado de Penales</h3>
            <p class="text-blue-600 text-xs mb-3">Este partido eliminatorio terminó en empate. Registra el marcador de penales.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Penales — {{ $match->homeTeam->name }}
                    </label>
                    <input type="number" name="home_penalties" min="0"
                        value="{{ old('home_penalties', $match->home_penalties) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Penales — {{ $match->awayTeam->name }}
                    </label>
                    <input type="number" name="away_penalties" min="0"
                        value="{{ old('away_penalties', $match->away_penalties) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                if (home === away) {
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

        {{-- Goles detallados --}}
        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-700">Detalle de goles</h3>
                <button type="button" onclick="addGoal()" id="add-goal-btn"
                        class="bg-green-100 text-green-700 px-3 py-1 rounded text-sm hover:bg-green-200">
                    + Agregar gol
                </button>
            </div>

            <div id="goals-container">
                @foreach($match->goals as $i => $goal)
                <div class="goal-row grid grid-cols-3 gap-3 mb-3 p-3 border rounded-lg bg-gray-50">
                    <div>
                        <label class="text-xs text-gray-500">Jugador</label>
                        <select name="goals[{{ $i }}][player_id]"
                                class="w-full border rounded px-2 py-1 text-sm mt-1">
                            <option value="">Sin especificar</option>
                            @foreach($match->homeTeam->players as $player)
                                <option value="{{ $player->id }}"
                                    {{ $goal->player_id == $player->id ? 'selected' : '' }}>
                                    {{ $player->dorsal }}. {{ $player->name }}
                                </option>
                            @endforeach
                            @foreach($match->awayTeam->players as $player)
                                <option value="{{ $player->id }}"
                                    {{ $goal->player_id == $player->id ? 'selected' : '' }}>
                                    {{ $player->dorsal }}. {{ $player->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Minuto</label>
                        <input type="number" name="goals[{{ $i }}][minute]"
                               value="{{ $goal->minute }}" min="1" max="120"
                               class="w-full border rounded px-2 py-1 text-sm mt-1">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Tipo</label>
                        <select name="goals[{{ $i }}][type]"
                                class="w-full border rounded px-2 py-1 text-sm mt-1">
                            <option value="regular" {{ $goal->type === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="penalty" {{ $goal->type === 'penalty' ? 'selected' : '' }}>Penal</option>
                            <option value="own_goal" {{ $goal->type === 'own_goal' ? 'selected' : '' }}>Autogol</option>
                        </select>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div id="validation-error" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
             La cantidad de goles registrados no coincide con el marcador.
            <span id="validation-detail"></span>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                    class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                Guardar resultado
            </button>
            <a href="{{ route('admin.matches.show', $match) }}"
               class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
let goalIndex = {{ $match->goals->count() }};
const homePlayers = @json($match->homeTeam->players);
const awayPlayers = @json($match->awayTeam->players);

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
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.classList.remove('hover:bg-green-200');
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.classList.add('hover:bg-green-200');
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
    div.className = 'goal-row grid grid-cols-4 gap-3 mb-3 p-3 border rounded-lg bg-gray-50';

    let playerOptions = '<option value="">Sin especificar</option>';
    homePlayers.forEach(p => {
        playerOptions += `<option value="${p.id}">${p.dorsal}. ${p.name} (Local)</option>`;
    });
    awayPlayers.forEach(p => {
        playerOptions += `<option value="${p.id}">${p.dorsal}. ${p.name} (Visitante)</option>`;
    });

    div.innerHTML = `
        <div>
            <label class="text-xs text-gray-500">Jugador</label>
            <select name="goals[${goalIndex}][player_id]" class="w-full border rounded px-2 py-1 text-sm mt-1">
                ${playerOptions}
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-500">Minuto</label>
            <input type="number" name="goals[${goalIndex}][minute]" min="1" max="120"
                   class="w-full border rounded px-2 py-1 text-sm mt-1">
        </div>
        <div>
            <label class="text-xs text-gray-500">Tipo</label>
            <select name="goals[${goalIndex}][type]" class="w-full border rounded px-2 py-1 text-sm mt-1">
                <option value="regular">Regular</option>
                <option value="penalty">Penal</option>
                <option value="own_goal">Autogol</option>
            </select>
        </div>
        <div class="flex items-end pb-1">
            <button type="button" onclick="removeGoal(this)"
                    class="text-red-500 hover:text-red-700 text-xs border border-red-300 rounded px-2 py-1 hover:bg-red-50">
                Eliminar
            </button>
        </div>
    `;
    container.appendChild(div);
    goalIndex++;
    updateAddButton();
}

document.addEventListener('DOMContentLoaded', function() {
    // Agregar botón eliminar a goles existentes
    document.querySelectorAll('.goal-row').forEach(row => {
        const deleteDiv = document.createElement('div');
        deleteDiv.className = 'flex items-end pb-1';
        deleteDiv.innerHTML = `
            <button type="button" onclick="removeGoal(this)"
                    class="text-red-500 hover:text-red-700 text-xs border border-red-300 rounded px-2 py-1 hover:bg-red-50">
                Eliminar
            </button>
        `;
        row.classList.add('grid-cols-4');
        row.classList.remove('grid-cols-3');
        row.appendChild(deleteDiv);
    });

    // Actualizar botón al cambiar marcador
    document.querySelector('input[name="home_score"]').addEventListener('input', updateAddButton);
    document.querySelector('input[name="away_score"]').addEventListener('input', updateAddButton);

    updateAddButton();

    // Validación al guardar
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
            errors.push('Los goles registrados no coinciden con el marcador. Verifica los jugadores y tipos de gol.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            errorDiv.classList.remove('hidden');
            detailSpan.innerHTML = '<ul class="mt-1 list-disc list-inside">' +
                errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }

        errorDiv.classList.add('hidden');
    });
});
</script>


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
    btn.textContent = '🤖 Analizando...';
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
        error.textContent = 'Error al conectar con la IA.';
        error.classList.remove('hidden');
        loading.classList.add('hidden');
    }

    btn.disabled = false;
    btn.textContent = '🤖 Predecir resultado';
}

function applyPrediction() {
    document.querySelector('input[name="home_score"]').value = predHomeScore;
    document.querySelector('input[name="away_score"]').value = predAwayScore;

    // Disparar evento para actualizar validaciones
    document.querySelector('input[name="home_score"]').dispatchEvent(new Event('input'));
    document.querySelector('input[name="away_score"]').dispatchEvent(new Event('input'));

    document.getElementById('prediction-widget').classList.add('hidden');
}
</script>
@endif
@endsection