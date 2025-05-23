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

        $fechaHora = $this->request->getGet('editar');
        $idPistas = $this->request->getGet('id');

        if ($fechaHora && $idPistas) {
            $reserva = $pistasClientesModel->where('fechaHora', $fechaHora)
                ->where('idPistas', $idPistas)
                ->first();
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

        $fechaHora = date('Y-m-d H:i:s', strtotime($this->request->getPost('fechaHora')));

        $data = [
            'fechaHora' => $fechaHora,
            'idPistas' => $this->request->getPost('idPistas'),
            'correoClientes' => $this->request->getPost('correoClientes'),
            'numPersonas' => $this->request->getPost('numPersonas'),
            'nivelPersonas' => $this->request->getPost('nivelPersonas') ?? null,
            'mediaNivel' => $this->request->getPost('mediaNivel') ?? null
        ];

        if ($modo === 'insertar') {
            $existe = $model->where('fechaHora', $fechaHora)
                ->where('idPistas', $data['idPistas'])
                ->first();
            if ($existe) {
                return redirect()->back()->with('error', 'Ya existe una reserva para esa fecha y pista.');
            }
            $model->insert($data);
        } else {
            $fechaHoraOriginal = $this->request->getPost('fechaHoraOriginal');
            $idPistasOriginal = $this->request->getPost('idPistasOriginal');

            $model->where('fechaHora', $fechaHoraOriginal)
                ->where('idPistas', $idPistasOriginal)
                ->set($data)
                ->update();
        }

        return redirect()->to('/admin/pistasClientes');
    }

    public function eliminarReserva()
    {
        $fechaHora = date('Y-m-d H:i:s', strtotime($this->request->getGet('fechaHora')));
        $idPistas = $this->request->getGet('id');

        $model = new PistasClientesModel();
        $model->where('fechaHora', $fechaHora)
            ->where('idPistas', $idPistas)
            ->delete();

        return redirect()->to('/admin/pistasClientes');
    }
}
