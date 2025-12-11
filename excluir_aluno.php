<?php
session_start();
include('verifica_login.php');
include('conexao.php');

// A função get_conexao() é importada APENAS de conexao.php.

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro_exclusao'] = "ID do aluno não fornecido ou inválido.";
    header('Location: lista_alunos.php');
    exit();
}

$id = mysqli_real_escape_string(get_conexao(), $_GET['id']);

// Query para exclusão
$query = "DELETE FROM alunos_cadastrados WHERE id = '$id'";

if (mysqli_query(get_conexao(), $query)) {
    // Exclusão bem-sucedida
    $_SESSION['msg'] = "Aluno (ID: $id) excluído com sucesso!";
} else {
    // Erro na exclusão
    $_SESSION['erro_exclusao'] = "Erro ao excluir aluno (ID: $id): " . mysqli_error(get_conexao());
}

header('Location: lista_alunos.php');
exit();
?>