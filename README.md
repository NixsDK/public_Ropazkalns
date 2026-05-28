# Ropazkalns (Public Design Demo)

Public showcase version of the Ropazkalns website project.  
This repo is focused on **UI/design and page flow**.

## Quick Start

### 1) Clone the repository

git clone https://github.com/NixsDK/public_Ropazkalns.git
cd public_Ropazkalns

### 2) Install PHP dependencies (vendor)
If the site does not load after cloning, install Composer dependencies:
composer install

If vendor/ already exists, but something is broken, refresh dependencies:
composer update

### 3) Run locally
1. Put project in your local web server directory (XAMPP/WAMP/Laragon), or use PHP built-in server.
2. Open site in browser.

Example (PHP built-in server):
php -S localhost:8000

Then open:
http://localhost:8000/HomePage.php

## What this repo includes

- Multi-page PHP website UI
- Responsive layout and styling
- Booking calendar/page design
- Private events page design
- Login/Register/Profile page design
- Demo user session flow (no real database)

## Demo account

Use these credentials for UI demo login:

- **Username:** `demo_user` (or `demo@example.com`)
- **Password:** `demo123`

> This is a demo-only login for preview. No real user/auth backend is connected.

## Important note

This public version is intentionally sanitized:

- No production database credentials
- No admin backend
- No real booking persistence
- No sensitive environment data

Forms and account features are in **design preview mode**.

## Tech stack

- PHP
- HTML/CSS
- JavaScript
- Bootstrap
- Font Awesome

## Project structure (main)

- `HomePage.php`
- `head.php`, `footer.php`, `lang.php`
- `About/`, `Activities/`, `Rentals/`, `Contact/`
- `login/`, `register/`, `UserProfile/`
- `css/`, `js/`, `images/`, `translations/`