@extends('layouts.admin')

@section('title', 'Partidos')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="mb-6 pb-5 border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center gap-2">
            <span class="text-green-600">📅</span> Partidos
        </h1>
        <p class="text-sm font-medium text-gray-400 mt-1">
            Lista general y control de resultados de todos los partidos agendados.
        </p>
    </div>

    {{-- Tabla de Partidos --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                    <tr>
                        <th class="px-6 py-4.5">Torneo</th>
                        <th class="px-6 py-4.5">Local</th>
                        <th class="px-6 py-4.5 text-center">Resultado</th>
                        <th class="px-6 py-4.5">Visitante</th>
                        <th class="px-6 py-4.5">Fecha</th>
                        <th class="px-6 py-4.5">Estado</th>
                        <th class="px-6 py-4.5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($matches as $match)
                    <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.matches.show', $match) }}'">
                        <td class="px-6 py-4 font-semibold text-gray-500 group-hover:text-green-700 transition-colors">
                            {{ $match->tournament->name }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ $match->homeTeam?->name ?? '(Equipo eliminado)' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($match->status === 'finished')
                                <span class="inline-flex items-center justify-center bg-slate-100 font-mono font-black text-xs md:text-sm px-3 py-1 rounded-xl tracking-tight text-gray-800">
                                    {{ $match->home_score }} - {{ $match->away_score }}
                                </span>
                            @else
                                <span class="text-xs font-black tracking-widest text-gray-300 uppercase">vs</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ $match->awayTeam?->name ?? '(Equipo eliminado)' }}
                        </td>
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs font-semibold whitespace-nowrap">
                            {{ $match->played_at->format('d/m/Y H:i') }} hs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase
                                {{ $match->status === 'finished'   ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                {{ $match->status === 'scheduled'  ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                {{ $match->status === 'live'       ? 'bg-rose-50 text-rose-700 border border-rose-100' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $match->status === 'finished'   ? 'bg-emerald-500' : '' }}
                                    {{ $match->status === 'scheduled'  ? 'bg-amber-500' : '' }}
                                    {{ $match->status === 'live'       ? 'bg-rose-500 animate-pulse' : '' }}"></span>
                                {{ \App\Helpers\StatusHelper::match($match->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                            @if($match->status === 'finished')
                                <a href="{{ route('admin.matches.show', $match) }}?from=matches"
                                   class="text-green-600 hover:text-green-800 font-bold text-xs hover:underline transition-colors">
                                    Ver
                                </a>
                            @else
                                <a href="{{ route('admin.matches.edit', $match) }}?from=matches"
                                   class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline transition-colors">
                                    Cargar resultado
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-slate-50/30">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="text-2xl">📅</span>
                                <span>No hay partidos registrados. Genera un fixture desde un torneo.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($matches->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
            {{ $matches->links() }}
        </div>
        @endif
    </div>

</div>
@endsection