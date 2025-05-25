<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PistasClientesModel;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class PistasClientes extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new PistasClientesModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($fechaHora = null)
    {
        $model = new PistasClientesModel();
        $data = $model->where('fechaHora', $fechaHora)->first();

        return ($data)
            ? $this->respond($data)
            : $this->failNotFound('No se encontró la reserva con esa fecha y hora.');
    }

    public function create()
    {
        $model = new PistasClientesModel();
        $data = $this->request->getJSON(true);

        $yaExiste = $model->where('correoClientes', $data['correoClientes'])
            ->where('idPistas', $data['idPistas'])
            ->where('fechaHora', $data['fechaHora'])
            ->first();

        if ($yaExiste) {
            return $this->fail('Ya estás apuntado a esta pista en esa fecha y hora.');
        }

        $personasExistentes = $model->where('idPistas', $data['idPistas'])
            ->where('fechaHora', $data['fechaHora'])
            ->selectSum('numPersonas')
            ->first()['numPersonas'] ?? 0;

        $total = intval($personasExistentes) + intval($data['numPersonas']);
        if ($total > 4) {
            return $this->fail('No se puede apuntar. La pista ya está completa o sobrepasaría el límite de 4 personas.');
        }

        $model->insert($data);
        return $this->respondCreated(['message' => 'Reserva realizada con éxito']);
    }

    public function update($fechaHora = null)
    {
        $model = new PistasClientesModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'fechaHora' => $json->fechaHora,
                'correoClientes' => $json->correoClientes,
                'idPistas' => $json->idPistas,
                'numPersonas' => $json->numPersonas,
                'nivelPersonas' => $json->nivelPersonas ?? null,
                'mediaNivel' => $json->mediaNivel ?? null
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'fechaHora' => $input['fechaHora'],
                'correoClientes' => $input['correoClientes'],
                'idPistas' => $input['idPistas'],
                'numPersonas' => $input['numPersonas'],
                'nivelPersonas' => $input['nivelPersonas'] ?? null,
                'mediaNivel' => $input['mediaNivel'] ?? null
            ];
        }

        if (!$model->update($fechaHora, $data)) {
            return $this->failValidationErrors($model->errors());
        }

        return $this->respond([
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Reserva actualizada correctamente'
            ]
        ]);
    }

    public function delete($fechaHora = null)
    {
        $model = new PistasClientesModel();

        if (!$model->where('fechaHora', $fechaHora)->delete()) {
            return $this->failNotFound('No se encontró la reserva.');
        }

        return $this->respondDeleted(['message' => 'Reserva eliminada correctamente.']);
    }

    public function getByFecha()
    {
        $fecha = $this->request->getGet('fecha');
        if (!$fecha) {
            return $this->failValidationErrors("Falta el parámetro de fecha.");
        }

        $model = new PistasClientesModel();
        $data = $model->where('DATE(fechaHora)', $fecha)->findAll();

        return $this->respond($data);
    }

    public function getJugadoresPorPista()
    {
        $fechaHora = $this->request->getGet('fechaHora');
        $idPista = $this->request->getGet('idPista');

        if (!$fechaHora || !$idPista) {
            return $this->failValidationErrors("Faltan parámetros: fechaHora o idPista");
        }

        $model = new PistasClientesModel();
        $jugadores = $model->where('fechaHora', $fechaHora)
            ->where('idPistas', $idPista)
            ->findAll();

        return $this->respond($jugadores);
    }

    public function pagarYReservar()
    {
        $data = $this->request->getJSON(true);

        \Stripe\Stripe::setApiKey('xxxx'); // tu clave secreta

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => ['name' => 'Reserva de pista'],
                            'unit_amount' => 750,
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'metadata' => [
                    'reserva' => json_encode($data) // 👈 ESTA LÍNEA ENVÍA LA RESERVA AL WEBHOOK
                ],
                'success_url' => 'https://borja.com.es/ProyectoDosDAW/partidas',
                'cancel_url' => 'https://borja.com.es/ProyectoDosDAW/partidas',
            ]);

            return $this->respond([
                'sessionId' => $session->id,
                'url' => $session->url
            ]);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    public function cancelarReserva()
    {
        $data = $this->request->getJSON(true);
        $fechaHora = new \DateTime($data['fechaHora']);
        $ahora = new \DateTime();
        $diffHoras = ($fechaHora->getTimestamp() - $ahora->getTimestamp()) / 3600;

        if ($diffHoras < 10) {
            return $this->fail('Solo puedes cancelar la reserva con 10 horas de antelación.');
        }

        $model = new PistasClientesModel();
        $model->where('fechaHora', $data['fechaHora'])
            ->where('idPistas', $data['idPistas'])
            ->update(null, [
                'cancelada' => 1,
                'fechaCancelacion' => date('Y-m-d H:i:s')
            ]);

        return $this->respond(['message' => 'Reserva cancelada correctamente']);
    }

}
