# Mission Dashboard

A Laravel 12 application with Livewire, Filament 4, and Pest.

## Requirements

- Docker & Docker Compose
- Composer
- Node.js & npm

## Tech Stack

- **Laravel 12** - PHP Framework
- **Livewire 3.7** - Full-stack framework for Laravel
- **Filament 4** - Admin panel builder
- **Laravel Reverb** - WebSocket server for real-time features
- **Pest 3** - Testing framework
- **PostgreSQL 18** - Database
- **Mailpit** - Email testing
- **pgAdmin** - Database management

## Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd mission-dashboard
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node.js dependencies:

```bash
npm install
```

4. Copy environment file:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

## Running with Docker (Laravel Sail)

Start all services:

```bash
./vendor/bin/sail up -d
```

Run migrations:

```bash
./vendor/bin/sail artisan migrate
```

Build frontend assets:

```bash
./vendor/bin/sail npm run dev
```

Start WebSocket server (for real-time messaging):

```bash
./vendor/bin/sail artisan reverb:start
```

Stop services:

```bash
./vendor/bin/sail down
```

## Available Services

| Service | URL |
|---------|-----|
| Application | <http://localhost> |
| Filament Admin | <http://localhost/admin> |
| Mailpit | <http://localhost:8025> |
| pgAdmin | <http://localhost:5050> |

### pgAdmin Credentials

- Email: `admin@admin.com`
- Password: `admin`

### Connecting pgAdmin to PostgreSQL

1. Open pgAdmin at <http://localhost:5050>
2. Add a new server with:
   - Host: `pgsql`
   - Port: `5432`
   - Database: `laravel`
   - Username: `sail`
   - Password: `password`

## Creating a Filament Admin User

```bash
./vendor/bin/sail artisan make:filament-user
```

## Running Tests

```bash
./vendor/bin/sail test
```

Or with Pest directly:

```bash
./vendor/bin/sail pest
```

## Development

Build assets for development:

```bash
./vendor/bin/sail npm run dev
```

Build assets for production:

```bash
./vendor/bin/sail npm run build
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
