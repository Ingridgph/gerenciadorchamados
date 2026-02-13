#🐞 Teste Técnico Laravel – Gestão de Chamados

Aplicação simples de Gestão de Chamados (Tickets), focada em Back-end com autenticação, CRUD de chamados, logs de status e regras de negócio.

##💻 Stack

Laravel 12+

Banco de dados: SQLite

Autenticação: Laravel Breeze

Testes: Pest

Opcional: Laravel Sanctum para API

Docker disponível para rodar rapidamente

##✨ Funcionalidades

🔑 Autenticação obrigatória

📄 CRUD completo de chamados

🔍 Filtros por status e prioridade

🔎 Busca por texto em título ou descrição

⚡ Atualização de status: resolved_at preenchido automaticamente e log de alteração

🛡️ Apenas solicitante ou admin podem excluir chamados

##🧪 Usuários de Teste

Admin: admin@test.com
 / Password123

Comum: user@test.com
 / Password123

Chamados: 10 exemplos com status e prioridades variadas

#🚀 Como rodar
##🐳 Docker
docker-compose up -d --build


Acesse: http://localhost:8080

##🔑 Endpoints da API
Método	Rota	Descrição
GET	/api/tickets	Lista tickets (com filtros)
GET	/api/tickets/{id}	Detalha ticket
POST	/api/tickets	Cria ticket
PATCH	/api/tickets/{id}/status	Atualiza status e cria log
DELETE	/api/tickets/{id}	Exclui ticket (soft delete)

##🛡️ Regras de Negócio

resolved_at preenchido automaticamente ao marcar RESOLVIDO

Apenas solicitante ou admin podem deletar

Logs registram toda mudança de status

Filtros: status e prioridade

Busca: título ou descrição

##🧪 Testes

✅ Usuário não autenticado não acessa tickets
✅ PATCH de status cria log e preenche resolved_at

##💖 Observações

Todos os recursos usam API Resources

Soft deletes ativados

Código organizado com Requests, Resources, Policies e Services

##
# Build e sobe o container
docker-compose up -d --build

# Acessa o container
docker exec -it <nome_do_container> bash

# Cria o banco SQLite
touch database/database.sqlite

# Instala dependências PHP
composer install

# Roda migrations e seeders
php artisan migrate --seed

# Roda testes
php artisan test
