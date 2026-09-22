#!/usr/bin/env bash
set -euo pipefail
base="${1:-http://127.0.0.1:18088}"

pagina="$(curl --fail --silent --show-error "$base/")"
grep -q 'Catálogo TechStore' <<<"$pagina"
grep -q 'Teclado Mecânico' <<<"$pagina"
grep -q 'Mouse Sem Fio' <<<"$pagina"
grep -q 'Headset USB' <<<"$pagina"

filtro="$(curl --fail --silent --show-error "$base/?busca=mouse")"
grep -q 'Mouse Sem Fio' <<<"$filtro"
if grep -q 'Teclado Mecânico' <<<"$filtro"; then
  echo 'FALHOU: filtro de busca exibiu produto indevido' >&2
  exit 1
fi

echo 'OK: página HTTP da TechStore'
