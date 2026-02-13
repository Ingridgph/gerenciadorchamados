#!/bin/bash

echo "=== Teste de Rotas GET/POST ==="
echo ""

echo "1. POST /api/auth/login (esperado: POST obrigatório)"
curl -i -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"fake@test.com","password":"12345"}' \
  2>/dev/null | head -20

echo ""
echo "2. GET /api/auth/login (esperado: 405 Method Not Allowed)"
curl -i -X GET http://localhost:8080/api/auth/login 2>/dev/null | head -10

echo ""
echo "3. OPTIONS /api/auth/login (esperado: 200 OK con CORS headers)"
curl -i -X OPTIONS http://localhost:8080/api/auth/login \
  -H "Origin: http://localhost:5500" \
  -H "Access-Control-Request-Method: POST" \
  2>/dev/null | head -15

echo ""
echo "=== Fim dos testes ==="
