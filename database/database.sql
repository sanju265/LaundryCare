-- Run this in pgAdmin Query Tool after creating database laundrycare_db.

CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS customers (
    id SERIAL PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(160),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
    id SERIAL PRIMARY KEY,
    service_name VARCHAR(120) UNIQUE NOT NULL,
    description TEXT,
    price_per_kg NUMERIC(10,2) NOT NULL CHECK (price_per_kg >= 0),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id SERIAL PRIMARY KEY,
    customer_id INTEGER NOT NULL REFERENCES customers(id) ON DELETE RESTRICT,
    service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE RESTRICT,
    weight NUMERIC(8,2) NOT NULL CHECK (weight > 0),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NOT NULL,
    total_amount NUMERIC(10,2) NOT NULL CHECK (total_amount >= 0),
    status VARCHAR(20) NOT NULL CHECK (status IN ('Received','Washing','Ready','Delivered'))
);

-- Sample services
INSERT INTO services(service_name,description,price_per_kg,is_active)
VALUES
('Wash & Fold','Regular washing and folding',60,TRUE),
('Dry Cleaning','Premium dry cleaning',120,TRUE),
('Ironing','Steam ironing service',40,TRUE)
ON CONFLICT (service_name) DO NOTHING;

-- Sample customers
INSERT INTO customers(name,phone,email)
SELECT 'Ravi Kumar','9876543210','ravi@example.com'
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE phone='9876543210');

INSERT INTO customers(name,phone,email)
SELECT 'Ananya S','9123456780','ananya@example.com'
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE phone='9123456780');

-- Sample orders are intentionally omitted; create them from the webpage.
