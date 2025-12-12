# TRABALHO-FINAL-LUCAS-2B-
Dashboard de Alunos: Painel de análise de dados de alunos construído com PHP e MySQL. O sistema processa e visualiza métricas complexas de Idade e Curso (média e dispersão individual) para dar suporte estratégico à gestão educacional. Possui uma interface moderna e Dark Mode.
# 🚀 Dashboard de Alunos: Idade e Curso no Foco! 

**Olá! Seja bem-vindo ao repositório do meu Dashboard Dinâmico de Alunos.**

Este projeto é um painel de controle feito com PHP, MySQL e o Chart.js para transformar dados brutos de cadastro em gráficos maneiros e fáceis de entender.

A grande sacada aqui é que o sistema foca em métricas cruciais como a **Média de Idade por Curso** e a **Dispersão das Idades dos Alunos**, dando uma visão top para a gestão da escola ou da turma.

## 🌟 O que Roda Aqui?

| Tecnologia | Função Principal |
| :--- | :--- |
| **PHP** | O "cérebro" que busca os dados no banco. |
| **MySQL** | Onde todos os dados dos alunos estão guardados. |
| **Bootstrap 5** | Deixa o visual responsivo e arrumadinho. |
| **Chart.js** | Cria os gráficos dinâmicos e bonitos. |

## 📊 Nossos Gráficos Mais Importantes

Removi aqueles gráficos que não estavam ajudando (tipo o de Gênero, que estava sem dados) e foquei no que importa!

| Gráfico | O que ele mostra? | Tipo |
| :--- | :--- | :--- |
| **G6** | **Média de Idade por Curso:** Qual a idade média da galera em cada curso? | Barra (Horizontal) |
| **G8** | **Dispersão de Idades:** Mostra a idade de CADA aluno em cada curso, revelando se as turmas são homogêneas. | Bolha/Dispersão |
| **G1, G2, G3, G4** | Outras métricas essenciais (Cursos, Responsáveis, Bairros e Frequência de Idades). | Vários |



## 🛠️ Como Colocar Para Rodar

Quer testar no seu PC? É super simples:

1.  **Baixe os Arquivos:** Faça o `git clone` deste repositório.
2.  **Crie o Banco:** Use o código SQL em `database/esquema.sql` para criar a tabela `alunos_cadastrados`.
3.  **Ajuste a Conexão:** No início do arquivo `index.php`, mude as linhas `$user`, `$password`, etc., para os dados do seu MySQL.
4.  **Acesse!** Jogue no seu servidor PHP (XAMPP/WAMP) e veja a mágica acontecer.

## 🔍 Olhando o Código (Para Estudantes!)

Se você está estudando PHP e MySQL, confira esses arquivos:

* **`index.php`:** É o arquivo principal. Veja como o PHP mistura o código HTML e o JavaScript para gerar os gráficos dinamicamente.
* **`database/consultas.sql`:** Aqui estão todas as consultas SQL que usamos para alimentar cada gráfico. É um ótimo lugar para aprender como agrupar e calcular dados como a média de idade!

* ## 📸 Imagens do Projeto

### 1️⃣ Tela de Login
![Tela de Login](imagem1)  
Tela inicial onde o usuário insere suas credenciais para acessar o sistema.

### 2️⃣ Dashboard
![Dashboard](imagem2)  
Resumo geral com indicadores e atalhos principais do sistema.

### 3️⃣ Consultas
![Consultas](imagem3)  
Área para pesquisar e visualizar informações dos alunos cadastrados.

### 4️⃣ Gerenciar Alunos
![Gerenciar Alunos](imagem4)  
Tela para editar dados, excluir registros e administrar informações dos alunos.

### 5️⃣ Cadastrar Novo Aluno
![Cadastrar Novo Aluno](imagem5)  
Formulário para adicionar um novo aluno ao banco de dados.

### 6️⃣ Banco de Alunos Cadastrados
![Banco de Alunos](imagem6)  
Lista completa de todos os alunos já registrados no sistema.

### 7️⃣ Estruturas VSCode (Parte 1)
![Estruturas VSCode 1](imagem7)  
Primeira parte da organização de pastas e arquivos do projeto no VSCode.

### 8️⃣ Estruturas VSCode (Parte 2)
![Estruturas VSCode 2](imagem8)  
Segunda parte da estrutura do projeto exibida no VSCode.

---

**Qualquer dúvida ou sugestão, é só abrir uma Issue! Bom estudo!**
