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
