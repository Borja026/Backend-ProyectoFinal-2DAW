<?php

namespace App\Controllers;

use App\Models\PistasClientesModel;
use App\Models\ClientesModel;
use App\Models\PistasModel;

class AdminPanelPistasClientes extends BaseController
{
    public function pistasClientes()
    {
        $pistasClientesModel = new PistasClientesModel();
        $clientesModel = new ClientesModel();
        $pistasModel = new PistasModel();

        $data['reservas'] = $pistasClientesModel->findAll();
        $data['clientes'] = $clientesModel->findAll();
        $data['pistas'] = $pistasModel->findAll();

        $id = $this->request->getGet('editar');
        if ($id) {
            $reserva = $pistasClientesModel->find($id);
            if ($reserva) {
                $data['reservaEditando'] = $reserva;
            }
        }

        return view('admin/pistasClientes_view', $data);
    }

    public function guardarReserva()
    {
        $model = new PistasClientesModel();
        $modo = $this->request->getPost('modo');

        $data = [
            'fechaHora' => date('Y-m-d H:i:s', strtotime($this->request->getPost('fechaHora'))),
            'idPistas' => $this->request->getPost('idPistas'),
            'correoClientes' => $this->request->getPost('correoClientes'),
            'numPersonas' => $this->request->getPost('numPersonas'),
            'nivelPersonas' => $this->request->getPost('nivelPersonas') ?? null,
            'mediaNivel' => $this->request->getPost('mediaNivel') ?? null,
            'estadoPago' => $this->request->getPost('estadoPago') ?? 'pagado',
            'cancelada' => $this->request->getPost('cancelada') ?? '0',
            'pago_id' => $this->request->getPost('pago_id') ?? null,
            'fechaCancelacion' => $this->request->getPost('fechaCancelacion') ?? null,
        ];

        if ($modo === 'insertar') {
            $personasExistentes = $model
                ->where('idPistas', $data['idPistas'])
                ->where('fechaHora', $data['fechaHora'])
                ->where('cancelada', '0')
                ->selectSum('numPersonas')
                ->first()['numPersonas'] ?? 0;

            $total = intval($personasExistentes) + intval($data['numPersonas']);
            if ($total > 5) {
                return redirect()->back()->with('error', 'La pista ya está completa o sobrepasaría el límite de 4 personas.');
            }

            $yaExiste = $model
                ->where('fechaHora', $data['fechaHora'])
                ->where('idPistas', $data['idPistas'])
                ->where('correoClientes', $data['correoClientes'])
                ->first();

            if ($yaExiste) {
                return redirect()->back()->with('error', 'Este cliente ya tiene una reserva en esa pista, fecha y hora.');
            }

            $model->insert($data);
        } else {
            $id = $this->request->getPost('idReserva');
            $model->update($id, $data);
        }

        return redirect()->to('/admin/pistasClientes');
    }

    public function eliminarReserva()
    {
        $id = $this->request->getGet('id');
        $model = new PistasClientesModel();
        $model->delete($id);
        return redirect()->to('/admin/pistasClientes');
    }
}
