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
        helper(['form', 'filesystem']);

        $model = new ClientesModel();
        $modo = $this->request->getPost('modo');

        $foto = $this->request->getFile('foto');
        $nombreArchivo = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $nombreArchivo = $foto->getRandomName();
            $rutaDestino = FCPATH . 'imgs/clientes';

            if (!is_dir($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }

            $foto->move($rutaDestino, $nombreArchivo);
        }

        $passwordPlano = $this->request->getPost('password');
        $passwordHash = $passwordPlano ? hash('sha256', $passwordPlano) : null;

        $correo = $this->request->getPost('correo');

        $data = [
            'correo' => $correo,
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha' => $this->request->getPost('fecha'),
            'telefono' => $this->request->getPost('telefono'),
            'username' => $this->request->getPost('username'),
            'sexo' => $this->request->getPost('sexo'),
            'nivel' => $this->request->getPost('nivel'),
            'posicion' => $this->request->getPost('posicion'),
            'reciveClases' => $this->request->getPost('reciveClases'),
        ];

        // Contraseña
        if ($passwordHash) {
            $data['password'] = $passwordHash;
        } elseif ($modo === 'editar') {
            $data['password'] = $model->find($correo)['password'] ?? null;
        }

        // Imagen de perfil
        if ($nombreArchivo) {
            $data['foto'] = $nombreArchivo;
        } elseif ($modo === 'editar') {
            $data['foto'] = $model->find($correo)['foto'] ?? 'default_user.png';
        } else {
            // Si es nuevo y no sube foto
            $data['foto'] = 'default_user.png';
        }

        // Insertar o actualizar
        if ($modo === 'insertar') {
            if ($model->find($correo)) {
                return redirect()->back()->with('error', 'Ya existe un cliente con ese correo.');
            }

            $model->insert($data);
        } else {
            $model->update($correo, $data);
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

        // Ruta del archivo de la imagen
        $foto = $cliente['foto'] ?? '';
        $rutaImagen = FCPATH . 'imgs/clientes/' . $foto;

        // Si no es la imagen por defecto y el archivo existe, lo eliminamos
        if ($foto && $foto !== 'default_user.png' && file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        $model->delete($correo, false);

        return redirect()->to('/admin/clientes');
    }


}
