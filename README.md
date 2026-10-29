# Store Order & Inventory

A Laravel application for managing products, customers, and customer orders. The application supports order creation, automatic subtotal and tax calculation, inventory deduction, low-stock reporting, and order confirmation logging through a queued job.

## Features

- **Product inventory:** Manage product information, pricing, tax percentages, and available stock.
- **Customer management:** Store customer names and unique email addresses.
- **Order creation:** Create orders containing one or more products with specified quantities.
- **Automatic calculations:** Calculate line totals, subtotal, tax, and grand total using product pricing and tax rates.
- **Inventory protection:** Validate available stock and use database transactions and row locking to prevent overselling.
- **Order history API:** Retrieve a customer's orders using their email address.
- **Low-stock API:** Retrieve products whose stock is below a configurable threshold.
- **Order confirmation job:** Dispatch `SendOrderConfirmation` after the order transaction commits and log confirmation details.
- **Order creation UI:** Provide a browser interface for selecting customers and products, reviewing totals, and placing orders.
- **Automated tests:** Test core order and inventory behavior, including insufficient-stock scenarios.

## Tech Stack

- PHP
- Laravel
- MySQL
- Blade, HTML, CSS, and JavaScript
- Laravel Eloquent ORM
- Laravel database transactions and queues
- PHPUnit / Laravel testing tools
- Postman for API testing

## Requirements

- PHP version compatible with the project's `composer.json`
- Composer
- MySQL or another database supported by the configured Laravel application
- Node.js and npm if frontend assets require building
- A configured Laravel environment file (`.env`)

## Installation

### 1. Clone the repository

```bash
git clone <YOUR_PRIVATE_GITHUB_REPOSITORY_URL>
cd store_inventory/source
```

Replace the repository URL with your private GitHub repository URL.

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Update the database settings in `.env` to match your local MySQL configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Create the database before running migrations.

### 4. Run database migrations

```bash
php artisan migrate
```

If the project includes seeders for sample products and customers, run them with:

```bash
php artisan db:seed
```

Only run seeders if they are configured for this project.

### 5. Start the application

```bash
php artisan serve
```

Open the local URL printed by Laravel in your browser.

## API Endpoints

The following endpoints are part of the application's API. The examples assume Laravel's API routes are registered under the `/api` prefix.

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/orders` | Create an order |
| GET | `/api/customers/orders?email=customer@example.com` | Retrieve a customer's order history |
| GET | `/api/products/low-stock?threshold=5` | Retrieve products below a stock threshold |

Confirm the exact low-stock endpoint against `routes/api.php` before submission.

### Create an order

**Request**

`POST /api/orders`

```json
{
  "customer_id": 4,
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 2,
      "quantity": 1
    }
  ]
}
```

The application validates the request, checks product availability, calculates order totals, creates the order and order details, and deducts stock within a database transaction.

A successful request returns HTTP `201 Created`.

### Retrieve customer order history

**Request**

`GET /api/customers/orders?email=customer@example.com`

The email is supplied as a query parameter. The response contains the customer's order history, including related order details and products.

### Retrieve low-stock products

**Request**

`GET /api/products/low-stock?threshold=5`

The threshold is configurable. Confirm the actual endpoint and default threshold in the controller and route definitions.

## Inventory and Transaction Safety

Order creation uses a database transaction to ensure that order records, order details, and inventory updates succeed or roll back together.

Product rows are locked using `lockForUpdate()` while the transaction is active. This helps prevent concurrent requests from purchasing the same available stock.

If a requested quantity exceeds available stock, the request is rejected with a validation response. Invalid quantities, including zero, are rejected by request validation.

## Queued Order Confirmation

The `SendOrderConfirmation` job loads the order and its customer and writes confirmation details to the Laravel application log.

The job is dispatched after the order transaction commits, so it does not process an order that failed to commit.

For an asynchronous queue driver, configure the queue connection and start a worker:

```bash
php artisan queue:work
```

For the synchronous queue driver, the job runs immediately after the transaction commits.

Check the application log at:

```text
storage/logs/laravel.log
```

Example log context:

```json
{
  "order_id": 4,
  "customer_email": "customer@example.com",
  "grand_total": "1923.06"
}
```

The values above are illustrative; actual values depend on the order.

## Running Tests

Run the automated test suite:

```bash
php artisan test
```

The current development run completed successfully with **10 tests passed and 38 assertions**.

API scenarios tested manually using Postman include:

- Successful order creation
- Rejection when requested stock exceeds available stock
- Rejection of a quantity of zero

Before submission, also verify that failed requests do not create order records or change inventory.

## User Interface

The application includes an order creation page with:

- Customer email and name
- Product selection and quantity controls
- Multiple product rows
- Line totals
- Subtotal, tax, and grand total
- Place Order action
- Low-stock alert

The interface submits orders to the Laravel API and is intended to follow the assignment's reference wireframe.

## Assumptions

- Product prices and tax percentages are maintained on product records.
- Order totals are calculated by the server using the current product data.
- Stock must be sufficient for every requested order line.
- Customer email addresses are unique.
- The confirmation job logs order details instead of sending a real email.
- The application uses the database and queue configuration specified in `.env`.

## Security

- Do not commit `.env`, passwords, API keys, or other secrets.
- Keep database credentials out of source control.
- Review `.gitignore` before pushing the repository.
- Share the repository privately with the intended reviewer.

## AI Assistance

AI tools were used during development for assistance with implementation, debugging, testing, and documentation. Actual prompts used during development are included in the `/prompts` directory, as required by the assignment.

## License

Developed as part of a Laravel developer assignment.
