# Product Inventory Management Project

A Laravel-based REST API application with authentication, database seeding, and comprehensive testing.

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / PostgreSQL / SQLite (This project will use SQLite by default)

## Spin Up

```bash
- composer install
- npm install && npm run build
- composer run dev

- php artisan migrate
- php artisan db:seed
```

The app will be available at: http://localhost:8000

## Test With Postman

Visit:

```
http://localhost:8000/docs/api
```

for more API details, and you can play around with POSTMAN.

**NOTE:** After logging in, copy the token and paste it into the Authorization tab in Postman. Choose **Bearer Token**.

## Features Test

```bash
php artisan test
```

## Useful Commands

| Command                            | Description                        |
| ---------------------------------- | ---------------------------------- |
| `composer run dev`                 | Start dev server with all services |
| `php artisan migrate`              | Run database migrations            |
| `php artisan migrate:fresh --seed` | Reset DB and reseed                |
| `php artisan db:seed`              | Seed the database                  | npm run check -- --fix |
| `php artisan test`                 | Run the test suite                 |
| `npm run build`                    | Build frontend assets              |
| `npm run dev`                      | Start Vite dev server              |

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
