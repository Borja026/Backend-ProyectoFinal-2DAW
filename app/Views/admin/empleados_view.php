<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
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
            height: 55px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;

            & a {
                display: block;
                padding: 8px;
                border-radius: 10px;
                color: white;
                text-decoration: none;

                &:nth-child(1) {
                    background-color: #007bff;

                    &:hover {
                        background-color: rgb(0, 87, 179);
                    }
                }

                &:nth-child(2) {
                    background-color: #ff0000;

                    &:hover {
                        background-color: rgb(177, 0, 0);
                    }
                }
            }
        }

        #filtro {
            width: 300px;
            padding: 8px;
            margin: 20px auto;
            border: 1px solid #ced4da;
            border-radius: 4px;
            display: block;
        }

        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?= view('admin/menu') ?>


    <h2>Gestión de Empleados</h2>


    <?php if (session()->getFlashdata('error')) { ?>
        <script>
            alert("<?= session()->getFlashdata('error') ?>");
        </script>
    <?php } ?>


    <form action="<?= base_url('admin/empleados/guardar') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="modo" value="<?= isset($empleadoEditando) ? 'editar' : 'insertar' ?>">

        <label>DNI:</label>
        <input type="text" name="dni" value="<?= isset($empleadoEditando) ? $empleadoEditando['dni'] : '' ?>"
            <?= isset($empleadoEditando) ? 'readonly' : '' ?> required>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= isset($empleadoEditando) ? $empleadoEditando['nombre'] : '' ?>"
            required>

        <label>Apellidos:</label>
        <input type="text" name="apellidos"
            value="<?= isset($empleadoEditando) ? $empleadoEditando['apellidos'] : '' ?>" required>

        <label>Correo electrónico:</label>
        <input type="email" name="correo" value="<?= isset($empleadoEditando) ? $empleadoEditando['correo'] : '' ?>"
            <?= isset($empleadoEditando) ? 'readonly' : '' ?> required>

        <label>Contraseña:</label>
        <input type="password" name="password"
            value="<?= isset($empleadoEditando) ? $empleadoEditando['password'] : '' ?>" required>

        <label>Foto:</label>
        <input type="file" name="foto" <?= !isset($empleadoEditando) ? 'required' : '' ?> required>

        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?= isset($empleadoEditando) ? $empleadoEditando['fecha'] : '' ?>"
            required>

        <label>Teléfono:</label>
        <input type="number" name="telefono"
            value="<?= isset($empleadoEditando) ? $empleadoEditando['telefono'] : '' ?>" min="0" required>

        <button type="submit"><?= isset($empleadoEditando) ? 'Actualizar' : 'Insertar' ?></button>
    </form>


    <p>Buscar empleado: <input type="text" id="filtro" placeholder="Buscar empleado..."></p>

    <table id="tablaEmpleados">
        <thead>
            <tr>
                <th>Foto</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Contraseña</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($empleados as $empleado) { ?>
                <tr>
                    <td>
                        <?php if (!empty($empleado['foto'])) { ?>
                            <a href="<?= base_url('imgs/empleados/' . esc($empleado['foto'])) ?>" target="_blank">
                                <img src="<?= base_url('imgs/empleados/' . esc($empleado['foto'])) ?>" alt="Foto"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            </a>
                        <?php } else { ?>
                            <a href="<?= base_url('imgs/empleados/default_user.png') ?>" target="_blank">
                                <img src="<?= base_url('imgs/empleados/default_user.png') ?>" alt="Foto"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            </a>
                        <?php } ?>
                    </td>
                    <td><?= esc($empleado['dni']) ?></td>
                    <td><?= esc($empleado['nombre']) ?></td>
                    <td><?= esc($empleado['apellidos']) ?></td>
                    <td><?= date('d/m/Y', strtotime($empleado['fecha'])) ?></td>
                    <td><?= esc($empleado['telefono']) ?></td>
                    <td><?= esc($empleado['correo']) ?></td>
                    <td title="<?= esc($empleado['password']) ?>"> <?= esc(substr($empleado['password'], 0, 10)) ?>... </td>
                    <td class="acciones">
                        <a href="<?= base_url('admin/empleados?editar=' . urlencode($empleado['dni'])) ?>">Editar</a>
                        <a href="<?= base_url('admin/empleados/eliminar?dni=' . urlencode($empleado['dni'])) ?>"
                            onclick="return confirm('¿Seguro que quieres eliminar este empleado?')">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

</body>

<script>
    const filtroInput = document.getElementById('filtro');
    const tabla = document.getElementById('tablaEmpleados').getElementsByTagName('tbody')[0];

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