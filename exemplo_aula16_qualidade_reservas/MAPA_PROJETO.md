# Mapa literal do projeto

```text
exemplo_aula16_qualidade_reservas/
├── api/
│   ├── config.php
│   ├── index.php
│   ├── src/
│   │   ├── database.php
│   │   ├── http.php
│   │   ├── repository.php
│   │   └── validation.php
│   └── tests/run.php
├── dist/                         # gerado por npm run build
│   ├── api/                      # cópia publicável, sem testes
│   ├── assets/
│   ├── index.html
│   └── robots.txt
├── public/robots.txt
├── scripts/copy-api.js
├── sql/
│   ├── schema.sql
│   └── seed.sql
├── src/
│   ├── components/
│   │   ├── Lists.jsx
│   │   ├── Lists.test.jsx
│   │   └── ReservationForm.jsx
│   ├── lib/
│   │   ├── api.js
│   │   ├── api.test.js
│   │   ├── period.js
│   │   └── period.test.js
│   ├── test/setup.js
│   ├── App.jsx
│   ├── App.test.jsx
│   ├── index.css
│   └── main.jsx
├── tests/smoke/
│   ├── compose.yaml
│   ├── config.php
│   ├── Dockerfile
│   └── run.sh
├── eslint.config.js
├── index.html
├── package.json
├── package-lock.json
├── ATIVIDADE_FINAL_AULA16.md
├── README-PUBLICACAO.md
├── README.md
└── vite.config.js
```

`node_modules/` e `coverage/` são locais e não fazem parte da publicação. Somente o conteúdo de `dist/` vai para a subpasta pública. Os arquivos `sql/` são importados separadamente no banco já criado pela hospedagem.
