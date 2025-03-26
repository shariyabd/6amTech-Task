## Laravel Organization Management System
A comprehensive Laravel-based system for managing organizations, teams, and employees with advanced features including API management, event-driven architecture, background processing, and custom reporting.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Features](#features)
 - [API Management with Sanctum](#api-management-with-sanctum)
  - [Eloquent Relationships & Data Aggregation](#eloquent-relationships--data-aggregation)
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

---

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/shariyabd/6amTech-Task.git
   cd 6amTech-Task
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
DB_DATABASE=your_database_name
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
### API Management with Sanctum
A comprehensive RESTful API with Laravel Sanctum authentication and role-based access control:
- Complete CRUD operations for organizations, teams, and employees.
- Secure authentication with API tokens.
- Role-based permissions (Admin, Manager).
- API versioning support.

API endpoints can be accessed using the appropriate authentication tokens and follow RESTful conventions.
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

Below is an alternative Markdown representation using bullet lists for clarity:

---

## Register User

**Endpoint:**  
```
POST /api/v1/auth/register
```

**Description:**  
Registers a new user in the system.

**HTTP Method:**  
`POST`

**Request Body Fields:**

- **name**  
  - **Type:** string  
  - **Required:** Yes  
  - **Description:** The full name of the user.

- **role_id**  
  - **Type:** integer  
  - **Required:** No (defaults to `2` if not provided)  
  - **Description:** The user's role ID. Acceptable values: `1` or `2`.

- **email**  
  - **Type:** string  
  - **Required:** Yes  
  - **Description:** The user's email address.

- **password**  
  - **Type:** string  
  - **Required:** Yes  
  - **Description:** The password for the user's account.

**Example Request Body:**

```json
{
  "name": "Shariya Shuvo",
  "role_id": 2,
  "email": "shariya5@gmail.com",
  "password": "shariyashuvo"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "access_token": "1|h03lauq1BX3mPGyfmqDDhPYJATO1qsE7k5XjYY8s678d7b28",
        "token_type": "Bearer",
        "user": {
            "name": "Shariya Shuvo",
            "role_id": 2,
            "email": "shariya5@gmail.com",
            "updated_at": "2025-03-25T23:27:24.000000Z",
            "created_at": "2025-03-25T23:27:24.000000Z",
            "id": 1
        }
    },
    "message": "Registration Successfully Done"
}
```

#### Login
**Endpoint:**  
```
POST /api/v1/auth/login
```

**HTTP Method:**  
`POST`

**Request Body Fields:**

- **email**  
  - **Type:** string  
  - **Required:** Yes  
  - **Description:** The user's email address.

- **password**  
  - **Type:** string  
  - **Required:** Yes  
  - **Description:** The password for the user's account.

**Description:**  
Login an existing user and generate an API token.

**Example Request Body:**
```json
{
    "email" : "shariya5@gmail.com",
    "password" : "shariyashuvo"

}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "access_token": "2|Ac4E81JcIcVy1cERwarQOgrorhgcR7kpdEhZgNhe8382141d",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "role_id": 2,
            "name": "Shariya Shuvo",
            "email": "shariya5@gmail.com",
            "email_verified_at": null,
            "created_at": "2025-03-25T23:27:24.000000Z",
            "updated_at": "2025-03-25T23:27:24.000000Z"
        }
    },
    "message": "Login successful"
}
```

### Me
**Endpoint:**
```
GET /api/v1/auth/me
```
**Description:**  
Authenticate User Information

**Headers:**  
`Authorization: Bearer {access_token}`

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "role_id": 2,
        "name": "Shariya Shuvo",
        "email": "shariya5@gmail.com",
        "email_verified_at": null,
        "created_at": "2025-03-25T23:27:24.000000Z",
        "updated_at": "2025-03-25T23:27:24.000000Z"
    },
    "message": "User information retrieved successfully"
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
 **Endpoint:** 
```
GET /api/v1/organizations?per_page=5
```
  **Query Parameters:**  
  - `per_page` (optional): Number of organizations per page (default: 10).

  **Response:**
  ```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "TechCorp",
                "teams_count": 3,
                "employees_count": 0
            },
            {
                "id": 2,
                "name": "FinSolve",
                "teams_count": 2,
                "employees_count": 0
            },
            {
                "id": 3,
                "name": "HealthPlus",
                "teams_count": 2,
                "employees_count": 0
            },
            {
                "id": 4,
                "name": "EduNation",
                "teams_count": 2,
                "employees_count": 0
            },
            {
                "id": 5,
                "name": "GreenEnergy",
                "teams_count": 2,
                "employees_count": 0
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 2,
            "total": 10,
            "per_page": 5
        },
        "performance": {
            "label": "Organization Index",
            "execution_time": "24.11ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select count(*) as aggregate from `organizations`",
                    "bindings": [],
                    "time": 1.38
                },
                {
                    "query": "select `id`, `name`, (select count(*) from `teams` where `organizations`.`id` = `teams`.`organization_id`) as `teams_count`, (select count(*) from `employees` inner join `teams` on `teams`.`id` = `employees`.`team_id` where `organizations`.`id` = `teams`.`organization_id`) as `employees_count` from `organizations` limit 5 offset 0",
                    "bindings": [],
                    "time": 1.06
                }
            ]
        }
    },
    "message": "All Organization Data"
}
  ```

- **Create Organization:**  
**Endpoint:**
```
   POST /api/v1/organization
```

Below is a Markdown representation of the request body fields based on the provided validation rules:

---

## Request Body Fields

- **name**
  - **Type:** string
  - **Required:** Yes

- **address**
  - **Type:** string
  - **Required:** No (nullable)

- **industry**
  - **Type:** string
  - **Required:** Yes

- **location**
  - **Type:** string
  - **Required:** No (nullable)

- **phone**
  - **Type:** string
  - **Required:** No (nullable)


- **email**
  - **Type:** email
  - **Required:** No (nullable)

- **website**
  - **Type:** string
  - **Required:** No (nullable)

- **founded_year**
  - **Type:** date
  - **Required:** No (nullable)

---

  **Example Request Body:**
  ```json
 {
    "name" : "6am Tech",
    "address" : "Mirpur",
    "industry" : "Software",
    "phone" : "019827673",
    "email" : "shariyacodeare@gmail.com",
    "website" : "https://sixam-tech.com"
}
  ```
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "organization": {
            "name": "6am Tech",
            "industry": "Software",
            "phone": "019827673",
            "email": "shariyacodeare@gmail.com",
            "website": "https://sixam-tech.com",
            "updated_at": "2025-03-26T00:07:50.000000Z",
            "created_at": "2025-03-26T00:07:50.000000Z",
            "id": 11
        },
        "performance": {
            "label": "Organization Store",
            "execution_time": "6.27ms",
            "total_queries": 1,
            "queries": [
                {
                    "query": "insert into `organizations` (`name`, `industry`, `phone`, `email`, `website`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?, ?)",
                    "bindings": [
                        "6am Tech",
                        "Software",
                        "019827673",
                        "shariyacodeare@gmail.com",
                        "https://sixam-tech.com",
                        "2025-03-26 00:07:50",
                        "2025-03-26 00:07:50"
                    ],
                    "time": 3.86
                }
            ]
        }
    },
    "message": "Organization Created Successfully"
}
  ```

- **Get Organization Details:**  
  **Endpoint:** 
```
GET /api/v1/organization/{id}
```
  **Response:**
```
{
    "success": true,
    "data": {
        "organization": {
            "id": 7,
            "name": "AutoMotiveX",
            "industry": "Automobile",
            "location": "Detroit",
            "phone": "999-000-1111",
            "email": "support@automotivex.com",
            "website": "www.automotivex.com",
            "founded_year": "1995",
            "created_at": null,
            "updated_at": null,
            "teams_count": 2,
            "employees_count": 0
        },
        "performance": {
            "label": "Organization Show",
            "execution_time": "7.11ms",
            "total_queries": 1,
            "queries": [
                {
                    "query": "select `organizations`.*, (select count(*) from `teams` where `organizations`.`id` = `teams`.`organization_id`) as `teams_count`, (select count(*) from `employees` inner join `teams` on `teams`.`id` = `employees`.`team_id` where `organizations`.`id` = `teams`.`organization_id`) as `employees_count` from `organizations` where `organizations`.`id` = ? limit 1",
                    "bindings": [
                        "7"
                    ],
                    "time": 1.74
                }
            ]
        }
    },
    "message": "Single Organization Data"
}
  ```

- **Update Organization:**  
  **Endpoint:** 

```
PATCH /api/v1/organization/{id}
```
  **Request Body:**
  ```json
  {
    "name": "HealthPlus test test ",
    "industry": "Healthcare",
    "location": "Chicago",
    "phone": "555-111-2222",
    "email": "support@healthplus.com",
    "website": "www.healthplus.com",
    "founded_year": "2008"
}
  ```
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "organization": {
            "id": 10,
            "name": "HealthPlus test test",
            "industry": "Healthcare",
            "location": "Chicago",
            "phone": "555-111-2222",
            "email": "support@healthplus.com",
            "website": "www.healthplus.com",
            "founded_year": "2008",
            "created_at": null,
            "updated_at": "2025-03-26T00:11:54.000000Z"
        },
        "performance": {
            "label": "Organization Update",
            "execution_time": "7.25ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select * from `organizations` where `organizations`.`id` = ? limit 1",
                    "bindings": [
                        "10"
                    ],
                    "time": 0.27
                },
                {
                    "query": "update `organizations` set `name` = ?, `industry` = ?, `location` = ?, `phone` = ?, `email` = ?, `website` = ?, `founded_year` = ?, `organizations`.`updated_at` = ? where `id` = ?",
                    "bindings": [
                        "HealthPlus test test",
                        "Healthcare",
                        "Chicago",
                        "555-111-2222",
                        "support@healthplus.com",
                        "www.healthplus.com",
                        "2008",
                        "2025-03-26 00:11:54",
                        10
                    ],
                    "time": 3.87
                }
            ]
        }
    },
    "message": "Organization Updated Successfully"
}
  ```

