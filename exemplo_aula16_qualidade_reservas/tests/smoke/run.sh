#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
base='http://127.0.0.1:18016/campus/departamento/aula16'
cleanup(){ docker compose down -v --remove-orphans >/dev/null 2>&1 || true; }
trap cleanup EXIT
docker compose down -v --remove-orphans >/dev/null 2>&1 || true
docker compose up -d --build --wait

index_status=$(curl -sS -o /dev/null -w '%{http_code}' "$base/")
[ "$index_status" = 200 ] || { echo "index: HTTP $index_status"; exit 1; }
get_body=$(curl -fsS "$base/api/index.php")
python3 -c 'import json,sys; d=json.load(sys.stdin); assert len(d["equipamentos"]) == 3 and len(d["reservas"]) == 2' <<<"$get_body"

payload='{"equipamento_id":1,"solicitante":"Smoke Test","inicio":"2099-06-01 12:00:00","fim":"2099-06-01 13:00:00"}'
unauthorized=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: application/json' -d "$payload" "$base/api/index.php")
[ "$unauthorized" = 401 ] || { echo "POST sem chave: HTTP $unauthorized"; exit 1; }
unsupported=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: text/plain' -H 'X-API-Key: chave-smoke-aula16' -d "$payload" "$base/api/index.php")
[ "$unsupported" = 415 ] || { echo "POST sem JSON: HTTP $unsupported"; exit 1; }
invalid_json=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: application/json' -H 'X-API-Key: chave-smoke-aula16' -d '{' "$base/api/index.php")
[ "$invalid_json" = 400 ] || { echo "JSON inválido: HTTP $invalid_json"; exit 1; }
invalid_period=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: application/json' -H 'X-API-Key: chave-smoke-aula16' -d '{"equipamento_id":1,"solicitante":"Período inválido","inicio":"2099-06-01 14:00:00","fim":"2099-06-01 13:00:00"}' "$base/api/index.php")
[ "$invalid_period" = 422 ] || { echo "período inválido: HTTP $invalid_period"; exit 1; }
method=$(curl -sS -o /dev/null -w '%{http_code}' -X PUT "$base/api/index.php")
[ "$method" = 405 ] || { echo "método inválido: HTTP $method"; exit 1; }
created=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: application/json' -H 'X-API-Key: chave-smoke-aula16' -d "$payload" "$base/api/index.php")
[ "$created" = 201 ] || { echo "POST válido: HTTP $created"; exit 1; }
overlap=$(curl -sS -o /dev/null -w '%{http_code}' -H 'Content-Type: application/json' -H 'X-API-Key: chave-smoke-aula16' -d '{"equipamento_id":1,"solicitante":"Conflito","inicio":"2099-06-01 12:30:00","fim":"2099-06-01 14:00:00"}' "$base/api/index.php")
[ "$overlap" = 409 ] || { echo "sobreposição: HTTP $overlap"; exit 1; }
final_body=$(curl -fsS "$base/api/index.php")
python3 -c 'import json,sys; d=json.load(sys.stdin); assert len(d["reservas"]) == 3' <<<"$final_body"
echo "SMOKE OK: index=200, GET=200, 401 sem chave, 415 sem JSON, 400 JSON inválido, 422 período, 405 método, 201 criação, 409 sobreposição, reservas=3"
