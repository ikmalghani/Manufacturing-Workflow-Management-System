# Manufacturing Workflow Management System

A lightweight web application for managing products, inventory movements, sales, and production schedules in a manufacturing environment. It provides a straightforward UI for day‑to‑day operations and reporting.

## Video Demo
[![Watch the demo](https://drive.google.com/thumbnail?id=1Gm72orpyRa2A_LwA1_UyL2JRDS39wHIH&sz=w1200)](https://drive.google.com/file/d/1Gm72orpyRa2A_LwA1_UyL2JRDS39wHIH/preview)

If the thumbnail does not load, use this direct preview link:
`https://drive.google.com/file/d/1Gm72orpyRa2A_LwA1_UyL2JRDS39wHIH/preview`

## Core Features
- Product management (create, update, list)
- Inventory tracking with history
- Sales entry and reporting
- Production scheduling
- User authentication
- Search across records
- Lightweight calculators and reports

## Tech Stack
- PHP (server‑side rendering)
- MySQL (via `connection.php`)
- HTML/CSS/JavaScript

## Quick Start
1. Set up a PHP server (Apache/Nginx) with MySQL.
2. Create a database and update credentials in `connection.php`.
3. Make sure Apache is running and pointing to this project folder (or add a virtual host).
4. Open `http://localhost:{apache_port}/` in your browser and log in.

## Entry Points
- `index.php` — dashboard / landing
- `login.php` — authentication
- `product_list.php` — products
- `inventory_list.php` — inventory
- `sales.php` — sales entry
- `production_schedule.php` — production schedule

## Notes
- Ensure write permissions are set correctly for any server‑side uploads/logging (if enabled).
- For production, configure proper session security and database backups.