- **Delete Organization:**  
  **Endpoint:**
```
DELETE /api/v1/organization/{id}
```
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
  **Endpoint:** 
```
GET /api/v1/teams?per_page=5
```  
  **Query Parameters:**  
  - `per_page` (optional): Number of teams per page (default: 10).

  **Response:**
  ```json
  {
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "Software Development",
                "organization_id": 1,
                "department": "Engineering",
                "organization": {
                    "id": 1,
                    "name": "TechCorp",
                    "industry": "Technology",
                    "location": "San Francisco"
                }
            },
            {
                "id": 2,
                "name": "Marketing",
                "organization_id": 1,
                "department": "Sales",
                "organization": {
                    "id": 1,
                    "name": "TechCorp",
                    "industry": "Technology",
                    "location": "San Francisco"
                }
            },
            {
                "id": 3,
                "name": "Risk Management",
                "organization_id": 2,
                "department": "Finance",
                "organization": {
                    "id": 2,
                    "name": "FinSolve",
                    "industry": "Finance",
                    "location": "New York"
                }
            },
            {
                "id": 4,
                "name": "Customer Support",
                "organization_id": 3,
                "department": "Support",
                "organization": {
                    "id": 3,
                    "name": "HealthPlus",
                    "industry": "Healthcare",
                    "location": "Chicago"
                }
            },
            {
                "id": 5,
                "name": "Research & Development",
                "organization_id": 3,
                "department": "Innovation",
                "organization": {
                    "id": 3,
                    "name": "HealthPlus",
                    "industry": "Healthcare",
                    "location": "Chicago"
                }
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 4,
            "total": 20,
            "per_page": 5
        },
        "performance": {
            "label": "Team Index",
            "execution_time": "4.8ms",
            "total_queries": 3,
            "queries": [
                {
                    "query": "select count(*) as aggregate from `teams`",
                    "bindings": [],
                    "time": 0.44
                },
                {
                    "query": "select id,name,organization_id, department from `teams` limit 5 offset 0",
                    "bindings": [],
                    "time": 0.34
                },
                {
                    "query": "select id, name, industry,location from `organizations` where `organizations`.`id` in (1, 2, 3)",
                    "bindings": [],
                    "time": 0.4
                }
            ]
        }
    },
    "message": "All Organization Data"
}
  ```

