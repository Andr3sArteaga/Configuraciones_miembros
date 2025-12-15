<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Base URL de la API de inventario
     */
    private string $baseUrl;

    /**
     * Timeout para las peticiones HTTP (en segundos)
     */
    private int $timeout = 7;

    public function __construct()
    {
        $this->baseUrl = config('services.microservices.inventory.base_url');
    }

    /**
     * Obtener todos los productos del inventario agrupados por producto
     * 
     * @return array
     */
    public function getProductsInventory(): array
    {
        try {
            Log::info('Intentando obtener productos del inventario desde la API', [
                'url' => $this->baseUrl . 'api/inventario/por-producto'
            ]);

            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->get($this->baseUrl . 'api/inventario/por-producto');

            if ($response->successful()) {
                $data = $response->json();

                if (is_array($data) && count($data) > 0) {
                    Log::info('Productos obtenidos exitosamente desde la API', [
                        'count' => count($data)
                    ]);

                    // Transformar datos de la API al formato esperado
                    return [
                        'success' => true,
                        'data' => collect($data)->map(function ($item) {
                            return [
                                'id_producto' => $item['id_producto'] ?? null,
                                'nombre' => $item['nombre'] ?? 'Producto sin nombre',
                                'descripcion' => $item['descripcion'] ?? '',
                                'unidad_medida' => $item['unidad_medida'] ?? 'unidad',
                                'stock_total' => $item['stock_total'] ?? 0,
                            ];
                        })->toArray(),
                        'source' => 'api',
                        'message' => null
                    ];
                }

                Log::warning('La API devolvió datos vacíos o inválidos');
                return $this->getFallbackProducts('La API devolvió datos vacíos');
            }

            Log::warning('Error en la respuesta de la API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getFallbackProducts('Error HTTP: ' . $response->status());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Error de conexión con la API de inventario', [
                'error' => $e->getMessage()
            ]);
            return $this->getFallbackProducts('No se pudo conectar con el servicio de inventario');

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Error en la petición a la API de inventario', [
                'error' => $e->getMessage()
            ]);
            return $this->getFallbackProducts('Error en la petición al servicio de inventario');

        } catch (\Exception $e) {
            Log::error('Error inesperado al obtener productos del inventario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->getFallbackProducts('Error inesperado al obtener productos');
        }
    }

    /**
     * Obtener productos de emergencia cuando la API no está disponible
     * 
     * @param string $reason
     * @return array
     */
    private function getFallbackProducts(string $reason): array
    {
        Log::info('Usando productos de emergencia', ['reason' => $reason]);

        return [
            'success' => false,
            'data' => [
                [
                    'id_producto' => 999,
                    'nombre' => 'Agua Potable',
                    'descripcion' => 'Agua potable de emergencia',
                    'unidad_medida' => 'L',
                    'stock_total' => 100,
                ],
                [
                    'id_producto' => 998,
                    'nombre' => 'Extintores Portátiles',
                    'descripcion' => 'Extintores adicionales de emergencia',
                    'unidad_medida' => 'unidad',
                    'stock_total' => 25,
                ],
                [
                    'id_producto' => 997,
                    'nombre' => 'Botiquines de Primeros Auxilios',
                    'descripcion' => 'Botiquines médicos de emergencia',
                    'unidad_medida' => 'unidad',
                    'stock_total' => 15,
                ],
                [
                    'id_producto' => 996,
                    'nombre' => 'Linternas',
                    'descripcion' => 'Linternas de emergencia',
                    'unidad_medida' => 'unidad',
                    'stock_total' => 30,
                ],
                [
                    'id_producto' => 995,
                    'nombre' => 'Mantas Térmicas',
                    'descripcion' => 'Mantas térmicas de emergencia',
                    'unidad_medida' => 'unidad',
                    'stock_total' => 20,
                ],
                [
                    'id_producto' => 994,
                    'nombre' => 'Cuerdas de Rescate',
                    'descripcion' => 'Cuerdas para operaciones de rescate',
                    'unidad_medida' => 'm',
                    'stock_total' => 50,
                ],
            ],
            'source' => 'fallback',
            'message' => $reason
        ];
    }
}
