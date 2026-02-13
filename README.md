# 🐞 Teste Técnico Laravel – Gestão de Chamados

Este projeto é uma aplicação de **Gestão de Chamados (Tickets)** desenvolvida para avaliar competências em Laravel 12, arquitetura de software e boas práticas.

---

## 💻 Stack Tecnológica

*   **Framework:** Laravel 12+
*   **Banco de Dados:** SQLite
*   **Autenticação:** Laravel Breeze (Session) & Sanctum (API)
*   **Testes:** Pest PHP
*   **Container:** Docker (Docker Compose)

---

## ✨ Funcionalidades

*   🔑 **Autenticação:** Acesso restrito a usuários autenticados com Bearer tokens (Sanctum).
*   📄 **CRUD de Chamados:** Gerenciamento completo de tickets (criar, listar, editar, deletar).
*   🔍 **Filtros e Busca:** Filtragem por status/prioridade e busca por texto (título/descrição).
*   ⚡ **Status Inteligente:** Preenchimento automático de `resolved_at` ao marcar como RESOLVIDO.
*   🛡️ **Segurança:** Autorização via Policies - apenas solicitante ou admin podem editar/deletar.
*   📝 **Auditoria:** Histórico detalhado de alterações de status em `chamado_logs`.
*   🏭 **Factory & Seeder:** 10 chamados de exemplo com status/prioridades variadas.

---

## 🚀 Como Rodar o Projeto

### 🐳 Via Docker (Recomendado)

```bash
# 1. Build e inicia os containers
docker-compose up -d --build

# 2. Acessa o container da aplicação
docker exec -it gerenciadorchamados-app-1 bash

# 3. Dentro do container, execute:
cd /var/www

# Criar arquivo SQLite
touch database/database.sqlite

# Instalar dependências
composer install

# Gerar APP_KEY
php artisan key:generate

# Executar migrations e seeds
php artisan migrate --seed

# Rodar testes
php artisan test
```

### 🌐 URLs de Acesso