Below is the corrected Markdown with proper code block formatting:

---

## Create Team

**Endpoint:**  
```
POST /api/v1/team
```

### Request Body Fields

- **name**
  - **Type:** string
  - **Required:** Yes

- **organization_id**
  - **Type:** (typically integer or string, depending on your system)
  - **Required:** Yes

- **department**
  - **Type:** (usually string)
  - **Required:** No (nullable)

### Request Body

```json
{
  "name": "Development App test sdfdf",
  "organization_id": 7,
  "department": "Development"
}
```

### Response

```json
{
  "success": true,
  "data": {
    "team": {
      "name": "Development App test sdfdf",
      "organization_id": 7,
      "department": "Development",
      "updated_at": "2025-03-26T00:20:13.000000Z",
      "created_at": "2025-03-26T00:20:13.000000Z",
      "id": 21
    },
    "performance": {
      "label": "Organization Store",
      "execution_time": "6.02ms",
      "total_queries": 1,
      "queries": [
        {
          "query": "insert into `teams` (`name`, `organization_id`, `department`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?)",
          "bindings": [
            "Development App test sdfdf",
            7,
            "Development",
            "2025-03-26 00:20:13",
            "2025-03-26 00:20:13"
          ],
          "time": 4.12
        }
      ]
    }
  },
  "message": "Team Created Successfully"
}
```

