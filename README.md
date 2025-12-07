# Mission Dashboard

A modern internal portal for **Digital Services Companies (ESN/IT Consulting firms)** to streamline mission management and talent allocation. Think of it as a mini LinkedIn for your organization, connecting consultants with commercial teams.

## 🎯 Overview

Managing missions and talents in a digital services company is often complex. Consultants are regularly assigned to client projects, while sales/commercial teams need to quickly identify the right profiles for each opportunity.

**Mission Dashboard** centralizes mission management and applications, facilitating the connection between consultants and commercial teams, while giving administrators a complete view of platform activity.

### Key Features

- **Mission Broadcasting** — Commercial teams can post missions with tags (developer, DevOps, AI, etc.)
- **Smart Notifications** — Consultants receive automatic notifications when missions match their skills
- **Streamlined Applications** — Consultants can easily apply to missions and track their application status
- **Real-time Messaging** — Built-in messaging system for quick exchanges between consultants and commercial teams
- **Admin Dashboard** — Complete oversight with user management, tags, missions, and activity statistics

---

## 👥 Platform Actors

### 🧑‍💻 Consultant

Consultants create their profile, apply to missions, and receive notifications tailored to their skills.

**Capabilities:**

- Create and manage profile (bio, skills, tags, CV)
- Browse and filter available missions by tag/skill
- Apply to missions and track application history
- Receive notifications for matching missions
- Exchange messages with commercial teams

![Consultant Interface](docs/images/consultant.png)

---

### 💼 Commercial

Commercial teams publish missions, review applications, and contact qualified consultants.

**Capabilities:**

- Create, edit, and archive missions
- Associate tags with missions
- View and filter applications by consultant skills
- Access detailed consultant profiles
- Update application status (pending, accepted, rejected)
- Contact consultants via messaging

![Commercial Interface](docs/images/commercial.png)

---

### 🛠️ Administrator

Administrators supervise the platform, manage users and tags, and access activity statistics.

**Capabilities:**

- Manage users (consultants, commercial, admins)
- Define and modify roles and permissions
- Manage the tag library (dev, cloud, data, AI...)
- View all missions and platform activity
- Access dashboard with statistics
- Configure platform settings (e.g., allowed email domains)

![Admin Interface](docs/images/admin.png)

---

## 🛠️ Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12 | PHP Framework |
| **Livewire** | 3.7 | Full-stack framework for Laravel |
| **Filament** | 4 | Admin panel builder |
| **Laravel Reverb** | - | WebSocket server for real-time features |
| **Pest** | 3 | Testing framework |
| **PostgreSQL** | 16 | Database |
| **Redis** | 7 | Cache, queues, sessions |
| **Tailwind CSS** | 4 | Styling |
| **Mailpit** | - | Email testing |
| **pgAdmin** | - | Database management |

---

## 🚀 Getting Started

### Prerequisites

- Docker & Docker Compose

### Installation

1. **Clone the repository:**

```bash
git clone https://github.com/francky-d/mission-dashboard.git
cd mission-dashboard
```

2. **Copy environment file:**

```bash
cp .env.example .env
```

3. **Install PHP dependencies (first time only):**

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

4. **Start all services:**

```bash
./vendor/bin/sail up -d
```

5. **Generate application key:**

```bash
./vendor/bin/sail artisan key:generate
```

6. **Run migrations and seed the database:**

```bash
./vendor/bin/sail artisan migrate --seed
```

7. **Install Node dependencies and build frontend assets:**

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

8. **Start WebSocket server (for real-time messaging):**

```bash
./vendor/bin/sail artisan reverb:start
```

### Stopping Services

```bash
./vendor/bin/sail down
```

### Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@example.com` | `password` |
| Commercial | `commercial@example.com` | `password` |
| Consultant | `consultant@example.com` | `password` |

---

## 🌐 Available Services

| Service | URL | Description |
|---------|-----|-------------|
| Consultant Space | <http://localhost/consultant> | Consultant dashboard and mission applications |
| Commercial Space | <http://localhost/commercial> | Commercial dashboard and mission management |
| Admin Panel | <http://localhost/admin> | Administration interface (Filament) |
| Mailpit | <http://localhost:8025> | Email testing interface |
| pgAdmin | <http://localhost:5050> | PostgreSQL database management |

### Consultant & Commercial Interfaces

- **Consultant Space** (`/consultant`): Profile management, mission browsing, applications, messaging
- **Commercial Space** (`/commercial`): Mission creation, application review, consultant profiles

### Mailpit (Email Testing)

All emails sent by the application are captured by Mailpit. Access the web interface at <http://localhost:8025> to view sent emails (notifications, password resets, etc.).

### pgAdmin (Database Management)

**Credentials:**

- **Email:** `admin@admin.com`
- **Password:** `admin`

**Connecting to PostgreSQL:**

1. Open pgAdmin at <http://localhost:5050>
2. Add a new server with:
   - **Host:** `pgsql`
   - **Port:** `5432`
   - **Database:** `laravel`
   - **Username:** `sail`
   - **Password:** `password`

---

## 🧪 Running Tests

```bash
./vendor/bin/sail artisan test
```

Or with Pest directly:

```bash
./vendor/bin/sail pest
```

---

## 📦 Development

**Build assets for development:**

```bash
./vendor/bin/sail npm run dev
```

**Build assets for production:**

```bash
./vendor/bin/sail npm run build
```

**Run code linting:**

```bash
./vendor/bin/sail bin pint
```

**Run static analysis:**

```bash
./vendor/bin/sail php vendor/bin/phpstan analyse
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
