<?php
session_start();
include('verifica_login.php');
include('conexao.php');

// A função get_conexao() é importada APENAS de conexao.php.

// ========== CARDS: Totalizadores ==========
$sqlTotal = "SELECT COUNT(*) AS total FROM alunos_cadastrados";
$totalAlunos = mysqli_fetch_assoc(mysqli_query(get_conexao(), $sqlTotal))['total'];

$sqlTotalCursos = "SELECT COUNT(DISTINCT curso) AS total FROM alunos_cadastrados";
$totalCursos = mysqli_fetch_assoc(mysqli_query(get_conexao(), $sqlTotalCursos))['total'];

$sqlTotalResponsaveis = "SELECT COUNT(DISTINCT tipo) AS total FROM alunos_cadastrados";
$totalResponsaveis = mysqli_fetch_assoc(mysqli_query(get_conexao(), $sqlTotalResponsaveis))['total'];

$sqlTotalBairros = "SELECT COUNT(DISTINCT bairro) AS total FROM alunos_cadastrados";
$totalBairros = mysqli_fetch_assoc(mysqli_query(get_conexao(), $sqlTotalBairros))['total'];


// ========== ÚLTIMOS INSCRITOS (Dados para Rolagem) ==========
$sqlUltimos = "SELECT nome_completo FROM alunos_cadastrados ORDER BY id DESC LIMIT 10"; 
$resultUltimos = mysqli_query(get_conexao(), $sqlUltimos);
$ultimosInscritos = [];
while ($row = mysqli_fetch_assoc($resultUltimos)) {
    $ultimosInscritos[] = htmlspecialchars($row['nome_completo']);
}


// ========== GRÁFICOS (Dados) ==========
// G1: Alunos por Curso (Barras Verticais)
$sqlCursos = "SELECT curso, COUNT(*) AS total FROM alunos_cadastrados GROUP BY curso ORDER BY total DESC";
$resultCursos = mysqli_query(get_conexao(), $sqlCursos);
$cursos = [];
$totaisCursos = [];
while ($row = mysqli_fetch_assoc($resultCursos)) {
    $cursos[] = $row['curso'];
    $totaisCursos[] = $row['total'];
}

// G2: Tipos de Responsáveis (Gráfico Rosca/Donut)
$sqlTipos = "SELECT tipo, COUNT(*) AS total FROM alunos_cadastrados GROUP BY tipo";
$resultTipos = mysqli_query(get_conexao(), $sqlTipos);
$tipos = [];
$totaisTipos = [];
while ($row = mysqli_fetch_assoc($resultTipos)) {
    $tipos[] = $row['tipo'];
    $totaisTipos[] = $row['total'];
}

// G3: Alunos por Bairro (Gráfico Pizza)
$sqlBairros = "SELECT bairro, COUNT(*) AS total FROM alunos_cadastrados GROUP BY bairro ORDER BY total DESC LIMIT 5";
$resultBairros = mysqli_query(get_conexao(), $sqlBairros);
$bairros = [];
$totaisBairros = [];
while ($row = mysqli_fetch_assoc($resultBairros)) {
    $bairros[] = $row['bairro'];
    $totaisBairros[] = $row['total'];
}

// G4: Idade dos Alunos (Gráfico de Linha) - Usando data_nascimento
$sqlIdades = "SELECT data_nascimento FROM alunos_cadastrados";
$resultIdades = mysqli_query(get_conexao(), $sqlIdades);
$idades = [];
while ($row = mysqli_fetch_assoc($resultIdades)) {
    // Cálculo da idade
    $idade = date_diff(date_create($row['data_nascimento']), date_create('today'))->y;
    $idades[] = $idade;
}
$contadorIdades = array_count_values($idades);
$labelsIdades = array_keys($contadorIdades);
$totaisIdades = array_values($contadorIdades);

