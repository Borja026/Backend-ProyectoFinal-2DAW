<?php

namespace App\Controllers;

use App\Models\PistasModel;

class AdminPanelPistas extends BaseController
{
    public function pistas()
    {
        $model = new PistasModel();
        $data['pistas'] = $model->findAll();

        $id = $this->request->getGet('editar');
        if ($id) {
            $pista = $model->find($id);
            if ($pista) {
                $data['pistaEditando'] = $pista;
            }
        }

        return view('admin/pistas_view', $data);
    }

    public function guardarPista()
    {
        $model = new PistasModel();
        $modo = $this->request->getPost('modo');

        $data = [
            'id' => $this->request->getPost('id'),
            'reservada' => $this->request->getPost('reservada')
        ];

        if ($modo === 'insertar') {
            $model->insert($data);
        } else {
            $model->update($data['id'], $data);
        }

        return redirect()->to('/admin/pistas');
    }

    public function eliminarPista()
    {
        $id = $this->request->getGet('id');

        $model = new PistasModel();
        $model->delete($id, false);

        return redirect()->to('/admin/pistas');
    }

}
