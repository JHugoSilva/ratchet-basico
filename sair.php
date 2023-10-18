<?php
session_start();

unset($_SESSION['usuario_id']);
unset($_SESSION['nome']);
$_SESSION['msg'] = "<p style='color:green;'>Deslogado com sucesso</p>";
header("Location:index.php");
exit;