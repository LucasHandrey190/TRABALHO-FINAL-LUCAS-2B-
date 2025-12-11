<?php
session_start();
include('verifica_login.php');
include('conexao.php');

$aluno = null;
$edicao_sucesso = false;
$erro_edicao = null; 

// ------------------------------------------
// 1. PROCESSAR EDIÇÃO (RECEBE DADOS VIA POST)
// ------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string(get_conexao(), $_POST['id']);
    $nome_completo = mysqli_real_escape_string(get_conexao(), $_POST['nome_completo']);
    $data_nascimento = mysqli_real_escape_string(get_conexao(), $_POST['data_nascimento']);
    $rua = mysqli_real_escape_string(get_conexao(), $_POST['rua']);
    $numero = mysqli_real_escape_string(get_conexao(), $_POST['numero']);
    $bairro = mysqli_real_escape_string(get_conexao(), $_POST['bairro']);
    $cep = mysqli_real_escape_string(get_conexao(), $_POST['cep']);
    $nome_responsavel = mysqli_real_escape_string(get_conexao(), $_POST['nome_responsavel']);
    $tipo = mysqli_real_escape_string(get_conexao(), $_POST['tipo']);
    $curso = mysqli_real_escape_string(get_conexao(), $_POST['curso']);
    
    // Query de UPDATE com APENAS as colunas existentes
    $queryUpdate = "UPDATE alunos_cadastrados SET 
                    nome_completo = '$nome_completo', 
                    data_nascimento = '$data_nascimento', 
                    rua = '$rua', 
                    numero = '$numero', 
                    bairro = '$bairro', 
                    cep = '$cep', 
                    nome_responsavel = '$nome_responsavel', 
                    tipo = '$tipo', 
                    curso = '$curso'
                    WHERE id = '$id'";

    if (mysqli_query(get_conexao(), $queryUpdate)) {
        $_SESSION['msg'] = "Aluno **$nome_completo** (ID: $id) atualizado com sucesso!";
        header('Location: lista_alunos.php');
        exit();
    } else {
        $erro_edicao = "Erro ao atualizar: " . mysqli_error(get_conexao());
        $_GET['id'] = $id; 
    }
} 

