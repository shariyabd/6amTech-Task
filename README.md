## Laravel Organization Management System
A comprehensive Laravel-based system for managing organizations, teams, and employees with advanced features including API management, event-driven architecture, background processing, and custom reporting.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Features](#features)
  - [Eloquent Relationships & Data Aggregation](#eloquent-relationships--data-aggregation)
  - [API Management with Sanctum](#api-management-with-sanctum)
  - [Event-Driven Architecture & Background Processing](#event-driven-architecture--background-processing)
  - [PDF Reporting Package](#pdf-reporting-package)
  - [Performance Optimization](#performance-optimization)
- [API Documentation](#api-documentation)
  - [Authentication](#authentication)
    - [Register](#register)
    - [Login](#login)
    - [Logout](#logout)
  - [Organizations](#organizations)
  - [Teams](#teams)
  - [Employees](#employees)
  - [Reports](#reports)
- [Testing](#testing)
- [Performance Monitoring](#performance-monitoring)
- [Contributing](#contributing)

---

## Requirements
- **PHP 8.2** or higher
- **Laravel 11**
- **MySQL 8.0+**
- **Composer**
- **Redis** (for queue processing)

---

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/organization-management.git
   cd organization-management
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Copy the environment file and configure your environment:**

   ```bash
   cp .env.example .env
   ```

4. **Generate application key:**

   ```bash
   php artisan key:generate
   ```

5. **Run database migrations and seeders:**

   ```bash
   php artisan migrate --seed
   ```

6. **Install and configure Laravel Telescope for performance monitoring:**

   ```bash
   php artisan telescope:install
   php artisan migrate
   ```

7. **Start the queue worker:**

   ```bash
   php artisan queue:work
   ```

---

## Configuration

### Database Configuration
Configure your database connection in the `.env` file:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=organization_management
DB_USERNAME=root
DB_PASSWORD=
```

### Queue Configuration
Configure queue processing:

```dotenv
QUEUE_CONNECTION=database
```

---

## Features

### Eloquent Relationships & Data Aggregation
The system implements advanced Eloquent relationships between Organizations, Teams, and Employees:
- **Organizations:** Can have multiple teams.
- **Teams:** Belong to an organization and can have multiple employees.
- **Employees:** Belong to a team.

**Key features:**
1. Advanced Eloquent relationship management
2. Data aggregation for salary reporting
3. Custom Eloquent scopes for filtering employees by start date
4. Optimized queries for large datasets

**Usage:**
```php
// Get organization with teams and employees
$organization = Organization::with(['teams.employees'])->find($id);

// Get average salary per team
$teamSalaryReport = Team::withAvgSalary()->get();

// Get total employees per organization
$organizationEmployeeCount = Organization::withEmployeeCount()->get();

// Filter employees by start date
$recentEmployees = Employee::startedAfter('2023-01-01')->get();
```

### API Management with Sanctum
A comprehensive RESTful API with Laravel Sanctum authentication and role-based access control:
- Complete CRUD operations for organizations, teams, and employees.
- Secure authentication with API tokens.
- Role-based permissions (Admin, Manager, Employee).
- API versioning support.

API endpoints can be accessed using the appropriate authentication tokens and follow RESTful conventions.

### Event-Driven Architecture & Background Processing
The system implements Laravel's event system for various operations:
- JSON employee data import processing in the background.
- Background processing with Laravel queues.
- Real-time progress tracking for long-running imports.
- Error handling and user notifications.
- Event-based salary update logging.

**Usage:**
```php
// Import employees from JSON
$importer = new EmployeeImporter($jsonData);
EmployeeImportEvent::dispatch($importer);

// The system will process the import in the background and notify users of progress
```

### PDF Reporting Package
A custom Laravel package for generating PDF employee reports:
- Reusable Laravel package structure.
- Customizable PDF templates.
- Multiple export options.
- Easy integration with the main application.

**Usage:**
```php
// Generate PDF employee report
$report = EmployeeReportPDF::create()
    ->forTeam($teamId)
    ->withSalaryData()
    ->generate();

return response()->download($report);
```

### Performance Optimization
Database and system optimizations for handling large datasets:
- Strategic database indexing.
- Query optimization techniques.
- Laravel Telescope integration for monitoring.
- Performance benchmarking tools.

---

## API Documentation

**Version:** v1  
**Base URL:** https://your-domain.com/api/v1

### Authentication
**Base URL:** https://your-domain.com/api/v1/auth
- **Register:** `POST /register`
- **Login:** `POST /login`
- **Logout:** `POST /logout`

#### Register
**Endpoint:**  
`POST /api/v1/auth/register`

**Description:**  
Register a new user in the system.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "yourpassword",
  "password_confirmation": "yourpassword"
}
```

**Response:**
```json
{
  "data": {
    "access_token": "generated_token",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  },
  "message": "Registration Successfully Done"
}
```

#### Login
**Endpoint:**  
`POST /api/v1/auth/login`

**Description:**  
Login an existing user and generate an API token.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "yourpassword"
}
```

**Response:**
```json
{
  "data": {
    "access_token": "generated_token",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  },
  "message": "Login successful"
}
```

#### Logout
**Endpoint:**  
`POST /api/v1/auth/logout`

**Description:**  
Logout the current user by revoking the current access token.

**Headers:**  
`Authorization: Bearer {access_token}`

**Response:**
```json
{
  "data": {},
  "message": "User logged out successfully."
}
```

### Organizations
_All endpoints in this section require authentication._

- **List Organizations:**  
  **Endpoint:** `GET /api/v1/organizations`  
  **Query Parameters:**  
  - `per_page` (optional): Number of organizations per page (default: 10).

  **Response:**
  ```json
  {
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "name": "Organization One"
        }
      ],
      "total": 1
    },
    "message": "All Organization Data"
  }
  ```

- **Create Organization:**  
  **Endpoint:** `POST /api/v1/organization`  
  **Request Body:**
  ```json
  {
    "name": "New Organization"
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 2,
      "name": "New Organization"
    },
    "message": "Organization Created Successfully"
  }
  ```

- **Get Organization Details:**  
  **Endpoint:** `GET /api/v1/organization/{id}`  
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Organization One"
    },
    "message": "Single Organization Data"
  }
  ```

- **Update Organization:**  
  **Endpoint:** `PATCH /api/v1/organization/{id}`  
  **Request Body:**
  ```json
  {
    "name": "Updated Organization Name"
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Updated Organization Name"
    },
    "message": "Organization Updated Successfully"
  }
  ```

- **Delete Organization:**  
  **Endpoint:** `DELETE /api/v1/organization/{id}`  
  **Response:**
  ```json
  {
    "data": {},
    "message": "Organization Deleted Successfully"
  }
  ```

### Teams
_All endpoints in this section require authentication._

- **List Teams:**  
  **Endpoint:** `GET /api/v1/teams`  
  **Query Parameters:**  
  - `per_page` (optional): Number of teams per page (default: 10).

  **Response:**
  ```json
  {
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "name": "Team A",
          "organization": {
            "id": 1,
            "name": "Organization One"
          }
        }
      ],
      "total": 1
    },
    "message": "Teams retrieved successfully"
  }
  ```

- **Create Team:**  
  **Endpoint:** `POST /api/v1/team`  
  **Request Body:**
  ```json
  {
    "name": "Team A",
    "organization_id": 1
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Team A",
      "organization_id": 1
    },
    "message": "Team Created Successfully"
  }
  ```

- **Get Team Details:**  
  **Endpoint:** `GET /api/v1/team/{id}`  
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Team A",
      "organization": {
        "id": 1,
        "name": "Organization One"
      }
    },
    "message": "Team retrieved successfully"
  }
  ```

- **Update Team:**  
  **Endpoint:** `PATCH /api/v1/team/{id}`  
  **Request Body:**
  ```json
  {
    "name": "Updated Team Name"
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Updated Team Name"
    },
    "message": "Team Updated Successfully"
  }
  ```

- **Delete Team:**  
  **Endpoint:** `DELETE /api/v1/team/{id}`  
  **Response:**
  ```json
  {
    "data": {},
    "message": "Team deleted successfully"
  }
  ```

### Employees
_All endpoints in this section require authentication._

- **List Employees:**  
  **Endpoint:** `GET /api/v1/employees`  
  **Query Parameters:**  
  - `per_page` (optional): Number of employees per page (default: 15).  
  - `start_date` (optional): Filter employees by start date.  
  - `team_id` (optional): Filter by team.  
  - `organization_id` (optional): Filter by organization.

  **Response:**
  ```json
  {
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "name": "Employee One",
          "salary": 5000,
          "team": { "id": 1, "name": "Team A" },
          "organization": { "id": 1, "name": "Organization One" }
        }
      ],
      "total": 1
    },
    "message": "Employee List"
  }
  ```

- **Create Employee:**  
  **Endpoint:** `POST /api/v1/employee`  
  **Request Body:**
  ```json
  {
    "name": "Employee One",
    "email": "employee@example.com",
    "salary": 5000,
    "start_date": "2023-01-01",
    "team_id": 1,
    "organization_id": 1
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Employee One",
      "email": "employee@example.com",
      "salary": 5000,
      "start_date": "2023-01-01",
      "team_id": 1,
      "organization_id": 1
    },
    "message": "Employee Created Successfully"
  }
  ```

- **Get Employee Details:**  
  **Endpoint:** `GET /api/v1/employee/{id}`  
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Employee One",
      "email": "employee@example.com",
      "salary": 5000,
      "start_date": "2023-01-01",
      "team": { "id": 1, "name": "Team A" },
      "organization": { "id": 1, "name": "Organization One" }
    },
    "message": "Single Employee Data"
  }
  ```

- **Update Employee:**  
  **Endpoint:** `PATCH /api/v1/employee/{id}`  
  **Request Body:**
  ```json
  {
    "name": "Updated Employee Name",
    "salary": 5500
  }
  ```
  **Response:**
  ```json
  {
    "data": {
      "id": 1,
      "name": "Updated Employee Name",
      "salary": 5500
    },
    "message": "Employee Updated Successfully"
  }
  ```

- **Delete Employee:**  
  **Endpoint:** `DELETE /api/v1/employee/{id}`  
  **Response:**
  ```json
  {
    "data": {},
    "message": "Employee has been deleted"
  }
  ```

### Reports
_All endpoints in this section require authentication._

- **Average Salary per Team:**  
  **Endpoint:** `GET /api/v1/reports/teams/salary`  
  **Description:**  
  Calculates the average salary per team and returns a summary with overall averages.

  **Response:**
  ```json
  {
    "data": {
      "teams": [
        {
          "id": 1,
          "name": "Team A",
          "average_salary": 5000.00
        }
      ],
      "summary": {
        "total_teams": 1,
        "overall_average": 5000.00
      }
    },
    "message": "Avarage Salery Per Employee"
  }
  ```

- **Employees per Organization:**  
  **Endpoint:** `GET /api/v1/reports/organizations/headcount`  
  **Description:**  
  Returns the count of employees per organization along with a summary of total employees and organizations.

  **Response:**
  ```json
  {
    "data": {
      "organization": [
        {
          "id": 1,
          "name": "Organization One",
          "employee_count": 10
        }
      ],
      "summary": {
        "total_organizations": 1,
        "total_employees": 10
      }
    },
    "message": "Organization Wise Employess"
  }
  ```

---

## Testing

### Unit & Feature Tests
Run the complete test suite to ensure everything is working correctly:

```bash
php artisan test
```

---

## Performance Monitoring
Laravel Telescope is integrated for performance monitoring.

- **Access the Telescope dashboard:** `/telescope`
- Monitor queued jobs and their status.
- Track database queries and performance.
- Monitor events and listeners.
- Check cache operations.

---

## Contributing
- Fork the repository.
- Create your feature branch:  
  ```bash
  git checkout -b feature/your-feature-name
  ```
- Commit your changes:  
  ```bash
  git commit -m 'Add some amazing feature'
  ```
- Push to the branch:  
  ```bash
  git push origin feature/your-feature-name
  ```
- Open a Pull Request.
```

This complete markdown document contains all sections linked appropriately via the Table of Contents. When viewed on platforms like GitHub, clicking any item in the Table of Contents will navigate to the corresponding section in the document.