@extends('layouts.admin')

@section('title', $tournament->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">🏆 {{ $tournament->name }}</h1>
        <p class="text-gray-500 mt-1">{{ $tournament->edition }} · {{ ucfirst($tournament->format) }}</p>
    </div>
    <div class="flex gap-2">
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
            {{ ucfirst($tournament->status) }}
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

{{-- Tabla de posiciones --}}
@if(!empty($standings))
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Tabla de Posiciones</h2>
    @foreach($standings as $groupName => $teams)
    <div class="mb-6">
        <h3 class="font-bold text-green-700 mb-2">Grupo {{ $groupName }}</h3>
        <table class="w-full text-sm">
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
        </table>
    </div>
    @endforeach
</div>
@endif

{{-- Partidos --}}
@if($tournament->matches()->exists())
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Partidos</h2>
    @foreach($tournament->matches()->with(['homeTeam','awayTeam','group'])->orderBy('played_at')->get()->groupBy('group.name') as $groupName => $matches)
    <div class="mb-4">
        <h3 class="font-bold text-green-700 mb-2">{{ $groupName ?? 'Fase eliminatoria' }}</h3>
        @foreach($matches as $match)
        <div class="flex items-center justify-between border rounded-lg px-4 py-3 mb-2 hover:bg-gray-50">
            <span class="font-medium w-1/3 text-right">{{ $match->homeTeam->name }}</span>
            <span class="mx-4 text-gray-500 text-sm">
                @if($match->status === 'finished')
                    <span class="font-bold text-gray-800">{{ $match->home_score }} - {{ $match->away_score }}</span>
                @else
                    <span class="text-xs">{{ $match->played_at->format('d/m H:i') }}</span>
                @endif
            </span>
            <span class="font-medium w-1/3">{{ $match->awayTeam->name }}</span>
            @if($match->status === 'finished')
                <a href="{{ route('admin.matches.show', $match) }}"
                class="text-xs text-green-700 hover:underline ml-4">
                    Ver
                </a>
            @else
                <a href="{{ route('admin.matches.edit', $match) }}"
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
@endsection