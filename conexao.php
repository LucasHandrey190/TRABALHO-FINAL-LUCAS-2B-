<?php

define('DB_HOST', 'localhost'); 
define('DB_USUARIO', 'root');
define('DB_SENHA', ''); 
define('DB_NOME', 'loginl');

function conectar_banco() {
    $conexao = mysqli_connect(DB_HOST, DB_USUARIO, DB_SENHA, DB_NOME);

    if (mysqli_connect_errno()) {
        die("Falha na conexão com o MySQL: " . mysqli_connect_error());
    }

    mysqli_set_charset($conexao, "utf8");

    return $conexao;
}

$conexao = conectar_banco();

function get_conexao() {
    return $GLOBALS['conexao'];
}

?>