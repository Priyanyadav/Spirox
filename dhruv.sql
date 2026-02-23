

-- 1. Admin(User Table) --

CREATE TABLE admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    mobile VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Manager', 'Salesman') NOT NULL,
    assigned_area VARCHAR(255) NULL,
    joining_date DATE NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- 2. Store Table --

CREATE TABLE stores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_fk BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    mobile VARCHAR(255) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL UNIQUE,
    city VARCHAR(255) NOT NULL,
    latitude VARCHAR(255) NOT NULL,
    longitude VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_stores_sales
    FOREIGN KEY (sales_fk)
    REFERENCES sales_executives(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 3. CATEGORIES TABLE (Spices Categories)

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- 4. PRODUCTS TABLE (Gram Variation Added)

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_fk BIGINT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    weight VARCHAR(255) NOT NULL,
    price VARCHAR(255) NOT NULL,
    variation_gram ENUM('100g', '200g', '500g', '1kg') NOT NULL,
    gst VARCHAR(255) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_products_category
    FOREIGN KEY (category_fk)
    REFERENCES categories(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 5. INVENTORY TABLE

CREATE TABLE inventories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_fk BIGINT UNSIGNED NOT NULL,
    batch_no VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    manufacture_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_inventories_product
    FOREIGN KEY (product_fk)
    REFERENCES products(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 6. SALES ORDERS TABLE

CREATE TABLE sales_orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_executive_fk BIGINT UNSIGNED NOT NULL,
    store_fk BIGINT UNSIGNED NOT NULL,
    order_date VARCHAR(255) NOT NULL,
    total_amount VARCHAR(255) NOT NULL,
    payment_status TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_sales_orders_sales_exec
    FOREIGN KEY (sales_executive_fk)
    REFERENCES sales_executives(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

    CONSTRAINT fk_sales_orders_store
    FOREIGN KEY (store_fk)
    REFERENCES stores(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 7. SALES ORDER ITEMS TABLE

CREATE TABLE sales_order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_order_fk BIGINT UNSIGNED NOT NULL,
    product_fk BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    gst DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_sales_order_items_order
    FOREIGN KEY (sales_order_fk)
    REFERENCES sales_orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

    CONSTRAINT fk_sales_order_items_product
    FOREIGN KEY (product_fk)
    REFERENCES products(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 8. SALES HISTORY TABLE

CREATE TABLE sales_executive_sales_histories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_exec_id BIGINT UNSIGNED NOT NULL,
    sales_order_fk BIGINT UNSIGNED NOT NULL,
    sale_amount DECIMAL(10,2) NOT NULL,
    sale_date DATE NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_sales_exec_history_exec
    FOREIGN KEY (sales_exec_id)
    REFERENCES sales_executives(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

    CONSTRAINT fk_sales_exec_history_order
    FOREIGN KEY (sales_order_fk)
    REFERENCES sales_orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 9. LIVE LOCATION TRACKING TABLE

CREATE TABLE live_location_trackings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_exec_id BIGINT UNSIGNED NOT NULL,
    latitude DECIMAL(10,7) NOT NULL,
    longitude DECIMAL(10,7) NOT NULL,
    location_time DATETIME NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_live_location_sales_exec
    FOREIGN KEY (sales_exec_id)
    REFERENCES sales_executives(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 10. BILLING TABLE

CREATE TABLE billing_p_d_f_s (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sales_order_fk BIGINT UNSIGNED NOT NULL,
    pdf_url VARCHAR(255) NOT NULL,
    shared_via TINYINT(1) NOT NULL DEFAULT 0, -- 0 = Whatsapp, 1 = Email
    shared_at DATETIME NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_billing_pdf_order
    FOREIGN KEY (sales_order_fk)
    REFERENCES sales_orders(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);


