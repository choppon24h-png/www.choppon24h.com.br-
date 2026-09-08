#!/usr/bin/env bash
set -euo pipefail

base_url="http://127.0.0.1:8011/delivery/process-delivery.php"
invalid_status=$(curl -sS -o /tmp/choppon-delivery-invalid.json -w '%{http_code}' -H 'Accept: application/json' -X POST --data-urlencode 'name=Teste' --data-urlencode 'whatsapp=31999999999' --data-urlencode 'city=Belo Horizonte' --data-urlencode 'state=MG' --data-urlencode 'email=invalido' --data-urlencode 'events_experience=sim' --data-urlencode 'delivery_experience=nao' --data-urlencode 'objective=novo_negocio' --data-urlencode 'lgpd=sim' "$base_url")
test "$invalid_status" = "422"

response=$(curl -sS -H 'Accept: application/json' -X POST --data-urlencode 'name=Lead de Teste' --data-urlencode 'whatsapp=31999999999' --data-urlencode 'city=Belo Horizonte' --data-urlencode 'state=MG' --data-urlencode 'email=lead.teste@example.com' --data-urlencode 'events_experience=sim' --data-urlencode 'delivery_experience=nao' --data-urlencode 'objective=novo_negocio' --data-urlencode 'lgpd=sim' "$base_url")
printf '%s' "$response" | grep -q '"success":true'
protocol=$(printf '%s' "$response" | sed -n 's/.*"protocol":"\([^"]*\)".*/\1/p')
test -n "$protocol"
record="/home/ubuntu/choppon-private/delivery-leads/${protocol,,}.json"
test -f "$record"
grep -q 'lead.teste@example.com' "$record"
test ! -e "/home/ubuntu/franchise-form-source/delivery/${protocol,,}.json"
rm -f "$record"
echo "Endpoint de interesse ChoppOn Delivery validado com sucesso."
