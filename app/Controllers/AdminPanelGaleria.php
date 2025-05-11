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

        // Buscar la carpeta de la categoría
        $categoria = $categoriasModel->find($categoriaId);
        if (!$categoria) {
            return redirect()->back()->with('error', 'Categoría no válida');
        }
        $nombreCarpeta = $categoria['nombreCarpeta'];

        if ($modo === 'insertar') {
            $archivo = $this->request->getFile('archivo');

            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $nombreArchivo = $archivo->getRandomName(); // Puedes usar ->getName() si prefieres el original

                $rutaDestino = FCPATH . 'imgs/galeria/' . $nombreCarpeta;

                // Crear la carpeta si no existe
                if (!is_dir($rutaDestino)) {
                    mkdir($rutaDestino, 0777, true);
                }

                // Mover el archivo
                $archivo->move($rutaDestino, $nombreArchivo);

                // Guardar en la base de datos
                $galeriaModel->insert([
                    'nombre' => $nombreArchivo,
                    'idCategoria' => $categoriaId
                ]);
            }
        } elseif ($modo === 'editar') {
            // En modo editar no se cambia el archivo, solo la categoría
            $nombre = $this->request->getPost('nombre');

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
