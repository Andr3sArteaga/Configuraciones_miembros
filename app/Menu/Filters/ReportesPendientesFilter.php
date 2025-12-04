<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use App\Models\Reporte;
use App\Models\EstadosSistema;

class ReportesPendientesFilter implements FilterInterface
{
    /**
     * Transforma los ítems del menú.
     *
     * @param  array  $item  Un ítem del menú
     * @return array  El ítem modificado
     */
    public function transform($item)
    {
        // Solo procesar el ítem "Reportes" con URL 'reportes-incendio'
        if (
            isset($item['text']) && $item['text'] === 'Reporte Rápido' &&
            isset($item['url']) && $item['url'] === 'reportes'
        ) {

            // Obtener el ID del estado "Pendiente" para reportes
            $estadoPendienteId = EstadosSistema::where('tabla', 'reportes')
                ->where('codigo', 'pendiente')
                ->orWhere(function ($query) {
                    $query->where('nombre', 'Pendiente')
                        ->where('tabla', 'reportes');
                })
                ->value('id');
            // Si no encontramos el estado específico, buscar el primero con nombre "Pendiente"
            if (!$estadoPendienteId) {
                $estados = EstadosSistema::where('nombre', 'Pendiente')->get();
                foreach ($estados as $estado) {
                    $count = Reporte::where('estado_id', $estado->id)->count();
                    if ($count > 0) {
                        $estadoPendienteId = $estado->id;
                        break;
                    }
                }
            }

            // Contar reportes pendientes
            if ($estadoPendienteId) {
                $reportesPendientes = Reporte::where('estado_id', $estadoPendienteId)->count();
                $reportesSinEstado = Reporte::whereNull('estado_id')->count();
                $reportesPendientes += $reportesSinEstado;
                // Si hay reportes pendientes, agregar el badge
                if ($reportesPendientes > 0) {
                    $item['label'] = $reportesPendientes;
                    $item['label_color'] = 'warning';
                    $item['classes'] = ($item['classes'] ?? '') . ' menu-item-with-indicator';
                }
            }
        }

        return $item;
    }
}
