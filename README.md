# 💰 Zorvyn Finance Backend

A RESTful API backend for a **Finance Data Processing and Access Control** system. Built with **Laravel 12**, **MySQL**, **Laravel Sanctum** (token authentication), and **Spatie Laravel Permission** (role-based access control).

---

## 📋 Table of Contents

- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Seeder Data (Pre-loaded Test Accounts)](#seeder-data)
- [API Endpoints](#api-endpoints)
- [Testing with Postman](#testing-with-postman)
- [Role Permissions](#role-permissions)
- [Response Format](#response-format)
- [Technical Decisions](#technical-decisions)

---

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP) |
| Authentication | Laravel Sanctum (API Tokens) |
| Authorization | Spatie Laravel Permission |
| Database | MySQL |
| Validation | Laravel FormRequest |
| API Resources | Laravel API Resources |

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php   # Login & Logout
│   │   │   └── RegisteredUserController.php         # Register
│   │   ├── FinancialRecordController.php             # Records CRUD
│   │   └── AnalyticsController.php                  # Dashboard Analytics
│   ├── Requests/
│   │   ├── StoreFinancialRecordRequest.php
│   │   └── UpdateFinancialRecordRequest.php
│   └── Resources/
│       ├── UserResource.php
│       └── FinancialRecordResource.php
├── Models/
│   ├── User.php
│   └── FinancialRecord.php
├── Traits/
│   └── ApiResponse.php
database/
├── migrations/
└── seeders/
    ├── DatabaseSeeder.php
    ├── RolePermissionSeeder.php
    ├── UserSeeder.php
    └── FinancialRecordSeeder.php
routes/
└── api.php
```

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/Ademighty2807/zorvyn-finance-backend.git
cd zorvyn-finance-backend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Set Up Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Open `.env` and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zorvyn_finance
DB_USERNAME=root
DB_PASSWORD=
```

> **Using XAMPP?** Make sure Apache and MySQL are running in the XAMPP Control Panel before continuing.

### 5. Run Migrations and Seed Database

```bash
php artisan migrate --seed
```

This single command will:
- Create all database tables
- Create roles: `admin`, `accountant`, `viewer`
- Create permissions and assign them per role
- Create 3 test users (one per role)
- Create 11 sample financial records spread across all users

### 6. Start the Server

```bash
php artisan serve
```

The API will be available at:
```
http://localhost:8000/api
```

---

## 👥 Seeder Data

After running `php artisan migrate --seed`, the following test accounts and records are available immediately — **no manual setup needed**.

### Test User Accounts

| Role | Email | Password | Access Level |
|---|---|---|---|
| **Admin** | admin@zorvyn.com | password123 | Full access — manage all records and users |
| **Accountant** | accountant@zorvyn.com | password123 | View, create and edit records + analytics |
| **Viewer** | viewer@zorvyn.com | password123 | View own records and analytics only |

### Pre-loaded Financial Records

| Title | Type | Amount | Category | User | Status |
|---|---|---|---|---|---|
| Q1 Product Sales | Income | ₦1,500,000 | Sales | Admin | Approved |
| Office Rent - January | Expense | ₦250,000 | Utilities | Admin | Approved |
| Staff Salaries - February | Expense | ₦800,000 | Salaries | Admin | Approved |
| Q1 Consulting Revenue | Income | ₦600,000 | Consulting | Admin | Approved |
| Software Subscriptions | Expense | ₦120,000 | Technology | Accountant | Approved |
| Client Invoice - March | Income | ₦450,000 | Invoice | Accountant | Approved |
| Marketing Campaign | Expense | ₦180,000 | Marketing | Accountant | Pending |
| Equipment Purchase | Expense | ₦350,000 | Equipment | Accountant | Approved |
| Freelance Income - January | Income | ₦85,000 | Freelance | Viewer | Approved |
| Training & Development | Expense | ₦25,000 | Training | Viewer | Pending |
| April Consulting Fee | Income | ₦120,000 | Consulting | Viewer | Pending |

> **Note:** When logged in as **Viewer**, the records endpoint only returns that user's own records. Admin and Accountant see all records.

---

## 📡 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Authentication

| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| POST | `/register` | Register a new user | ❌ |
| POST | `/login` | Login and get token | ❌ |
| POST | `/logout` | Logout and revoke token | ✅ |

### Financial Records

| Method | Endpoint | Description | Roles Allowed |
|---|---|---|---|
| GET | `/records` | Get all records (paginated) | Admin, Accountant, Viewer |
| GET | `/records/{id}` | Get single record | Admin, Accountant, Viewer |
| POST | `/records` | Create new record | Admin, Accountant |
| PUT | `/records/{id}` | Update a record | Admin, Accountant |
| DELETE | `/records/{id}` | Delete a record | Admin only |

#### Query Parameters for GET `/records`

| Parameter | Type | Example | Description |
|---|---|---|---|
| `search` | string | `?search=sales` | Search title, category, description |
| `type` | string | `?type=income` | Filter by `income` or `expense` |
| `category` | string | `?category=Sales` | Filter by category |
| `status` | string | `?status=approved` | Filter by `pending`, `approved`, `rejected` |
| `from` | date | `?from=2026-01-01` | Filter records from this date |
| `to` | date | `?to=2026-04-30` | Filter records up to this date |
| `page` | integer | `?page=2` | Pagination (15 records per page) |

### Analytics

| Method | Endpoint | Description | Roles Allowed |
|---|---|---|---|
| GET | `/analytics` | Get dashboard summary | Admin, Accountant, Viewer |

#### Query Parameters for GET `/analytics`

| Parameter | Type | Example |
|---|---|---|
| `from` | date | `?from=2026-01-01` |
| `to` | date | `?to=2026-04-30` |
| `type` | string | `?type=income` |

---

## 🧪 Testing with Postman

### Step 1 — Register or Login

**Login with a seeded account:**

```
POST http://localhost:8000/api/login
Content-Type: application/json

{
    "email": "admin@zorvyn.com",
    "password": "password123"
}
```

Copy the `token` value from the response.

---

### Step 2 — Set Authorization Header

For every authenticated request, add this header:

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

---

### Step 3 — Create a Record

```
POST http://localhost:8000/api/records
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
    "title": "New Sales Revenue",
    "description": "Revenue from product launch",
    "type": "income",
    "amount": 750000.00,
    "category": "Sales",
    "date": "2026-04-05"
}
```

---

### Step 4 — Get All Records with Filters

```
GET http://localhost:8000/api/records?type=income&from=2026-01-01&to=2026-04-30
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### Step 5 — Get Analytics

```
GET http://localhost:8000/api/analytics
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### Step 6 — Logout

```
POST http://localhost:8000/api/logout
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 💡 Postman Tip — Auto-save Token

Add this script to the **Tests** tab of your Login request in Postman to automatically save the token as an environment variable:

```javascript
const res = pm.response.json();
pm.environment.set("token", res.data.token);
```

Then use `{{token}}` in your Authorization header instead of pasting manually every time.

---

## 🔐 Role Permissions

| Permission | Admin | Accountant | Viewer |
|---|---|---|---|
| View records | ✅ | ✅ | ✅ (own only) |
| Create records | ✅ | ✅ | ❌ |
| Edit records | ✅ | ✅ | ❌ |
| Delete records | ✅ | ❌ | ❌ |
| View analytics | ✅ | ✅ | ✅ (own only) |

> Roles are assigned at registration via the optional `role` field. If no role is provided, the user defaults to `viewer`.

**Register with a specific role:**

```json
{
    "name": "New Accountant",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "accountant"
}
```

---

## 📦 Response Format

All endpoints return a consistent JSON structure:

### Success Response
```json
{
    "status": true,
    "message": "Records fetched successfully",
    "data": { }
}
```

### Error Response
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "amount": ["The amount field is required."]
    }
}
```

### Paginated Response
```json
{
    "status": true,
    "message": "Records fetched successfully",
    "data": {
        "data": [ ],
        "current_page": 1,
        "per_page": 15,
        "total": 11,
        "last_page": 1
    }
}
```

---

## ⚙️ Technical Decisions

### Authentication — Laravel Sanctum
Sanctum was chosen for token-based API authentication. Each login generates a personal access token. Logout revokes only the current token, allowing multi-device sessions.

### Authorization — Spatie Laravel Permission
Roles and permissions are managed via Spatie's package rather than a manual implementation. This keeps role logic clean, database-driven, and easy to extend. Three roles are defined: `admin`, `accountant`, and `viewer`.

### Role Scoping on Records
Viewers are scoped to their own records at the query level, not just the response level. This means a viewer cannot access another user's record even by guessing the ID.

### Soft Deletes
Financial records use soft deletes (`deleted_at` column) so deleted records are retained in the database for audit purposes and can be restored if needed.

### Standardized API Responses
A shared `ApiResponse` trait wraps all controller responses in a consistent `{ status, message, data }` envelope. This makes frontend integration predictable regardless of which endpoint is called.

### Validation
All incoming requests are validated through dedicated `FormRequest` classes (`StoreFinancialRecordRequest`, `UpdateFinancialRecordRequest`). Validation errors are caught globally and returned in a standard format with a `422` status code.

---

## 📄 License

This project was built as a screening assessment for Zorvyn FinTech Pvt. Ltd.