// ------------------------------------------
// 2. CARREGAR DADOS (RECEBE ID VIA GET)
// ------------------------------------------
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_edicao = mysqli_real_escape_string(get_conexao(), $_GET['id']);
    
    $querySelect = "SELECT * FROM alunos_cadastrados WHERE id = '$id_edicao' LIMIT 1";
    $resultSelect = mysqli_query(get_conexao(), $querySelect);

    if (mysqli_num_rows($resultSelect) == 1) {
        $aluno = mysqli_fetch_assoc($resultSelect);
    } else {
        $_SESSION['erro_exclusao'] = "Aluno com ID $id_edicao não encontrado.";
        header('Location: lista_alunos.php');
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: lista_alunos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema IMF - Editar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Estilos de Tema e Formulário (Mantidos) */
        body { transition: background-color 0.3s, color 0.3s; }
        [data-bs-theme="dark"] body { background-color: #121212; color: #E0E0E0; }
        [data-bs-theme="dark"] .card { background-color: #1F1F1F; color: #E0E0E0; border: 1px solid #333; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select { background-color: #2b2b2b; color: #E0E0E0; border-color: #444; }
        .bg-imf-primary { background-color: #A020F0 !important; }
        .btn-imf-primary { background-color: #A020F0; border-color: #A020F0; color: white; }
        .btn-imf-primary:hover { background-color: #8A2BE2; border-color: #8A2BE2; color: white; }
        .card-form { border-radius: 15px; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }

        /* --- Estilos do Menu Flutuante --- */
        #menu-flutuante {
            position: fixed; top: 30%; left: 0; width: 50px; height: auto;
            background-color: var(--bs-body-bg); border-radius: 0 10px 10px 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: width 0.4s ease-in-out, background-color 0.3s, box-shadow 0.3s;
            z-index: 1030; padding: 10px 0; overflow: hidden;
            border: 1px solid var(--bs-border-color); cursor: pointer;
        }
        #menu-flutuante:hover { width: 250px; box-shadow: 8px 0 20px rgba(0, 0, 0, 0.2); }
        .menu-icon { text-align: center; padding: 10px 0; color: var(--bs-primary); }
        .menu-content { padding: 10px 15px; white-space: nowrap; opacity: 0; transition: opacity 0.2s 0.2s; }
        #menu-flutuante:hover .menu-content { opacity: 1; }
        .menu-content a { color: var(--bs-info) !important; }
    </style>
</head>
<body>

<div id="menu-flutuante">
    <div class="menu-icon" title="Menu de Navegação Rápida">
        <i class="bi bi-list-nested fs-3"></i>
    </div>
    <div class="menu-content">
        <h6 class="mb-3 text-primary">Navegação Rápida</h6>
        <ul class="list-unstyled small">
            <li class="mb-2"><a href="painel.php" class="text-decoration-none d-block text-primary"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
            <li class="mb-2"><a href="lista_alunos.php" class="text-decoration-none d-block"><i class="bi bi-gear-fill me-2"></i> Gerenciar Alunos</a></li>
            <li class="mb-2"><a href="formulario.php" class="text-decoration-none d-block"><i class="bi bi-pencil-square me-2"></i> Novo Cadastro</a></li>
            <li class="mb-3 border-top pt-3 mt-3"><a href="logout.php" class="text-decoration-none d-block text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema</a></li>
        </ul>
        <p class="small mt-3 text-end text-muted">Passe o mouse para esconder.</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg mb-4 bg-imf-primary" data-bs-theme="dark"> 
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="painel.php">SISTEMA IMF - EDIÇÃO</a>
    <div class="ms-auto">
        <button class="btn btn-light ms-3" id="theme-toggle" type="button" title="Mudar Tema"><i class="bi bi-moon-fill"></i></button>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 card-form">
                <h3 class="mb-4 text-center"><i class="bi bi-person-fill me-2"></i> Editar Aluno (ID: <?= $aluno['id'] ?>)</h3>

                <?php if (isset($erro_edicao)): ?>
                    <div class="alert alert-danger" role="alert"><?= $erro_edicao ?></div>
                <?php endif; ?>

                <?php if ($aluno): ?>
                    <form action="editar_aluno.php" method="POST">
                        <input type="hidden" name="id" value="<?= $aluno['id'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nome_completo" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($aluno['nome_completo']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="data_nascimento" class="form-label">Data de Nasc.</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($aluno['data_nascimento']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="rua" class="form-label">Rua</label>
                                <input type="text" class="form-control" id="rua" name="rua" value="<?= htmlspecialchars($aluno['rua']) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($aluno['numero']) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="<?= htmlspecialchars($aluno['bairro']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($aluno['cep']) ?>">
                            </div>
                            <div class="col-md-8">
                                <label for="nome_responsavel" class="form-label">Nome do Responsável</label>
                                <input type="text" class="form-control" id="nome_responsavel" name="nome_responsavel" value="<?= htmlspecialchars($aluno['nome_responsavel']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tipo" class="form-label">Tipo Responsável</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="Mãe" <?= ($aluno['tipo'] == 'Mãe') ? 'selected' : '' ?>>Mãe</option>
                                    <option value="Pai" <?= ($aluno['tipo'] == 'Pai') ? 'selected' : '' ?>>Pai</option>
                                    <option value="Outro" <?= ($aluno['tipo'] == 'Outro') ? 'selected' : '' ?>>Outro</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="curso" class="form-label">Curso</label>
                                <input type="text" class="form-control" id="curso" name="curso" value="<?= htmlspecialchars($aluno['curso']) ?>" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="lista_alunos.php" class="btn btn-secondary me-md-2"><i class="bi bi-x-circle me-1"></i> Cancelar</a>
                            <button type="submit" class="btn btn-imf-primary"><i class="bi bi-save me-1"></i> Salvar Alterações</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Lógica de Alternância de Tema
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;
        const iconElement = toggleButton.querySelector('i');
        
        function updateTheme(theme) {
            htmlElement.setAttribute('data-bs-theme', theme);
            iconElement.classList.toggle('bi-moon-fill', theme === 'light');
            iconElement.classList.toggle('bi-sun-fill', theme === 'dark');
        }

        function toggleTheme() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            updateTheme(newTheme);
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        updateTheme(savedTheme);

        toggleButton.addEventListener('click', toggleTheme);
    });
</script>

</body>
</html>