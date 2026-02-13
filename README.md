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

*   🔑 **Autenticação:** Acesso restrito a usuários autenticados.
*   📄 **CRUD de Chamados:** Gerenciamento completo de tickets.
*   🔍 **Filtros e Busca:** Filtragem por status/prioridade e busca por texto.
*   ⚡ **Status Inteligente:** Preenchimento automático de `resolved_at` e geração de logs.
*   🛡️ **Segurança:** Regras de exclusão restritas ao solicitante ou administrador.
*   📝 **Auditoria:** Histórico detalhado de alterações de status (`ticket_logs`).

---

## 🚀 Como Rodar o Projeto

### 🐳 Via Docker (Recomendado)

```bash
# Build e sobe os containers
docker-compose up -d --build

# Acessa o container da aplicação
docker exec -it app bash

# Configuração interna
touch database/database.sqlite
composer install
php artisan migrate --seed
php artisan test

## 🌐 URL de Acesso
Acesse a aplicação em: [http://localhost:8080](http://localhost:8080)

---

## 🔑 Endpoints da API

| Método | Rota | Descrição |
| :--- | :--- | :--- |
| `GET` | /api/tickets | Lista tickets com filtros |
| `GET` | /api/tickets/{id} | Detalhes de um ticket |
| `POST` | /api/tickets | Criação de novo chamado |
| `PATCH` | /api/tickets/{id}/status | Atualiza status e gera log |
| `DELETE` | /api/tickets/{id} | Exclusão (Soft Delete) |

---

## 🛡️ Regras de Negócio

*   ✅ **Fechamento:** Ao mudar status para `RESOLVIDO`, o campo `resolved_at` é preenchido na hora.
*   ✅ **Auditoria:** Toda mudança de status (ex: `ABERTO` -> `EM_ANDAMENTO`) gera um registro de log.
*   ✅ **Privacidade:** Um usuário comum não pode excluir chamados de terceiros.
*   ✅ **Persistência:** Uso de Soft Deletes para evitar perda acidental de dados.

---

## 🧪 Usuários de Teste

| Perfil    | E-mail         | Senha       |
| :---      | :---           | :---        |
| **Admin** | admin@test.com | Password123 |
| **Comum** | user@test.com  | Password123 |

---

## 🧪 Testes Automatizados

A aplicação utiliza [Pest PHP](https://pestphp.com) para garantir a qualidade do código, cobrindo:
*   **Segurança:** Autenticação e proteção de rotas.
*   **Integridade:** Lógica de criação de logs em transições de status.
*   **Permissões:** Validação rigorosa via Policies.