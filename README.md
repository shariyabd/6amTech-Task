## Laravel Organization Management System
A comprehensive Laravel-based system for managing organizations, teams, and employees with advanced features including API management, event-driven architecture, background processing, and custom reporting.

## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Features](#features)
  - [API Management with Sanctum](#api-management-with-sanctum)
  - [Role-Based Access Control and Authentication](#role-based-access-control-and-authentication)
  - [Eloquent Relationships & Data Aggregation](#eloquent-relationships--data-aggregation)
  - [Event-Driven Architecture & Background Processing](#event-driven-architecture--background-processing)
  - [PDF Reporting Package](#pdf-reporting-package)
  - [Performance Optimization](#performance-optimization)
- [API Documentation](#api-documentation)
  - [Role and Access](#role-and-access)
  - [Authentication](#authentication)
    - [Register](#register)
    - [Login](#login)
    - [Logout](#logout)
  - [Organizations](#organizations)
  - [Teams](#teams)
  - [Employees](#employees)
  - [Reports](#reports)
    - [Employees Organization Report Download As PDF](#employees-organization-report-download-as-pdf)
  - [Employee Data Import & Salary Update Logs](#employee-data-import--salary-update-logs)
- [Testing](#testing)
- [Performance Monitoring](#performance-monitoring)
- [Api Collection](api-collection/)
- [API Collection](#api-collection)




---

## Requirements
- **PHP 8.3** or higher
- **Laravel 11**
- **MySQL 8.0+**
- **Composer 2.7** or higher

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

5. **Run database migrations and seeders:** (Before migrate configure database)

   ```bash
   php artisan migrate --seed
   ```
> **Note:** Since employee data is not being seeded, before testing, generate the employee JSON file and import it or add employee via API. For details, refer to the [Employee Data Import & Salary Update Logs](#employee-data-import--salary-update-logs).
> 
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



## Features
- Role-Based Access Control and Authentication
- API Management with Sanctum
- Eloquent Relationships & Data Aggregation
- Event-Driven Architecture & Background Processing
- PDF Reporting Package
- Performance Optimization And Monitoring 


### API Management with Sanctum
A comprehensive RESTful API with Laravel Sanctum authentication and role-based access control:
- Complete CRUD operations for organizations, teams, and employees.
- Secure authentication with API tokens.
- Role-based permissions (Admin, Manager).
- API versioning support.

API endpoints can be accessed using the appropriate authentication tokens and follow RESTful conventions.

### Role-Based Access Control and Authentication

**Role-Based Access:**

- **Admin:**  
  Full access to organizations, teams, employees, and reports (reports are view-only).

- **Manager:**  
  Restricted access with full permissions only for employees and limited access for teams.

**Authentication:**

- Endpoints for user registration, login, and logout.
- Uses Laravel Sanctum for secure API token management.

**Default Role:**

- If no `role_id` is provided during registration, the system assigns the default role (Manager).

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

### Event-Driven Architecture & Background Processing
The system implements Laravel's event system for various operations:
- JSON employee data import processing in the background.
- Background processing with Laravel queues.
- Real-time progress tracking for long-running imports.
- Error handling and user notifications.
- Event-based salary update logging.

### PDF Reporting Package
A custom Laravel package for generating PDF employee reports:
- Reusable Laravel package structure.
- Customizable PDF templates.
- Multiple export options.
- Easy integration with the main application.


### Performance Optimization
Database and system optimizations for handling large datasets:
- Strategic database indexing.
- Query optimization techniques.
- Laravel Telescope integration for monitoring.
- Performance benchmarking tools.
- 

## API Documentation

**Version:** v1  
**Base URL:** https://your-domain.com/api/v1
## Role Information

- **Admin:** id 1  
- **Manager:** id 2  

*Default Role:* Assigned to every user if they don't provide the `role_id`.

---

## Access Permissions

- **Organization, Team, Employee, Report:**  
  - **Admin** has full access of organization, team, employee, and report (report is view-only), including add, edit, update, view, delete, and list.
  
- **Employee:**  
  - **Manager** has full access to employee, including add, edit, update, view, delete, and list.
  
- **Team:**  
  - **Manager** can view only the team list and a single team.
  

### Authentication
**Base URL:** https://your-domain.com/api/v1/auth
- **Register:** `POST /register`
- **Login:** `POST /login`
- **Logout:** `POST /logout`

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

- **role_id**  
  - **Type:** integer  
  - **Required:** No (defaults to `2` if not provided)  
  - **Description:** The user's role ID. Acceptable values: `1` or `2`.

- **email**  
  - **Type:** string  
  - **Required:** Yes 

- **password**  
  - **Type:** string  
  - **Required:** Yes  

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

- **password**  
  - **Type:** string  
  - **Required:** Yes  

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
**HTTP Method:**  
`GET`
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
**HTTP Method:**  
`POST`
**Headers:**  
`Authorization: Bearer {access_token}`
**Description:**  
Logout the current user by revoking the current access token.

**Headers:**  
`Authorization: Bearer {access_token}`

**Response:**
```json
{
    "success": true,
    "data": [],
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
**HTTP Method:**  
`GET`
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
**HTTP Method:**  
`POST`

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


## Get Organization Details

**Endpoint:**  
```http
GET /api/v1/organization/{id}
```
**HTTP Method:**  
`GET`
**Response:**  
```json
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

---

- **Update Organization:**  
  **Endpoint:** 

```
PATCH /api/v1/organization/{id}
```
**HTTP Method:**  
`PATCH`
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
**HTTP Method:**  
`DELETE`
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "performance": {
            "label": "Organization Delete",
            "execution_time": "7.09ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select * from `organizations` where `organizations`.`id` = ? limit 1",
                    "bindings": [
                        "3"
                    ],
                    "time": 0.23
                },
                {
                    "query": "delete from `organizations` where `id` = ?",
                    "bindings": [
                        3
                    ],
                    "time": 5.01
                }
            ]
        }
    },
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
**HTTP Method:**  
`GET`
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

---

## Create Team

**Endpoint:**  
```
POST /api/v1/team
```
**HTTP Method:**  
`POST`
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
**HTTP Method:**  
`GET`

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
**HTTP Method:**  
`PATCH`
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
    "success": true,
    "data": {
        "performance": {
            "label": "Team Delete",
            "execution_time": "6.37ms",
            "total_queries": 2,
            "queries": [
                {
                    "query": "select * from `teams` where `teams`.`id` = ? limit 1",
                    "bindings": [
                        "2"
                    ],
                    "time": 0.32
                },
                {
                    "query": "delete from `teams` where `id` = ?",
                    "bindings": [
                        2
                    ],
                    "time": 4.63
                }
            ]
        }
    },
    "message": "Team Deleted Successfully"
}
  ```

### Employees
_All endpoints in this section require authentication._

- **List Employees:**  
  **Endpoint:** 
```
GET /api/v1/employees
```
**HTTP Method:**  
`GET`
  **Query Parameters:**  
  - `per_page` (optional): Number of employees per page (default: 15).  
  - `start_date` (optional): Filter employees by start date.  
  - `team_id` (optional): Filter by team.  
  - `organization_id` (optional): Filter by organization.

  **Response:**
  ```json
 {
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "Michael Lee",
                "email": "michael.lee@company.org",
                "team_id": 3,
                "organization_id": 9,
                "salary": 244168,
                "start_date": "2024-07-30T00:00:00.000000Z",
                "position": "Administrative Assistant",
                "created_at": "2025-03-26T05:16:12.000000Z",
                "updated_at": "2025-03-26T05:16:12.000000Z",
                "team": {
                    "id": 3,
                    "name": "Risk Management",
                    "department": "Finance"
                },
                "organization": {
                    "id": 9,
                    "name": "BuildTech",
                    "industry": "Construction",
                    "location": "Denver"
                }
            },
            {
                "id": 2,
                "name": "Isabella Wright",
                "email": "isabella.wright@business.io",
                "team_id": 7,
                "organization_id": 6,
                "salary": 51206,
                "start_date": "2022-04-12T00:00:00.000000Z",
                "position": "Senior Software Engineer",
                "created_at": "2025-03-26T05:16:12.000000Z",
                "updated_at": "2025-03-26T05:16:12.000000Z",
                "team": {
                    "id": 7,
                    "name": "Human Resources",
                    "department": "HR"
                },
                "organization": {
                    "id": 6,
                    "name": "Foodies",
                    "industry": "Food & Beverage",
                    "location": "Los Angeles"
                }
            },
            {
                "id": 3,
                "name": "James Moore",
                "email": "james.moore@business.io",
                "team_id": 5,
                "organization_id": 9,
                "salary": 199082,
                "start_date": "2024-10-23T00:00:00.000000Z",
                "position": "Accounting Manager",
                "created_at": "2025-03-26T05:16:12.000000Z",
                "updated_at": "2025-03-26T05:16:12.000000Z",
                "team": {
                    "id": 5,
                    "name": "Research & Development",
                    "department": "Innovation"
                },
                "organization": {
                    "id": 9,
                    "name": "BuildTech",
                    "industry": "Construction",
                    "location": "Denver"
                }
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 395,
            "total": 1184,
            "per_page": 3
        },
        "performance": {
            "label": "Employee Index",
            "execution_time": "50.36ms",
            "total_queries": 6,
            "queries": [
                {
                    "query": "select * from `cache` where `key` in (?)",
                    "bindings": [
                        "employees_page_1_per_page_3_filters_de1b6378305762e355588386e8c59e24"
                    ],
                    "time": 0.76
                },
                {
                    "query": "select count(*) as aggregate from `employees`",
                    "bindings": [],
                    "time": 2.75
                },
                {
                    "query": "select * from `employees` limit 3 offset 0",
                    "bindings": [],
                    "time": 0.75
                },
                {
                    "query": "select id,name,department from `teams` where `teams`.`id` in (3, 5, 7)",
                    "bindings": [],
                    "time": 1.51
                },
                {
                    "query": "select id, name, industry,location from `organizations` where `organizations`.`id` in (6, 9)",
                    "bindings": [],
                    "time": 1.35
                },
  ```

- **Create Employee:**  
  **Endpoint:** 
```
POST /api/v1/employee`
```
**HTTP Method:**  
`POST`

**Request Body Fields:**

- **name**  
  - **Type:** string  
  - **Required:** Yes  

- **email**  
  - **Type:** string (email format)  
  - **Required:** Yes  

- **team_id**  
  - **Type:** integer  
  - **Required:** Yes  

- **organization_id**  
  - **Type:** integer  
  - **Required:** Yes  

- **salary**  
  - **Type:** numeric  
  - **Required:** Yes  

- **start_date**  
  - **Type:** date  
  - **Required:** Yes  

- **position**  
  - **Type:** string  
  - **Required:** No  

**Example Request Body:**
 
  ```json
{
    "name" : "Shariya Shuvo",
    "email" : "shariya@gmail.com.com",
    "team_id" : "6",
    "organization_id" : "9",
    "salary" : "40000",
    "start_date" : "23-03-2025"
}
  ```
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "employee": {
            "name": "Shariya Shuvo",
            "email": "shariya@gmail.com.com",
            "team_id": "6",
            "organization_id": "9",
            "salary": 40000,
            "start_date": "2025-03-23T00:00:00.000000Z",
            "updated_at": "2025-03-26T06:34:02.000000Z",
            "created_at": "2025-03-26T06:34:02.000000Z",
            "id": 1185
        },
        "performance": {
            "label": "Employee Store",
            "execution_time": "188.55ms",
            "total_queries": 3,
            "queries": [
                {
                    "query": "insert into `employees` (`name`, `email`, `team_id`, `organization_id`, `salary`, `start_date`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?, ?, ?)",
                    "bindings": [
                        "Shariya Shuvo",
                        "shariya@gmail.com.com",
                        "6",
                        "9",
                        "40000",
                        "2025-03-23 00:00:00",
                        "2025-03-26 06:34:02",
                        "2025-03-26 06:34:02"
                    ],
                    "time": 15.7
                },
                {
                    "query": "select * from `cache` where `key` in (?)",
                    "bindings": [
                        "employee_cache_keys"
                    ],
                    "time": 0.66
                },
                {
                    "query": "delete from `cache` where `key` in (?, ?)",
                    "bindings": [
                        "employee_cache_keys",
                        "illuminate:cache:flexible:created:employee_cache_keys"
                    ],
                    "time": 0.76
                }
            ]
        }
    },
    "message": "Employee Created Successfully"
}
  ```

- **Get Employee Details:**  
  **Endpoint:** 
```
GET /api/v1/employee/{id}
```
**HTTP Method:**  
`GET`

  **Response:**
  ```json
  {
    "success": true,
    "data": {
        "employee": {
            "id": 10,
            "name": "Robert Hall",
            "email": "robert.hall@corp.net",
            "team_id": 4,
            "organization_id": 3,
            "salary": 79644,
            "start_date": "2021-09-08T00:00:00.000000Z",
            "position": "Product Manager",
            "created_at": "2025-03-26T05:16:12.000000Z",
            "updated_at": "2025-03-26T05:16:12.000000Z",
            "team": {
                "id": 4,
                "name": "Customer Support",
                "department": "Support"
            },
            "organization": {
                "id": 3,
                "name": "HealthPlus",
                "industry": "Healthcare",
                "location": "Chicago"
            }
        },
        "performance": {
            "label": "Employee Show",
            "execution_time": "27.17ms",
            "total_queries": 5,
            "queries": [
                {
                    "query": "select * from `cache` where `key` in (?)",
                    "bindings": [
                        "employee_10"
                    ],
                    "time": 0.6
                },
                {
                    "query": "select * from `employees` where `employees`.`id` = ? limit 1",
                    "bindings": [
                        "10"
                    ],
                    "time": 1.05
                },
                {
                    "query": "select id,name,department from `teams` where `teams`.`id` in (4)",
                    "bindings": [],
                    "time": 0.7
                },
                {
                    "query": "select id, name, industry,location from `organizations` where `organizations`.`id` in (3)",
                    "bindings": [],
                    "time": 0.64
                },
  ```

- **Update Employee:**  
  **Endpoint:** 
```
PATCH /api/v1/employee/{id}
```
**HTTP Method:**  
`PATCH`
  **Request Body:**
  ```json
{
{
    "name": "Charles Harris updated",
    "email": "charles.harris@example.com",
    "team_id" : "6",
    "organization_id" : "9",
    "salary" : "40000",
    "start_date" : "23-03-2025"
}
}
  ```
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "employee": {
            "id": 2,
            "name": "Charles Harris updated",
            "email": "charles.harris@example.com",
            "team_id": 6,
            "organization_id": 9,
            "salary": 40000,
            "start_date": "2025-03-23T00:00:00.000000Z",
            "position": "Software Engineer",
            "created_at": "2025-03-26T13:11:17.000000Z",
            "updated_at": "2025-03-26T14:05:04.000000Z"
        },
        "performance": {
            "label": "Employee Update",
            "execution_time": "0.91ms",
            "total_queries": 1,
            "queries": [
                {
                    "query": "select * from `employees` where `employees`.`id` = ? limit 1",
                    "bindings": [
                        "2"
                    ],
                    "time": 0.2
                }
            ]
        }
    },
    "message": "Employee Updated Successfully"
}
  ```

- **Delete Employee:**  
  **Endpoint:** 
```
DELETE /api/v1/employee/{id}
```
**HTTP Method:**  
`DELETE`
  **Response:**
  ```json
{
    "success": true,
    "data": {
        "performance": {
            "label": "Employee Delete",
            "execution_time": "32.89ms",
            "total_queries": 5,
            "queries": [
                {
                    "query": "select * from `employees` where `employees`.`id` = ? limit 1",
                    "bindings": [
                        "10"
                    ],
                    "time": 1.05
                },
                {
                    "query": "delete from `employees` where `id` = ?",
                    "bindings": [
                        10
                    ],
                    "time": 9
                },
                {
                    "query": "delete from `cache` where `key` in (?, ?)",
                    "bindings": [
                        "employee_10",
                        "illuminate:cache:flexible:created:employee_10"
                    ],
                    "time": 9.7
                },
                {
                    "query": "select * from `cache` where `key` in (?)",
                    "bindings": [
                        "employee_cache_keys"
                    ],
                    "time": 1.07
                },
            ]
        }
    },
    "message": "Employee Deleted Successfully"
}
  ```

### Reports
_All endpoints in this section require authentication._

- **Average Salary per Team:**  
  **Endpoint:** 
```
GET/api/v1/reports/teams/salary
```
**HTTP Method:**  
`GET`
  **Description:**  
  Calculates the average salary per team and returns a summary with overall averages.

  **Response:**
  ```json
  {
    "success": true,
    "data": {
        "teams": [
            {
                "id": 1,
                "name": "Software Development",
                "average_salary": 126871.32
            },
            {
                "id": 2,
                "name": "Marketing",
                "average_salary": 131474.41
            },
            {
                "id": 3,
                "name": "Risk Management",
                "average_salary": 130017.28
            },
            {
                "id": 4,
                "name": "Customer Support",
                "average_salary": 129997.04
            },
            {
                "id": 5,
                "name": "Research & Development",
                "average_salary": 121824.32
            },
            {
                "id": 6,
                "name": "Sales",
                "average_salary": 115521
            },
            {
                "id": 7,
                "name": "Human Resources",
                "average_salary": 113592.73
            },
            {
                "id": 8,
                "name": "Data Analytics",
                "average_salary": 115280.84
            },
            {
                "id": 9,
                "name": "Public Relations",
                "average_salary": 128785.62
            },
            {
                "id": 10,
                "name": "Cybersecurity",
                "average_salary": 123022.14
            },
            {
                "id": 11,
                "name": "Cloud Infrastructure",
                "average_salary": 120708.83
            },
            {
                "id": 12,
                "name": "Product Management",
                "average_salary": 114652.6
            },
            {
                "id": 13,
                "name": "Operations",
                "average_salary": 122283.56
            },
            {
                "id": 14,
                "name": "IT Support",
                "average_salary": 129984.66
            },
            {
                "id": 15,
                "name": "Logistics",
                "average_salary": 115298.11
            },
            {
                "id": 16,
                "name": "Legal Compliance",
                "average_salary": 112009.32
            },
            {
                "id": 17,
                "name": "Business Intelligence",
                "average_salary": 117018.05
            },
            {
                "id": 18,
                "name": "Financial Planning",
                "average_salary": 135090.73
            },
            {
                "id": 19,
                "name": "Customer Relations",
                "average_salary": 129644.79
            },
            {
                "id": 20,
                "name": "UX/UI Design",
                "average_salary": 130600.45
            }
        ],
        "summary": {
            "total_teams": 20,
            "overall_average": 123183.89
        }
    },
    "message": "Avarage Salery Per Employee"
}
  ```

- **Employees per Organization:**  
  **Endpoint:** 

```
GET /api/v1/reports/organizations/headcount
```

**HTTP Method:**  
`GET`
  **Description:**  
  Returns the count of employees per organization along with a summary of total employees and organizations.

  **Response:**
  ```json
{
    "success": true,
    "data": {
        "organization": [
            {
                "id": 1,
                "name": "TechCorp",
                "employee_count": 188
            },
            {
                "id": 2,
                "name": "FinSolve",
                "employee_count": 106
            },
            {
                "id": 3,
                "name": "HealthPlus",
                "employee_count": 111
            },
            {
                "id": 4,
                "name": "EduNation",
                "employee_count": 133
            },
            {
                "id": 5,
                "name": "GreenEnergy",
                "employee_count": 109
            },
            {
                "id": 6,
                "name": "Foodies",
                "employee_count": 49
            },
            {
                "id": 7,
                "name": "AutoMotiveX",
                "employee_count": 111
            },
            {
                "id": 8,
                "name": "RetailGiant",
                "employee_count": 123
            },
            {
                "id": 9,
                "name": "BuildTech",
                "employee_count": 132
            },
            {
                "id": 10,
                "name": "CloudNet",
                "employee_count": 122
            }
        ],
        "summary": {
            "total_organizations": 10,
            "total_employees": 1184
        }
    },
    "message": "Organization Wise Employess"
}
  ```
## Custom PDF Generator Pdf Package Link:
```
https://github.com/shariyabd/pdf-generator
```
## Employees Organization Report Download As PDF:

  **Endpoint:** 
```
GET /api/v1/reports/employee
```
**HTTP Method:**  
`GET`
  **Description:**  
  Create a  pdf file of Complete Employee Organization Report
  
  **Usage**
```
$result = [
'total_organizations' => $organizations->count(),
'report'              => $report,
];

$pdf = Pdf::loadView('reports.employee-report', [
    'result' => $result
]);

return $pdf->download('employee_report_' . now()->format('Y-m-d') . '.pdf');
```

# Employee Data Import & Salary Update Logs

This documentation outlines the process of generating, importing, and tracking employee data as well as monitoring salary updates.

---

## 1. Employee Data Generation

- **Endpoint:**  
  `GET http://127.0.0.1:8000/api/v1/import`

- **Description:**  
  This endpoint generates 300 employee records under various teams and exports the data to a JSON file.

- **Output File Location:**  
  `public\exports\employee_data.json`

---

## 2. Employee Data Import (Event-Driven)

- **Endpoint:**  
  `POST http://127.0.0.1:8000/api/v1/employees/import`

- **Description:**  
  After generating the JSON file, hit this endpoint to insert the employee data into the system using an event-driven architecture (event, listener, job, queue).

- **Response Example:**
  ```json
  {
      "success": true,
      "data": {
          "progress_url": "http://127.0.0.1:8000/api/v1/employees/import/status/5"
      },
      "message": "Your import has been queued and will be processed shortly"
  }
  ```

- **Process Tracking:**  
  The import process tracks the status of the job as it is processed.

---

## 3. Import Status Tracking

- **Endpoint:**  
  `GET http://127.0.0.1:8000/api/v1/employees/import/status/5`

- **Description:**  
  Retrieve the current status of the import job by its ID.

- **Response Example:**
  ```json
  {
      "success": true,
      "data": {
          "id": 5,
          "user_id": 1,
          "file_path": "imports/fnVlUDF2FPaY8UR7YXNMOxBshib04HFZCcuAGhZl.json",
          "status": "completed",
          "job_id": "29d3f47a-40e3-4d50-b93e-f97e911dbe23",
          "total_records": 1000,
          "processed_records": 2000,
          "failed_records": 0,
          "error_message": null,
          "created_at": "2025-03-26T05:39:59.000000Z",
          "updated_at": "2025-03-26T05:40:47.000000Z"
      },
      "message": "Import Status"
  }
  ```

- **Progress Notification:**  
  The system sends progress notifications after every 10% of records processed.

---

## 4. Import Completion & Failure Notifications

- **Notification Endpoints:**
  - **Success Notification:**  
    
  - **Failure Notification:**  

---

### Success Email Example

Subject: Employee Import Completed

Your employee data import has been completed.
Total records: {total_records}
Successfully processed: {processed_records - failed_records}
Failed records: {failed_records}

[View Details](http://127.0.0.1:8000/import/status/{import_job_id})


---

## 5. Import Statistics Summary Email Send To Logged In User

- **Trigger Condition:**  
  If the import job completes with more than 100 total records **or** if there are 10 or more failed records, the admin receives an import statistics summary email.



## Employee Import Summary Mail Format 

**Import Job #12345** has been completed.  

## Import Statistics:  

| Metric                 | Value              |
|------------------------|--------------------|
| **Total Records**      | 10,000             |
| **Successfully Processed** | 9,800         |
| **Failed Records**     | 200                |
| **Success Rate**       | 98%                |
| **Processing Duration** | 00:02:30         |
| **Performance**        | 66 records/second  |
| **Completed At**       | 2025-03-26 14:30:00 |

---

## ⚠️ Warning  
This import had **200 failed records**. You may want to review the logs for details.  

[**View Import Details**](#) *(A button linking to the import details page)*  

Thanks,  
**OrganizationManagement**


- **Admin Email:**  
  `shariya873@gmail.com`

- **Statistics Endpoint:**  
  `GET http://127.0.0.1:8000/api/v1/employees/import-statistics`


- **Response:**
```
{
    "success": true,
    "data": {
        "total_statistics": 8,
        "statistics": [
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -32,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:37:15"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -32,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:37:15"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -32,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:37:15"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -32,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:37:15"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -30,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:51:55"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -30,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:51:55"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -30,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:51:55"
            },
            {
                "import_job_id": null,
                "user_id": 1,
                "total_records": 1000,
                "processed_records": 2000,
                "failed_records": 0,
                "success_rate": 200,
                "duration": -30,
                "records_per_second": 0,
                "completed_at": "2025-03-26 15:51:55"
            }
        ]
    },
    "message": "Employee Import Statistics"
}
```

## 6. Salary Update Logs

- **Description:**  
  During the import process, if there is an update to an employee's salary, the system tracks both the old and new salary values.

- **Salary Logs Endpoint (List View):**  
  `GET http://127.0.0.1:8000/api/v1/salery-log`

This endpoint provides a list view of the salary update logs.
- **Response:**
```{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "employee_id": 50,
                "old_salary": "216635.00",
                "new_salary": "94928.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            },
            {
                "id": 2,
                "employee_id": 50,
                "old_salary": "216635.00",
                "new_salary": "94928.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            },
            {
                "id": 3,
                "employee_id": 50,
                "old_salary": "94928.00",
                "new_salary": "216635.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            },
            {
                "id": 4,
                "employee_id": 50,
                "old_salary": "94928.00",
                "new_salary": "216635.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            },
            {
                "id": 5,
                "employee_id": 50,
                "old_salary": "216635.00",
                "new_salary": "94928.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            },
            {
                "id": 6,
                "employee_id": 50,
                "old_salary": "216635.00",
                "new_salary": "94928.00",
                "employee": {
                    "id": 50,
                    "name": "Jacob Thomas",
                    "salary": 94928,
                    "team_id": 19,
                    "organization_id": 10,
                    "organization": {
                        "id": 10,
                        "name": "CloudNet",
                        "industry": "Cloud Computing",
                        "location": "Austin"
                    },
                    "team": {
                        "id": 19,
                        "name": "Customer Relations",
                        "department": "Support"
                    }
                }
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 1,
            "total": 6,
            "per_page": 15
        }
    },
    "message": " Salery Log List"
}
```
---

## Testing

### Unit & Feature Tests
Run the complete test suite to ensure everything is working correctly:

```bash
php artisan test
```

> **Note:** In the `ReportTest` class, I have written only two unit tests for report functionality:
 - **Team Report Tests:**
   - `it_can_get_average_salary_per_team`
   - `it_handles_team_with_no_employees`
 - **Organization Report Tests:**
   - `it_can_get_employees_per_organization`
   - `it_handles_organization_with_no_employees`

---

## Performance Monitoring
Laravel Telescope is integrated for performance monitoring.

- **Access the Telescope dashboard:** `/telescope`
- Monitor queued jobs and their status.
- Track database queries and performance.
- Monitor events and listeners.
- Check cache operations.

---
## API Collection
- [API Collection Folder](api-collection/)
- [6amTechTask Postman Collection](api-collection/6amTechTask.postman_collection.json)

# Developer Note

## Overview  
This note outlines the limitations encountered during development and explains the workarounds implemented due to time constraints before project submission.

## Known Issues & Workarounds

### 1. Custom PDF Package Integration  
- **Issue:**  
  I developed a custom PDF package generator that worked flawlessly in the local environment. However, after pushing the package to GitHub and Packagist, I encountered the following error during installation:  

  ```
  Class "Shariya\PdfGenerator\Providers\PdfGeneratorServiceProvider" not found
  ```

- **Actions Taken:**  
  I attempted several solutions to identify and resolve the issue, but due to the tight project deadline, I was unable to fully address it.

- **Workaround:**  
  To maintain PDF generation functionality, I integrated **Dompdf** into the project. My custom package link is still provided for reference and future improvement.

### 2. Unit Testing  
- **Issue:**  
  Due to time constraints, I was only able to write unit tests for the report module.

- **Actions Taken:**  
  I focused on covering all critical functionalities as specified in the task requirements, even though the overall test coverage is not comprehensive.

## Final Remarks  
I acknowledge these limitations and plan to revisit them for further enhancements when more time is available. Thank you for your understanding and feedback.

---
