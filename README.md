LaundryCare – Laundry Management System

LaundryCare is a web-based Laundry Management System developed using PHP, PostgreSQL, HTML, CSS, and XML.

The system provides an admin dashboard to manage customers, laundry services, and orders. It supports complete CRUD operations and XML import/export.

Features:
- Admin login and authentication
- Dashboard with laundry statistics
- Customer management
- Add, view, edit, and delete customers
- Search customers
- Service management
- Add, view, edit, and delete services
- Set service price per kg
- Active/Inactive service status
- Order management
- Add, view, edit, and delete orders
- Automatic order total calculation
- Order status tracking
- Recent Orders dashboard
- Today's Deadline Orders dashboard
- PostgreSQL database integration
- XML import
- XML export
Technologies Used:

Frontend: HTML, CSS
Backend: PHP
Database: PostgreSQL
Data Format: XML
Server: Apache / XAMPP
Database Tool: pgAdmin 4

Database:

The application uses PostgreSQL to store customer, service, order, and administrator information.

Main tables:
- admin_users
- customers
- services
- orders
CRUD Operations:

Create – Add customers, services, and orders
Read – Display stored records
Update – Edit existing records
Delete – Remove records

XML Integration:

LaundryCare supports XML data exchange.

XML Import: XML files containing customers, services, and orders can be uploaded through the application. The PHP application reads the XML data and inserts the corresponding records into PostgreSQL.

XML Export: Data stored in PostgreSQL can be exported as an XML file containing customer details, service details, and order details.

The application demonstrates:

XML → PHP → PostgreSQL

and

PostgreSQL → PHP → XML

Order Status:
- Received
- Washing
- Ready
- Delivered
The dashboard also displays orders that are due on the current date.

Project Structure:

LaundryCare/
- assets/
- config/
- customers/
- database/
- includes/
- orders/
- services/
- xml/
- dashboard.php
- index.php
- login.php
- logout.php
Setup:
1. Install XAMPP, PostgreSQL, and pgAdmin 4.
2. Copy the LaundryCare project into C:\xampp\htdocs\LaundryCare
3. Create a PostgreSQL database named laundrycare_db.
4. Execute database/database.sql using pgAdmin.
5. Configure the PostgreSQL connection in config/database.php.
6. Start Apache from XAMPP and make sure PostgreSQL is running.
7. Open http://localhost/LaundryCare/ in a browser.
Database Verification:

The data stored through the PHP application can be verified directly in PostgreSQL using:

SELECT * FROM customers;

SELECT * FROM services;

SELECT * FROM orders;

This demonstrates that data entered through the PHP application is persisted in PostgreSQL.

Project Workflow:

Admin Login → Dashboard → Customers → Services → Orders → PostgreSQL → XML Import / Export

Project Purpose:

LaundryCare was developed as a database management system demonstration combining PHP web development, PostgreSQL database management, CRUD operations, authentication, XML processing, database persistence, and data import/export.
