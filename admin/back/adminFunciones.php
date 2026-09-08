<?php



function esNulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function esEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function validaPassword($password, $repassword)
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}

function generaToken()
{
    return md5(uniqid(mt_rand(), false));
}

function registraCliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, estatus, fecha_alta) VALUES (?,?,?,?,1,now())");
    if ($sql->execute($datos)) {
        return $con->LastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, activacion, token, id_cliente) VALUES (?,?,1,?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    ($sql->execute([$usuario]));
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    ($sql->execute([$email]));
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}



function mostrarMensajes(array $errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"</button></div>';
    }
}

function login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT id, usuario, password, nombre FROM admin WHERE usuario LIKE ? AND activo = 1 LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            $_SESSION['user_type'] = 'admin';
            header('Location: inicio.php');
            exit;
        }
    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function eliminarProducto($id) {
    global $con; // Usa la conexión global

    try {
        // Sentencia SQL para eliminar el producto por su ID
        $sql = $con->prepare("DELETE FROM productos WHERE id = :id");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Ejecutar la sentencia
        if ($sql->execute()) {
            return true; // Éxito
        } else {
            return false; // Falló
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function actualizarProducto($id, $nuevoNombre, $nuevoPrecio) {
    global $con; // Utiliza la conexión global

    try {
        // Sentencia SQL para actualizar el producto por su ID
        $sql = $con->prepare("UPDATE productos SET nombre = :nombre, precio = :precio WHERE id = :id");
        $sql->bindParam(':nombre', $nuevoNombre, PDO::PARAM_STR);
        $sql->bindParam(':precio', $nuevoPrecio, PDO::PARAM_INT);
        $sql->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la sentencia
        if ($sql->execute()) {
            return true; // Éxito
        } else {
            return false; // Falló
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

class AdminFunciones
{
    // Otras funciones de tu clase...

    public function agregarProducto($id, $nombre, $descripcion, $precio, $activo)
    {
        try {
            require 'config/basededatos.php';  // Ajusta la ruta según la estructura de tu proyecto
            $db = new Database();
            $con = $db->conectar();

            // Prepara la consulta SQL
            $sql = $con->prepare("INSERT INTO productos (id, nombre, descripcion, precio, activo) VALUES (:id, :nombre, :descripcion, :precio, :activo)");

            // Bind de los parámetros
            $sql->bindParam(':id', $id);
            $sql->bindParam(':nombre', $nombre);
            $sql->bindParam(':descripcion', $descripcion);
            $sql->bindParam(':precio', $precio);
            $sql->bindParam(':activo', $activo);


            // Ejecuta la consulta
            $sql->execute();

            // Cierre de la conexión
            $con = null;

            return true;
        } catch (PDOException $e) {
            // Manejo de errores, puedes personalizar según tus necesidades
            echo "Error al agregar producto: " . $e->getMessage();
            return false;
        }
    }

    public function actualizarProductoProducto($nombre, $descripcion, $precio)
    {
        try {
            require 'config/basededatos.php';  // Ajusta la ruta según la estructura de tu proyecto
            $db = new Database();
            $con = $db->conectar();

            // Prepara la consulta SQL
            $sql = $con->prepare("UPDATE INTO productos (id, nombre, descripcion, precio, activo) VALUES (:id, :nombre, :descripcion, :precio, :activo)");

            // Bind de los parámetros
            $sql->bindParam(':id', $id);
            $sql->bindParam(':nombre', $nombre);
            $sql->bindParam(':descripcion', $descripcion);
            $sql->bindParam(':precio', $precio);
            $sql->bindParam(':activo', $activo);


            // Ejecuta la consulta
            $sql->execute();

            // Cierre de la conexión
            $con = null;

            return true;
        } catch (PDOException $e) {
            // Manejo de errores, puedes personalizar según tus necesidades
            echo "Error al agregar producto: " . $e->getMessage();
            return false;
        }
    }

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function eliminarProducto($id) {
        $con = $this->db->conectar();

        try {
            // Preparar la consulta SQL utilizando una declaración preparada
            $stmt = $con->prepare("DELETE FROM productos WHERE id = ?");
            
            // Vincular el parámetro
            $stmt->bindParam(1, $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si algún registro fue afectado
            if ($stmt->rowCount() > 0) {
                return true; // Éxito: Producto eliminado correctamente
            } else {
                return false; // Fracaso: No se eliminó ningún producto
            }
        } catch (PDOException $e) {
            // Manejar errores de la base de datos
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            // Cerrar la conexión
            $con = null;
        }
    }
}



