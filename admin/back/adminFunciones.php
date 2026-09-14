<?php
/**
 * NEXORA - funciones del panel de administracion.
 *
 * Todas las funciones que tocan la base de datos reciben la conexion como
 * parametro. La version anterior usaba "global $con", pero $con era una
 * variable local del script que las llamaba, asi que dentro de la funcion
 * siempre llegaba vacia y la consulta tronaba.
 */

function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim((string) $parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validaPassword($password, $repassword)
{
    return strcmp($password, $repassword) === 0;
}

function generaToken()
{
    return md5(uniqid(mt_rand(), false));
}

function mostrarMensajes(array $errors)
{
    if (count($errors) === 0) {
        return;
    }

    echo '<div class="alert alert-warning" role="alert"><ul class="mb-0">';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul></div>';
}

/** Inicia sesion en el panel. Devuelve un mensaje de error o redirige. */
function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario = ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        session_regenerate_id(true);            // evita fijacion de sesion
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['user_name'] = $row['nombre'];
        $_SESSION['user_type'] = 'admin';
        header('Location: inicio.php');
        exit;
    }

    return 'El usuario y/o contraseña son incorrectos.';
}

/* ---------------- productos ---------------- */

function listarProductos($con)
{
    $sql = $con->prepare(
        "SELECT p.id, p.nombre, p.precio, p.descuento, p.descripcion, p.activo,
                COALESCE(c.nombre, 'Sin categoría') AS categoria
         FROM productos p
         LEFT JOIN categorias c ON c.id = p.id_categoria
         ORDER BY p.id"
    );
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProducto($id, $con)
{
    $sql = $con->prepare("SELECT * FROM productos WHERE id = ? LIMIT 1");
    $sql->execute([$id]);
    return $sql->fetch(PDO::FETCH_ASSOC);
}

/** Alta de producto. El id lo asigna MySQL (AUTO_INCREMENT), no el formulario. */
function agregarProducto($con, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo)
{
    $sql = $con->prepare(
        "INSERT INTO productos (nombre, descripcion, precio, descuento, id_categoria, activo)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    return $sql->execute([$nombre, $descripcion, $precio, $descuento, $id_categoria, $activo])
        ? (int) $con->lastInsertId()
        : 0;
}

/** Actualiza un producto. Antes la consulta decia "UPDATE INTO", que no es SQL valido. */
function actualizarProducto($con, $id, $nombre, $descripcion, $precio, $descuento, $id_categoria, $activo)
{
    $sql = $con->prepare(
        "UPDATE productos
         SET nombre = ?, descripcion = ?, precio = ?, descuento = ?, id_categoria = ?, activo = ?
         WHERE id = ?"
    );
    $sql->execute([$nombre, $descripcion, $precio, $descuento, $id_categoria, $activo, $id]);
    return $sql->rowCount() >= 0;
}

/**
 * Baja logica: marca activo = 0 en vez de borrar el renglon.
 * Un DELETE real rompe el historial de cualquier pedido que ya lo incluya.
 */
function eliminarProducto($con, $id)
{
    $sql = $con->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
    $sql->execute([$id]);
    return $sql->rowCount() > 0;
}

function listarCategorias($con)
{
    $sql = $con->prepare("SELECT id, nombre FROM categorias WHERE activo = 1 ORDER BY nombre");
    $sql->execute();
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}
