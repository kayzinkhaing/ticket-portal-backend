

---

# ✅ 📄 **FINAL Backend README.md (Professional Standard)**

```markdown
# 🎫 Ticket Portal Backend API

A scalable, production-ready Ticket Management System backend built with Laravel, following modern engineering standards including SDLC, Clean Architecture, OOP, and SOLID principles.

This API supports multi-organization ticket handling, SLA tracking, role-based access control, and asynchronous notification processing using queues and schedulers.

---

## 🚀 Tech Stack

- **Framework**: Laravel (API-first design)
- **Authentication**: Laravel Passport (OAuth2)
- **Database**: MySQL 8
- **Caching**: Redis (tag-based caching)
- **Queue & Scheduler**: Laravel Queue + Scheduler (Docker-managed)
- **Containerization**: Docker (Multi-service architecture)
- **Testing**: PHPUnit (Unit testing)

---

## 🧠 Architecture Overview

This project follows a **Clean N-Tier Architecture**:

### Layers

- Controller Layer (API endpoints)
- Service Layer (business logic)
- Repository Layer (data access abstraction)
- Interface Layer (contracts for dependency inversion)
- Model Layer (Eloquent ORM)

---

## 🧩 Design Principles

### OOP & SOLID

The system is designed using strong object-oriented principles:

- **Single Responsibility**  
  Each class has a clearly defined responsibility (Controller, Service, Repository).

- **Open/Closed Principle**  
  Services are extendable without modifying existing logic.

- **Dependency Inversion**  
  Controllers depend on interfaces instead of concrete implementations.

- **DRY (Don't Repeat Yourself)**  
  One Model → One Controller → One Service → One Repository.

---

## 🏗️ Structural Pattern

Each module follows a consistent structure:

```

Model
├── Controller (extends BaseController)
├── Service
├── Repository
├── Interface

```

- BaseController handles reusable CRUD logic.
- Child controllers extend BaseController for consistency.

---

## 🔐 Authentication & Authorization

- OAuth2 authentication via Laravel Passport
- Role-Based Access Control (RBAC)

### Roles

#### Client User
- Access tickets within their organization
- Create and view comments (no internal notes)

#### Support Agent
- Access tickets across organizations
- Manage ticket lifecycle (status, priority, assignment)
- View internal notes

---

## 🎫 Ticket Lifecycle & SLA

### Status Flow

```

Open → In Progress → Resolved → Closed

```

### Priority-Based SLA

- SLA is dynamically calculated based on priority
- SLA States:
  - On Track
  - Due Soon
  - Overdue

---

## ⚡ Advanced Backend Features

### 🧠 Redis Caching (Tag-Based)

- Implemented using Redis cache tagging
- Centralized cache invalidation via **BaseModel Observers**
- Automatic cache flush on CRUD operations

---

### 🔄 Queue & Notification System

Asynchronous notification system for ticket lifecycle events:

#### Implemented Jobs

- SendTicketCreatedEmail
- SendTicketClosedEmail
- SendTicketDueSoonEmail
- SendTicketOverdueEmail

#### Supporting Components

- Mail classes for each event
- Blade templates for email rendering
- Console command: `NotifyTickets`

---

### ⏰ Scheduler & Queue Execution

Queue workers and scheduler are **fully managed via Docker services**:

- No manual `queue:work` required
- No manual `schedule:work` required

#### Running Services

- `ticket-queue` → processes jobs
- `ticket-scheduler` → executes scheduled commands

---

## 🌐 API Design

### Versioning

```

