<?php

use Api\Websocket\DbConnection;

session_start();

ob_start();

//echo password_hash('123', PASSWORD_DEFAULT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celke - Chat</title>
</head>
<body>
    <h1>Acessar Chat</h1>
    <?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if (!empty($dados['acessar'])) {
        $_SESSION['usuario'] = $dados['usuario'];
        require_once './api/src/DbConnection.php';
        $dbConn = new DbConnection();
        $rs = $dbConn->select('usuarios', "usuario = '{$dados['usuario']}'")->fetchObject();
        if(!empty($rs) && $rs) {
            if (password_verify($dados['senha_usuario'], $rs->senha_usuario)) {
                $_SESSION['usuario_id'] = $rs->id;
                $_SESSION['nome'] = $rs->nome;
                header("Location:chat.php");
                exit;
            } else {
                $_SESSION['msg'] = "<p style='color:#F00;'>Erro: Usuário ou senha inválida</p>";
                header("Location:index.php");
                exit;
            }
        } else {
            $_SESSION['msg'] = "<p style='color:#F00;'>Erro: Usuário ou senha inválida</p>";
            header("Location:index.php");
            exit;
        }
       
    }

    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
    <form action="" method="post">
        <label for="">Nome: </label>
        <input type="text" name="usuario" placeholder="Digite o nome"> <br><br>
        <label for="">Senha: </label>
        <input type="password" name="senha_usuario" placeholder="******"> <br><br>
        <input type="submit" name="acessar" value="Acessar"> <br><br>
    </form>
</body>
</html>