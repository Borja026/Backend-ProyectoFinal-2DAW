<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Galería</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f8f9fa;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 650px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            column-gap: 20px;
            row-gap: 15px;

            & label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
                color: #495057;
            }

            & input[type="text"],
            & input[type="password"],
            & input[type="number"],
            & input[type="email"],
            & input[type="date"],
            & input[type="datetime-local"],
            & input[type="file"],
            & select,
            & select option {
                width: 100%;
                padding: 8px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                box-sizing: border-box;

                text-align: center;
            }

            & button {
                grid-column: 1 / -1;
                /* ocupa toda la fila */
                margin-top: 10px;
                padding: 10px 20px;
                background-color: #007bff;
                border: none;
                color: white;
                border-radius: 4px;
                cursor: pointer;
                justify-self: center;
                width: 200px;

                &:hover {
                    background-color: #0056b3;
                }
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;

            & th,
            & td {
                padding: 12px;
                text-align: center;
            }

            & th {
                background-color: #e9ecef;
                color: #495057;
            }

            & tr {
                border-bottom: 1px solid #dee2e6;

                &:hover {
                    background-color: #f1f3f5;
                }
            }
        }

        .acciones {
            height: 100px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;

            & a {
                display: block;
                width: max-content;
                height: max-content;
                padding: 8px;
                border-radius: 10px;
                color: white;
                text-decoration: none;

                &:nth-child(1) {
                    background-color: #007bff;
                }

                &:nth-child(2) {
                    background-color: #ff0000;
                }

                &:hover {

                    &:nth-child(1) {
                        background-color: rgb(0, 87, 179);
                    }

                    &:nth-child(2) {
                        background-color: rgb(177, 0, 0);
                    }
                }
            }
        }

        #filtro {
            width: 300px;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }



        /* ----------------------------------- */
        .galeria-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s;
            cursor: pointer;

            &:hover {
                transform: scale(1.05);
            }
        }

        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?= view('admin/menu') ?>


    <h2>Gestión de Galería</h2>


    <?php if (session()->getFlashdata('error')) { ?>
        <script>
            alert("<?= session()->getFlashdata('error') ?>");
        </script>
    <?php } ?>



    <form action="<?= base_url('admin/galeria/guardar') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="modo" value="<?= isset($imagenEditando) ? 'editar' : 'insertar' ?>">

        <?php if (!isset($imagenEditando)) { ?>
            <label>Archivo de imagen:</label>
            <input type="file" name="archivo" accept="image/*" required>
        <?php } else { ?>
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= esc($imagenEditando['nombre']) ?>" readonly required>
        <?php } ?>

        <label>Categoría:</label>
        <select name="idCategoria" required>
            <option value="">-- Selecciona una categoría --</option>
            <?php foreach ($categorias as $cat) { ?>
                <option value="<?= $cat['id'] ?>" <?= isset($imagenEditando) && $imagenEditando['idCategoria'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['categoria']) ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit"><?= isset($imagenEditando) ? 'Actualizar' : 'Insertar' ?></button>
    </form>



    <p>Buscar imagen / video: <input type="text" id="filtro" placeholder="Buscar imagen / video..."></p>

    <table id="tablaGaleria">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($imagenes as $img) { ?>
                <?php $categoriaNombre = '';
                foreach ($categorias as $cat) {
                    if ($cat['id'] == $img['idCategoria']) {
                        $categoriaNombre = $cat['categoria'];
                        $categoriaNombreCarpeta = $cat['nombreCarpeta'];
                        break;
                    }
                } ?>
                <tr>
                    <td>
                        <?php if (!empty($img['nombre'])) { ?>
                            <a href="<?= base_url('imgs/galeria/' . esc($categoriaNombreCarpeta) . "/" . esc($img['nombre'])) ?>"
                                target="_blank">
                                <img class="galeria-img"
                                    src="<?= base_url('imgs/galeria/' . esc($categoriaNombreCarpeta) . "/" . esc($img['nombre'])) ?>"
                                    alt="Foto">
                            </a>
                        <?php } else { ?>
                            Sin foto
                        <?php } ?>
                    </td>
                    <td><?= esc($img['nombre']) ?></td>
                    <td><?= esc($categoriaNombre) ?></td>
                    <td class="acciones">
                        <a href="<?= base_url('admin/galeria?editar=' . $img['nombre']) ?>">Editar</a>
                        <a href="<?= base_url('admin/galeria/eliminar?nombre=' . $img['nombre']) ?>"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

<!-- Funcionalidad del Filtro -->
<script>
    const filtroInput = document.getElementById('filtro');
    const tabla = document.getElementById('tablaGaleria').getElementsByTagName('tbody')[0];

    filtroInput.addEventListener('keyup', function () {
        const filtro = this.value.toLowerCase();
        const filas = tabla.getElementsByTagName('tr');

        Array.from(filas).forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });
</script>

</html>