/api/v1/*

```

---

## 🔓 Authentication Routes (Public)

```

POST /api/auth/register
POST /api/auth/login
POST /api/auth/verify-email
POST /api/auth/resend-code
POST /api/auth/forgot-password
POST /api/auth/reset-password
POST /api/auth/resend-code-password
GET  /api/auth/google
GET  /api/auth/github

```

---

## 🔐 Authenticated Routes

```

GET /api/v1/verify-email
POST /api/v1/change-password
POST /api/v1/two-factor/enable
POST /api/v1/logout

```

---

## 🌐 Public API

```

GET /api/v1/organizations
GET /api/v1/organizations/{id}

```

---

## 📦 Core API Modules

### 🎫 Tickets

```

GET    /api/v1/tickets
POST   /api/v1/tickets
GET    /api/v1/tickets/{id}
PUT    /api/v1/tickets/{id}
DELETE /api/v1/tickets/{id}

```

### ⚡ Ticket Custom Features

```

GET /api/v1/tickets/status
GET /api/v1/tickets/organization
GET /api/v1/tickets/status-counts
GET /api/v1/tickets/advanced-search

```

---

### 💬 Comments

```

GET    /api/v1/comments
POST   /api/v1/comments
GET    /api/v1/comments/{id}
PUT    /api/v1/comments/{id}
DELETE /api/v1/comments/{id}

```

---

### 👥 Users & Agents

```

GET /api/v1/agents
GET /api/v1/users
POST /api/v1/users
PUT /api/v1/users/{id}
DELETE /api/v1/users/{id}

```

---

### 🔐 Roles & Permissions

```

GET /api/v1/roles
POST /api/v1/roles
PUT /api/v1/roles/{id}
DELETE /api/v1/roles/{id}

```

---

### 📊 Dashboard

```

GET /api/v1/dashboard

```

---

## 🗄️ Database Design

### Core Entities

- Users
- Roles & Permissions
- Organizations
- Client Profiles
- Tickets
- Comments
- Ticket Statuses
- Ticket Priorities
- Ticket Status Histories
- Media (Polymorphic)

---

### 🔗 Relationships

- Organization → Users
- Ticket → Comments
- Ticket → Status History
- Media → Polymorphic relation

---

## ⚡ Database Optimization

- Indexed foreign keys
- Pagination for large datasets
- Eager loading for relationships (avoiding N+1 queries)
- Normalized schema design

---

## 🧪 Testing Strategy

Unit tests implemented for:

- BaseRepository
- BaseService
- Models
- Traits

Focus:

- Business logic validation
- Reusability of core components
- Data integrity

---

## 🐳 Docker Setup

### 1. Clone Repository

```

git clone [https://github.com/kayzinkhaing/ticket-portal-backend](https://github.com/kayzinkhaing/ticket-portal-backend)

```

---

### 2. Setup Environment

```

cp .env.example .env
composer install

```

---

### 3. Run Docker

```

cd docker
docker compose -p ticketbackend up -d --build

```

---

### 4. Enter Container

```

docker compose -p ticketbackend exec app bash

```

---

### 5. Setup Passport

```

php artisan passport:install

```

Add to `.env`:

```

PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=YOUR_SECRET

```

---

### 6. Run Migration

```

php artisan migrate --seed

```

---

## 📦 Running Services (Docker)

| Service           | Description                  |
|------------------|-----------------------------|
| ticket-app       | Laravel application         |
| ticket-nginx     | Web server                  |
| ticket-mysql     | Database                    |
| ticket-phpmyadmin| DB UI                       |
| ticket-queue     | Queue worker (auto-run)     |
| ticket-scheduler | Scheduler (auto-run)        |

---

## 🔐 Security

- OAuth2 authentication
- Role-based access control
- Input validation
- Secure password hashing

---

## ⚡ Performance

- Redis caching
- Queue-based background processing
- Optimized queries with eager loading
- Pagination for large data

---

## ⏱️ Timebox Scope

### Implemented

- Authentication & roles
- Ticket lifecycle & SLA
- Queue notifications
- Clean architecture
- API versioning

### Not Included

- Real-time notifications (WebSockets)
- Advanced analytics dashboards
- Full audit logging system

---

## 🚧 Next Steps

- Implement WebSocket-based notifications
- Add API rate limiting
- Expand integration testing
- Add monitoring & logging tools

---

## ⚠️ Known Limitations

- SLA assumes 24/7 operation
- Queue delay may affect email timing
- No real-time UI updates

---

## 📎 Repository

https://github.com/kayzinkhaing/ticket-portal-backend
```

---
