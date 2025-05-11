<?php

namespace App\Controllers;

use App\Models\ClientesModel;

class AdminPanelClientes extends BaseController
{
    public function clientes()
    {
        $clientesModel = new ClientesModel();
        $data['clientes'] = $clientesModel->findAll();

        $correo = $this->request->getGet('editar');
        if ($correo) {
            $cliente = $clientesModel->find($correo);
            if ($cliente) {
                $data['clienteEditando'] = $cliente;
            }
        }

        return view('admin/clientes_view', $data);
    }

    public function guardarCliente()
    {
        $model = new ClientesModel();
        $modo = $this->request->getPost('modo');

        $data = [
            'correo' => $this->request->getPost('correo'),
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha' => $this->request->getPost('fecha'),
            'telefono' => $this->request->getPost('telefono')
        ];

        if ($modo === 'insertar') {
            $model->insert($data);
        } else {
            $model->update($data['correo'], $data);
        }

        return redirect()->to('/admin/clientes');
    }

    public function eliminarCliente()
    {
        $correo = urldecode($this->request->getGet('correo'));

        $model = new ClientesModel();

        // Verifica si el cliente existe
        $cliente = $model->find($correo);
        if (!$cliente) {
            return $this->response->setStatusCode(404)->setBody('Cliente no encontrado.');
        }

        // Elimina usando el segundo parámetro 'false'
        $model->delete($correo, false);

        return redirect()->to('/admin/clientes');
    }


}
