# KoboTrack - Payment Verification & Registration Management Platform

A full-stack payment verification and registration management platform built with **CodeIgniter 4** (backend) and **Tailwind CSS** (frontend dashboard).

## Features

- **Business Management** - Create, edit, delete, and manage businesses with auto-generated API keys
- **Transaction Creation API** - Secure REST API endpoint to create payment transactions
- **Kobo-Based Verification** - Unique randomized kobo value appended to each transaction for payment verification
- **Transaction Polling** - Real-time transaction status polling endpoint (every 5 seconds)
- **Payment Webhooks** - HMAC SHA256 signed webhook endpoint for payment gateway callbacks
- **Registration Storage** - Dynamic JSON registration data storage linked to transactions
- **Admin Dashboard** - Modern Tailwind CSS dashboard with analytics, filtering, and management

## Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | CodeIgniter 4 (PHP 7.4+ / 8.0+) |
| Database | MySQL |
| Frontend | Tailwind CSS, Alpine.js |
| Icons | Font Awesome 6 |
| Auth | Session-based with bcrypt hashing |

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite (or Nginx)
- Composer

### Step 1: Clone / Setup

Navigate to your web root (e.g., `htdocs`, `www`, or `/var/www/html`) and ensure the project is at the document root:

```
/path/to/webroot/
├── .env
├── .htaccess
├── index.php
├── ci/
├── writable/
└── public/
```

### Step 2: Database Setup

**Option A: Using the SQL file (recommended for initial setup)**

```bash
mysql -u root -p < database.sql
```

**Option B: Using CodeIgniter Migrations**

1. Configure your database credentials in `.env`:
   ```
   database.default.hostname = localhost
   database.default.database = kobotrack
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   ```

2. Run migrations:
   ```bash
   php ci/spark migrate
   ```

3. (Optional) Run seeders:
   ```bash
   php ci/spark db:seed AdminSeeder
   php ci/spark db:seed BusinessSeeder
   ```

### Step 3: Configure Application

Edit the `.env` file to match your environment:

```env
app.baseURL = http://localhost/kobotrack/
CI_ENVIRONMENT = development
encryption.key = your-32-char-encryption-key-here
```

### Step 4: Set Directory Permissions

Ensure the `writable/` directory is writable by your web server:

```bash
chmod -R 755 writable/
chmod -R 755 ci/writable/
```

### Step 5: Verify Installation

Open your browser and navigate to:
- **Admin Login**: `http://localhost/kobotrack/login`
- **Default Credentials**: admin / admin123

---

## API Documentation

### Authentication

All API endpoints (except webhook) require API key authentication via headers:

| Header | Description |
|--------|-------------|
| `X-API-Key` | Your business public API key |
| `X-API-Secret` | Your business secret API key |

### Endpoints

#### 1. Create Transaction

Creates a new payment transaction with randomized kobo verification value.

**Endpoint:** `POST /api/v1/transaction/create`

**Headers:**
```
X-API-Key: pk_live_YOUR_PUBLIC_KEY
X-API-Secret: sk_live_YOUR_SECRET_KEY
Content-Type: application/json
```

**Request Body:**
```json
{
    "business_id": "BUS_001",
    "email": "user@example.com",
    "amount": 5000
}
```

**With Registration Data:**
```json
{
    "business_id": "BUS_001",
    "email": "user@example.com",
    "amount": 10000,
    "registration": {
        "full_name": "John Doe",
        "event": "Tech Conference 2025",
        "ticket_type": "VIP",
        "quantity": 2
    }
}
```

**Response (201 Created):**
```json
{
    "status": true,
    "transaction_id": "TXN_ABC123",
    "original_amount": 5000,
    "payable_amount": 4999.37,
    "currency": "NGN",
    "message": "Transaction created successfully",
    "business_id": "BUS_01",
    "registration_id": "REG_XYZ789"
}
```

#### 2. Transaction Status Polling

