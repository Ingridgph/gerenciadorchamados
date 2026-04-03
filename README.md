# Gerenciador de Chamados

Sistema web completo para gerenciamento de chamados de suporte, desenvolvido com Laravel 12, interface moderna com Tailwind CSS e API RESTful autenticada via Laravel Sanctum. O projeto oferece funcionalidades de criacao, acompanhamento, filtragem e resolucao de tickets, com controle de acesso baseado em perfis de usuario (administrador e usuario comum).

---

## Sumario

- [Visao Geral da Arquitetura](#visao-geral-da-arquitetura)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Modelo de Dados](#modelo-de-dados)
- [Fluxo de Autenticacao](#fluxo-de-autenticacao)
- [Rotas e Endpoints](#rotas-e-endpoints)
- [Camada de Servico](#camada-de-servico)
- [Politicas de Autorizacao](#politicas-de-autorizacao)
- [Validacao de Dados](#validacao-de-dados)
- [Interface Web](#interface-web)
- [API RESTful](#api-restful)
- [Testes Automatizados](#testes-automatizados)
- [Instalacao e Configuracao](#instalacao-e-configuracao)
- [Execucao com Docker](#execucao-com-docker)
- [Execucao Local](#execucao-local)
- [Credenciais de Acesso](#credenciais-de-acesso)
- [Scripts Disponiveis](#scripts-disponiveis)

---

## Visao Geral da Arquitetura

O sistema segue a arquitetura MVC do Laravel com uma camada de servico adicional para encapsular regras de negocio. A aplicacao expoe duas interfaces: uma interface web com Blade Templates e uma API REST para integracao com clientes externos.

```
┌─────────────────────────────────────────────────────────────────┐
│                        Cliente (Navegador)                      │
└──────────────────────────────┬──────────────────────────────────┘
                               │
              ┌────────────────┴────────────────┐
              │                                 │
              v                                 v
   ┌─────────────────────┐           ┌─────────────────────┐
   │   Interface Web     │           │     API REST        │
   │  (Blade + Alpine)   │           │  (JSON + Sanctum)   │
   │   Sessao/Cookie     │           │   Token Bearer      │
   └─────────┬───────────┘           └─────────┬───────────┘
              │                                 │
              v                                 v
   ┌─────────────────────┐           ┌─────────────────────┐
   │  Web Controllers    │           │  API Controllers    │
   │  - AuthController   │           │  - TicketController │
   │  - TicketController │           │                     │
   │  - DashboardCtrl    │           │                     │
   └─────────┬───────────┘           └─────────┬───────────┘
              │                                 │
              └────────────────┬────────────────┘
                               │
                    ┌──────────v──────────┐
                    │   Form Requests     │
                    │   (Validacao)       │
                    └──────────┬──────────┘
                               │
                    ┌──────────v──────────┐
                    │   ChamadoService    │
                    │  (Regras de Negocio)│
                    └──────────┬──────────┘
                               │
                    ┌──────────v──────────┐
                    │    Policies         │
                    │  (Autorizacao)      │
                    └──────────┬──────────┘
                               │
              ┌────────────────┴────────────────┐
              │                                 │
              v                                 v
   ┌─────────────────────┐           ┌─────────────────────┐
   │   Eloquent Models   │           │   Enums             │
   │  - User             │           │  - ChamadoStatus    │
   │  - Chamado          │           │  - ChamadoPrioridade│
   │  - ChamadoLog       │           │                     │
   └─────────┬───────────┘           └─────────────────────┘
              │
              v
   ┌─────────────────────┐
   │    SQLite Database   │
   └─────────────────────┘
```

---

## Tecnologias Utilizadas

| Camada         | Tecnologia                        | Versao   |
|----------------|-----------------------------------|----------|
| Backend        | PHP                               | 8.2+     |
| Framework      | Laravel                           | 12.x     |
| Autenticacao   | Laravel Sanctum                   | 4.x      |
| Banco de Dados | SQLite                            | 3.x      |
| Frontend       | Tailwind CSS                      | 3.4.x    |
| Interatividade | Alpine.js                         | 3.14.x   |
| Build          | Vite                              | 5.x      |
| Testes         | PestPHP                           | 4.x      |
| Containerizacao| Docker + Docker Compose           | -        |
| Servidor Web   | Nginx + PHP-FPM                   | 8.3      |
| Localizacao    | laravel-pt-br-localization        | 3.x      |

---

## Estrutura do Projeto

```
gerenciadorchamados/
│
├── app/
│   ├── Enums/
│   │   ├── ChamadoPrioridadeEnum.php    # Enum: baixa, media, alta
│   │   └── ChamadoStatusEnum.php        # Enum: aberto, em_andamento, resolvido
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── TicketController.php  # Endpoints REST para chamados
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   └── Web/
│   │   │       ├── AuthController.php        # Login/logout via sessao
│   │   │       ├── DashboardController.php   # Painel de estatisticas
│   │   │       └── TicketController.php      # CRUD de chamados (web)
│   │   │
│   │   ├── Middleware/
│   │   │   └── EnsureEmailIsVerified.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── Auth/LoginRequest.php
│   │   │   ├── ChamadoCreateRequest.php
│   │   │   ├── ChamadoUpdateStatusRequest.php
│   │   │   └── ListChamadoRequest.php
│   │   │
│   │   └── Resources/
│   │       ├── ChamadoLogResource.php
│   │       └── ChamadoResource.php
│   │
│   ├── Models/
│   │   ├── Chamado.php         # Modelo principal de chamados
│   │   ├── ChamadoLog.php      # Historico de alteracoes de status
│   │   └── User.php            # Modelo de usuario
│   │
│   ├── Policies/
│   │   └── ChamadoPolicy.php   # Regras de autorizacao
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── AuthServiceProvider.php
│   │
│   └── Services/
│       └── ChamadoService.php  # Logica de negocio centralizada
│
├── database/
│   ├── factories/
│   │   ├── ChamadoFactory.php
│   │   └── UserFactory.php
│   ├── migrations/             # 7 arquivos de migracao
│   └── seeders/
│       ├── ChamadoSeeder.php
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php
│
├── resources/
│   ├── css/app.css             # Estilos customizados + Tailwind
│   ├── js/app.js               # Alpine.js + configuracao
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── chamados/
│       │   ├── create.blade.php
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── components/ui/       # 8 componentes reutilizaveis
│       ├── dashboard.blade.php
│       └── layouts/
│           ├── app.blade.php    # Layout autenticado
│           └── guest.blade.php  # Layout publico (login)
│
├── routes/
│   ├── api.php                 # Rotas da API REST
│   ├── auth.php                # Rotas de autenticacao (Breeze)
│   └── web.php                 # Rotas da interface web
│
├── tests/
│   ├── Feature/
│   │   ├── ConsultarChamadoTest.php
│   │   ├── CriarChamadoTest.php
│   │   ├── DashboardTest.php
│   │   ├── EditarStatusChamadoTest.php
│   │   └── ExcluirChamadoTest.php
│   └── TestCase.php            # Classe base com helpers
│
├── docker-compose.yml
├── Dockerfile
├── nginx.conf
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── phpunit.xml
```

---

## Modelo de Dados

### Diagrama de Entidade-Relacionamento

```
┌────────────────────────┐       ┌──────────────────────────────────┐
│         users          │       │            chamado               │
├────────────────────────┤       ├──────────────────────────────────┤
│ id          BIGINT PK  │──┐    │ id              BIGINT PK       │
│ name        VARCHAR    │  │    │ titulo          VARCHAR(120)     │
│ email       VARCHAR UQ │  │    │ descricao       TEXT             │
│ password    VARCHAR    │  ├───>│ solicitante_id  FK -> users      │
│ admin       BOOLEAN    │  │    │ responsavel_id  FK -> users NULL │
│ email_verified_at TS   │  ├───>│ status          ENUM             │
│ remember_token VARCHAR │  │    │ prioridade      ENUM             │
│ created_at  TIMESTAMP  │  │    │ resolved_at     DATETIME NULL    │
│ updated_at  TIMESTAMP  │  │    │ created_at      TIMESTAMP        │
└────────────────────────┘  │    │ updated_at      TIMESTAMP        │
                            │    │ deleted_at      TIMESTAMP NULL   │
                            │    └───────────────┬──────────────────┘
                            │                    │
                            │                    │ 1:N
                            │                    v
                            │    ┌──────────────────────────────────┐
                            │    │         chamado_logs             │
                            │    ├──────────────────────────────────┤
                            │    │ id              BIGINT PK       │
                            │    │ chamado_id      FK -> chamado   │
                            └───>│ user_id         FK -> users     │
                                 │ de              VARCHAR          │
                                 │ para            VARCHAR          │
                                 │ created_at      TIMESTAMP        │
                                 │ updated_at      TIMESTAMP        │
                                 └──────────────────────────────────┘
```

### Tabela: users

| Coluna            | Tipo      | Restricoes              | Descricao                             |
|-------------------|-----------|-------------------------|---------------------------------------|
| id                | BIGINT    | PK, auto-incremento     | Identificador unico                   |
| name              | VARCHAR   | obrigatorio             | Nome completo do usuario              |
| email             | VARCHAR   | obrigatorio, unico      | Endereco de e-mail                    |
| password          | VARCHAR   | obrigatorio             | Senha criptografada com bcrypt        |
| admin             | BOOLEAN   | padrao: false           | Define se o usuario e administrador   |
| email_verified_at | TIMESTAMP | nulo                    | Data de verificacao do e-mail         |
| remember_token    | VARCHAR   | nulo                    | Token de sessao persistente           |
| created_at        | TIMESTAMP | automatico              | Data de criacao do registro           |
| updated_at        | TIMESTAMP | automatico              | Data da ultima atualizacao            |

### Tabela: chamado

| Coluna          | Tipo         | Restricoes           | Descricao                              |
|-----------------|--------------|----------------------|----------------------------------------|
| id              | BIGINT       | PK, auto-incremento  | Identificador unico do chamado         |
| titulo          | VARCHAR(120) | obrigatorio          | Titulo resumido do chamado             |
| descricao       | TEXT         | obrigatorio          | Descricao detalhada do problema        |
| status          | ENUM         | obrigatorio          | aberto, em_andamento, resolvido        |
| prioridade      | ENUM         | obrigatorio          | baixa, media, alta                     |
| solicitante_id  | FK -> users  | obrigatorio          | Usuario que abriu o chamado            |
| responsavel_id  | FK -> users  | nulo                 | Usuario responsavel pelo atendimento   |
| resolved_at     | DATETIME     | nulo                 | Data e hora da resolucao               |
| created_at      | TIMESTAMP    | automatico           | Data de criacao                        |
| updated_at      | TIMESTAMP    | automatico           | Data da ultima atualizacao             |
| deleted_at      | TIMESTAMP    | nulo (soft delete)   | Data da exclusao logica                |

**Indices de performance:**

| Indice                          | Colunas                    | Justificativa                          |
|---------------------------------|----------------------------|----------------------------------------|
| chamado_status_index            | status                     | Filtragem por status                   |
| chamado_prioridade_index        | prioridade                 | Filtragem por prioridade               |
| chamado_solicitante_id_index    | solicitante_id             | Consultas por solicitante              |
| chamado_responsavel_id_index    | responsavel_id             | Consultas por responsavel              |
| chamado_resolved_at_index       | resolved_at                | Ordenacao por data de resolucao        |
| chamado_deleted_at_index        | deleted_at                 | Consultas excluindo registros apagados |
| chamado_created_at_index        | created_at                 | Ordenacao cronologica                  |
| chamado_status_prioridade_index | status, prioridade         | Filtragem combinada                    |

### Tabela: chamado_logs

| Coluna     | Tipo        | Restricoes           | Descricao                                |
|------------|-------------|----------------------|------------------------------------------|
| id         | BIGINT      | PK, auto-incremento  | Identificador unico do log               |
| chamado_id | FK->chamado | obrigatorio          | Chamado associado                        |
| user_id    | FK->users   | obrigatorio          | Usuario que realizou a alteracao         |
| de         | VARCHAR     | obrigatorio          | Status anterior                          |
| para       | VARCHAR     | obrigatorio          | Novo status                              |
| created_at | TIMESTAMP   | automatico           | Data da alteracao                        |
| updated_at | TIMESTAMP   | automatico           | Data da ultima atualizacao               |

### Enumeracoes

**ChamadoStatusEnum:**

| Valor         | Descricao                                      |
|---------------|-------------------------------------------------|
| aberto        | Chamado recem-criado, aguardando atendimento    |
| em_andamento  | Chamado em processo de resolucao                |
| resolvido     | Chamado finalizado e resolvido                  |

**ChamadoPrioridadeEnum:**

| Valor  | Descricao                                         |
|--------|---------------------------------------------------|
| baixa  | Prioridade baixa, sem urgencia                    |
| media  | Prioridade intermediaria                          |
| alta   | Prioridade alta, requer atencao imediata          |

### Relacionamentos entre Modelos

```
User 1 ──────< N Chamado        (solicitante_id)
User 1 ──────< N Chamado        (responsavel_id, opcional)
User 1 ──────< N ChamadoLog     (user_id)
Chamado 1 ───< N ChamadoLog     (chamado_id)
```

| Modelo     | Relacionamento | Modelo Alvo | Chave Estrangeira | Descricao                          |
|------------|----------------|-------------|-------------------|------------------------------------|
| Chamado    | belongsTo      | User        | solicitante_id    | Usuario que abriu o chamado        |
| Chamado    | belongsTo      | User        | responsavel_id    | Usuario responsavel pelo chamado   |
| Chamado    | hasMany        | ChamadoLog  | chamado_id        | Historico de alteracoes de status  |
| Chamado    | hasOne         | ChamadoLog  | chamado_id        | Log mais recente (latestOfMany)    |
| ChamadoLog | belongsTo      | Chamado     | chamado_id        | Chamado associado ao log           |
| ChamadoLog | belongsTo      | User        | user_id           | Usuario que realizou a alteracao   |

---

## Fluxo de Autenticacao

O sistema possui dois mecanismos de autenticacao independentes, cada um atendendo a uma interface distinta.

### Autenticacao Web (Sessao)

```
  Navegador                     Servidor
     │                             │
     │  GET /                      │
     │────────────────────────────>│  Exibe formulario de login
     │<────────────────────────────│
     │                             │
     │  POST / (email, senha)      │
     │────────────────────────────>│  LoginRequest::authenticate()
     │                             │  Auth::attempt(credenciais)
     │                             │  Session::regenerate()
     │  302 Redirect /dashboard    │
     │<────────────────────────────│  Cookie de sessao definido
     │                             │
     │  GET /dashboard             │
     │────────────────────────────>│  Middleware 'auth' valida sessao
     │<────────────────────────────│  Retorna pagina do dashboard
     │                             │
     │  POST /logout               │
     │────────────────────────────>│  Auth::guard('web')->logout()
     │                             │  Session::invalidate()
     │  302 Redirect /             │
     │<────────────────────────────│
```

- O middleware `auth` protege todas as rotas web exceto a pagina de login.
- A sessao e armazenada no banco de dados (tabela `sessions`).
- O rate limiting permite no maximo 5 tentativas de login por combinacao de e-mail e IP.

### Autenticacao API (Sanctum Token)

```
  Cliente HTTP                  Servidor
     │                             │
     │  POST /api/login-token      │
     │  Body: {email, senha}       │
     │────────────────────────────>│  LoginRequest::authenticate()
     │                             │  User::createToken('api-token')
     │  200 {token: "abc123..."}   │
     │<────────────────────────────│
     │                             │
     │  GET /api/tickets           │
     │  Header: Authorization:     │
     │  Bearer abc123...           │
     │────────────────────────────>│  Middleware 'auth:sanctum'
     │<────────────────────────────│  Retorna JSON com chamados
```

- Tokens sao do tipo Bearer, gerados via Laravel Sanctum.
- O middleware `auth:sanctum` protege todos os endpoints da API.
- Cada token e nomeado como `api-token` e armazenado na tabela `personal_access_tokens`.

---

## Rotas e Endpoints

### Rotas Web

| Metodo | URI                          | Controller                    | Middleware | Nome da Rota         | Descricao                        |
|--------|------------------------------|-------------------------------|------------|----------------------|----------------------------------|
| GET    | `/`                          | Web\AuthController@create     | guest      | login                | Formulario de login              |
| POST   | `/`                          | Web\AuthController@store      | guest      | -                    | Processar login                  |
| POST   | `/logout`                    | Web\AuthController@destroy    | auth       | logout               | Encerrar sessao                  |
| GET    | `/dashboard`                 | Web\DashboardController       | auth       | dashboard            | Painel de estatisticas           |
| GET    | `/chamados`                  | Web\TicketController@index    | auth       | chamados.index       | Listar chamados                  |
| GET    | `/chamados/create`           | Web\TicketController@create   | auth       | chamados.create      | Formulario de criacao            |
| POST   | `/chamados`                  | Web\TicketController@store    | auth       | chamados.store       | Criar chamado                    |
| GET    | `/chamados/{chamado}`        | Web\TicketController@show     | auth       | chamados.show        | Detalhes do chamado              |
| DELETE | `/chamados/{chamado}`        | Web\TicketController@destroy  | auth       | chamados.destroy     | Excluir chamado (soft delete)    |
| PATCH  | `/chamados/{chamado}/status` | Web\TicketController@updateStatus | auth  | chamados.updateStatus| Alterar status do chamado        |

### Rotas da API REST

Todas as rotas da API utilizam o prefixo `/api` e retornam respostas em formato JSON.

| Metodo | URI                               | Controller                  | Autenticacao   | Descricao                     |
|--------|-----------------------------------|-----------------------------|----------------|-------------------------------|
| POST   | `/api/login-token`                | AuthenticatedSessionCtrl    | Publica        | Gerar token de acesso         |
| GET    | `/api/user`                       | Closure                     | auth:sanctum   | Dados do usuario autenticado  |
| GET    | `/api/tickets`                    | Api\TicketController@index  | auth:sanctum   | Listar chamados               |
| GET    | `/api/tickets/{chamado}`          | Api\TicketController@show   | auth:sanctum   | Detalhes de um chamado        |
| POST   | `/api/tickets`                    | Api\TicketController@store  | auth:sanctum   | Criar chamado                 |
| PATCH  | `/api/tickets/{chamado}/status`   | Api\TicketController@updateStatus | auth:sanctum | Alterar status           |
| DELETE | `/api/tickets/{chamado}`          | Api\TicketController@destroy| auth:sanctum   | Excluir chamado               |

### Parametros de Filtragem (GET /chamados e GET /api/tickets)

| Parametro  | Tipo   | Obrigatorio | Valores Aceitos                        | Descricao                        |
|------------|--------|-------------|----------------------------------------|----------------------------------|
| search     | string | nao         | Texto livre (max 255 caracteres)       | Busca por titulo ou descricao    |
| status     | string | nao         | aberto, em_andamento, resolvido        | Filtrar por status               |
| prioridade | string | nao         | baixa, media, alta                     | Filtrar por prioridade           |

---

## Camada de Servico

A classe `ChamadoService` centraliza as regras de negocio, sendo utilizada tanto pelos controllers web quanto pelos controllers da API, garantindo consistencia no comportamento independentemente da interface de acesso.

### Metodo: create

```
Entrada: ChamadoCreateRequest (titulo, descricao, prioridade)
Processo:
  1. Define status como "aberto" automaticamente
  2. Associa o usuario autenticado como solicitante
  3. Persiste o registro na tabela chamado
Saida: Instancia do modelo Chamado
```

### Metodo: updateStatus

```
Entrada: ChamadoUpdateStatusRequest (status), Chamado existente
Processo:
  1. Registra o status atual como status anterior
  2. Atualiza o status do chamado para o novo valor
  3. Define o usuario autenticado como responsavel
  4. Se o novo status for "resolvido", registra a data/hora da resolucao
  5. Cria um registro em chamado_logs com:
     - de: status anterior
     - para: novo status
     - user_id: usuario autenticado
  6. Retorna o chamado atualizado a partir do banco
Saida: Instancia atualizada do modelo Chamado
```

### Diagrama do Fluxo de Alteracao de Status

```
  Usuario                    Controller              ChamadoService           Banco de Dados
    │                            │                        │                        │
    │  PATCH /status             │                        │                        │
    │  {status: "resolvido"}     │                        │                        │
    │───────────────────────────>│                        │                        │
    │                            │  Valida request        │                        │
    │                            │  (FormRequest)         │                        │
    │                            │                        │                        │
    │                            │  updateStatus(req, ch) │                        │
    │                            │───────────────────────>│                        │
    │                            │                        │  UPDATE chamado        │
    │                            │                        │  SET status,           │
    │                            │                        │  responsavel_id,       │
    │                            │                        │  resolved_at           │
    │                            │                        │───────────────────────>│
    │                            │                        │                        │
    │                            │                        │  INSERT chamado_logs   │
    │                            │                        │  (de, para, user_id)   │
    │                            │                        │───────────────────────>│
    │                            │                        │                        │
    │                            │  Chamado atualizado    │                        │
    │                            │<───────────────────────│                        │
    │  Redirect com flash        │                        │                        │
    │<───────────────────────────│                        │                        │
```

---

## Politicas de Autorizacao

O sistema utiliza `ChamadoPolicy` para controlar o acesso as operacoes sobre chamados. A policy e registrada no `AuthServiceProvider` e aplicada nos controllers.

| Acao     | Regra                                                                 | Perfil Necessario       |
|----------|-----------------------------------------------------------------------|-------------------------|
| viewAny  | Sempre permitido                                                      | Qualquer usuario        |
| view     | Sempre permitido                                                      | Qualquer usuario        |
| create   | Sempre permitido                                                      | Qualquer usuario        |
| update   | Permitido se o usuario for admin OU se for o solicitante do chamado   | Admin ou solicitante    |
| delete   | Permitido apenas para administradores                                 | Somente admin           |

A exclusao de chamados utiliza soft delete (exclusao logica), preservando o registro na base de dados com a coluna `deleted_at` preenchida. O botao de exclusao na interface web so e exibido para usuarios com permissao, controlado pela diretiva `@can('delete', $chamado)`.

---

## Validacao de Dados

### Criacao de Chamado (ChamadoCreateRequest)

| Campo      | Regras                                | Mensagens de Erro (pt-BR)                    |
|------------|---------------------------------------|----------------------------------------------|
| titulo     | obrigatorio, string, min:3, max:120   | "O titulo e obrigatorio."                    |
|            |                                       | "O titulo deve ter pelo menos 3 caracteres." |
|            |                                       | "O titulo deve ter no maximo 120 caracteres."|
| descricao  | obrigatorio, string, min:5            | "A descricao e obrigatoria."                 |
|            |                                       | "A descricao deve ter pelo menos 5 chars."   |
| prioridade | obrigatorio, enum valido              | "A prioridade e obrigatoria."                |
|            |                                       | "A prioridade selecionada e invalida."       |

### Alteracao de Status (ChamadoUpdateStatusRequest)

| Campo  | Regras                    | Descricao                                 |
|--------|---------------------------|-------------------------------------------|
| status | obrigatorio, enum valido  | Deve ser um valor do ChamadoStatusEnum    |

### Listagem e Filtragem (ListChamadoRequest)

| Campo      | Regras                                     | Descricao                              |
|------------|--------------------------------------------|----------------------------------------|
| status     | nulo, in:[aberto,em_andamento,resolvido]   | Filtro opcional por status             |
| prioridade | nulo, in:[baixa,media,alta]                | Filtro opcional por prioridade         |
| search     | nulo, string, max:255                      | Termo de busca livre                   |

### Login (LoginRequest)

| Campo  | Regras                       | Protecao Adicional                          |
|--------|------------------------------|---------------------------------------------|
| email  | obrigatorio, string, email   | Rate limiting: 5 tentativas por email+IP    |
| password | obrigatorio, string        | Evento Lockout disparado ao exceder limite  |

---

## Interface Web

### Layouts

**Layout Autenticado (`layouts/app.blade.php`):**
- Barra lateral fixa na esquerda com navegacao principal (Dashboard, Chamados)
- Cabecalho superior com nome do usuario e avatar com iniciais
- Menu hamburger para dispositivos moveis com controle via Alpine.js
- Area de conteudo principal com slot para paginas filhas
- Componente de notificacao toast para mensagens de sucesso

**Layout Publico (`layouts/guest.blade.php`):**
- Fundo com gradiente escuro e elementos decorativos
- Card centralizado para formularios de autenticacao

### Paginas

**Login (`auth/login.blade.php`):**
- Formulario com campos de e-mail e senha
- Icones SVG integrados nos campos de entrada
- Exibicao de erros de validacao inline

**Dashboard (`dashboard.blade.php`):**
- 4 cartoes de estatisticas: total de chamados, abertos, em andamento e resolvidos
- Lista dos 5 chamados mais recentes com indicadores visuais de status e prioridade
- Link de acesso rapido para a lista completa de chamados

**Lista de Chamados (`chamados/index.blade.php`):**
- Filtros por status, prioridade e busca textual
- Tabela responsiva com colunas: ID, titulo, solicitante, prioridade, status, data
- Badges coloridos para status e prioridade
- Botao de exclusao condicional (visivel apenas para administradores)
- Paginacao integrada
- Estado vazio com componente dedicado quando nao ha resultados

**Criacao de Chamado (`chamados/create.blade.php`):**
- Formulario com campos de titulo, descricao (textarea) e prioridade (select)
- Validacao server-side com exibicao de erros

**Detalhes do Chamado (`chamados/show.blade.php`):**
- Layout em duas colunas (responsivo)
- Coluna principal: descricao completa e historico de atividades (timeline de alteracoes de status)
- Coluna lateral: informacoes do chamado (status, prioridade, solicitante, responsavel, data de resolucao) e formulario para alteracao de status

### Componentes de Interface

| Componente   | Arquivo                        | Props                                                | Descricao                                 |
|--------------|--------------------------------|------------------------------------------------------|-------------------------------------------|
| Badge        | components/ui/badge.blade.php  | variant                                              | Etiqueta colorida com 8 variantes         |
| Button       | components/ui/button.blade.php | variant, size, disabled, href                        | Botao ou link estilizado                  |
| Card         | components/ui/card.blade.php   | padding                                              | Container com borda e sombra              |
| Input        | components/ui/input.blade.php  | label, name, type, value, placeholder, icon, disabled| Campo de entrada com icone opcional       |
| Select       | components/ui/select.blade.php | label, name, options, value, placeholder, disabled   | Menu de selecao estilizado                |
| Stat Card    | components/ui/stat-card.blade.php | title, value, color, icon, subtitle               | Cartao de estatistica para o dashboard    |
| Empty State  | components/ui/empty-state.blade.php | title, description, icon, actionLabel, actionUrl | Estado vazio com acao opcional             |
| Toast        | components/ui/toast.blade.php  | -                                                    | Notificacao temporaria (5s, auto-oculta)  |

### Design System

O frontend utiliza um sistema de design baseado em variaveis CSS HSL, seguindo a convencao do shadcn/ui. As cores sao definidas como custom properties no Tailwind e incluem tokens para:

- Cores primarias, secundarias e de destaque
- Cores semanticas: sucesso (verde), alerta (amarelo), destrutivo (vermelho), informacao (azul)
- Superficies: card, popover, sidebar, muted
- Animacoes customizadas: fade-in, slide-up, slide-in-right
- Sombras customizadas: soft, card, elevated

---

## API RESTful

### Formato de Resposta

Todas as respostas da API seguem o formato padrao do Laravel API Resources.

**Resposta de um chamado (ChamadoResource):**

```json
{
  "id": 1,
  "titulo": "Problema no sistema de login",
  "descricao": "O sistema apresenta erro ao tentar fazer login com credenciais validas.",
  "status": "aberto",
  "prioridade": "alta",
  "resolved_at": null,
  "solicitante": {
    "id": 1,
    "name": "Admin",
    "email": "admin@test.com"
  },
  "responsavel": null,
  "latest_log": null,
  "created_at": "2026-04-03T00:00:00.000000Z",
  "updated_at": "2026-04-03T00:00:00.000000Z"
}
```

**Resposta de log (ChamadoLogResource):**

```json
{
  "id": 1,
  "de": "aberto",
  "para": "em_andamento",
  "user_id": 1,
  "created_at": "2026-04-03T12:30:00.000000Z"
}
```

### Exemplos de Uso da API

**Autenticacao - Obter Token:**

```bash
curl -X POST http://localhost:8080/api/login-token \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@test.com", "password": "Password123"}'
```

Resposta:
```json
{
  "token": "1|abc123def456..."
}
```

**Listar Chamados com Filtros:**

```bash
curl -X GET "http://localhost:8080/api/tickets?status=aberto&prioridade=alta" \
  -H "Authorization: Bearer 1|abc123def456..."
```

**Criar Chamado:**

```bash
curl -X POST http://localhost:8080/api/tickets \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Servidor fora do ar",
    "descricao": "O servidor de producao parou de responder desde as 14h.",
    "prioridade": "alta"
  }'
```

**Alterar Status:**

```bash
curl -X PATCH http://localhost:8080/api/tickets/1/status \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -d '{"status": "em_andamento"}'
```

**Excluir Chamado (somente admin):**

```bash
curl -X DELETE http://localhost:8080/api/tickets/1 \
  -H "Authorization: Bearer 1|abc123def456..."
```

Resposta: `204 No Content`

---

## Testes Automatizados

O projeto utiliza PestPHP como framework de testes, com banco de dados SQLite em memoria e a trait `RefreshDatabase` para isolamento entre testes.

### Classe Base (TestCase)

A classe `TestCase` fornece dois metodos auxiliares para autenticacao nos testes:

| Metodo               | Descricao                                          |
|----------------------|----------------------------------------------------|
| `authenticated()`    | Cria e autentica um usuario comum                  |
| `authenticatedAdmin()` | Cria e autentica um usuario administrador        |

### Suites de Teste

**CriarChamadoTest (5 testes):**

| Teste                                                    | Verificacao                                        |
|----------------------------------------------------------|----------------------------------------------------|
| Criacao exige autenticacao                                | Redireciona para login (302)                       |
| Dados validos criam chamado                               | Redireciona para index, flash de sucesso, registro no banco |
| Dados invalidos retornam erros                            | Erros de validacao na sessao para titulo e descricao|
| Solicitante e atribuido automaticamente                   | solicitante_id igual ao usuario autenticado         |
| Pagina de criacao carrega corretamente                    | Retorna status 200                                 |

**ConsultarChamadoTest (2 testes):**

| Teste                                                    | Verificacao                                        |
|----------------------------------------------------------|----------------------------------------------------|
| Listagem exige autenticacao                               | Redireciona para login (302)                       |
| Usuario autenticado visualiza chamados                    | Status 200, view recebe colecao com contagem correta|

**EditarStatusChamadoTest (4 testes):**

| Teste                                                    | Verificacao                                        |
|----------------------------------------------------------|----------------------------------------------------|
| Alteracao de status exige autenticacao                    | Redireciona para login (302)                       |
| Status atualizado com sucesso                             | Redireciona para show, status alterado no banco    |
| Alteracao gera registro de log                            | ChamadoLog criado com de, para e user_id corretos  |
| Resolver chamado registra data de resolucao               | Campo resolved_at preenchido                       |

**ExcluirChamadoTest (2 testes):**

| Teste                                                    | Verificacao                                        |
|----------------------------------------------------------|----------------------------------------------------|
| Admin pode excluir chamado                                | Redireciona para index, registro soft-deleted       |
| Usuario comum recebe 403 ao tentar excluir                | Resposta Forbidden                                 |

**DashboardTest (3 testes):**

| Teste                                                    | Verificacao                                        |
|----------------------------------------------------------|----------------------------------------------------|
| Dashboard exige autenticacao                              | Redireciona para login (302)                       |
| Estatisticas exibidas corretamente                        | Contagens corretas por status                      |
| Consulta otimizada sem N+1                                | Status 200 com volume de dados                     |

### Execucao dos Testes

```bash
# Executar todos os testes
php artisan test

# Executar com saida detalhada
php artisan test --verbose

# Executar suite especifica
php artisan test --filter=CriarChamadoTest
```

### Configuracao de Testes (phpunit.xml)

| Variavel         | Valor      | Motivo                                      |
|------------------|------------|---------------------------------------------|
| DB_CONNECTION    | sqlite     | Banco leve e rapido                         |
| DB_DATABASE      | :memory:   | Banco em memoria para velocidade            |
| BCRYPT_ROUNDS    | 4          | Hashing mais rapido durante testes          |
| CACHE_STORE      | array      | Cache em memoria                            |
| SESSION_DRIVER   | array      | Sessao em memoria                           |
| QUEUE_CONNECTION | sync       | Jobs executados de forma sincrona           |

---

## Instalacao e Configuracao

### Pre-requisitos

| Software       | Versao Minima | Obrigatorio |
|----------------|---------------|-------------|
| PHP            | 8.2           | Sim         |
| Composer       | 2.x           | Sim         |
| Node.js        | 20.x          | Sim         |
| NPM            | 9.x           | Sim         |
| Docker         | 20.x          | Opcional    |
| Docker Compose | 2.x           | Opcional    |

### Extensoes PHP Necessarias

- pdo_sqlite
- mbstring
- exif
- pcntl
- bcmath

---

## Execucao com Docker

### 1. Clonar o repositorio

```bash
git clone https://github.com/Ingridgph/gerenciadorchamados.git
cd gerenciadorchamados
```

### 2. Criar o arquivo de ambiente

```bash
# Linux/macOS
cp .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

### 3. Construir e iniciar os containers

```bash
docker-compose up -d --build
```

A primeira execucao pode levar alguns minutos, pois o Docker ira instalar todas as dependencias do PHP e do Node.js, alem de compilar os assets do frontend.

### 4. Configurar a aplicacao dentro do container

```bash
docker exec -it gerenciadorchamados-app-1 bash
```

Dentro do container, execute:

```bash
composer install
npm install
npm run build
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
chown -R www-data:www-data /var/www/storage /var/www/database
exit
```

### 5. Acessar a aplicacao

Abra o navegador e acesse: `http://localhost:8080`

---

## Execucao Local

### 1. Instalar dependencias e configurar

```bash
git clone https://github.com/Ingridgph/gerenciadorchamados.git
cd gerenciadorchamados
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
```

Ou, de forma simplificada, utilizando o script de setup:

```bash
composer run setup
```

### 2. Iniciar o servidor de desenvolvimento

```bash
composer run dev
```

Este comando inicia simultaneamente:
- Servidor PHP na porta 8000 (`php artisan serve`)
- Worker de filas (`php artisan queue:listen`)
- Visualizador de logs (`php artisan pail`)
- Servidor Vite para hot-reload dos assets (`npm run dev`)

Acesse a aplicacao em: `http://localhost:8000`

---

## Credenciais de Acesso

O seeder cria dois usuarios para testes:

| Perfil         | E-mail           | Senha        | Permissoes                              |
|----------------|------------------|--------------|-----------------------------------------|
| Administrador  | admin@test.com   | Password123  | Acesso total, incluindo exclusao        |
| Usuario Comum  | user@test.com    | Password123  | Criar, visualizar e alterar status      |

---

## Scripts Disponiveis

### Composer

| Comando              | Descricao                                                              |
|----------------------|------------------------------------------------------------------------|
| `composer run setup` | Instalacao completa: dependencias, chave, migracoes, build do frontend |
| `composer run dev`   | Inicia todos os servicos de desenvolvimento simultaneamente            |

### NPM

| Comando          | Descricao                                        |
|------------------|--------------------------------------------------|
| `npm run dev`    | Inicia o servidor Vite com hot-reload            |
| `npm run build`  | Compila os assets para producao                  |

### Artisan

| Comando                        | Descricao                                    |
|--------------------------------|----------------------------------------------|
| `php artisan migrate`          | Executar migracoes pendentes                 |
| `php artisan migrate --seed`   | Migrar e popular o banco com dados iniciais  |
| `php artisan migrate:fresh --seed` | Recriar todo o banco do zero             |
| `php artisan test`             | Executar a suite de testes                   |
| `php artisan tinker`           | Abrir console interativo do Laravel          |

---

## Licenca

Este projeto e de uso educacional e nao possui licenca de distribuicao definida.
