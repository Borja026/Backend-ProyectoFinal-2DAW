<?php

namespace App\Controllers;

use App\Models\GaleriaModel;
use App\Models\CategoriasModel;

class AdminPanelGaleria extends BaseController
{
    public function galeria()
    {
        $galeriaModel = new GaleriaModel();
        $categoriaModel = new CategoriasModel();

        $data['imagenes'] = $galeriaModel->findAll();
        $data['categorias'] = $categoriaModel->findAll();

        $nombre = $this->request->getGet('editar');
        if ($nombre) {
            $imagen = $galeriaModel->find($nombre);
            if ($imagen) {
                $data['imagenEditando'] = $imagen;
            }
        }

        return view('admin/galeria_view', $data);
    }



    public function guardarImagen()
    {
        helper(['form', 'filesystem']);

        $galeriaModel = new GaleriaModel();
        $categoriasModel = new CategoriasModel();

        $modo = $this->request->getPost('modo');
        $categoriaId = $this->request->getPost('idCategoria');

        $categoria = $categoriasModel->find($categoriaId);
        if (!$categoria) {
            return redirect()->back()->with('error', 'Categoría no válida');
        }

        $nombreCarpeta = $categoria['nombreCarpeta'];

        if ($modo === 'insertar') {
            $archivo = $this->request->getFile('archivo');

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                // $nombreArchivo = $archivo->getRandomName();
                $nombreArchivo = $archivo->getName(); // Usar el nombre original del archivo

                $rutaDestino = FCPATH . 'imgs/galeria/' . $nombreCarpeta;
                if (!is_dir($rutaDestino)) {
                    mkdir($rutaDestino, 0777, true);
                }

                $archivo->move($rutaDestino, $nombreArchivo);

                // Evitar duplicado de clave primaria
                $existe = $galeriaModel->find($nombreArchivo);
                if ($existe) {
                    return redirect()->back()->with('error', 'Ya existe una imagen con ese nombre.');
                }

                $galeriaModel->insert([
                    'nombre' => $nombreArchivo,
                    'idCategoria' => $categoriaId
                ]);
            }
        } elseif ($modo === 'editar') {
            $nombre = $this->request->getPost('nombre');

            if (!$galeriaModel->find($nombre)) {
                return redirect()->back()->with('error', 'La imagen no existe.');
            }

            $galeriaModel->update($nombre, [
                'idCategoria' => $categoriaId
            ]);
        }

        return redirect()->to('/admin/galeria');
    }



    public function eliminarImagen()
    {
        $nombre = $this->request->getGet('nombre');

        $model = new GaleriaModel();
        $model->delete($nombre, false);

        return redirect()->to('/admin/galeria');
    }

}
