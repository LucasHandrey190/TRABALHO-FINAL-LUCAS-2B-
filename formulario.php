<?php
session_start();
include('verifica_login.php');
// Não precisamos incluir conexao.php aqui, pois este é apenas o formulário de entrada de dados.
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema IMF - Cadastro de Aluno</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Estilos de Tema */
        body { transition: background-color 0.3s, color 0.3s; }
        [data-bs-theme="dark"] body { background-color: #121212; color: #E0E0E0; }
        [data-bs-theme="dark"] .card { background-color: #1F1F1F; color: #E0E0E0; border: 1px solid #333; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }
        [data-bs-theme="dark"] .form-control, [data-bs-theme="dark"] .form-select { background-color: #2b2b2b; color: #E0E0E0; border-color: #444; }
        
        /* Cor Institucional IMF */
        .bg-imf-primary { background-color: #A020F0 !important; }
        .btn-imf-primary { background-color: #A020F0; border-color: #A020F0; color: white; }
        .btn-imf-primary:hover { background-color: #8A2BE2; border-color: #8A2BE2; color: white; }

        .card-form { border-radius: 15px; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }
        .card-header-imf { 
            background-color: #A020F0; 
            color: white; 
            border-radius: 15px 15px 0 0 !important; 
            padding: 1.5rem;
        }

        /* Menu Flutuante (Replicado de outros arquivos) */
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
            <li class="mb-2"><a href="lista_alunos.php" class="text-decoration-none d-block"><i class="bi bi-gear-fill me-2"></i> Gerenciar Alunos</a></li>
            <li class="mb-2"><a href="formulario.php" class="text-decoration-none d-block text-primary"><i class="bi bi-pencil-square me-2"></i> Novo Cadastro (Atual)</a></li>
            <li class="mb-3 border-top pt-3 mt-3"><a href="logout.php" class="text-decoration-none d-block text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema</a></li>
        </ul>
        <p class="small mt-3 text-end text-muted">Passe o mouse para esconder.</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg mb-4 bg-imf-primary" data-bs-theme="dark"> 
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="painel.php">SISTEMA IMF - CADASTRO</a>
    <div class="ms-auto">
        <button class="btn btn-light ms-3" id="theme-toggle" type="button" title="Mudar Tema"><i class="bi bi-moon-fill"></i></button>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg card-form">
                <div class="card-header-imf text-center">
                    <h1 class="fs-4 card-title fw-bold m-0"><i class="bi bi-person-plus-fill me-2"></i> Formulário de Cadastro de Aluno</h1>
                </div>
                <div class="card-body p-5">

                    <?php if (isset($_SESSION['cadastro_sucesso'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?= $_SESSION['cadastro_sucesso']; unset($_SESSION['cadastro_sucesso']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['cadastro_erro'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['cadastro_erro']; unset($_SESSION['cadastro_erro']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="salvar_cadastro.php" method="POST">

                        <h5 class="mb-3 text-primary"><i class="bi bi-person-badge me-2"></i> Dados Pessoais</h5>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="nome_completo" class="form-label">Nome Completo</label>
                                <input type="text" name="nome_completo" id="nome_completo" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="data_nascimento" class="form-label">Data de Nasc.</label>
                                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" required>
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3 text-primary"><i class="bi bi-geo-alt-fill me-2"></i> Endereço</h5>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="rua" class="form-label">Rua</label>
                                <input type="text" name="rua" id="rua" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" name="numero" id="numero" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control" required placeholder="00000-000">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" name="bairro" id="bairro" class="form-control" required>
                        </div>
                        
                        <h5 class="mt-4 mb-3 text-primary"><i class="bi bi-people-fill me-2"></i> Responsável e Curso</h5>

                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="nome_responsavel" class="form-label">Nome do Responsável</label>
                                <input type="text" name="nome_responsavel" id="nome_responsavel" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="tipo" class="form-label">Grau de Parentesco</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="">Selecione</option>
                                    <option value="Pai">Pai</option>
                                    <option value="Mãe">Mãe</option>
                                    <option value="Avô">Avô</option>
                                    <option value="Avó">Avó</option>
                                    <option value="Tio(a)">Tio(a)</option>
                                    <option value="Irmão(ã)">Irmão(ã)</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="curso" class="form-label">Curso Desejado</label>
                            <select name="curso" id="curso" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                                <option value="Informática">Informática</option>
                                <option value="Enfermagem">Enfermagem</option>
                                <option value="Administração">Administração</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="painel.php" class="btn btn-secondary me-md-2"><i class="bi bi-x-circle me-1"></i> Cancelar</a>
                            <button type="submit" class="btn btn-imf-primary"><i class="bi bi-send me-1"></i> Enviar Cadastro</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Lógica de Alternância de Tema (Replicada de outros arquivos)
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