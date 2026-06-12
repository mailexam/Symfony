# Symfony + Mailexam

Minimal [Symfony](https://symfony.com/) example that sends test mail through [Mailexam](https://mailexam.io/) SMTP via [Symfony Mailer](https://symfony.com/doc/current/mailer.html).

Based on the [Mailexam Symfony guide](https://wiki.mailexam.ru/en/examples/symfony/).

## What you need

- A Mailexam account and a project with SMTP credentials.
- PHP 8.2+ and [Composer](https://getcomposer.org/).

From your Mailexam welcome email or dashboard:

| Variable | Description |
|----------|-------------|
| `MAILEXAM_LOGIN` | SMTP login (for example, `xxxxx`) |
| `MAILEXAM_PASSWORD` | SMTP password (paired with the login) |
| Host | `{MAILEXAM_LOGIN}.mailexam.io` (used in `MAILER_DSN`) |

## Quick start (host)

1. Install dependencies:

```bash
composer install
```

2. Copy the example environment file and fill in your credentials:

```bash
cp .env.example .env
```

3. Edit `.env` — set `MAILEXAM_LOGIN`, `MAILEXAM_PASSWORD`, and a random `APP_SECRET`:

```env
APP_SECRET=your-random-secret
MAILEXAM_LOGIN=YOUR_LOGIN
MAILEXAM_PASSWORD=YOUR_PASSWORD
MAILEXAM_PORT=587
MAIL_FROM=noreply@example.test

MAILER_DSN=smtp://${MAILEXAM_LOGIN}:${MAILEXAM_PASSWORD}@${MAILEXAM_LOGIN}.mailexam.io:${MAILEXAM_PORT}
```

4. Run the server:

```bash
symfony server:start
# or: php -S 127.0.0.1:8000 -t public
```

The server listens on `http://127.0.0.1:8000` by default.

5. Send a test message:

```bash
curl -X POST http://127.0.0.1:8000/mail/test \
  -H 'Content-Type: application/json' \
  -d '{"to":"user@example.test","subject":"Test","body":"Hello"}'
```

The message appears in the Mailexam dashboard → your project → inbox.

## Environment variables

| Variable | Required | Default | Description |
|----------|----------|---------|-------------|
| `MAILEXAM_LOGIN` | yes | — | SMTP login; subdomain of the host in `MAILER_DSN` |
| `MAILEXAM_PASSWORD` | yes | — | SMTP password |
| `MAILEXAM_PORT` | no | `587` | SMTP port (`587`, `2525`, or `25`) |
| `MAIL_FROM` | no | `noreply@example.test` | Sender address |
| `MAILER_DSN` | yes | — | Symfony Mailer DSN (see `.env.example`) |
| `APP_SECRET` | yes | — | Symfony app secret (any random string for local dev) |
| `HTTP_HOST` | no | `127.0.0.1` | HTTP bind address (Docker) |
| `HTTP_PORT` | no | `8000` | HTTP listen port (Docker) |

If the password contains `@`, `:`, `/`, or other DSN characters, URL-encode it with `rawurlencode()` or set `MAILER_DSN` manually as a single line in `.env.local`.

## Project layout

```
.
├── composer.json
├── config/packages/mailer.yaml
├── src/Controller/MailController.php
├── public/index.php
├── .env.example
├── Dockerfile         # for local debugging only
└── docker-compose.yml
```

## Docker (debugging)

Docker is provided for local debugging. For day-to-day development, run the app on the host (see above).

```bash
cp .env.example .env
# edit .env with your credentials and APP_SECRET

docker compose up --build
```

Then call the same endpoint on the mapped port:

```bash
curl -X POST http://127.0.0.1:8000/mail/test \
  -H 'Content-Type: application/json' \
  -d '{"to":"user@example.test","subject":"Test","body":"Hello"}'
```

Inside the container the server binds to `0.0.0.0:8000` so the port mapping works.

## CI

Set these secrets in your CI environment:

```yaml
variables:
  MAILEXAM_LOGIN: $MAILEXAM_LOGIN
  MAILEXAM_PASSWORD: $MAILEXAM_PASSWORD
  MAILEXAM_PORT: "587"
  MAIL_FROM: "noreply@example.test"
  MAILER_DSN: "smtp://${MAILEXAM_LOGIN}:${MAILEXAM_PASSWORD}@${MAILEXAM_LOGIN}.mailexam.io:587"
```

After sending a message in a test, verify delivery via the [Mailexam API](https://mailexam.io/api).

For unit tests without real sending, use `null://null` as the mailer DSN in the test environment.

## Troubleshooting

**Authentication failed / connection refused**

- Check `MAILER_DSN`: host `{login}.mailexam.io`, login and password must be a pair from the same Mailexam project.

**Error due to characters in password**

- URL-encode the password in the DSN or set the full DSN string manually in `.env.local`.

**Configuration cache**

- After changing `.env`: `php bin/console cache:clear`.

**Message not in the dashboard**

- Open the inbox of the same Mailexam project.

## See also

- [Mailexam Symfony guide (wiki)](https://wiki.mailexam.ru/en/examples/symfony/)
- [Laravel](https://github.com/mailexam/Laravel) and [Yii](https://github.com/mailexam/Yii) — other PHP frameworks
- [Symfony Mailer documentation](https://symfony.com/doc/current/mailer.html)
- [Mailexam API documentation](https://mailexam.io/api)

## License

Apache 2.0
