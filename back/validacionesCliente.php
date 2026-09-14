<?php

function esNulo(array $parametros){
    foreach($parametros as $parametro){
        if(strlen(trim((string) $parametro)) < 1){
            return true;
        }
    }
    return false;
}

function esEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function validaPassword($password, $repassword){
    if(strcmp($password, $repassword) === 0){
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
    if($sql->execute($datos)){
        return $con->LastInsertId();
    }
    return 0;
}

function registraUsuario(array $datos, $con){
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, activacion, token, id_cliente) VALUES (?,?,1,?,?)");
    if($sql->execute($datos)){
        return true;
    }
    return false;
}

function usuarioExiste($usuario, $con){
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    ($sql->execute([$usuario]));
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}

function emailExiste($email, $con){
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    ($sql->execute([$email]));
    if($sql->fetchColumn() > 0){
        return true;
    }
    return false;
}



function mostrarMensajes(array $errors){
    if(count($errors) > 0){
        echo '<div class="aviso" role="alert"><ul>';
        foreach($errors as $error){
            echo '<li>' . htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') . '</li>';

        }
        echo '</ul>';
        echo '</div>';
    }
}

function login($usuario, $password, $con, $proceso){
    $sql = $con->prepare("SELECT id, usuario, password, id_cliente FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if($row = $sql->fetch(PDO::FETCH_ASSOC)){
        if(esActivo($usuario, $con)){
            if(password_verify($password, $row['password'])){
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                $_SESSION['user_cliente'] = $row['id_cliente'];
                if($proceso == 'pago'){
                    header("Location: realizarpago.php");
                }else{
                    header("Location: index.php");
                }
                
                exit;
            }
        }else{
            return 'El usuario no ha sido activado.';
        }

    }
    return 'El usuario y/o contraseña son incorrectos.';
}

function esActivo($usuario, $con){
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row['activacion'] == 1){
        return true;
    }
    return false;
}
?>