# Projeto de estudo: backend com PHP

Aplicação de estudo para praticar PHP no backend com formulários, requisições HTTP, PDO e MySQL. O projeto também contém exercícios de orientação a objetos em `src/`.

## Funcionalidades atuais

- Cadastro, listagem, busca, conclusão e remoção de tarefas.
- Formulário de contato que grava mensagens no banco.
- Consultas preparadas com PDO.
- Validação de entrada, proteção CSRF e escape de conteúdo HTML.
- Criação automática das tabelas `tarefas` e `mensagens` ao conectar ao banco.

## Requisitos

- PHP 8.1 ou superior.
- Extensões PHP `pdo_mysql` e `mbstring`.
- Servidor MySQL local.

Veja [INSTALACAO.md](./INSTALACAO.md) para configurar o ambiente no Windows.

## Executar

Na pasta do projeto, configure as variáveis de conexão ao MySQL e inicie o servidor local:

```powershell
$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '3306'
$env:DB_NAME = 'estudo_php'
$env:DB_USER = 'root'
$env:DB_PASSWORD = ''
php -S 127.0.0.1:8000
```

Abra `http://127.0.0.1:8000` no navegador. Ajuste usuário e senha conforme a configuração do seu MySQL. As variáveis definidas no PowerShell valem apenas para aquela janela.

## Estrutura principal

```text
index.php          Gerenciador de tarefas
contato.php        Formulário de contato e histórico
includes/
  config.php       Sessão e configuração geral
  db.php           Conexão PDO com MySQL e criação das tabelas
  funcoes.php      Escape HTML, CSRF, redirecionamentos e mensagens
src/               Exercícios de orientação a objetos
css/               Estilos
js/                JavaScript
```

O foco atual é praticar aplicações web com banco de dados. A pasta `src/` contém exercícios separados e ainda não é usada pelas páginas de tarefas e contato.
