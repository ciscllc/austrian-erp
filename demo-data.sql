-- Demo Kunden
INSERT INTO customers (customer_number, company_name, first_name, last_name, email) VALUES
('DEMO-001', 'Muster GmbH', 'Max', 'Muster', 'max@muster.at'),
('DEMO-002', 'Test AG', 'Hans', 'Müller', 'hans@test.at');

-- Demo Produkte
INSERT INTO products (name, sku, base_price, unit_id, tax_rate) VALUES
('Zementsäuberer', 'Z-001', 45.50, 1, 20),
('Milchkontainer Reiniger', 'M-002', 78.90, 1, 20);
