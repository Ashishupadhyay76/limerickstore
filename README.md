## Local Docker environment

Requires Docker with Compose. Runs PHP 8.5 + Apache (`wordpress:php8.5-apache`) and MySQL 8.0.
The repo is mounted as the web root; `docker/wp-config.php` is mounted over `wp-config.php` inside the container only, so the real file is untouched.

Start:

```sh
docker compose up -d
```

- Site: http://localhost:8080
- MySQL from the host: `127.0.0.1:3307` (user `limerick_local`, see `docker-compose.yml`)
- Database data lives in the named volume `db_data`. `docker compose down -v` deletes it.

The database starts empty. Import your own dump (never commit it) with:

```sh
docker compose exec -T db sh -c 'mysql -ulimerick_local -plocal-password-change-me limerickstore' < your-dump.sql
```

Then rewrite URLs:

```sh
docker compose run --rm wpcli search-replace 'https://www.limerickstore.com' 'http://localhost:8080' --all-tables
```

WP-CLI (runs in a one-off `wpcli` container with the same mounts):

```sh
docker compose run --rm wpcli core version
docker compose run --rm wpcli plugin list
```

Logs: `docker compose logs -f web`, and `wp-content/debug.log` (WP_DEBUG is on).