Poll the transaction status (recommended every 5 seconds from frontend).

**Endpoint:** `GET /api/v1/transaction/status/{transaction_id}`

**Response (200 OK):**
```json
{
    "status": true,
    "transaction_id": "TXN_ABC123",
    "payment_status": "success",
    "amount_paid": 4999.37,
    "business_id": "BUS_01",
    "registration_id": "REG_XYZ789"
}
```

**Possible Status Values:**
- `pending` - Transaction created, awaiting payment
- `success` - Payment confirmed via webhook
- `failed` - Payment verification failed
- `expired` - Transaction expired

#### 3. Payment Webhook

Receives payment gateway callbacks and verifies authenticity.

**Endpoint:** `POST /api/v1/webhook/payment`

**Headers:**
```
X-Payment-Signature: HMAC_SHA256_SIGNATURE
Content-Type: application/json
```

**Request Body:**
```json
{
    "transaction_id": "TXN_ABC123",
    "amount_paid": 4999.37,
    "currency": "NGN",
    "status": "completed"
}
```

**Response (200 OK):**
```json
{
    "status": true,
    "message": "Webhook processed successfully",
    "data": {
        "transaction_id": "TXN_ABC123",
        "payment_status": "success"
    }
}
```

### Webhook Signature Generation

The signature is an HMAC SHA256 hash of the raw JSON request body using your business webhook secret:

```php
$signature = hash_hmac('sha256', $payload, $webhook_secret);
```

### Error Responses

**401 Unauthorized:**
```json
{
    "status": false,
    "message": "Invalid API credentials"
}
```

**422 Validation Error:**
```json
{
    "status": false,
    "message": "Validation failed",
    "errors": {
        "email": "The email field must contain a valid email address.",
        "amount": "The amount field must be greater than 100."
    }
}
```

**404 Not Found:**
```json
{
    "status": false,
    "message": "Transaction not found"
}
```

---

## Webhook Testing Guide

### Using Postman

1. Import the `postman_collection.json` file
2. Set the `base_url` variable to your instance URL
3. Replace placeholder API keys with your business credentials
4. For webhook testing, compute the HMAC SHA256 signature:

### Computing HMAC Signature (PHP)

```php
$payload = json_encode([
    'transaction_id' => 'TXN_ABC123',
    'amount_paid' => 4999.37,
    'currency' => 'NGN',
    'status' => 'completed'
]);

$webhookSecret = 'whsec_YOUR_WEBHOOK_SECRET';
$signature = hash_hmac('sha256', $payload, $webhookSecret);

// Add this as X-Payment-Signature header
echo $signature;
```

### Using cURL

```bash
# Step 1: Generate HMAC signature
PAYLOAD='{"transaction_id":"TXN_ABC123","amount_paid":4999.37,"currency":"NGN","status":"completed"}'
SIGNATURE=$(echo -n "$PAYLOAD" | openssl dgst -sha256 -hmac "whsec_YOUR_WEBHOOK_SECRET" | awk '{print $NF}')

# Step 2: Send webhook
curl -X POST http://localhost/kobotrack/api/v1/webhook/payment \
  -H "Content-Type: application/json" \
  -H "X-Payment-Signature: $SIGNATURE" \
  -d "$PAYLOAD"
```

---

## Admin Dashboard

| URL | Description |
|-----|-------------|
| `/login` | Admin login page |
| `/admin` | Dashboard with analytics |
| `/admin/businesses` | Business management |
| `/admin/businesses/create` | Create new business |
| `/admin/businesses/edit/{id}` | Edit business & view API keys |
| `/admin/transactions` | Transaction management |
| `/admin/transactions/view/{id}` | Transaction details |

### Default Admin Account

| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## Database Tables

| Table | Description |
|-------|-------------|
| `businesses` | Business accounts with API keys |
| `transactions` | Payment transaction records |
| `registrations` | Dynamic JSON registration data |
| `webhook_logs` | Incoming webhook request logs |
| `admins` | Dashboard admin users |

