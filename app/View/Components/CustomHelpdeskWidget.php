<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Lukehowland\HelpdeskWidget\HelpdeskService;
use Lukehowland\HelpdeskWidget\View\Components\HelpdeskWidget;

class CustomHelpdeskWidget extends HelpdeskWidget
{
    public function __construct(
        ?string $height = null,
        ?string $width = null,
        ?bool $border = null
    ) {
        $this->height = $height ?? config('helpdeskwidget.iframe_height', '600px');
        $this->width = $width ?? config('helpdeskwidget.iframe_width', '100%');
        $this->border = $border ?? config('helpdeskwidget.iframe_border', false);
        $this->error = null;
        $this->isReady = false;

        $this->initializeCustom();
    }

    /**
     * Inicializa el componente con los atributos personalizados del modelo Usuario
     */
    private function initializeCustom(): void
    {
        // Verificar que hay un usuario autenticado
        /** @var \Illuminate\Contracts\Auth|null $user */
        $user = Auth::user();

        if (!$user) {
            $this->error = 'Debes iniciar sesión para usar el Centro de Soporte.';
            $this->iframeSrc = '';
            return;
        }

        // Verificar configuración
        $apiKey = config('helpdeskwidget.api_key');

        if (empty($apiKey)) {
            $this->error = 'El widget de Helpdesk no está configurado correctamente.';
            $this->iframeSrc = '';
            return;
        }

        /** @var HelpdeskService $service */
        $service = app(HelpdeskService::class);

        // Preparar datos del usuario con atributos personalizados
        $email = $user->email;
        $userData = [
            'email' => $email,
            'first_name' => $this->getUserFirstName($user),
            'last_name' => $this->getUserLastName($user),
        ];

        // DETECCIÓN DE CAMBIO DE USUARIO
        if ($service->hasUserChanged($email)) {
            // El servicio ya invalidó el cache del usuario anterior
        }

        // Intentar obtener token (login automático)
        $tokenResult = $service->getAuthToken($email);

        if ($tokenResult['success'] && !empty($tokenResult['token'])) {
            // Usuario ya tiene cuenta, usar token
            $this->iframeSrc = $service->getWidgetUrl($userData, $tokenResult['token']);
        } else {
            // Usuario nuevo o error, enviar al flujo de autenticación
            $this->iframeSrc = $service->getWidgetUrl($userData);
        }

        $this->isReady = true;
    }

    /**
     * Get the user's first name from the custom Usuario model
     *
     * @param mixed $user
     * @return string
     */
    private function getUserFirstName($user): string
    {
        return $user->nombre ?? '';
    }

    /**
     * Get the user's last name from the custom Usuario model
     *
     * @param mixed $user
     * @return string
     */
    private function getUserLastName($user): string
    {
        return $user->apellido ?? '';
    }
}
