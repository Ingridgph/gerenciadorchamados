# Gerenciador de Chamados

Aplicação Laravel para gerenciamento de chamados (tickets), rodando em Docker, com autenticação e interface moderna estilizada com Tailwind CSS.

## 🚀 Funcionalidades

-   **Autenticação**: Login e Logout.
-   **Gerenciamento de Chamados**:
    -   Criar novo chamado.
    -   Listar chamados com paginação.
    -   **Filtrar** por status (Aberto, Em Andamento, Resolvido) e prioridade (Baixa, Média, Alta).
    -   **Buscar** por título ou descrição.
    -   Ver detalhes do chamado e **atualizar status**.
    -   **Excluir** chamados.
-   **Design**: Interface limpa e responsiva.

## 🛠️ Tecnologias

-   [Laravel 11](https://laravel.com)
-   [Docker](https://www.docker.com) & Docker Compose
-   [Tailwind CSS](https://tailwindcss.com) & [Vite](https://vitejs.dev)
-   SQLite 

---

## ⚙️ Instalação e Execução

Siga os passos abaixo para rodar o projeto do zero usando Docker.

### 1. Pré-requisitos

Certifique-se de ter o **Docker** e o **Docker Compose** instalados na sua máquina.

### 2. Configuração Inicial

Clone o repositório e entre na pasta:

```bash
cd gerenciadorchamados
```

Crie o arquivo `.env`:

```bash
# Windows (Powershell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

### 3. Subir os Containers

Este comando irá criar as imagens (incluindo a instalação do PHP, Composer e Node.js) e iniciar os containers em segundo plano.

```bash
docker-compose up -d --build
```

> **Nota:** A primeira execução pode demorar alguns minutos pois estará instalando todas as dependências do Laravel e do Frontend dentro do container.

### 4. Configurar a Aplicação

Execute os comandos abaixo para gerar a chave da aplicação e criar as tabelas no banco de dados.

```bash
# Entrar no terminal do container
docker exec -it gerenciadorchamados-app-1 bash

# --- DENTRO DO CONTAINER ---

# 1. Instalar dependências do PHP (se não foram instaladas no build)
composer install

# 2. Instalar dependencias do frontend 
npm install 
npm run build

# 3. Gerar chave única da aplicação
php artisan key:generate

# 4. Criar o arquivo do banco de dados (SQLite)
touch database/database.sqlite

# 5. Rodar as migrações e seeds (cria tabelas e usuários padrão)
php artisan migrate --seed

# 6. Definir permissões (caso haja erros de "Permission denied")
chown -R www-data:www-data /var/www/storage /var/www/database

# 7. Sair do container
exit
```

### 5. Frontend Assets (CSS/JS)

O `Dockerfile` já está configurado para instalar e buildar os assets automaticamente. Caso a estilização não apareça, você pode forçar a reconstrução:

```bash
docker exec gerenciadorchamados-app-1 npm run build
```

---

## 🖥️ Acessando a Aplicação

Acesse no seu navegador:

👉 **http://localhost:8080**

### Login

Admin:
-   **Email:** `admin@test.com`
-   **Senha:** `Password123`

User:
-   **Email:** `user@test.com`
-   **Senha:** `Password123`

---