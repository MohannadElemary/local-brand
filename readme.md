# Technical Documentation
This project implements a employee management API with endpoints for listing, showing, and deleting employee records based on `empId`. The API includes an efficient CSV import process that handles large data volumes while maintaining data integrity and performance.

## Setup
- Clone Repository
- In the root directory of the project, run `docker-compose build`
- Run `docker-compose run`
- In another tab, run `docker-compose exec app bash`
- Inside bash, run `cd html`
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate`

## Postman Documentation
https://documenter.getpostman.com/view/8868758/2sAY4uCP9v

## Approach and Implementation

### 1. Architecture Overview

The application is built with a layered structure that organizes logic into controllers, services, and repositories. This design simplifies maintenance, testing, and scalability by separating business logic from data access and request handling.

- **Controllers**: Handle HTTP requests and responses. Each controller method delegates logic to services, ensuring minimal business logic is in the controller layer.
- **Services**: Contain business logic for managing employee data, including validation and deletion. Services communicate with repositories for database interactions.
- **Repositories**: Interact directly with the database, handling data persistence and retrieval. This setup provides a single data access layer, making data operations consistent and reusable across the application.
- **Resources**: Used for JSON serialization, providing a structured format for API responses.

### 2. CSV Import Process

The CSV import process is designed for performance, security, and scalability, ensuring efficient handling of large files with minimal memory usage.

#### Import Steps

1. **File Chunking**: Large CSV files are split into smaller chunks in the `EmployeeImportService`. Each chunk is stored temporarily and processed independently to prevent memory overload and improve throughput.
2. **Job Dispatching**: Each file chunk is processed by a background job, `ProcessEmployeeCsv`, which reads and prepares the data. This approach keeps the import asynchronous, allowing it to run in parallel without blocking other operations.
3. **Data Validation and Batch Insertion**: The `ProcessEmployeeChunk` job validates each row in a chunk using `UserDataValidationService`. Validated data is grouped and batch-inserted into the database to reduce database load and speed up the import.

### Key Design Decisions

#### Performance
- **Asynchronous Jobs**: Using Laravel’s job queues, each file chunk is processed in parallel, reducing processing time and enabling the system to handle large files efficiently.
- **Batch Insertion**: Instead of inserting each record individually, the system groups validated records into batches and performs a single insert operation per batch. This minimizes database interaction time and optimizes import performance.
- **Chunked Processing**: By splitting large files into manageable chunks, memory usage remains low, even when handling files with hundreds of thousands of rows.

#### Security
- **Data Validation**: Each row is validated before insertion. `UserDataValidationService` ensures that data adheres to expected formats and constraints, reducing the risk of malformed data entering the database.
- **Error Handling**: Both `ProcessEmployeeCsv` and `ProcessEmployeeChunk` use error handling and logging, capturing any database or validation errors without interrupting the import process. This allows the application to handle minor data inconsistencies gracefully while logging details for review.

#### Scalability
- **Job Queue Configuration**: The job queue setup allows horizontal scaling, so adding more worker instances will increase processing capacity. This flexibility enables the system to handle higher volumes of imports as needed.
- **Adjustable Chunk Size**: The system allows for configuration of chunk size and batch insertion size based on server capabilities, letting the application adjust to different resource constraints or performance needs.
- **Stateless Processing**: Each chunk is processed independently, which means the import process doesn’t rely on the state of previous chunks. This design makes it easy to distribute processing across multiple workers, increasing throughput and reliability.

## API Structure

### Routes and Endpoints

1. **List Employees**
    - **Endpoint**: `GET /api/employee`
    - **Description**: Retrieves a paginated list of employees.
    - **Parameters**:
        - `page` (optional): Page number (default is 1).
        - `per_page` (optional): Number of employees per page (default is 15).

2. **Show employee by `empId`**
    - **Endpoint**: `GET /api/employee/{empId}`
    - **Description**: Retrieves a specific employee based on `empId`.

3. **Delete employee by `emp_id`**
    - **Endpoint**: `DELETE /api/employee/{empId}`
    - **Description**: Deletes a specific employee based on `empId`.

