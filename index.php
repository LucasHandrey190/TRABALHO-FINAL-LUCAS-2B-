<?php
session_start();
// O arquivo login.php espera 'email' e 'senha'.
// A label e o input foram ajustados para usar name="email"
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="Página de login do Sistema IMF">
    <title>Sistema IMF - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* --- Estilos de Tema (Dark/Light) --- */
        body { 
            transition: background-color 0.3s, color 0.3s;
            background-color: #f8f9fa; 
        }
        [data-bs-theme="dark"] body { 
            background-color: #121212; 
            color: #E0E0E0; 
        }
        
        /* Estilo dos Cards em Dark Mode */
        [data-bs-theme="dark"] .card { 
            background-color: #1F1F1F; 
            color: #E0E0E0; 
            border: 1px solid #333; 
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6); 
        }
        
        /* Estilo dos Inputs em Dark Mode */
        [data-bs-theme="dark"] .form-control { 
             background-color: #2b2b2b; 
             color: #E0E0E0; 
             border-color: #444;
        }

        /* --- Estilo do Card de Login --- */
        .card-login {
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            transition: box-shadow 0.3s;
        }

        /* --- Estilo de Destaque (Roxo IMF) --- */
        .btn-imf-primary {
             background-color: #A020F0; 
             border-color: #A020F0;
             color: white;
             font-weight: bold;
             transition: background-color 0.3s, transform 0.2s;
        }
        .btn-imf-primary:hover {
            background-color: #8A2BE2;
            border-color: #8A2BE2;
            transform: translateY(-2px);
            color: white;
        }
        .text-imf-primary {
            color: #A020F0 !important;
        }

        /* --- POSICIONAMENTO DO BOTÃO DE TEMA NO CANTO --- */
        #theme-toggle-container {
            position: absolute;
            top: 20px; /* Distância do topo */
            right: 20px; /* Distância da direita */
            z-index: 1000;
        }
    </style>
</head>

<body>
    
    <div id="theme-toggle-container">
        <button class="btn btn-outline-secondary" id="theme-toggle" type="button" title="Mudar Tema">
            <i class="bi bi-moon-fill"></i> 
        </button>
    </div>

    <section class="h-100 py-5">
        <div class="container h-100">
            <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
                    
                    <div class="text-center my-5">
                        <i class="bi bi-shield-lock-fill text-imf-primary" style="font-size: 4rem;"></i>
                        <h1 class="text-imf-primary mt-2 fw-bolder">IMF</h1>
                    </div>

                    <div class="card shadow-lg card-login">
                        <div class="card-body p-5">
                            <h2 class="fs-4 card-title fw-bold mb-4 text-center">Acesso ao Sistema</h2>
                            
                            <?php if (isset($_SESSION['nao_autenticado'])): ?>
                                <div class="alert alert-danger text-center small p-2" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Usuário ou Senha inválidos.
                                </div>
                            <?php endif; unset($_SESSION['nao_autenticado']); ?>

                            <form action="login.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
                                
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email"><i class="bi bi-person-fill me-1"></i> E-mail/Usuário</label>
                                    <input id="email" type="text" class="form-control" name="email" value="" required autofocus>
                                    <div class="invalid-feedback">O e-mail é obrigatório.</div>
                                </div>

                                <div class="mb-3">
                                    <div class="mb-2 w-100">
                                        <label class="text-muted" for="password"><i class="bi bi-lock-fill me-1"></i> Senha</label>
                                        <a href="forgot.html" class="float-end small text-decoration-none text-imf-primary">
                                            Esqueceu a Senha?
                                        </a>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="senha" required>
                                    <div class="invalid-feedback">A senha é obrigatória.</div>
                                </div>

                                <div class="d-flex align-items-center mt-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                        <label for="remember" class="form-check-label small text-muted">Lembrar de mim</label>
                                    </div>
                                    <button type="submit" class="btn btn-imf-primary ms-auto btn-lg">
                                        Entrar
                                    </button>
                                </div> 
                            </form>
                        </div>
                        <div class="card-footer py-3 border-0">
                            <div class="text-center small">
                                Não tem uma conta? <a href="telacadastro.php" class="text-imf-primary text-decoration-none fw-bold">Criar Cadastro</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted small">
                        &copy; 2024 &mdash; Sistema IMF
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- Lógica de Validação Bootstrap ---
        (function () {
          'use strict'
          var forms = document.querySelectorAll('.needs-validation')
          Array.prototype.slice.call(forms)
            .forEach(function (form) {
              form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                  event.preventDefault()
                  event.stopPropagation()
                }
                form.classList.add('was-validated')
              }, false)
            })
        })()
        
        // --- Lógica de Alternância de Tema ---
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;
            const iconElement = toggleButton.querySelector('i');
            
            function updateTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                iconElement.classList.toggle('bi-moon-fill', theme === 'light');
                iconElement.classList.toggle('bi-sun-fill', theme === 'dark');
                // Altera a cor do botão de tema de acordo com o modo
                toggleButton.classList.toggle('btn-outline-secondary', theme === 'light');
                toggleButton.classList.toggle('btn-outline-light', theme === 'dark');
            }

            function toggleTheme() {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                updateTheme(newTheme);
            }

            // Inicialização (Carrega o tema salvo ou usa 'light')
            const savedTheme = localStorage.getItem('theme') || 'light';
            updateTheme(savedTheme);

            toggleButton.addEventListener('click', toggleTheme);
        });
    </script>
    
</body>
</html>