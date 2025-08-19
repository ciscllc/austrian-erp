-- Austrian ERP Database Schema
-- Korrektur der Foreign Key Constraints

SET FOREIGN_KEY_CHECKS = 0;

-- Users and Permissions
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role_id INT NOT NULL,
    language VARCHAR(5) DEFAULT 'de',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    module VARCHAR(50),
    action VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- Customer Groups (Muss vor customers kommen)
CREATE TABLE IF NOT EXISTS customer_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    payment_terms INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customers
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_number VARCHAR(50) UNIQUE NOT NULL,
    company_name VARCHAR(255),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255),
    office_email VARCHAR(255),
    phone VARCHAR(50),
    mobile VARCHAR(50),
    website VARCHAR(255),
    language VARCHAR(5) DEFAULT 'de',
    tax_number VARCHAR(50),
    vat_number VARCHAR(50),
    customer_group_id INT,
    payment_terms INT DEFAULT 30,
    credit_limit DECIMAL(12,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_group_id) REFERENCES customer_groups(id)
);

-- Customer Addresses
CREATE TABLE IF NOT EXISTS customer_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    type ENUM('billing', 'shipping') NOT NULL,
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    postal_code VARCHAR(20),
    state VARCHAR(100),
    country VARCHAR(2),
    is_default BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Units (Muss vor products kommen)
CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    symbol VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tax Classes (Muss vor products kommen)
CREATE TABLE IF NOT EXISTS tax_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rate DECIMAL(5,2) NOT NULL,
    country VARCHAR(2),
    state VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Manufacturers (Muss vor products kommen)
CREATE TABLE IF NOT EXISTS manufacturers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    website VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Product Categories (Muss vor products kommen)
CREATE TABLE IF NOT EXISTS product_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    parent_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES product_categories(id)
);

-- Products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    sku VARCHAR(100) UNIQUE,
    manufacturer_sku VARCHAR(100),
    manufacturer_id INT,
    category_id INT,
    base_price DECIMAL(12,4) NOT NULL,
    cost_price DECIMAL(12,4),
    tax_rate DECIMAL(5,2) NOT NULL,
    tax_class_id INT,
    unit_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_reusable BOOLEAN DEFAULT FALSE,
    hazard_symbols JSON,
    barcode VARCHAR(255),
    weight DECIMAL(10,3),
    dimensions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id),
    FOREIGN KEY (category_id) REFERENCES product_categories(id),
    FOREIGN KEY (unit_id) REFERENCES units(id),
    FOREIGN KEY (tax_class_id) REFERENCES tax_classes(id)
);

-- Sub Products
CREATE TABLE IF NOT EXISTS sub_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    cost_price DECIMAL(12,4),
    manufacturer_id INT,
    hazard_symbols JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manufacturer_id) REFERENCES manufacturers(id)
);

-- Product Sub Products
CREATE TABLE IF NOT EXISTS product_sub_products (
    product_id INT,
    sub_product_id INT,
    quantity DECIMAL(10,3) NOT NULL DEFAULT 1,
    price_override DECIMAL(12,4),
    PRIMARY KEY (product_id, sub_product_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (sub_product_id) REFERENCES sub_products(id) ON DELETE CASCADE
);

-- Suppliers
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    website VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Forwarders
CREATE TABLE IF NOT EXISTS forwarders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    website VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    status ENUM('draft', 'pending', 'confirmed', 'in_progress', 'shipped', 'delivered', 'cancelled') DEFAULT 'draft',
    order_date DATE NOT NULL,
    delivery_date DATE,
    payment_terms INT DEFAULT 30,
    total_amount DECIMAL(12,2) NOT NULL,
    tax_amount DECIMAL(12,2) NOT NULL,
    notes TEXT,
    vehicle_license_plate VARCHAR(20),
    trailer_license_plate VARCHAR(20),
    supplier_id INT,
    forwarder_id INT,
    signature_data TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (forwarder_id) REFERENCES forwarders(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(10,3) NOT NULL,
    unit_price DECIMAL(12,4) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    tax_rate DECIMAL(5,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Documents
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('order', 'delivery', 'invoice', 'reminder') NOT NULL,
    order_id INT NOT NULL,
    document_number VARCHAR(50) NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE,
    total_amount DECIMAL(12,2),
    tax_amount DECIMAL(12,2),
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    pdf_path VARCHAR(500),
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Reminders
CREATE TABLE IF NOT EXISTS reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    reminder_level INT DEFAULT 1,
    reminder_date DATE NOT NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id)
);

-- Settings
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Backups
CREATE TABLE IF NOT EXISTS backups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    type ENUM('database', 'full') NOT NULL,
    size BIGINT,
    location VARCHAR(500),
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default data
INSERT IGNORE INTO roles (id, name, description) VALUES 
(1, 'admin', 'Administrator with full access'),
(2, 'gf', 'Geschäftsführer'),
(3, 'office', 'Büro Mitarbeiter'),
(4, 'user', 'Standard Benutzer');

INSERT IGNORE INTO permissions (name, description, module, action) VALUES
('view_dashboard', 'Can view dashboard', 'dashboard', 'view'),
('manage_users', 'Can manage users', 'users', 'manage'),
('manage_customers', 'Can manage customers', 'customers', 'manage'),
('manage_products', 'Can manage products', 'products', 'manage'),
('manage_orders', 'Can manage orders', 'orders', 'manage'),
('view_reports', 'Can view reports', 'reports', 'view'),
('manage_settings', 'Can manage settings', 'settings', 'manage');

INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7),
(2, 1), (2, 3), (2, 4), (2, 5), (2, 6),
(3, 1), (3, 3), (3, 5), (3, 6),
(4, 1);

INSERT IGNORE INTO customer_groups (id, name, description, discount_percent, payment_terms) VALUES 
(1, 'Standard', 'Standard Kunden', 0, 30),
(2, 'VIP', 'VIP Kunden mit Sonderkonditionen', 5, 14),
(3, 'Neukunden', 'Neu registrierte Kunden', 0, 14);

INSERT IGNORE INTO units (id, name, symbol) VALUES
(1, 'Stück', 'Stk'),
(2, 'Kilogramm', 'kg'),
(3, 'Liter', 'l'),
(4, 'Meter', 'm'),
(5, 'Stunde', 'h');

INSERT IGNORE INTO tax_classes (id, name, rate, country, description) VALUES
(1, 'Standard Steuer', 20.00, 'AT', 'Standard Mehrwertsteuer'),
(2, 'Reduzierte Steuer', 10.00, 'AT', 'Reduzierte Mehrwertsteuer');

INSERT IGNORE INTO manufacturers (id, name, email, phone) VALUES
(1, 'Standard Hersteller', 'info@example.com', '+43 123 456789');

INSERT IGNORE INTO settings (id, key_name, value, type) VALUES
(1, 'company_name', 'Österreichische Firma GmbH', 'string'),
(2, 'company_address', 'Musterstraße 1, 1234 Wien', 'string'),
(3, 'company_email', 'office@firma.at', 'string'),
(4, 'company_phone', '+43 1 23456789', 'string'),
(5, 'tax_number', 'AT12345678', 'string'),
(6, 'vat_number', 'ATU12345678', 'string'),
(7, 'default_language', 'de', 'string'),
(8, 'currency', 'EUR', 'string'),
(9, 'backup_frequency', 'weekly', 'string'),
(10, 'backup_retention', '30', 'integer'),
(11, 'theme', 'tremor', 'string');

SET FOREIGN_KEY_CHECKS = 1;