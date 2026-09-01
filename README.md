# RadiantCommerce - Modern Full-Stack E-Commerce Platform

RadiantCommerce is an enterprise-grade full-stack e-commerce monorepo featuring a robust Laravel RESTful API backend, a Filament-powered administrative panel, an integrated Midtrans payment gateway, and modern React client applications built with Vite and Tailwind CSS.

---

## Table of Contents

- [Architecture Overview](#architecture-overview)
- [Directory Structure](#directory-structure)
- [Technology Stack](#technology-stack)
- [Key Features](#key-features)
- [API Reference](#api-reference)
- [Installation & Getting Started](#installation--getting-started)
  - [Prerequisites](#prerequisites)
  - [Backend Setup](#backend-setup)
  - [Frontend Setup](#frontend-setup)
- [Environment Configuration](#environment-configuration)
- [Payment Gateway Integration (Midtrans)](#payment-gateway-integration-midtrans)
- [Testing](#testing)
- [License](#license)

---

## Architecture Overview

The repository is structured as a decoupled monorepo containing:

1. **Backend Service (`/backend`)**: Headless REST API powered by Laravel, handling business logic, authentication, transactional data, notifications, and payment processing. Includes an administrative dashboard powered by Filament.
2. **Frontend Client (`/frontend-demo-1`)**: High-performance Single Page Application (SPA) built with React 19, Vite, and Tailwind CSS.
3. **Shared Logic Layer (`/shared`)**: Reusable API clients, React Context providers (Authentication, Cart, Wishlist, Multi-language i18n), and common utility functions across frontend applications.
4. **Documentation (`/docs`)**: Architecture references, design system specifications, and design assets.

```
                    +-----------------------------+
                    |      Filament Admin         |
                    |      (Internal Team)        |
                    +--------------+--------------+
                                   |
+--------------------+             v             +--------------------+
|  React Client SPA  | <---> [ Laravel API ] <-> |  Midtrans Gateway  |
| (frontend-demo-1)  |       [  & Database ]     | (Snap & Webhooks)  |
+---------+----------+             ^             +--------------------+
          |                        |
          +---- [ Shared Logic ] --+
             (Contexts & API SDK)
```

---

## Directory Structure

```
.
├── backend/                  # Laravel REST API & Filament Admin Panel
│   ├── app/
│   │   ├── Filament/         # Admin resources (Products, Orders, Users, Reviews)
│   │   ├── Http/Controllers/ # REST API endpoints
│   │   ├── Mail/             # Transactional email templates
│   │   ├── Models/           # Eloquent data models
│   │   └── Services/         # Order, Cart, and Midtrans business services
│   ├── config/               # Application, CORS, and payment configurations
│   ├── database/             # Migrations, seeders, and model factories
│   ├── routes/               # API, Web, and Console routes
│   └── tests/                # Automated feature and unit test suites
├── frontend-demo-1/          # React SPA (Modern Minimalist Studio Theme)
│   ├── public/               # Static assets and icons
│   ├── src/
│   │   ├── assets/           # Local visual assets
│   │   ├── components/       # UI components (Navbar, Footer, CartDrawer, etc.)
│   │   ├── pages/            # Application views (Products, Detail, Checkout, etc.)
│   │   ├── App.jsx           # Main router and layout wrapper
│   │   └── main.jsx          # SPA entrypoint
│   └── package.json
├── shared/                   # Shared modules for frontends
│   ├── api/                  # Axios HTTP client and domain API methods
│   ├── context/              # Auth, Cart, Wishlist, and Language Contexts
│   └── utils/                # Currency formatters, date helpers, Snap loader
├── docs/                     # Design documentation and theme specifications
├── .gitignore                # Global workspace gitignore
└── README.md                 # Project documentation
```

---

## Technology Stack

### Backend
- **Framework**: Laravel 11/12 (PHP 8.2+)
- **Admin Panel**: Filament v3 / v4 (Livewire & Alpine.js)
- **Authentication**: Laravel Sanctum (Token-based SPA authentication)
- **Database**: MySQL / MariaDB (Supports SQLite for lightweight testing)
- **Payment Gateway**: Midtrans PHP SDK (Snap Payment Engine)
- **Testing**: PHPUnit

### Frontend
- **Framework**: React 19
- **Build Tool**: Vite 6+
- **Routing**: React Router v7
- **Styling**: Tailwind CSS v4
- **Icons**: Lucide React
- **HTTP Client**: Axios

### Shared Layer
- Modular Context API (State persistence with `localStorage`)
- Centralized Axios interceptors for automated bearer token handling
- Dual-language dictionary (Indonesian & English)

---

## Key Features

### 1. Customer Experience
- **Authentication & User Profiles**: User registration, login, profile management, and password update via Sanctum tokens.
- **Multiple Address Book**: Add, edit, delete, and designate primary delivery addresses.
- **Product Catalog & Discovery**: Fast filtering by category, search by keyword, featured products, best sellers, and new arrivals.
- **Product Detail & Gallery**: Multi-image gallery, real-time stock indicator, and customer reviews display.
- **Shopping Cart & Checkout**: Real-time quantity sync, automatic subtotal and shipping calculations.
- **Wishlist**: Quick bookmarking with persisted storage across sessions.
- **Midtrans Payment Integration**: Integrated Snap popup supporting GoPay, QRIS, Virtual Accounts (BCA, Mandiri, BNI, BRI), Credit/Debit Cards, and Convenience Stores.
- **Order Management & Tracking**: View order histories, payment statuses (`pending`, `paid`, `cancelled`), and invoice breakdowns.
- **Bilingual Interface**: Seamless switching between Bahasa Indonesia and English.

### 2. Administrator & Merchant Tools (Filament)
- **Executive Dashboard**: Key performance metrics, total revenue counters, and recent order feeds.
- **Product Management**: SKU generation, price settings, stock levels, category assignments, and multi-image uploaders.
- **Category Management**: Hierarchical categorization with slug auto-generation.
- **Order Processing**: Detailed order inspection, manual or automated payment status updates, shipping tracking notes.
- **Customer Moderation**: Customer lists, address records, and customer review approvals.

---

## API Reference

All API routes are prefixed with `/api`.

### Public Endpoints
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/auth/register` | Create a new customer account |
| `POST` | `/api/auth/login` | Authenticate customer and receive bearer token |
| `GET` | `/api/products` | Paginated product list with search & category filters |
| `GET` | `/api/products/{slug}` | Detailed product data with images and category |
| `GET` | `/api/products/featured` | Curated list of featured products |
| `GET` | `/api/products/best-sellers` | Top-selling products |
| `GET` | `/api/products/new-arrivals` | Latest inventory additions |
| `GET` | `/api/categories` | List all active product categories |
| `GET` | `/api/categories/{slug}` | Retrieve single category details |
| `GET` | `/api/products/{productId}/reviews` | Fetch reviews for a specific product |
| `POST` | `/api/payments/notification` | Midtrans Webhook URL for transaction lifecycle events |

### Protected Endpoints (Requires `Authorization: Bearer <token>`)
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/auth/user` | Fetch current authenticated user profile |
| `PUT` | `/api/auth/profile` | Update profile information (name, phone) |
| `PUT` | `/api/auth/password` | Update account password |
| `POST` | `/api/auth/logout` | Invalidate current session token |
| `GET` | `/api/addresses` | List all saved delivery addresses |
| `POST` | `/api/addresses` | Create a new delivery address |
| `PUT` | `/api/addresses/{id}` | Update existing address details |
| `DELETE` | `/api/addresses/{id}` | Remove address record |
| `POST` | `/api/addresses/{id}/primary`| Set target address as primary |
| `GET` | `/api/cart` | Get current active cart with product details |
| `POST` | `/api/cart/items` | Add product to cart |
| `PUT` | `/api/cart/items/{id}` | Update cart item quantity |
| `DELETE` | `/api/cart/items/{id}` | Remove line item from cart |
| `DELETE` | `/api/cart/clear` | Empty cart |
| `GET` | `/api/orders` | Fetch user's order history |
| `GET` | `/api/orders/{id}` | Detailed order status and invoice |
| `POST` | `/api/orders` | Create order and checkout from current cart |
| `PUT` | `/api/orders/{id}/cancel` | Cancel an unpaid order |
| `GET` | `/api/payments/{orderId}/snap-token` | Generate Midtrans Snap payment token |
| `GET` | `/api/payments/{orderId}/status` | Check remote payment status from Midtrans |
| `GET` | `/api/wishlist` | Retrieve customer wishlist |
| `POST` | `/api/wishlist/toggle` | Add/remove item from wishlist |
| `POST` | `/api/products/{productId}/reviews` | Submit product review and rating |

---

## Installation & Getting Started

### Prerequisites
- **PHP**: >= 8.2 with `pdo`, `mbstring`, `openssl`, `curl`, `gd`, `zip` extensions enabled
- **Composer**: >= 2.x
- **Node.js**: >= 18.x with `npm`
- **Database Server**: MySQL 8.x or SQLite 3

---

### Backend Setup

1. Open terminal and navigate into the `backend` directory:
   ```bash
   cd backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure environment settings:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database settings inside `backend/.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecommerce_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Run database migrations and seed sample demo data:
   ```bash
   php artisan migrate --seed
   ```

6. Link public storage directory for media assets:
   ```bash
   php artisan storage:link
   ```

7. Start the local backend development server:
   ```bash
   php artisan serve
   ```
   The backend API will run at `http://127.0.0.1:8000`.  
   The Filament Admin Panel is accessible at `http://127.0.0.1:8000/admin`.

---

### Frontend Setup

1. Open a new terminal and navigate into the `frontend-demo-1` directory:
   ```bash
   cd frontend-demo-1
   ```

2. Install Node dependencies:
   ```bash
   npm install
   ```

3. Configure environment settings:
   ```bash
   cp .env.example .env
   ```

4. Verify or adjust variables in `frontend-demo-1/.env`:
   ```env
   VITE_API_URL=http://localhost:8000/api
   VITE_MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   ```

5. Start the Vite development server:
   ```bash
   npm run dev
   ```
   The client application will run at `http://localhost:5173`.

---

## Environment Configuration

### Backend (`backend/.env`)
| Variable | Description | Default / Example |
|---|---|---|
| `APP_NAME` | Name of the application | `RadiantCommerce` |
| `APP_URL` | Base URL of backend server | `http://localhost:8000` |
| `FRONTEND_URLS` | Allowed origin URLs for CORS | `http://localhost:5173,http://127.0.0.1:5173` |
| `MIDTRANS_SERVER_KEY` | Midtrans Secret Server Key | `SB-Mid-server-...` |
| `MIDTRANS_CLIENT_KEY` | Midtrans Client Key | `SB-Mid-client-...` |
| `MIDTRANS_IS_PRODUCTION` | Production flag for payments | `false` |
| `MIDTRANS_MERCHANT_ID` | Merchant ID assigned by Midtrans | `G123456789` |

### Frontend (`frontend-demo-1/.env`)
| Variable | Description | Default / Example |
|---|---|---|
| `VITE_API_URL` | Endpoint URL pointing to Backend API | `http://localhost:8000/api` |
| `VITE_MIDTRANS_CLIENT_KEY` | Midtrans Client Key for Snap JS SDK | `SB-Mid-client-...` |

---

## Payment Gateway Integration (Midtrans)

1. Register or sign in to your [Midtrans Dashboard](https://dashboard.midtrans.com/).
2. Switch to **Sandbox Mode** for local testing.
3. Obtain your **Server Key** and **Client Key** from `Settings > Access Keys`.
4. Add the keys to `backend/.env` and `frontend-demo-1/.env`.
5. Set your Payment Webhook / Notification URL in Midtrans Dashboard (`Settings > Configuration > Payment Notification URL`):
   ```
   https://your-domain.com/api/payments/notification
   ```
   *(For local testing, use tunneling tools such as Ngrok or Cloudflare Tunnels to forward webhooks).*

---

## Testing

Run the automated backend test suite using PHPUnit:

```bash
cd backend
php artisan test
```

To run a specific test suite:
```bash
php artisan test --filter=EcommerceApiTest
```

---

## License

This project is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).
