CREATE DATABASE IF NOT EXISTS sales_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    USE sales_db;

    CREATE TABLE customers (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE,
        phone VARCHAR(20) ,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    );

    CREATE TABLE products (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL (10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    );

    CREATE TABLE invoices (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        invoice_number VARCHAR(30) NULL  UNIQUE,
        customer_id INT UNSIGNED NOT NULL,
        invoice_date DATE NOT NULL,
        total DECIMAL (10,2) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (customer_id) REFERENCES customers(id),
        INDEX (invoice_date)
    );


    CREATE TABLE invoice_items (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        invoice_id INT UNSIGNED NOT NULL,
        product_id INT UNSIGNED NOT NULL,
        quantity INT UNSIGNED NOT NULL,
        unit_price DECIMAL(10, 2) NOT NULL,
        line_total DECIMAL(10, 2) NOT NULL,

        FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id)
    );