```markdown
# 🚗 Drophere Backend API

This repository contains the **backend REST API** for the Drophere carpooling application, built with **PHP**. It handles all core server-side operations, including user authentication, ride management, driver and vehicle data handling, and real-time updates.

---

## 🔍 Project Overview

The **Drophere** backend acts as the bridge between the mobile/web clients and the database. It provides secure, scalable, and well-structured endpoints to manage all aspects of the carpooling platform.

This API supports:
- User & driver registration/login
- Ride creation, matching, and management
- Vehicle data management
- File uploads (e.g., driver licenses, vehicle documents)

---

## 🛠️ Tech Stack

- **PHP** – Server-side scripting language.
- **MySQL** – Relational database to store user, vehicle, and trip data.
- **RESTful APIs** – Clean, scalable, and secure endpoints.
- **JWT** – For user authentication and authorization.
- **CORS** – Handled via custom middleware.

---

## 📁 Project Structure

```
/src
│
├── Controller/              # Handles HTTP requests and business logic
│   ├── AuthController.php
│   ├── DriverController.php
│   ├── FileUploadController.php
│   ├── RideController.php
│   ├── UserController.php
│   └── VehicleController.php
│
├── Middleware/              # Middleware for request handling
│
├── Repository/              # Handles database operations and queries
│
├── Router/                  # Route definitions and request mapping
│
├── Service/                 # Business logic and service layer
│
├── Utility/                 # Helper utilities (DB config, CORS, etc.)
│   ├── ca.pem
│   ├── cors.php
│   ├── DBConfig.php
│   └── ResponseUtility.php
│
├── .htaccess                # Apache configuration for routing
├── index.php                # Entry point of the API
└── readme.md
```

---

## 🚀 Getting Started

### Prerequisites

- PHP >= 7.4
- Apache/Nginx
- MySQL
- Composer (optional, if you manage dependencies)
- Firebase credentials for real-time features

### Setup Instructions

1. **Clone the Repository:**

```bash
git clone https://github.com/your-username/drophere-restapi.git
cd drophere-restapi
```

2. **Configure Database:**

Update `src/Utility/DBConfig.php` with your MySQL credentials:

3. **Start Local Server (Apache):**

Place the project inside your Apache `htdocs` directory or set up a virtual host.
