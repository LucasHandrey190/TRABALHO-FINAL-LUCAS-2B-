<?php
session_start();
include('verifica_login.php');
include('conexao.php');

// Query corrigida para buscar APENAS as colunas existentes na tabela
$sqlAlunos = "SELECT id, nome_completo, data_nascimento, curso, bairro, nome_responsavel, tipo FROM alunos_cadastrados ORDER BY nome_completo ASC";
$resultAlunos = mysqli_query(get_conexao(), $sqlAlunos); 
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema IMF - Gerenciar Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Estilos de Tema */
        body { transition: background-color 0.3s, color 0.3s; }
        [data-bs-theme="dark"] body { background-color: #121212; color: #E0E0E0; }
        [data-bs-theme="dark"] .card, [data-bs-theme="dark"] .table { 
            background-color: #1F1F1F; color: #E0E0E0; border: 1px solid #333; 
        }
        /* Ajuste de cor de fundo para linhas ímpares no modo escuro */
        [data-bs-theme="dark"] .table-striped>tbody>tr:nth-of-type(odd)>* { --bs-table-bg-type: #2c2c2c; }
        .bg-imf-primary { background-color: #A020F0 !important; }
        .btn-imf-primary { background-color: #A020F0; border-color: #A020F0; color: white; }
        .btn-imf-primary:hover { background-color: #8A2BE2; border-color: #8A2BE2; color: white; }
        .card-list { border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        
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
        /* Para destacar o link atual */
        .menu-content .active-link { font-weight: bold; color: #A020F0 !important; }

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
            <li class="mb-2"><a href="painel.php" class="text-decoration-none d-block"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
            <li class="mb-2"><a href="lista_alunos.php" class="text-decoration-none d-block active-link"><i class="bi bi-gear-fill me-2"></i> Gerenciar Alunos (Atual)</a></li>
            <li class="mb-2"><a href="formulario.php" class="text-decoration-none d-block"><i class="bi bi-pencil-square me-2"></i> Novo Cadastro</a></li>
            <li class="mb-3 border-top pt-3 mt-3"><a href="logout.php" class="text-decoration-none d-block text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema</a></li>
        </ul>
        <p class="small mt-3 text-end text-muted">Passe o mouse para esconder.</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg mb-4 bg-imf-primary" data-bs-theme="dark"> 
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="painel.php">SISTEMA IMF</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link text-white" href="painel.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active text-white" aria-current="page" href="lista_alunos.php">Gerenciar Alunos</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="formulario.php">Novo Cadastro</a></li>
        </ul>
    </div>
    <button class="btn btn-light ms-3" id="theme-toggle" type="button" title="Mudar Tema">
        <i class="bi bi-moon-fill"></i> 
    </button>
  </div>
</nav>

<div class="container">
    <div class="card p-4 card-list">
        <div class="d-flex justify-content-between align-items-center mb-4">
             <h3 class="m-0"><i class="bi bi-people-fill me-2 text-imf-primary"></i> Gerenciar Alunos</h3>
             <a href="formulario.php" class="btn btn-imf-primary"><i class="bi bi-person-plus-fill me-2"></i> Novo Cadastro</a>
        </div>
        
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-success" role="alert"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['erro_exclusao'])): ?>
            <div class="alert alert-danger" role="alert"><?= $_SESSION['erro_exclusao']; unset($_SESSION['erro_exclusao']); ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr class="table-imf-primary">
                        <th scope="col"># ID</th>
                        <th scope="col">Nome Completo</th>
                        <th scope="col">Data Nasc.</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Bairro</th>
                        <th scope="col">Resp.</th>
                        <th scope="col" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($resultAlunos) > 0): ?>
                        <?php while($aluno = mysqli_fetch_assoc($resultAlunos)): ?>
                            <tr>
                                <th scope="row"><?= $aluno['id'] ?></th>
                                <td><?= htmlspecialchars($aluno['nome_completo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?></td>
                                <td><?= htmlspecialchars($aluno['curso']) ?></td>
                                <td><?= htmlspecialchars($aluno['bairro']) ?></td>
                                <td><?= htmlspecialchars($aluno['tipo']) ?></td>
                                <td class="text-center" style="min-width: 150px;">
                                    <a href="editar_aluno.php?id=<?= $aluno['id'] ?>" class="btn btn-sm btn-primary me-2" title="Editar">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $aluno['id'] ?>, '<?= htmlspecialchars($aluno['nome_completo']) ?>')" title="Excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Nenhum aluno cadastrado no sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(id, nome) {
        if (confirm(`Tem certeza que deseja EXCLUIR o aluno(a) ${nome} (ID: ${id})? Esta ação é irreversível.`)) {
            window.location.href = `excluir_aluno.php?id=${id}`;
        }
    }
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