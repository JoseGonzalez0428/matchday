<?php

namespace App\Helpers;

class StatusHelper
{
    public static function match(string $status): string
    {
        return match($status) {
            'scheduled' => 'Programado',
            'live'      => 'En vivo',
            'finished'  => 'Finalizado',
            default     => ucfirst($status),
        };
    }

    public static function tournament(string $status): string
    {
        return match($status) {
            'draft'    => 'Borrador',
            'active'   => 'Activo',
            'finished' => 'Finalizado',
            default    => ucfirst($status),
        };
    }

    public static function stage(string $stage): string
    {
        return match($stage) {
            'group'   => 'Fase de grupos',
            'round32' => 'Ronda de 32 (Dieciseisavos)',
            'round16' => 'Octavos de final',
            'quarter' => 'Cuartos de final',
            'semi'    => 'Semifinal',
            'final'   => 'Final',
            default   => ucfirst($stage),
        };
    }

    public static function position(string $position): string
    {
        return match($position) {
            'GK'  => 'Portero',
            'DEF' => 'Defensa',
            'MID' => 'Mediocampista',
            'FWD' => 'Delantero',
            default => $position,
        };
    }

    public static function goalType(string $type): string
    {
        return match($type) {
            'regular'  => 'Regular',
            'penalty'  => 'Penal',
            'own_goal' => 'Autogol',
            default    => ucfirst($type),
        };
    }
}