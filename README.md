**Gestão de Hortifruti – Frutas (PHP + MySQL)**

Projeto simples para listar e remover frutas de um banco MySQL.
Frontend em HTML + Tailwind (CDN) com jQuery/AJAX consumindo APIs em PHP (PDO).
Remoção é feita via soft delete (não apaga de fato, só marca como removida).

🧰 Tecnologias e técnicas

- PHP + PDO (MySQL): conexão segura, ERRMODE_EXCEPTION, utf8mb4.
- Soft delete: coluna removed_at (NULL = ativo; NOW() = removido).

APIs REST simples:

- GET /api-list.php → retorna JSON com frutas ativas.
- POST /api-delete.php → { id } faz soft delete.

Frontend:

- jQuery (3.7) para AJAX (+ delegação de eventos).
- Tailwind (browser) para estilo responsivo sem build.
- Renderização em cards <div> (sem <table>) para melhor mobile.
