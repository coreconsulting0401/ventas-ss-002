<?php
// ══════════════════════════════════════════════════════════════════
// ARCHIVO: app/Notifications/FechaFinActualizadaNotification.php
// ══════════════════════════════════════════════════════════════════

namespace App\Notifications;

use App\Models\Proforma;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FechaFinActualizadaNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Proforma $proforma,
        public readonly User     $vendedor,
        public readonly int      $count,
        public readonly string   $tipo = 'gerente',
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $nro = 'NTC-' . str_pad($this->proforma->id, 11, '0', STR_PAD_LEFT);

        if ($this->tipo === 'vendedor') {
            return [
                'tipo'         => 'fecha_fin_limite',
                'titulo'       => 'Límite de modificaciones alcanzado',
                'mensaje'      => "Has alcanzado {$this->count} modificaciones a la Fecha de Vencimiento en la proforma {$nro}. Esta acción ha sido notificada al Gerente.",
                'proforma_id'  => $this->proforma->id,
                'proforma_nro' => $nro,
                'vendedor_id'  => $this->vendedor->id,
                'vendedor'     => $this->vendedor->name,
                'count'        => $this->count,
                'icono'        => 'bi-exclamation-triangle-fill',
                'color'        => 'warning',
            ];
        }

        return [
            'tipo'          => 'fecha_fin_limite_gerente',
            'titulo'        => 'Alerta: Múltiples modificaciones en fecha de vencimiento',
            'mensaje'       => "El vendedor {$this->vendedor->name} (Código: {$this->vendedor->codigo}, DNI: {$this->vendedor->dni}) ha realizado {$this->count} modificaciones a la Fecha de Vencimiento en la proforma {$nro}.",
            'proforma_id'   => $this->proforma->id,
            'proforma_nro'  => $nro,
            'vendedor_id'   => $this->vendedor->id,
            'vendedor'      => $this->vendedor->name,
            'vendedor_cod'  => $this->vendedor->codigo,
            'vendedor_dni'  => $this->vendedor->dni,
            'vendedor_tel'  => $this->vendedor->telefono_user,
            'count'         => $this->count,
            'icono'         => 'bi-shield-exclamation',
            'color'         => 'danger',
        ];
    }
}
