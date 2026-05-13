@extends('layouts.admin')

@section('title', 'Partidos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-800">📅 Partidos</h1>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="text-left px-4 py-3">Torneo</th>
                <th class="text-left px-4 py-3">Local</th>
                <th class="text-left px-4 py-3">Resultado</th>
                <th class="text-left px-4 py-3">Visitante</th>
                <th class="text-left px-4 py-3">Fecha</th>
                <th class="text-left px-4 py-3">Estado</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matches as $match)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $match->tournament->name }}</td>
                <td class="px-4 py-3 font-medium">{{ $match->homeTeam->name }}</td>
                <td class="px-4 py-3 text-center font-bold">
                    @if($match->status === 'finished')
                        {{ $match->home_score }} - {{ $match->away_score }}
                    @else
                        vs
                    @endif
                </td>
                <td class="px-4 py-3 font-medium">{{ $match->awayTeam->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $match->played_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $match->status === 'finished' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $match->status === 'scheduled' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $match->status === 'live' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ \App\Helpers\StatusHelper::match($match->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    @if($match->status === 'finished')
                        <a href="{{ route('admin.matches.show', $match) }}?from=matches"
                        class="text-green-700 hover:underline text-xs">Ver</a>
                    @else
                        <a href="{{ route('admin.matches.edit', $match) }}?from=matches"
                        class="text-blue-600 hover:underline text-xs">Cargar resultado</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                    No hay partidos registrados. Genera un fixture desde un torneo.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">
        {{ $matches->links() }}
    </div>
</div>
@endsection