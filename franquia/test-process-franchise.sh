#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${1:-http://127.0.0.1:8011}"
TEMP_DIR="$(mktemp -d)"
LEADS_DIR="/home/ubuntu/choppon-private/franchise-leads"
trap 'rm -rf "$TEMP_DIR"' EXIT

invalid_status="$(curl -sS -o "$TEMP_DIR/invalid.json" -w '%{http_code}' -X POST "$BASE_URL/franquia/process-franchise.php" \
  --data-urlencode 'nome=Teste Sintetico' \
  --data-urlencode 'cpf=111.111.111-11' \
  --data-urlencode 'email=teste.sintetico@example.invalid' \
  --data-urlencode 'telefone=(31) 99999-0000' \
  --data-urlencode 'cidade_residencia=Belo Horizonte' \
  --data-urlencode 'cidade_interesse=Sete Lagoas' \
  --data-urlencode 'uf_interesse=MG' \
  --data-urlencode 'capital_disponivel=120' \
  --data-urlencode 'origem_recursos=recursos_proprios' \
  --data-urlencode 'experiencia_negocios=empreendedor' \
  --data-urlencode 'dedicacao=integral' \
  --data-urlencode 'prazo_implantacao=ate_3' \
  --data-urlencode 'objetivo=novo_negocio' \
  --data-urlencode 'status_ponto=mapeando' \
  --data-urlencode 'tipo_ponto=rua' \
  --data-urlencode 'fluxo=alto' \
  --data-urlencode 'metragem=medio' \
  --data-urlencode 'infraestrutura=sim' \
  --data-urlencode 'modelo_interesse=station' \
  --data-urlencode 'aceite_lgpd=sim')"
test "$invalid_status" = "422"
grep -q 'CPF válido' "$TEMP_DIR/invalid.json"

valid_status="$(curl -sS -c "$TEMP_DIR/cookies.txt" -o "$TEMP_DIR/valid.json" -w '%{http_code}' -X POST "$BASE_URL/franquia/process-franchise.php" \
  --data-urlencode 'nome=Teste Sintetico' \
  --data-urlencode 'cpf=529.982.247-25' \
  --data-urlencode 'email=teste.sintetico@example.invalid' \
  --data-urlencode 'telefone=(31) 99999-0000' \
  --data-urlencode 'cidade_residencia=Belo Horizonte' \
  --data-urlencode 'cidade_interesse=Sete Lagoas' \
  --data-urlencode 'uf_interesse=MG' \
  --data-urlencode 'capital_disponivel=120' \
  --data-urlencode 'origem_recursos=recursos_proprios' \
  --data-urlencode 'experiencia_negocios=empreendedor' \
  --data-urlencode 'dedicacao=integral' \
  --data-urlencode 'prazo_implantacao=ate_3' \
  --data-urlencode 'objetivo=novo_negocio' \
  --data-urlencode 'status_ponto=mapeando' \
  --data-urlencode 'tipo_ponto=rua' \
  --data-urlencode 'fluxo=alto' \
  --data-urlencode 'metragem=medio' \
  --data-urlencode 'infraestrutura=sim' \
  --data-urlencode 'modelo_interesse=station' \
  --data-urlencode 'aceite_lgpd=sim')"
test "$valid_status" = "201"
protocol="$(sed -n 's/.*"protocol":"\([^"]*\)".*/\1/p' "$TEMP_DIR/valid.json")"
test -n "$protocol"
record="$LEADS_DIR/$(printf '%s' "$protocol" | tr '[:upper:]' '[:lower:]').json"
test -f "$record"
grep -q '"cpf_hash"' "$record"
grep -q '"cpf_final"' "$record"
! grep -q '52998224725\|529.982.247-25' "$record"
rm -f "$record"
rmdir "$LEADS_DIR" 2>/dev/null || true
rmdir "$(dirname "$LEADS_DIR")" 2>/dev/null || true

printf 'Endpoint de qualificação validado com sucesso.\n'
