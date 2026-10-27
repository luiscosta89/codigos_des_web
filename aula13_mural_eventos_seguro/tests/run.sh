#!/usr/bin/env sh
set -eu
ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
DB="aula13-db-$$"; WEB="aula13-web-$$"; NET="aula13-net-$$"; PORT="${PORT:-18083}"
cleanup() {
  docker rm -f "$WEB" "$DB" >/dev/null 2>&1 || true
  docker network rm "$NET" >/dev/null 2>&1 || true
  python3 -c "from pathlib import Path; [p.unlink() for p in Path('$ROOT/publicar/uploads').glob('*.bin')]"
}
trap cleanup EXIT INT TERM
cleanup
docker network create "$NET" >/dev/null
docker run -d --name "$DB" --network "$NET" -e MARIADB_ROOT_PASSWORD=root -e MARIADB_DATABASE=mural_aula13 -e MARIADB_USER=aula13 -e MARIADB_PASSWORD=teste-aula13 mariadb:10.5 >/dev/null
for i in $(seq 1 60); do
  docker exec "$DB" mariadb -uaula13 -pteste-aula13 mural_aula13 -e 'SELECT 1' >/dev/null 2>&1 && break
  [ "$i" -lt 60 ] || { echo 'MariaDB não iniciou a tempo.' >&2; exit 1; }
  sleep 1
done
docker exec -i "$DB" mariadb -uaula13 -pteste-aula13 mural_aula13 < "$ROOT/banco/01_estrutura.sql"
docker exec -i "$DB" mariadb -uaula13 -pteste-aula13 mural_aula13 < "$ROOT/banco/02_dados.sql"
docker run --rm -v "$ROOT:/app" -w /app techstore-php-test sh -lc 'find publicar tests -type f -name "*.php" -print0 | xargs -0 -n1 php -l'
docker run --rm -v "$ROOT:/app" -w /app techstore-php-test php tests/unit.php
docker run --rm --network "$NET" -e DB_HOST="$DB" -v "$ROOT:/app" -w /app techstore-php-test php tests/integracao.php
docker run -d --name "$WEB" --network "$NET" -p "$PORT:8000" -e DB_HOST="$DB" -e DB_USER=aula13 -e DB_PASS=teste-aula13 -e DB_NAME=mural_aula13 -v "$ROOT/publicar:/app" -w /app techstore-php-test php -S 0.0.0.0:8000 >/dev/null
for i in $(seq 1 30); do
  curl -fsS "http://127.0.0.1:$PORT/login.php" >/dev/null 2>&1 && break
  [ "$i" -lt 30 ] || { echo 'Servidor PHP não iniciou a tempo.' >&2; exit 1; }
  sleep 1
done
cd "$ROOT"
BASE_URL="http://127.0.0.1:$PORT" python3 tests/http_test.py
docker exec "$DB" mariadb -uaula13 -pteste-aula13 mural_aula13 -N -e "SELECT CONCAT('contagens finais: usuarios=',(SELECT COUNT(*) FROM usuarios),', eventos=',(SELECT COUNT(*) FROM eventos));"
echo 'Todas as verificações passaram; containers serão removidos.'
