# Online Ticket Booking System: The Dacca Automobile Museum

A web app where visitors create an account, log in and book museum tickets. Built as a university coursework project (group project, North South University) with **PHP and MySQL**, then cleaned up afterwards: bugs fixed, security hardened, UI polished.

## Features
- Register and log in (passwords hashed with `password_hash`)
- Ticket booking form with server-side validation and a booking reference number
- Login required for booking; booking form is pre-filled with the account email
- Responsive pages (desktop and mobile)
- `analytics_queries.sql`: six SQL queries on the bookings (monthly trend, popular places, group-size bands, busiest weekdays, repeat visitors, month-over-month change using CTE and window functions)

## Tech
PHP 8 (mysqli, prepared statements), MySQL / MariaDB, HTML, CSS. No framework.

## Run it locally
1. Install PHP 8+ and MySQL/MariaDB (or use XAMPP).
2. Create the database and tables:
   ```
   mysql -u root -e "CREATE DATABASE museum_db CHARACTER SET utf8mb4"
   mysql -u root museum_db < schema.sql
   ```
3. Copy `config.sample.php` to `config.php` and enter your database details.
4. Start the site: `php -S localhost:8000` inside this folder, then open http://localhost:8000/home.php (with XAMPP, copy the folder into `htdocs` instead).
5. Optional: `mysql -u root museum_db < sample_data.sql` loads fake bookings so you can try the analytics queries.

## Deploy on free hosting (InfinityFree)
1. Create a hosting account, then create a MySQL database in the control panel. Note the host, username, password and full database name.
2. In phpMyAdmin, select that database and import `schema.sql`.
3. Copy `config.sample.php` to `config.php`, enter the details from step 1, and upload every file (including `config.php`) to the `htdocs` folder with the file manager or FTP. Do not commit `config.php` to Git.
4. Open your site URL, register an account, log in and make a test booking.

## Database
| Table | Purpose |
|---|---|
| `user_form` | Accounts: unique email, hashed password |
| `book_form` | Bookings: name, email, phone, address, place, visitors, arrival date |

## Changes since the original course submission
| Problem in the original | Fix |
|---|---|
| Phone number was never saved (form field `Phone`, code read `phone`) | Field names match; phone uses `type="tel"` |
| Visitor count was never saved (variable name mismatch) | Fixed and validated (1 to 50) |
| SQL built by string concatenation (SQL injection) | Prepared statements everywhere |
| Passwords stored as MD5 | `password_hash` / `password_verify` |
| Same email could register twice with a different password | Unique email, checked on registration |
| Pages after a redirect still loaded for logged-out users (no `exit`) | Redirects stop execution; booking requires login |
| No confirmation after booking | Reference number shown; errors keep what you typed |
| Two databases, no schema file | One database, `schema.sql` included |
| Credentials in code | `config.php` kept out of Git (`config.sample.php` provided) |

## Limitations and ideas
- No admin page yet to view or manage bookings (the analytics queries are run directly in SQL).
- No ticket pricing, payment or confirmation emails.
- No limit on how many visitors can book the same day.

## Team
Group coursework project. Contributors: Raihan Rashid and two teammates (add their names if they agree).
