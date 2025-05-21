# Documentação do Sistema de Busca Foto com Laravel

## Visão Geral

Este projeto é uma migração do sistema original de Busca Foto baseado em Node.js/Express para uma aplicação Laravel completa. A aplicação permite que usuários encontrem suas fotos em eventos através de Busca Foto usando AWS Rekognition.

## Requisitos do Sistema

- PHP 8.0 ou superior
- Composer
- MySQL (para produção)
- Extensões PHP: curl, xml, sqlite3 (desenvolvimento), mysql (produção)
- Conta AWS com acesso ao Rekognition e S3

## Instalação

### 1. Clone o repositório

```bash
git clone [seu-repositorio] reconhecimento-facial
cd reconhecimento-facial
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Configure o ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure as seguintes variáveis:

```
# Configurações básicas
APP_NAME="Busca Foto"
APP_URL=http://seu-dominio.com

# Banco de dados (SQLite para desenvolvimento)
DB_CONNECTION=sqlite
# DB_DATABASE=/caminho/absoluto/para/database.sqlite

# Banco de dados (MySQL para produção)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=reconhecimento_facial
# DB_USERNAME=seu_usuario
# DB_PASSWORD=sua_senha

# Configurações AWS
AWS_ACCESS_KEY_ID=sua_chave_de_acesso
AWS_SECRET_ACCESS_KEY=sua_chave_secreta
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=seu_bucket_s3
AWS_REKOGNITION_COLLECTION_ID=sua_colecao_rekognition
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Execute as migrações

Para desenvolvimento com SQLite:
```bash
touch database/database.sqlite
php artisan migrate
```

Para produção com MySQL:
```bash
php artisan migrate
```

### 6. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`.

## Estrutura do Projeto

- **app/Http/Controllers/RekognitionController.php**: Contém a lógica para interação com AWS Rekognition e S3
- **app/Http/Controllers/DashboardController.php**: Gerencia as páginas principais e dashboard
- **app/Http/Middleware/AdminMiddleware.php**: Middleware para proteger rotas de administrador
- **resources/views/**: Templates Blade para todas as páginas
- **routes/web.php**: Definição de todas as rotas da aplicação

## Funcionalidades

### Autenticação

- Login e registro de usuários
- Diferenciação entre usuários comuns e administradores
- Proteção de rotas baseada em perfil

### Busca Foto

- Upload de fotos (apenas administradores)
- Busca por selfie (todos os usuários)
- Visualização de resultados de busca
- Galeria de fotos
- Download de imagens individuais ou em lote

## Migração para MySQL (Produção)

Para migrar do SQLite (desenvolvimento) para MySQL (produção):

1. Configure as variáveis de banco de dados no `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reconhecimento_facial
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

2. Certifique-se de que a extensão PHP para MySQL está instalada:

```bash
sudo apt-get install php-mysql
```

3. Crie o banco de dados:

```bash
mysql -u root -p
CREATE DATABASE reconhecimento_facial;
GRANT ALL PRIVILEGES ON reconhecimento_facial.* TO 'seu_usuario'@'localhost' IDENTIFIED BY 'sua_senha';
FLUSH PRIVILEGES;
EXIT;
```

4. Execute as migrações:

```bash
php artisan migrate
```

## Deploy na Dreamhost

Para fazer deploy na Dreamhost:

1. Crie um banco de dados MySQL no painel da Dreamhost

2. Faça upload dos arquivos para o servidor via FTP ou SSH

3. Configure o `.env` com as credenciais do banco de dados e AWS

4. No diretório do projeto, execute:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. Configure o domínio para apontar para a pasta `public` do projeto

6. Certifique-se de que o arquivo `.htaccess` está presente na pasta `public`

## Primeiro Usuário Administrador

O primeiro usuário registrado no sistema será automaticamente definido como administrador. Para registrar usuários administradores adicionais, você pode usar o Tinker:

```bash
php artisan tinker
User::find(ID_DO_USUARIO)->update(['is_admin' => true]);
```

## Solução de Problemas

### Erro de permissão no SQLite

Se encontrar erros de permissão com SQLite, certifique-se de que o arquivo do banco de dados e seu diretório têm permissões adequadas:

```bash
chmod 755 database/
chmod 644 database/database.sqlite
```

### Problemas com AWS

Verifique se as credenciais AWS estão corretas e se o usuário tem permissões para acessar Rekognition e S3. Certifique-se também de que a coleção do Rekognition existe.

## Customização

### Alterando o tema visual

As cores principais podem ser alteradas no arquivo CSS em `resources/views/layouts/app.blade.php`:

```css
:root {
    --primary-color: #1e88e5; /* Azul */
    --secondary-color: #ff8f00; /* Laranja */
    /* Outras cores */
}
```

### Adicionando novas funcionalidades

Para adicionar novas funcionalidades, siga o padrão MVC do Laravel:

1. Crie um controller: `php artisan make:controller NomeController`
2. Adicione rotas em `routes/web.php`
3. Crie views em `resources/views/`

## Contato e Suporte

Para suporte ou dúvidas, entre em contato através de [seu-email@exemplo.com].
