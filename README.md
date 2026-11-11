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

# Instalação do ambiente de estudo (Windows)

## 1. Instale e inicie o ambiente local

Instale o [Laragon Full](https://laragon.org/download), que inclui PHP e MySQL. Inicie os serviços **Apache** e **MySQL** pelo Laragon. O XAMPP também pode ser usado, desde que o PHP tenha as extensões `pdo_mysql` e `mbstring` habilitadas.

Abra o terminal do Laragon (ou um novo PowerShell) e confira:

```powershell
php -v
php -m
```

Na lista de módulos de `php -m`, confirme que aparecem `PDO`, `pdo_mysql` e `mbstring`. Se `php` não for reconhecido no PowerShell, use o terminal do Laragon ou configure o PHP no `PATH`.

## 2. Crie o banco de dados

No cliente MySQL que acompanha seu ambiente (por exemplo, HeidiSQL ou phpMyAdmin), execute:

```sql
CREATE DATABASE estudo_php
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

O usuário configurado para a aplicação precisa ter permissão para criar e alterar tabelas nesse banco. As tabelas `tarefas` e `mensagens` são criadas automaticamente pelo projeto na primeira conexão.

## 3. Configure e execute o projeto

No PowerShell, entre na pasta do projeto e defina os dados do seu MySQL. O exemplo abaixo usa a configuração padrão comum do Laragon; altere usuário e senha se o seu ambiente for diferente:

```powershell
cd "C:\Users\user\Documents\Estudos PHP"
$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '3306'
$env:DB_NAME = 'estudo_php'
$env:DB_USER = 'root'
$env:DB_PASSWORD = ''
php -S 127.0.0.1:8000
```

Deixe essa janela aberta e acesse <http://127.0.0.1:8000>. As variáveis `$env:DB_*` permanecem disponíveis apenas na janela atual do PowerShell. Não use a conta `root` nem credenciais sem senha em um servidor público.

## Se algo não funcionar

- **`php` não é reconhecido:** abra o terminal do Laragon ou adicione a pasta do PHP ao `PATH`.
- **Erro de driver MySQL:** habilite `pdo_mysql` no `php.ini` usado pelo PHP e reinicie o terminal.
- **Erro de conexão:** confirme que o serviço MySQL está iniciado e que host, porta, banco, usuário e senha correspondem ao seu ambiente.
- **Banco inexistente:** execute o comando `CREATE DATABASE` da etapa 2.
