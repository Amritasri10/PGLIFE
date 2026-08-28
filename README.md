# PG Life

PG Life is a full-stack PG (Paying Guest) discovery and booking web app built with **PHP**, **MySQL**, **Bootstrap**, and **JavaScript**. Users can search PGs by city, mark interest, request bookings, and pay online after admin confirmation. Admins manage cities, properties, testimonials, users, bookings, and payments from a dedicated panel.

---

## Features

### User website
- Search PGs by city (case-insensitive) and browse major cities
- Property list with rent / rating filters and interested-user counts
- Property detail page with carousel, amenities, ratings, and dynamic testimonials
- Signup / Login (AJAX + JSON APIs) with SweetAlert notifications
- Mark / unmark interested PGs (login required)
- Book Now → booking request (`pending`)
- Dashboard: profile, bookings, cancel unpaid bookings, interested list
- Pay Now (Razorpay) after admin confirms a booking

### Admin panel (`/PGLIFE/admin/`)
- Dashboard stats (properties, cities, users, bookings, payments, testimonials)
- Manage **Properties** (CRUD, amenities, cover image)
- Manage **Cities**
- Manage **Testimonials** (tabular list + add/edit form; shown on PG detail)
- Manage **Users** and roles (`user` / `admin`)
- Manage **Bookings** (pending → confirmed / cancelled / completed)
- View **Payments** (Razorpay order ID, payment ID, method, amount)

---

## Roles

| Role | Access |
|------|--------|
| **user** | Website browse, interest, book, pay, dashboard |
| **admin** | Admin panel + website view; cannot create user bookings |

Default admin (auto-created on first DB connect):

- **Email:** `admin@pglife.com`
- **Password:** `Admin@123`

After login, admins are redirected to `/PGLIFE/admin/index.php`.

---

## Technology stack

| Layer | Tech |
|-------|------|
| Frontend | HTML, CSS, Bootstrap 4, JavaScript, AJAX / Fetch, SweetAlert2, Razorpay Checkout |
| Backend | PHP 8.x |
| Database | MySQL / MariaDB |
| Server | Apache (XAMPP / WAMP) |

---

## Project structure

```text
PGLIFE/
├── admin/                 # Admin UI pages
├── api/                   # JSON APIs (auth, booking, payment, admin CRUD)
├── css/                   # Styles (responsive)
├── includes/              # Header, footer, DB, schema, Razorpay config
├── img/                   # Images & amenities icons
├── js/                    # Frontend scripts
├── pg_life_database/      # SQL dump
├── index.php
├── property_list.php
├── property_detail.php
├── dashboard.php
└── README.md
```

---

## Setup

### 1. Place the project
Copy or clone the project into your web root, e.g. `C:\xampp\htdocs\PGLIFE`.

### 2. Start Apache + MySQL
Start services from the XAMPP Control Panel.

> This project is configured for MySQL on **port `3307`** (see `includes/database_connect.php`).  
> If your MySQL uses `3306`, change `$db_port` in:
> - `includes/database_connect.php`
> - `includes/database_connect_hide_error.php`

### 3. Import database
1. Open phpMyAdmin
2. Import `pg_life_database/pg_life.sql` (creates database `pg_life`)
3. On first page load, the app auto-migrates extra columns/tables (`role`, `bookings`, `payments`, etc.)

Default DB credentials in code:

- Host: `127.0.0.1`
- User: `root`
- Password: *(empty)*
- Database: `pg_life`
- Port: `3307`

### 4. Razorpay (optional for payments)
Edit `includes/razorpay_config.php` and paste your **Test** Key ID and Secret from the [Razorpay Dashboard](https://dashboard.razorpay.com/):

```php
return array(
  "key_id" => "rzp_test_xxxxxxxx",
  "key_secret" => "xxxxxxxxxxxxxxxx",
);
```

Test card (Razorpay test mode): `4111 1111 1111 1111`, any future expiry, CVV `123`.

Without keys, booking still works; **Pay Now** returns a JSON message that keys are missing.

### 5. Open the app
Website: [http://localhost/PGLIFE/index.php](http://localhost/PGLIFE/index.php)  
Admin: [http://localhost/PGLIFE/admin/index.php](http://localhost/PGLIFE/admin/index.php) (login as admin first)

---

## Main user flow

1. Open home → search or pick a city  
2. Open a PG → View details  
3. Login → Book Now (status `pending`)  
4. Admin confirms booking  
5. User Dashboard → **Pay now** (Razorpay)  
6. Admin sees payment under **Payments** / **Bookings**

---

## APIs (JSON)

Examples:

| Endpoint | Purpose |
|----------|---------|
| `api/signup_submit.php` | Register user |
| `api/login_submit.php` | Login (returns `role`, `redirect`) |
| `api/toggle_interested.php` | Heart toggle |
| `api/create_booking.php` | Create booking |
| `api/cancel_booking.php` | Cancel unpaid booking |
| `api/create_razorpay_order.php` | Create Razorpay order |
| `api/verify_razorpay_payment.php` | Verify payment signature |
| `api/admin_*.php` | Admin CRUD (auth required) |

Responses use `Content-Type: application/json`.

---

## Responsive UI

Layouts are tuned for desktop, tablet, and mobile:

- Collapsible navbar  
- Adaptive home hero and city grid  
- Property cards and detail sections stack on small screens  
- Dashboard / admin tables scroll horizontally on narrow viewports  
- Admin sidebar stacks into a top navigation strip on tablets/phones  

---

## Notes

- Signup always creates a **user** role account.  
- Paid bookings cannot be cancelled by the user or set back to pending/cancelled by admin.  
- Console errors like `chrome-extension://invalid` come from browser extensions, not this app.  
- Educational / internship project — do not use production secrets in the repo.

---

## License

For educational purposes only.
