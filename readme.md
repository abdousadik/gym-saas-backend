# 🏋️ Gym SaaS Platform — Backend API

A multi-tenant backend system designed to manage gym operations at scale, including memberships, subscriptions, attendance tracking, and staff management.

This project focuses on building a **production-oriented backend architecture**, rather than a simple CRUD application.

---

## 🚀 Overview

This platform allows gyms to:

- Manage members and subscriptions
- Track attendance and activity
- Define membership plans
- Handle internal staff roles
- Operate independently in a multi-tenant environment

Each gym acts as an isolated tenant within the system, ensuring clean data separation and scalability.

---

## 🧱 Architecture

The application follows a **modular monolith architecture**, structured around business domains rather than technical layers.

Client
↓
Symfony API
↓
Domain Modules (Members, Subscriptions, Plans, Attendance, Users)
↓
PostgreSQL

Each module encapsulates its own:

- Controllers
- Services
- Entities
- Repositories

This allows the system to remain maintainable while being ready for future scaling (e.g. caching, queues, microservices if needed).

---

## 🧩 Core Features

### Authentication & Access

- JWT-based authentication
- Platform-level and gym-level roles
- Secure access to tenant-specific data

### Multi-Tenant Structure

- Each gym operates in isolation
- Shared infrastructure with logical separation via `gym_id`

### Member Management

- Member profiles with lifecycle management
- Status handling (active, inactive)

### Membership Plans

- Configurable plans (duration, pricing)
- Activation/deactivation support

### Subscriptions

- Plan assignment and renewal
- Status tracking (active, expired, cancelled)

### Attendance Tracking

- Check-in / check-out system
- Daily attendance monitoring

### Dashboard

- Aggregated metrics:
  - Active members
  - Active subscriptions
  - Daily attendance

---

## ⚙️ Tech Stack

- **Backend:** PHP 8.x, Symfony
- **Database:** PostgreSQL
- **Authentication:** JWT
- **Containerization:** Docker / Docker Compose
- **API:** RESTful JSON

---

## 📦 Project Structure

src/
Module/
Auth/
Gym/
User/
Member/
Plan/
Subscription/
Attendance/
Common/

The codebase is organized by domain to keep boundaries clear and business logic centralized

---

## 🛠️ Getting Started

### Prerequisites

- Docker
- Docker Compose

### Installation

```bash
git clone https://github.com/abdousadik/gym-saas-backend.git
cd gym-saas-backend

docker-compose up --build
```

### Environment

Configure your .env file for:

Database connection
JWT secrets

---

## 🔐 Example Flow

1. A gym registers via onboarding
2. The first user becomes the gym owner
3. Staff members are added with roles
4. Membership plans are created
5. Members subscribe to plans
6. Attendance is tracked daily

---

## 📌 Notes

The system is intentionally built as a modular monolith to balance simplicity and scalability
Designed with future extensions in mind:

- caching (Redis)
- background jobs (queues)
- cloud deployment (AWS)

---

## 📈 Roadmap

- Redis caching layer
- Background job processing (queue system)
- Cloud deployment (AWS)
- CI/CD pipeline
- Frontend dashboard (React)

---

## 🤝 About

This project is part of a broader effort to build production-grade backend systems, focusing on:

- clean architecture
- scalability
- real-world use cases
