<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PistasClientesModel;

class StripeWebhook extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $input = @file_get_contents("php://input");
        $event = json_decode($input);

        if (!$event || !isset($event->type)) {
            return $this->fail('Evento no válido');
        }

        // Solo actuamos si el pago se completó
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Leemos la reserva desde los metadatos
            $reserva = json_decode($session->metadata->reserva ?? '{}');

            if (!$reserva || !isset($reserva->correoClientes)) {
                return $this->fail('Datos de reserva no válidos');
            }

            $model = new PistasClientesModel();

            // Validar duplicado
            $yaExiste = $model->where('correoClientes', $reserva->correoClientes)
                ->where('fechaHora', $reserva->fechaHora)
                ->where('idPistas', $reserva->idPistas)
                ->first();

            if ($yaExiste) {
                return $this->respond(['message' => 'Ya estabas apuntado']);
            }

            // Validar capacidad
            $personasExistentes = $model->where('idPistas', $reserva->idPistas)
                ->where('fechaHora', $reserva->fechaHora)
                ->selectSum('numPersonas')
                ->first();

            $totalExistente = intval($personasExistentes['numPersonas'] ?? 0);
            $nuevos = intval($reserva->numPersonas);
            $totalFinal = $totalExistente + $nuevos;

            if ($totalFinal > 4) {
                return $this->respond([
                    'message' => 'No se pudo guardar. Excede el límite de 4 personas.',
                    'total' => $totalFinal
                ]);
            }

            // Guardar la reserva en la base de datos
            $model->insert([
                'fechaHora' => $reserva->fechaHora,
                'correoClientes' => $reserva->correoClientes,
                'idPistas' => $reserva->idPistas,
                'numPersonas' => $reserva->numPersonas,
                'nivelPersonas' => $reserva->nivelPersonas,
                'mediaNivel' => $reserva->mediaNivel,
                'estadoPago' => 'pagado',
                'pago_id' => $session->id,
                'cancelada' => 0,
                'fechaCancelacion' => null
            ]);

            return $this->respond(['message' => 'Reserva guardada correctamente']);
        }

        // Si es otro tipo de evento
        return $this->respond(['message' => 'Evento no procesado']);
    }

}
