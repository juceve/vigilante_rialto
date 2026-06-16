<?php

namespace App\Http\Controllers;

use App\Models\Rrhhadelanto;
use App\Models\Rrhhpermiso;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        // Aquí podrías mostrar una página de notificaciones completas
        return view('notifications.index');
    }

    /**
     * Devuelve la cantidad de notificaciones para el badge (AJAX)
     */
    public function getNotificationsData(Request $request)
    {
        $totalNotificaciones = 0;
        $notifications = [];

        $permisos = Rrhhpermiso::where('status', 'SOLICITADO')
            ->where('activo', 1)
            ->get();

        if ($permisos->count() > 0) {
            $totalNotificaciones += $permisos->count();

            foreach ($permisos as $permiso) {
                $notifications[] = [
                    'icon' => 'fas fa-fw fa-calendar-plus text-info',
                    'text' => 'Licencias/Permiso <br>' . ($permiso->empleado->nombres . ' ' . $permiso->empleado->apellidos ?? 'Usuario'),
                    'time' => $permiso->created_at->diffForHumans(),
                    'route'  => route('rrhh.kardex', $permiso->empleado_id) . '?tab=permisos',
                ];
            }
        }

        $adelantos = Rrhhadelanto::where('estado', 'SOLICITADO')
            ->where('activo', 1)
            ->get();

        if ($adelantos->count() > 0) {
            $totalNotificaciones += $adelantos->count();

            foreach ($adelantos as $adelanto) {
                $notifications[] = [
                    'icon' => 'fas fa-fw fa-comment-dollar text-success',
                    'text' => 'Adelantos <br>' . ($adelanto->empleado->nombres . ' ' . $adelanto->empleado->apellidos ?? 'Usuario'),
                    'time' => $adelanto->created_at->diffForHumans(),
                    'route'  => route('rrhh.kardex', $adelanto->empleado_id) . '?tab=adelantos',
                ];
            }
        }

        // $notifications = [
        //     [
        //         'icon' => 'fas fa-fw fa-envelope',
        //         'text' => rand(0, 10) . ' new messages',
        //         'time' => rand(0, 10) . ' minutes',
        //     ],
        //     [
        //         'icon' => 'fas fa-fw fa-users text-primary',
        //         'text' => rand(0, 10) . ' friend requests',
        //         'time' => rand(0, 60) . ' minutes',
        //     ],
        //     [
        //         'icon' => 'fas fa-fw fa-file text-danger',
        //         'text' => rand(0, 10) . ' new reports',
        //         'time' => rand(0, 60) . ' minutes',
        //     ],
        // ];

        // Now, we create the notification dropdown main content.

        $dropdownHtml = '';

        foreach ($notifications as $key => $not) {
            $icon = "<i class='mr-2 {$not['icon']}'></i>";

            $time = "<span class='float-right text-muted text-xs'>
                   {$not['time']}
                 </span>";

            $dropdownHtml .= "<a href='{$not['route']}' class='dropdown-item' style='font-size: 11px;'>
                            {$icon}{$not['text']}{$time}
                          </a>";

            if ($key < count($notifications) - 1) {
                $dropdownHtml .= "<div class='dropdown-divider'></div>";
            }
        }

        // Return the new notification data.

        return [
            'label' => $totalNotificaciones,
            'label_color' => 'danger',
            'icon_color' => $totalNotificaciones > 0 ? 'primary' : 'dark',
            'dropdown' => $dropdownHtml,
        ];
    }
}
