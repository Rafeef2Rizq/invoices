# 🧾 InvoiceApp — Electronic Invoicing SaaS

> A clean, minimal invoicing system built with Laravel, Blade, and MySQL.  
> Create invoices, manage clients, download PDFs, and share public links — all in one workspace.

---

## 🤖 Built with Claude Code

This entire project was designed, architected, and built in collaboration with **[Claude](https://claude.ai)** by Anthropic — specifically using **Claude Code**, Anthropic's AI coding assistant.

Every step of the development was guided by Claude:

-   ✅ Database schema design & migrations
-   ✅ Eloquent models & relationships
-   ✅ Controllers with ownership authorization
-   ✅ Form Requests for validation
-   ✅ Resourceful routing
-   ✅ Blade views with Bootstrap 5
-   ✅ DomPDF invoice generation
-   ✅ Public share links with secure tokens
-   ✅ Company settings & brand customization
-   ✅ Duplicate invoice feature
-   ✅ Reports & analytics with Chart.js
-   ✅ Excel export with Maatwebsite
-   ✅ Landing page (HTML/CSS)

> Claude didn't just write code — it explained every decision, taught the patterns behind each feature, and helped debug issues in real time.

---

## 📸 Screenshots

| Dashboard                       | Create Invoice                     | PDF Invoice                    |
| ------------------------------- | ---------------------------------- | ------------------------------ |
| Stats, revenue, recent invoices | Dynamic line items with live total | Branded PDF with logo & colors |

---

## ✨ Features

### Core

-   🔐 Authentication via Laravel Breeze
-   👤 Single-tenant — all data scoped to logged-in user
-   👥 Customer management (Create / Edit / Delete)
-   🧾 Invoice creation with dynamic line items
-   📄 PDF generation with DomPDF
-   🔁 Duplicate invoice in one click

### Advanced

-   🌐 Public invoice page — shareable link, no login needed
-   🎨 Brand customization — logo, color, prefix, footer
-   📊 Reports — monthly revenue charts, top clients, Excel export
-   🔗 Signed token URLs for secure public access

---

## 🛠 Tech Stack

| Layer     | Technology              |
| --------- | ----------------------- |
| Framework | Laravel 11              |
| Frontend  | Blade + Bootstrap 5     |
| Database  | MySQL                   |
| Auth      | Laravel Breeze          |
| PDF       | barryvdh/laravel-dompdf |
| Excel     | maatwebsite/excel       |
| Charts    | Chart.js (CDN)          |
| Icons     | Bootstrap Icons (CDN)   |

---

## 🗄 Database Schema

```
users
 ├── customers      (user_id FK)
 └── invoices       (user_id FK, customer_id FK)
       ├── invoice_items  (user_id FK, invoice_id FK)
       └── settings       (user_id FK)
```

### Tables

| Table           | Key Fields                                                                                  |
| --------------- | ------------------------------------------------------------------------------------------- |
| `users`         | id, name, email, password                                                                   |
| `customers`     | id, user_id, name, email, phone                                                             |
| `invoices`      | id, user_id, customer_id, invoice_number, status, total, issue_date, due_date, public_token |
| `invoice_items` | id, user_id, invoice_id, name, quantity, price, subtotal                                    |
| `settings`      | id, user_id, company_name, company_logo, invoice_color, invoice_prefix, invoice_footer      |

---

## 🚀 Installation

### Requirements

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL

### Steps

```bash
# 1. Clone the project
git clone https://github.com/your-username/invoiceapp.git
cd invoiceapp

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install && npm run build

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
DB_DATABASE=invoiceapp
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Create storage symlink (for logo uploads)
php artisan storage:link

# 8. Start the server
php artisan serve
```

Visit `http://localhost:8000` and register your account.

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── CustomerController.php
│   │   ├── InvoiceController.php
│   │   ├── PublicInvoiceController.php
│   │   ├── ReportController.php
│   │   └── SettingController.php
│   └── Requests/
│       ├── CustomerRequest.php
│       ├── InvoiceRequest.php
│       └── SettingRequest.php
├── Models/
│   ├── User.php
│   ├── Customer.php
│   ├── Invoice.php
│   ├── InvoiceItem.php
│   └── Setting.php
└── Exports/
    └── InvoicesExport.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   └── guest.blade.php
├── dashboard.blade.php
├── customers/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── _form.blade.php
├── invoices/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   ├── public.blade.php
│   └── pdf.blade.php
├── reports/
│   └── index.blade.php
└── settings/
    └── edit.blade.php
```

---

## 🔗 Routes

| Method   | URI                            | Name                    | Description               |
| -------- | ------------------------------ | ----------------------- | ------------------------- |
| GET      | `/dashboard`                   | `dashboard`             | Main dashboard            |
| GET/POST | `/customers`                   | `customers.*`           | Customer CRUD             |
| GET/POST | `/invoices`                    | `invoices.*`            | Invoice CRUD              |
| PATCH    | `/invoices/{id}/status`        | `invoices.updateStatus` | Toggle paid/draft         |
| PATCH    | `/invoices/{id}/toggle-public` | `invoices.togglePublic` | Enable/disable share link |
| POST     | `/invoices/{id}/duplicate`     | `invoices.duplicate`    | Clone an invoice          |
| GET      | `/invoices/{id}/pdf`           | `invoices.pdf`          | Download PDF              |
| GET      | `/invoice/{token}`             | `invoices.public`       | Public invoice page       |
| GET      | `/reports`                     | `reports.index`         | Analytics & reports       |
| GET      | `/reports/export`              | `reports.export`        | Export Excel              |
| GET/PUT  | `/settings`                    | `settings.*`            | Company settings          |

---

## 🎯 Key Design Decisions

### Security

Every controller uses an ownership check before any operation:

```php
abort_if($invoice->user_id !== auth()->id(), 403);
```

This prevents users from accessing other users' data via URL manipulation.

### Auto Invoice Numbers

Invoice numbers are generated automatically in the `Invoice` model's `booted()` method using the user's custom prefix:

```
INV-0001, INV-0002 ...  (default)
BILL-0001, BILL-0002 ... (custom prefix)
```

### Public Links

Secure 64-character hex tokens allow clients to view invoices without logging in:

```
https://yourapp.com/invoice/a3f9b2c1d4e5...
```

Tokens can be disabled at any time by the invoice owner.

### PDF Generation

DomPDF uses `storage_path()` instead of `asset()` for logo images since it reads directly from the filesystem — not via HTTP.

---

## 📊 Reports

The reports page includes:

-   📈 Monthly revenue bar chart (Chart.js)
-   🍩 Invoice status doughnut chart
-   📉 Last 30 days line chart
-   🏆 Top 5 customers by revenue
-   📥 Excel export with styled headers

---

## 🧩 Features Roadmap

-   [ ] Email invoice to client (Laravel Mail)
-   [ ] VAT / Tax support
-   [ ] Recurring invoices (Laravel Scheduler)
-   [ ] Partial payments tracking
-   [ ] Stripe payment integration
-   [ ] REST API with Sanctum
-   [ ] Multi-tenancy support

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## 📄 License

[MIT](LICENSE)

---

## 🙏 Acknowledgements

-   **[Anthropic Claude](https://claude.ai)** — AI pair programmer that built this project from scratch
-   **[Laravel](https://laravel.com)** — The PHP framework for web artisans
-   **[barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)** — PDF generation
-   **[Maatwebsite/Laravel-Excel](https://laravel-excel.com)** — Excel export
-   **[Bootstrap](https://getbootstrap.com)** — CSS framework
-   **[Chart.js](https://chartjs.org)** — JavaScript charts

---

<div align="center">
  <strong>Built with ❤️ using Laravel + Claude Code</strong><br>
  <sub>Every line of this project was written in collaboration with Claude by Anthropic</sub>
</div>