- **Frontend:** [http://localhost:8080/public/front/index.html](http://localhost:8080/public/front/)
- **API Base:** `http://localhost:8080/api`

---

## 🔐 Credenciais de Teste

Após executar `php artisan migrate --seed`:

| Perfil    | E-mail         | Senha       |
| :---      | :---           | :---        |
| **Admin** | admin@test.com | Password123 |
| **Comum** | user@test.com  | Password123 |

---

## 📡 Endpoints da API

### Autenticação

```bash
# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"Password123"}'

# Resposta:
# {
#   "token": "...long-token-string..."
# }
```

### Chamados (require autenticação com Bearer token)

| Método   | Rota                     | Descrição                          |
| :---     | :---                     | :---                               |
| `GET`    | `/api/tickets`           | Lista tickets com filtros          |
| `GET`    | `/api/tickets/{id}`      | Detalhes de um ticket              |
| `POST`   | `/api/tickets`           | Criar novo chamado                 |
| `PATCH`  | `/api/tickets/{id}/status` | Atualizar status (cria log)        |
| `DELETE` | `/api/tickets/{id}`      | Deletar (soft delete)              |
| `GET`    | `/api/user`              | Dados do usuário autenticado       |
| `POST`   | `/api/auth/logout`       | Logout (revoga token)              |

### Exemplos de Requisição

```bash
# Listar todos os chamados (com filtros opcionais)
curl -X GET "http://localhost:8080/api/tickets?status=aberto&prioridade=alta" \
  -H "Authorization: Bearer {token}"

# Criar novo chamado
curl -X POST http://localhost:8080/api/tickets \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "titulo": "Erro no formulário de login",
    "descricao": "Usuários estão recebendo erro 500 ao tentar fazer login no sistema"
  }'

# Atualizar status para "em_andamento"
curl -X PATCH http://localhost:8080/api/tickets/1/status \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{"status": "em_andamento"}'

# Deletar chamado
curl -X DELETE http://localhost:8080/api/tickets/1 \
  -H "Authorization: Bearer {token}"
```

---

## 🛡️ Regras de Negócio

- ✅ **Login obrigatório:** Todas as rotas de API exigem autenticação via Sanctum.
- ✅ **Auto-fill ao criar:** `solicitante_id` é preenchido automaticamente com o usuário autenticado.
- ✅ **Status padrão:** Novos chamados iniciam em `ABERTO` com prioridade `MEDIA`.
- ✅ **Fechamento automático:** Ao alterar status para `RESOLVIDO`, `resolved_at` é preenchido com `now()`.
- ✅ **Auditoria:** Cada mudança de status cria um registro em `chamado_logs` com: `de`, `para`, `user_id`, `created_at`.
- ✅ **Autorização:** Apenas o solicitante ou admin podem editar/deletar chamados.
- ✅ **Soft Delete:** Chamados deletados não desaparecem do banco, apenas marcados como deletados.

---

## 🧪 Testes Automatizados

A aplicação utiliza **Pest PHP** para garantir qualidade, cobrindo:

```bash
# Rodar todos os testes
php artisan test

# Rodar com output detalhado
php artisan test --verbose

# Rodar teste específico
php artisan test tests/Feature/EditarStatusChamadoTest.php
php artisan test tests/Feature/ConsultarChamadoTest.php
```

### Testes Implementados

1. **ConsultarChamadoTest**
   - ✅ Não permite acesso sem autenticação (retorna 401)
   - ✅ Permite listar chamados com autenticação

2. **EditarStatusChamadoTest**
   - ✅ Não permite editar status sem autenticação (retorna 401)
   - ✅ Atualiza status com autenticação
   - ✅ Cria log de auditoria ao alterar status
   - ✅ Preenche `resolved_at` ao marcar como RESOLVIDO

---

## 📁 Estrutura de Diretórios

```
app/
├── Http/
│   ├── Controllers/
│   │   └── ChamadoController.php       # Endpoints da API
│   ├── Requests/
│   │   ├── ChamadoCreateRequest.php    # Validação de criação
│   │   ├── ChamadoUpdateStatusRequest.php
│   │   └── ListChamadoRequest.php
│   └── Resources/
│       └── ChamadoResource.php         # Transformação para JSON
├── Models/
│   ├── Chamado.php
│   ├── ChamadoLog.php
│   └── User.php
├── Services/
│   └── ChamadoService.php              # Lógica de negócio
├── Policies/
│   └── ChamadoPolicy.php               # Autorização
└── Enums/
    ├── ChamadoStatusEnum.php           # ABERTO, EM_ANDAMENTO, RESOLVIDO
    └── ChamadoPrioridadeEnum.php       # BAIXA, MEDIA, ALTA

database/
├── migrations/
│   └── *_create_chamado_table.php
├── factories/
│   └── ChamadoFactory.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── UserSeeder.php
    └── ChamadoSeeder.php

tests/
├── Feature/
│   ├── EditarStatusChamadoTest.php
│   └── ConsultarChamadoTest.php
└── TestCase.php                        # Base com helpers (authenticated())

routes/
├── api.php                             # Rotas REST
└── auth.php                            # Rotas de autenticação
```

---

## 🔧 Dependências Principais

- `laravel/framework` ^12.0
- `laravel/sanctum` - Autenticação API com tokens
- `laravel/breeze` - Scaffolding de autenticação
- `pestphp/pest` - Framework de testes

---

## 📝 Notas Adicionais

- **Sem interface web para admin:** O projeto foca em API REST; o frontend é HTML simples para testes.
- **Soft Deletes:** Chamados deletados não aparecem em listagens por padrão.
- **Validações:** Todas as validações são server-side (Form Requests).
- **Índices:** `status` e `prioridade` possuem índices para performance em filtros.

---