---

## Security Features

- API authentication via public/secret key pair
- HMAC SHA256 webhook signature verification
- bcrypt password hashing for admin accounts
- CSRF protection on web forms
- Input validation and sanitization
- SQL injection prevention via Query Builder
- XSS filtering via Escaper library
- Session-based admin authentication
- Environment variable configuration for sensitive data
- Auto-generated cryptographically secure API keys

---

## Project Structure

```
/
├── .env                    # Environment configuration
├── .htaccess               # Apache rewrite rules
├── index.php               # Front controller
├── database.sql            # Complete database schema + seed data
├── postman_collection.json # API test collection
├── README.md               # Documentation
├── public/
│   ├── css/admin.css
│   └── js/admin.js
├── ci/
│   └── app/
│       ├── Config/
│       │   ├── Routes.php
│       │   ├── Filters.php
│       │   ├── Database.php
│       │   └── App.php
│       ├── Controllers/
│       │   ├── Api/
│       │   │   ├── Transaction.php
│       │   │   └── Webhook.php
│       │   └── Admin/
│       │       ├── Auth.php
│       │       ├── Dashboard.php
│       │       ├── Business.php
│       │       └── Transaction.php
│       ├── Models/
│       ├── Services/
│       │   ├── AuthService.php
│       │   ├── BusinessService.php
│       │   ├── TransactionService.php
│       │   └── WebhookService.php
│       ├── Libraries/
│       │   ├── ApiResponse.php
│       │   └── PaymentGateway.php
│       ├── Filters/
│       │   ├── ApiAuth.php
│       │   ├── AdminAuth.php
│       │   └── Cors.php
│       ├── Helpers/
│       │   ├── api_helper.php
│       │   └── kobo_helper.php
│       ├── Database/
│       │   ├── Migrations/
│       │   │   └── InitialSchema.php
│       │   └── Seeds/
│       │       ├── AdminSeeder.php
│       │       └── BusinessSeeder.php
│       └── Views/
│           └── admin/
│               ├── layouts/main.php
│               ├── auth/login.php
│               ├── dashboard/index.php
│               ├── businesses/
│               │   ├── index.php
│               │   ├── create.php
│               │   └── edit.php
│               ├── transactions/
│               │   ├── index.php
│               │   ├── view.php
│               │   └── registration.php
│               └── components/
│                   ├── alerts.php
│                   ├── header.php
│                   ├── sidebar.php
│                   └── footer.php
```

---

## Kobo Verification System

The kobo-based verification system works as follows:

1. **Transaction Creation**: When a transaction is created, the system deducts ₦1 (100 kobo) from the original amount
2. **Random Kobo Value**: A random kobo value (1-99) is added to the deducted amount
3. **Payable Amount**: The customer is asked to pay `original_amount - 100 + random_kobo` (e.g., ₦5000 becomes ₦4900.37)
4. **Webhook Verification**: The webhook handler matches the exact payable amount including the random kobo value
5. **Security**: Only the system and the paying customer know the exact kobo amount, making it a unique verification mechanism

---

## Deployment

### Production Checklist

- [ ] Set `CI_ENVIRONMENT = production` in `.env`
- [ ] Disable debug toolbar in `Config/Filters.php`
- [ ] Set strong encryption key in `.env`
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Set strong `baseURL` in `.env`
- [ ] Remove/disable any debug endpoints
- [ ] Configure rate limiting on API endpoints
- [ ] Set up database backups
- [ ] Configure error logging

### Apache Configuration

Ensure `mod_rewrite` is enabled and `.htaccess` files are allowed:

```apache
<Directory /path/to/project>
    AllowOverride All
    Require all granted
</Directory>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name kobotrack.example.com;
    root /path/to/project;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

## License

MIT License - See LICENSE file for details.
