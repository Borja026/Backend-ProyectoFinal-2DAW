<?php

namespace App\Controllers;

use App\Models\EmpleadosModel;

class AdminPanelEmpleados extends BaseController
{
    public function empleados()
    {
        $empleadosModel = new EmpleadosModel();
        $data['empleados'] = $empleadosModel->findAll();

        $dni = $this->request->getGet('editar');
        if ($dni) {
            $empleado = $empleadosModel->find($dni);
            if ($empleado) {
                $data['empleadoEditando'] = $empleado;
            }
        }

        return view('admin/empleados_view', $data);
    }



    public function guardarEmpleado()
    {
        helper(['form', 'filesystem']);

        $model = new EmpleadosModel();
        $modo = $this->request->getPost('modo');

        $dni = $this->request->getPost('dni');
        $foto = $this->request->getFile('foto');
        $passwordPlano = $this->request->getPost('password');

        $nombreArchivo = null;
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $nombreArchivo = $foto->getRandomName();
            $rutaDestino = FCPATH . 'imgs/empleados';
            if (!is_dir($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            $foto->move($rutaDestino, $nombreArchivo);
        }

        $data = [
            'dni' => $dni,
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha' => $this->request->getPost('fecha'),
            'telefono' => $this->request->getPost('telefono'),
            'correo' => $this->request->getPost('correo')
        ];

        if ($passwordPlano) {
            $data['password'] = hash('sha256', $passwordPlano);
        } elseif ($modo === 'editar') {
            $data['password'] = $model->find($dni)['password'] ?? null;
        }

        if ($nombreArchivo) {
            $data['foto'] = $nombreArchivo;
        } elseif ($modo === 'editar') {
            $data['foto'] = $model->find($dni)['foto'] ?? null;
        }

        if ($modo === 'insertar') {
            $existe = $model->find($dni);
            if ($existe) {
                return redirect()->back()->with('error', 'Ya existe un empleado con ese DNI.');
            }
            $model->insert($data);
        } elseif ($modo === 'editar') {
            $model->update($dni, $data);
        }

        return redirect()->to('/admin/empleados');
    }


    public function eliminarEmpleado()
    {
        $dni = urldecode($this->request->getGet('dni'));

        $model = new EmpleadosModel();

        // Verifica si el empleado existe
        $empleado = $model->find($dni);
        if (!$empleado) {
            return $this->response->setStatusCode(404)->setBody('Empleado no encontrado.');
        }

        // Ruta del archivo de la imagen
        $foto = $empleado['foto'] ?? '';
        $rutaImagen = FCPATH . 'imgs/empleados/' . $foto;

        // Si no es la imagen por defecto y el archivo existe, se elimina
        if ($foto && $foto !== 'default_user.png' && file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        $model->delete($dni, false);

        return redirect()->to('/admin/empleados');
    }
}
