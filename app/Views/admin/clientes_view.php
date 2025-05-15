<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión de Clientes</title>

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


  <h2>Gestión de Clientes</h2>


  <?php if (session()->getFlashdata('error')) { ?>
    <script>
      alert("<?= session()->getFlashdata('error') ?>");
    </script>
  <?php } ?>


  <form action="<?= base_url('admin/clientes/guardar') ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="modo" value="<?= isset($clienteEditando) ? 'editar' : 'insertar' ?>">

    <label>Correo electrónico:</label>
    <input type="text" name="correo" value="<?= isset($clienteEditando) ? $clienteEditando['correo'] : '' ?>"
      <?= isset($clienteEditando) ? 'readonly' : '' ?> required>

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= isset($clienteEditando) ? $clienteEditando['nombre'] : '' ?>" required>

    <label>Apellidos:</label>
    <input type="text" name="apellidos" value="<?= isset($clienteEditando) ? $clienteEditando['apellidos'] : '' ?>"
      required>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?= isset($clienteEditando) ? $clienteEditando['fecha'] : '' ?>" required>

    <label>Foto: <span style="font-size: 12px;">(opcional)</span></label>
    <input type="file" name="foto" <?= !isset($clienteEditando) ? '' : '' ?>>

    <label>Teléfono:</label>
    <input type="number" name="telefono" value="<?= isset($clienteEditando) ? $clienteEditando['telefono'] : '' ?>"
      min="0" required>

    <label>Username:</label>
    <input type="text" name="username" value="<?= isset($clienteEditando) ? $clienteEditando['username'] : '' ?>"
      required>

    <label>Password:</label>
    <input type="password" name="password" value="<?= isset($clienteEditando) ? $clienteEditando['password'] : '' ?>"
      required>

    <label>Sexo: <span style="font-size: 12px;">(opcional)</span></label>
    <select name="sexo" id="">
      <option value="" <?= isset($clienteEditando) && $clienteEditando['sexo'] == '' ? 'selected' : '' ?>>-- Selecciona un
        sexo --</option>
      <option value="0" <?= isset($clienteEditando) && $clienteEditando['sexo'] == 0 ? 'selected' : '' ?>>Mujer</option>
      <option value="1" <?= isset($clienteEditando) && $clienteEditando['sexo'] == 1 ? 'selected' : '' ?>>Hombre</option>
    </select>

    <label>Nivel:</label>
    <select name="nivel" id="" required>
      <option value="" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '' ? 'selected' : '' ?>>-- Selecciona
        una nivel --</option>
      <option value="1.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '1.00' ? 'selected' : '' ?>>1.00
      </option>
      <option value="1.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '1.25' ? 'selected' : '' ?>>1.25
      </option>
      <option value="1.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '1.50' ? 'selected' : '' ?>>1.50
      </option>
      <option value="1.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '1.75' ? 'selected' : '' ?>>1.75
      </option>
      <option value="2.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '2.00' ? 'selected' : '' ?>>2.00
      </option>
      <option value="2.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '2.25' ? 'selected' : '' ?>>2.25
      </option>
      <option value="2.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '2.50' ? 'selected' : '' ?>>2.50
      </option>
      <option value="2.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '2.75' ? 'selected' : '' ?>>2.75
      </option>
      <option value="3.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '3.00' ? 'selected' : '' ?>>3.00
      </option>
      <option value="3.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '3.25' ? 'selected' : '' ?>>3.25
      </option>
      <option value="3.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '3.50' ? 'selected' : '' ?>>3.50
      </option>
      <option value="3.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '3.75' ? 'selected' : '' ?>>3.75
      </option>
      <option value="4.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '4.00' ? 'selected' : '' ?>>4.00
      </option>
      <option value="4.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '4.25' ? 'selected' : '' ?>>4.25
      </option>
      <option value="4.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '4.50' ? 'selected' : '' ?>>4.50
      </option>
      <option value="4.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '4.75' ? 'selected' : '' ?>>4.75
      </option>
      <option value="5.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '5.00' ? 'selected' : '' ?>>5.00
      </option>
      <option value="5.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '5.25' ? 'selected' : '' ?>>5.25
      </option>
      <option value="5.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '5.50' ? 'selected' : '' ?>>5.50
      </option>
      <option value="5.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '5.75' ? 'selected' : '' ?>>5.75
      </option>
      <option value="6.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '6.00' ? 'selected' : '' ?>>6.00
      </option>
      <option value="6.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '6.25' ? 'selected' : '' ?>>6.25
      </option>
      <option value="6.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '6.50' ? 'selected' : '' ?>>6.50
      </option>
      <option value="6.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '6.75' ? 'selected' : '' ?>>6.75
      </option>
      <option value="7.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '7.00' ? 'selected' : '' ?>>7.00
      </option>
      <option value="7.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '7.25' ? 'selected' : '' ?>>7.25
      </option>
      <option value="7.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '7.50' ? 'selected' : '' ?>>7.50
      </option>
      <option value="7.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '7.75' ? 'selected' : '' ?>>7.75
      </option>
      <option value="8.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '8.00' ? 'selected' : '' ?>>8.00
      </option>
      <option value="8.25" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '8.25' ? 'selected' : '' ?>>8.25
      </option>
      <option value="8.50" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '8.50' ? 'selected' : '' ?>>8.50
      </option>
      <option value="8.75" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '8.75' ? 'selected' : '' ?>>8.75
      </option>
      <option value="9.00" <?= isset($clienteEditando) && $clienteEditando['nivel'] == '9.00' ? 'selected' : '' ?>>9.00
      </option>
    </select>

    <label>Posición:</label>
    <select name="posicion" id="" required>
      <option value="" <?= isset($clienteEditando) && $clienteEditando['posicion'] == '' ? 'selected' : '' ?>>-- Selecciona
        una posición --</option>
      <option value="Indiferente" <?= isset($clienteEditando) && $clienteEditando['posicion'] == 'Indiferente' ? 'selected' : '' ?>> Indiferente</option>
      <option value="Derecha" <?= isset($clienteEditando) && $clienteEditando['posicion'] == 'Derecha' ? 'selected' : '' ?>> Derecha </option>
      <option value="Revés" <?= isset($clienteEditando) && $clienteEditando['posicion'] == 'Revés' ? 'selected' : '' ?>>
        Revés </option>
    </select>

    <label>Recibe clases?: <span style="font-size: 12px;">(opcional)</span></label>
    <select name="recibeClases" id="">
      <option value="" <?= isset($clienteEditando) && $clienteEditando['recibeClases'] == '' ? 'selected' : '' ?>>--
        Selecciona una opción --</option>
      <option value="0" <?= isset($clienteEditando) && $clienteEditando['recibeClases'] == 0 ? 'selected' : '' ?>>NO
      </option>
      <option value="1" <?= isset($clienteEditando) && $clienteEditando['recibeClases'] == 1 ? 'selected' : '' ?>>SI
      </option>
    </select>

    <button type="submit"><?= isset($clienteEditando) ? 'Actualizar' : 'Insertar' ?></button>
  </form>


  <p>Buscar cliente: <input type="text" id="filtro" placeholder="Buscar cliente..."></p>

  <table id="tablaClientes">
    <thead>
      <tr>
        <th>Foto</th>
        <th>Correo</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Fecha</th>
        <th>Teléfono</th>
        <th>Username</th>
        <th>Password</th>
        <th>Sexo</th>
        <th>Nivel</th>
        <th>Posición</th>
        <th>Recibe clases?</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($clientes as $cliente) { ?>
        <tr>
          <td>
            <?php if (!empty($cliente['foto'])) { ?>
              <a href="<?= base_url('imgs/clientes/' . esc($cliente['foto'])) ?>" target="_blank">
                <img src="<?= base_url('imgs/clientes/' . esc($cliente['foto'])) ?>" alt="Foto"
                  style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
              </a>
            <?php } else { ?>
              <a href="<?= base_url('imgs/clientes/default_user.png') ?>" target="_blank">
                <img src="<?= base_url('imgs/clientes/default_user.png') ?>" alt="Foto"
                  style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
              </a>
            <?php } ?>
          </td>
          <td><?= esc($cliente['correo']) ?></td>
          <td><?= esc($cliente['nombre']) ?></td>
          <td><?= esc($cliente['apellidos']) ?></td>
          <td><?= date('d/m/Y', strtotime($cliente['fecha'])) ?></td>
          <td><?= esc($cliente['telefono']) ?></td>
          <td><?= esc($cliente['username']) ?></td>
          <td title="<?= esc($cliente['password']) ?>"><?= esc(substr($cliente['password'], 0, 10)) ?>...</td>
          <td>
            <?php
            if ($cliente['sexo'] == 0) {
              echo "Mujer";
            } else {
              echo "Hombre";
            }
            ?>
          </td>
          <td><?= esc($cliente['nivel']) ?></td>
          <td><?= esc($cliente['posicion']) ?></td>
          <td>
            <?php
            if ($cliente['recibeClases'] == 0) {
              echo "NO";
            } else {
              echo "SI";
            }
            ?>
          </td>
          <td class="acciones">
            <a href="<?= base_url('admin/clientes?editar=' . urlencode($cliente['correo'])) ?>">Editar</a>
            <a href="<?= base_url('admin/clientes/eliminar?correo=' . urlencode($cliente['correo'])) ?>"
              onclick="return confirm('¿Seguro que quieres eliminar este clientes?')">Eliminar</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</body>

<script>
  const filtroInput = document.getElementById('filtro');
  const tabla = document.getElementById('tablaClientes').getElementsByTagName('tbody')[0];

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