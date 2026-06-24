# Hotel Menu Management System

A simple PHP-based hotel menu and order management system for admins and users.

## Features
- Admin login and dashboard
- Add and manage hotel menu items
- View customer orders
- User registration/login
- Place orders and view order history

## Setup
1. Start a local PHP server from the project root:
   ```bash
   php -S localhost:8000
   ```
2. Open your browser at http://localhost:8000/
3. Import the SQL file from the database folder into your MySQL database.
4. Update the database credentials in config.php.

## Structure
- admin/ - admin panel pages
- backend/ - authentication and order processing logic
- user/ - user-facing pages
- database/ - SQL schema files
