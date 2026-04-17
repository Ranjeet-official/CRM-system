# Laravel CRM System

## About Project

This is a Customer Relationship Management (CRM) system built with Laravel.
It helps manage users, clients, projects, and tasks efficiently with advanced features like role-based access, API integration, and activity tracking.

---

## Features

### Core Modules

* User Management (Admin Panel)
* Client Management
* Project Management
* Task Management

---

### Routing Advanced

* Route Model Binding in Resource Controllers
* Homepage redirects to login page

---

### Database Advanced

* Database Seeders & Factories
* Eloquent Query Scopes (e.g., active clients/projects)
* Polymorphic Relationships (Media Uploads)
* Eloquent Accessors & Mutators (custom date formats)
* Soft Deletes (restore deleted data)

---

### Authentication & Authorization

* Role & Permission System (Admin / User)
* Gates & Policies
* Email Verification

---

### API System

* RESTful API Routes & Controllers
* API Resources for clean JSON responses
* Token-based Authentication using Sanctum

---

### Notifications System

* Notifications for:

  * Task assignment
  * Project updates
  * New client creation
* Stored in database

---

### Activity Logs

* Tracks all user activities
* Logs actions like:

  * User creation/update
  * Client updates
  * Project changes
  * Task updates

---

## Tech Stack

* PHP (Laravel)
* MySQL
* JavaScript / AJAX
* Bootstrap / Tailwind CSS

---

## Installation Guide

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database

Update your database credentials in `.env`

### 4. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 5. Run Application

```bash
php artisan serve
```

---

## Roles & Permissions

| Role  | Access             |
| ----- | ------------------ |
| Admin | Full system access |
| User  | Limited access     |

---

## API Example

```bash
GET /api/clients
```

Authentication via API token.

---

## Project Modules

* Users
* Clients
* Projects
* Tasks

---

## License

This project is open-source and available for learning purposes.
