@extends('layouts.captain')

@section('title', 'Mi Equipo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal de Perfil --}}
    <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        @if($team->shield_url)
            <img src="{{ Storage::url($team->shield_url) }}" class="w-16 h-16 rounded-full object-cover border-2 border-green-100 shadow-sm p-0.5 bg-white">
        @else
            <div class="w-16 h-16 rounded-full bg-green-50 text-green-700 font-black text-xl flex items-center justify-center border border-green-100 shadow-inner">
                {{ strtoupper(substr($team->name, 0, 2)) }}
            </div>
        @endif
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight">{{ $team->name }}</h1>
            <p class="text-sm font-medium text-gray-400 mt-1">{{ $team->country ?? 'Sin país' }}</p>
        </div>
    </div>

    {{-- Tabla de la Plantilla --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4.5 border-b border-gray-50 flex justify-between items-center bg-slate-50/20">
            <div>
                <h2 class="text-base font-bold text-gray-800 tracking-tight">Plantilla</h2>
                <p class="text-xs text-gray-400">Jugadores registrados bajo tu capitanía.</p>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider bg-white border px-3 py-1 rounded-xl text-gray-500 shadow-xs">{{ $team->players->count() }} jugadores</span>
        </div>

        @if($team->players->isEmpty())
            <div class="text-center py-12 text-sm font-medium text-gray-400">
                No hay jugadores registrados en este equipo.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">#</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Posición</th>
                            <th class="px-6 py-4">Nacionalidad</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($team->players->sortBy('dorsal') as $player)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center font-mono font-black text-green-700 text-sm md:text-base">
                                {{ $player->dorsal }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $player->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border
                                    {{ $player->position === 'GK'  ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                    {{ $player->position === 'DEF' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                    {{ $player->position === 'MID' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                    {{ $player->position === 'FWD' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                    {{ \App\Helpers\StatusHelper::position($player->position) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium">
                                {{ $player->nationality ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('captain.players.show', $player) }}"
                                   class="inline-flex bg-slate-50 hover:bg-green-50 text-gray-600 hover:text-green-700 border border-gray-200 hover:border-green-200 px-3 py-1 rounded-lg text-xs font-bold transition-all shadow-xs">
                                    Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection