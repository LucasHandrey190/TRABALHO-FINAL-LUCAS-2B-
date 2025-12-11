<?php
session_start();
include('conexao.php'); // conexão ao banco

// Verifica se todos os campos foram enviados
if (
    empty($_POST['nome_completo']) || empty($_POST['data_nascimento']) ||
    empty($_POST['rua']) || empty($_POST['numero']) ||
    empty($_POST['bairro']) || empty($_POST['cep']) ||
    empty($_POST['responsavel']) || empty($_POST['tipo']) ||
    empty($_POST['curso'])
) {
    $_SESSION['mensagem'] = "
        <div class='alert alert-warning alert-dismissible fxade show' role='alert'>
            Preencha todos os campos obrigatórios.
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>
    ";
    header('Location: formulario.php');
    exit();
}

// Sanitização
$nome         = mysqli_real_escape_string($conexao, trim($_POST['nome_completo']));
$data         = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
$rua          = mysqli_real_escape_string($conexao, trim($_POST['rua']));
$numero       = mysqli_real_escape_string($conexao, trim($_POST['numero']));
$bairro       = mysqli_real_escape_string($conexao, trim($_POST['bairro']));
$cep          = mysqli_real_escape_string($conexao, trim($_POST['cep']));
$responsavel  = mysqli_real_escape_string($conexao, trim($_POST['responsavel']));
$tipo         = mysqli_real_escape_string($conexao, trim($_POST['tipo']));
$curso        = mysqli_real_escape_string($conexao, trim($_POST['curso']));

// SQL de inserção
$sql = "INSERT INTO alunos_cadastrados
        (nome_completo, data_nascimento, rua, numero, bairro, cep, nome_responsavel, tipo, curso)
        VALUES 
        ('$nome', '$data', '$rua', '$numero', '$bairro', '$cep', '$responsavel', '$tipo', '$curso')";

// Executa a query e verifica sucesso
if (mysqli_query($conexao, $sql)) {

    // MENSAGEM BOOTSTRAP DE SUCESSO
    $_SESSION['mensagem'] = "
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <h5 class='alert-heading'>Aluno cadastrado com sucesso!</h5>
            <p><strong>Resumo do cadastro:</strong></p>
            <hr>
            Nome: $nome <br>
            Data de nascimento: $data <br>
            Endereço: Rua $rua, Nº $numero, Bairro $bairro, CEP $cep <br>
            Responsável: $responsavel ($tipo) <br>
            Curso desejado: $curso <br>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>
    ";

    header('Location: formulario.php');
    exit();

} else {

    $_SESSION['mensagem'] = "
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            Erro ao cadastrar aluno: " . mysqli_error($conexao) . "
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>
    ";

    header('Location: formulario.php');
    exit();
}
?>