// Passamos todos os nomes para o JavaScript para o efeito de rolagem
$nomesParaRolagem = json_encode($ultimosInscritos);
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema IMF - Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* --- Estilos Globais e do Tema --- */
        body { transition: background-color 0.3s, color 0.3s; }
        [data-bs-theme="dark"] body { background-color: #121212; color: #E0E0E0; }
        [data-bs-theme="dark"] .card, [data-bs-theme="dark"] .list-group-item { background-color: #1F1F1F; color: #E0E0E0; border: 1px solid #333; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); }

        /* --- Cards de Totalizadores --- */
        .card-total { border-radius: 20px; box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3); transition: transform 0.3s, box-shadow 0.3s; height: 100%; }
        .card-total:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4); }
        [data-bs-theme="dark"] #card-total-1 { background: linear-gradient(135deg, #A020F0, #8A2BE2); border: none; }
        [data-bs-theme="light"] #card-total-1 { background: linear-gradient(135deg, #0d6efd, #0B5ED7); color: #FFFFFF; }
        /* Outras cores de cards (Mantidas) */
        [data-bs-theme="dark"] #card-total-2 { background: linear-gradient(135deg, #00CED1, #008B8B); border: none; }
        [data-bs-theme="dark"] #card-total-3 { background: linear-gradient(135deg, #FF8C00, #FF4500); border: none; }
        [data-bs-theme="dark"] #card-total-4 { background: linear-gradient(135deg, #3CB371, #2E8B57); border: none; }
        [data-bs-theme="light"] #card-total-2 { background: linear-gradient(135deg, #FF69B4, #C71585); color: #FFFFFF; }
        [data-bs-theme="light"] #card-total-3 { background: linear-gradient(135deg, #FFD700, #DAA520); color: #000000; }
        [data-bs-theme="light"] #card-total-4 { background: linear-gradient(135deg, #DC3545, #C82333); color: #FFFFFF; }
        .card-total h5, .card-total .contador { color: inherit !important; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4); }
        .contador { font-size: 48px; font-weight: 900; }
        
        /* --- Menu Flutuante --- */
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

        /* --- Lista de Inscritos (Rolagem) --- */
        #lista-inscritos-container { display: flex; justify-content: center; align-items: center; height: 30px; overflow: hidden; font-size: 1.1em; font-weight: bold; color: var(--bs-primary); }
        .nome-inscrito { position: absolute; opacity: 0; transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out; transform: translateY(100%); }
        .nome-inscrito.active { opacity: 1; transform: translateY(0); }
        .nome-inscrito.leaving { opacity: 0; transform: translateY(-100%); }

        /* --- Estilos de Gráfico --- */
        .chart-container { position: relative; height: 350px; width: 100%; }
        .card-grafico { border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .bg-imf-primary { background-color: #A020F0 !important; }

    </style>
</head>
<body>

<div id="menu-flutuante">
    <div class="menu-icon" title="Menu de Configurações e Navegação">
        <i class="bi bi-list-nested fs-3"></i>
    </div>
    <div class="menu-content">
        <h6 class="mb-3 text-primary">Navegação Rápida</h6>
        <ul class="list-unstyled small">
            <li class="mb-2"><a href="painel.php" class="text-decoration-none d-block text-primary"><i class="bi bi-house-door-fill me-2"></i> Dashboard (Atual)</a></li>
            <li class="mb-2"><a href="lista_alunos.php" class="text-decoration-none d-block"><i class="bi bi-gear-fill me-2"></i> Gerenciar Alunos</a></li>
            <li class="mb-2"><a href="formulario.php" class="text-decoration-none d-block"><i class="bi bi-pencil-square me-2"></i> Novo Cadastro</a></li>
            <li class="mb-3 border-top pt-3 mt-3"><a href="logout.php" class="text-decoration-none d-block text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sair do Sistema</a></li>
        </ul>
        <p class="small mt-3 text-end text-muted">Passe o mouse para esconder.</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg mb-4 bg-imf-primary" data-bs-theme="dark"> 
  <div class="container-fluid">
    <a class="navbar-brand text-white fw-bold" href="#">SISTEMA IMF</a>
    <div class="ms-auto">
         <button class="btn btn-light" id="theme-toggle" type="button" title="Mudar Tema">
            <i class="bi bi-moon-fill"></i> 
        </button>
    </div>
  </div>
</nav>

<div class="container">
    <div class="alert alert-info text-center" role="alert">
        Bem-vindo, <strong><?= $_SESSION['email'] ?></strong>! Este é o seu painel de gerenciamento.
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card p-3 card-grafico">
                <h5 class="mb-3 text-center">✨ Último(s) Aluno(s) Cadastrado(s)</h5>
                <div id="lista-inscritos-container">
                    <?php if (empty($ultimosInscritos)) : ?>
                        <div class="nome-inscrito active text-muted">Nenhum aluno cadastrado recentemente.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-white text-center mb-5 g-4">
        <div class="col-md-3">
            <div class="card card-total p-3" id="card-total-1">
                <h5>Total de Alunos</h5>
                <div class="contador"><?= $totalAlunos ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-total p-3" id="card-total-2">
                <h5>Cursos Cadastrados</h5>
                <div class="contador"><?= $totalCursos ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-total p-3" id="card-total-3">
                <h5>Tipos de Responsável</h5>
                <div class="contador"><?= $totalResponsaveis ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-total p-3" id="card-total-4">
                <h5>Bairros</h5>
                <div class="contador"><?= $totalBairros ?></div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card p-3 card-grafico">
                <h5 class="text-center mb-3">🎓 Alunos por Curso (Colunas)</h5>
                <div class="chart-container"><canvas id="g1"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 card-grafico">
                <h5 class="text-center mb-3">👤 Tipos de Responsável (Rosca)</h5>
                <div class="chart-container"><canvas id="g2"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 card-grafico">
                <h5 class="text-center mb-3">📍 Principais Bairros (Pizza)</h5>
                <div class="chart-container"><canvas id="g3"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 card-grafico">
                <h5 class="text-center mb-3">🎂 Frequência de Idades (Linha)</h5>
                <div class="chart-container"><canvas id="g4"></canvas></div>
            </div>
        </div>
    </div>
    
    <p class="text-center mt-4 text-secondary"><small>Dashboard de Análise de Dados IMF.</small></p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ----- Dados PHP → JS (Gráficos) -----
    const cursos = <?= json_encode($cursos) ?>;
    const totaisCursos = <?= json_encode($totaisCursos) ?>;
    const tipos = <?= json_encode($tipos) ?>;
    const totaisTipos = <?= json_encode($totaisTipos) ?>;
    const bairros = <?= json_encode($bairros) ?>;
    const totaisBairros = <?= json_encode($totaisBairros) ?>;
    const labelsIdades = <?= json_encode($labelsIdades) ?>;
    const totaisIdades = <?= json_encode($totaisIdades) ?>;
    const nomesUltimosInscritos = <?= $nomesParaRolagem ?>;

    const PALETA_DARK = ['#A020F0', '#00CED1', '#FF8C00', '#3CB371', '#BA55D3', '#FF4500'];
    const COR_DESTAQUE_DARK = '#A020F0'; 
    const BORDA_DESTAQUE_DARK = '#E0E0E0'; 
    const PALETA_LIGHT = ['#0d6efd', '#FF69B4', '#FFD700', '#DC3545', '#4169E1', '#C71585'];
    const COR_DESTAQUE_LIGHT = '#0d6efd'; 
    const BORDA_DESTAQUE_LIGHT = '#FFFFFF'; 

    let charts = {};

    function getChartOptions(theme) {
        const fontColor = theme === 'dark' ? '#E0E0E0' : '#333333';
        const gridColor = theme === 'dark' ? '#33333355' : '#EAEAEA'; 
        return {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: fontColor }}},
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: fontColor }},
                y: { grid: { color: gridColor }, ticks: { color: fontColor, beginAtZero: true }}
            }
        };
    }

    function renderCharts(theme) {
        Object.values(charts).forEach(chart => { if (chart) chart.destroy(); });
        charts = {};

        const options = getChartOptions(theme);
        const segmentoColors = theme === 'dark' ? PALETA_DARK : PALETA_LIGHT;
        const mainColor = theme === 'dark' ? COR_DESTAQUE_DARK : COR_DESTAQUE_LIGHT;
        const borderColor = theme === 'dark' ? BORDA_DESTAQUE_DARK : BORDA_DESTAQUE_LIGHT;
        const cardBgColor = theme === 'dark' ? '#1F1F1F' : '#FFFFFF';

        charts.g1 = new Chart(document.getElementById('g1'), {
            type: 'bar',
            data: { labels: cursos, datasets: [{ label: "Total de Alunos", data: totaisCursos, backgroundColor: mainColor, borderColor: mainColor, borderWidth: 1 }] },
            options: options
        });

        charts.g2 = new Chart(document.getElementById('g2'), {
            type: 'doughnut',
            data: { labels: tipos, datasets: [{ data: totaisTipos, backgroundColor: segmentoColors.slice(0, tipos.length), borderColor: cardBgColor, borderWidth: 2 }] },
            options: {...options, scales: {x: {display: false}, y: {display: false}}}
        });

        charts.g3 = new Chart(document.getElementById('g3'), {
            type: 'pie',
            data: { labels: bairros, datasets: [{ data: totaisBairros, backgroundColor: segmentoColors.slice(0, bairros.length), borderColor: cardBgColor, borderWidth: 2 }] },
            options: {...options, scales: {x: {display: false}, y: {display: false}}}
        });

        charts.g4 = new Chart(document.getElementById('g4'), {
            type: 'line',
            data: { labels: labelsIdades, datasets: [{ 
                label: "Frequência de Idades", 
                data: totaisIdades,
                backgroundColor: theme === 'dark' ? 'rgba(160, 32, 240, 0.2)' : 'rgba(13, 110, 253, 0.2)',
                borderColor: mainColor, pointBackgroundColor: borderColor, borderWidth: 2, tension: 0.4, fill: true
            }]},
            options: options
        });
    }

    // Lógica de Rolagem Dinâmica de Nomes
    let currentIndex = 0;
    const container = document.getElementById('lista-inscritos-container');

    function animateNameChange() {
        if (nomesUltimosInscritos.length === 0) return;

        const currentNameElement = container.querySelector('.nome-inscrito.active');
        if (currentNameElement) {
            currentNameElement.classList.remove('active');
            currentNameElement.classList.add('leaving');
            
            setTimeout(() => {
                currentNameElement.remove();
            }, 500);
        }

        currentIndex = (currentIndex + 1) % nomesUltimosInscritos.length;
        const nextName = nomesUltimosInscritos[currentIndex];

        const newNameElement = document.createElement('div');
        newNameElement.classList.add('nome-inscrito');
        newNameElement.innerHTML = `<i class="bi bi-person-badge-fill me-2 text-primary"></i>${nextName}`;
        
        container.appendChild(newNameElement);
        void newNameElement.offsetWidth; 
        newNameElement.classList.add('active');
    }

    // Lógica de Inicialização e Tema
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;
        const iconElement = toggleButton.querySelector('i');
        
        function toggleTheme() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            iconElement.classList.toggle('bi-moon-fill', newTheme === 'light');
            iconElement.classList.toggle('bi-sun-fill', newTheme === 'dark');
            renderCharts(newTheme);
        }

        const savedTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-bs-theme', savedTheme);
        iconElement.classList.toggle('bi-moon-fill', savedTheme === 'light');
        iconElement.classList.toggle('bi-sun-fill', savedTheme === 'dark');
        renderCharts(savedTheme);

        toggleButton.addEventListener('click', toggleTheme);
        
        if (nomesUltimosInscritos.length > 0) {
             animateNameChange(); 
             setInterval(animateNameChange, 3500); 
        }
    });
</script>

</body>
</html>