---

- **Get Team Details:**  
  **Endpoint:** 
```
GET /api/v1/team/{id}
```
  **Response:**
  ```json
  {
    "success": true,
    "data": {
        "team": {
            "id": 5,
            "name": "Research & Development",
            "organization_id": 3,
            "department": "Innovation",
            "created_at": null,
            "updated_at": null,
            "organization": {
                "id": 3,
                "name": "HealthPlus",
                "industry": "Healthcare",
                "location": "Chicago"
            }
        },
        "performance": {
            "label": "Team Show",
            "execution_time": "3.15ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select * from `teams` where `teams`.`id` = ? limit 1",
                    "bindings": [
                        "5"
                    ],
                    "time": 0.37
                },
                {
                    "query": "select id, name, industry,location from `organizations` where `organizations`.`id` in (3)",
                    "bindings": [],
                    "time": 0.28
                }
            ]
        }
    },
    "message": "Single Team Data"
}
  ```

- **Update Team:**  
  **Endpoint:** 
```
PATCH /api/v1/team/{id}
```
  **Request Body:**
  ```json
{
    "name" : "Development App test purpose",
    "organization_id" : 7,
    "department" : "Development"
    
}  
```
  **Response:**

  ```json
  {
    "success": true,
    "data": {
        "organization": {
            "id": 1,
            "name": "Development App test purpose",
            "organization_id": 7,
            "department": "Development",
            "created_at": null,
            "updated_at": "2025-03-26T00:28:43.000000Z"
        },
        "performance": {
            "label": "Organization Update",
            "execution_time": "6ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select * from `teams` where `teams`.`id` = ? limit 1",
                    "bindings": [
                        "1"
                    ],
                    "time": 0.26
                },
                {
                    "query": "update `teams` set `name` = ?, `organization_id` = ?, `department` = ?, `teams`.`updated_at` = ? where `id` = ?",
                    "bindings": [
                        "Development App test purpose",
                        7,
                        "Development",
                        "2025-03-26 00:28:43",
                        1
                    ],
                    "time": 4.12
                }
            ]
        }
    },
    "message": "Team Updated Successfully"
}
  ```

- **Delete Team:**  
  **Endpoint:** 
```
DELETE /api/v1/team/{id}
```
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