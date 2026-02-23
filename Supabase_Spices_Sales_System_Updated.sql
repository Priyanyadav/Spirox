
-- ============================================
-- Spices / Food Processing Sales System
-- Supabase PostgreSQL Ready SQL Script (UPDATED)
-- ============================================

-- 1. USERS TABLE
CREATE TABLE users (
  user_id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  mobile VARCHAR(15) UNIQUE,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) CHECK (role IN ('Admin','Manager','Salesman')) NOT NULL,
  assigned_area VARCHAR(100),
  joining_date DATE,
  status VARCHAR(20) CHECK (status IN ('Active','Inactive')) DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. STORES TABLE
CREATE TABLE stores (
  store_id SERIAL PRIMARY KEY,
  store_name VARCHAR(150) NOT NULL,
  mobile VARCHAR(15),
  address TEXT,
  city VARCHAR(100),
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  created_by INT REFERENCES users(user_id) ON DELETE SET NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. CATEGORIES TABLE (Spices Categories)
CREATE TABLE categories (
  category_id SERIAL PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL,
  status VARCHAR(20) DEFAULT 'Active'
);

-- 4. PRODUCTS TABLE (Gram Variation Added)
CREATE TABLE products (
  product_id SERIAL PRIMARY KEY,
  product_name VARCHAR(150) NOT NULL,
  category_id INT REFERENCES categories(category_id) ON DELETE SET NULL,
  variation_gram VARCHAR(10) CHECK (variation_gram IN ('100g','200g','500g','1kg')) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  gst DECIMAL(5,2) DEFAULT 0,
  status VARCHAR(20) CHECK (status IN ('Available','Out of Stock')) DEFAULT 'Available',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. INVENTORY TABLE
CREATE TABLE inventory (
  inventory_id SERIAL PRIMARY KEY,
  product_id INT REFERENCES products(product_id) ON DELETE CASCADE,
  batch_no VARCHAR(50),
  quantity INT NOT NULL,
  manufacture_date DATE,
  expiry_date DATE,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. SALES ORDERS TABLE
CREATE TABLE sales_orders (
  order_id SERIAL PRIMARY KEY,
  store_id INT REFERENCES stores(store_id) ON DELETE CASCADE,
  sales_exec_id INT REFERENCES users(user_id) ON DELETE SET NULL,
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10,2),
  payment_status VARCHAR(20) CHECK (payment_status IN ('Paid','Pending')) DEFAULT 'Pending'
);

-- 7. SALES ORDER ITEMS TABLE
CREATE TABLE sales_order_items (
  order_item_id SERIAL PRIMARY KEY,
  order_id INT REFERENCES sales_orders(order_id) ON DELETE CASCADE,
  product_id INT REFERENCES products(product_id) ON DELETE SET NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2),
  gst DECIMAL(5,2),
  total DECIMAL(10,2)
);

-- 8. SALES HISTORY TABLE
CREATE TABLE sales_history (
  sales_history_id SERIAL PRIMARY KEY,
  sales_exec_id INT REFERENCES users(user_id) ON DELETE SET NULL,
  order_id INT REFERENCES sales_orders(order_id) ON DELETE CASCADE,
  sale_amount DECIMAL(10,2),
  sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 9. LIVE LOCATION TRACKING TABLE
CREATE TABLE live_locations (
  location_id SERIAL PRIMARY KEY,
  sales_exec_id INT REFERENCES users(user_id) ON DELETE CASCADE,
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  location_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. BILLING TABLE
CREATE TABLE billing (
  bill_id SERIAL PRIMARY KEY,
  order_id INT REFERENCES sales_orders(order_id) ON DELETE CASCADE,
  pdf_url VARCHAR(255),
  shared_via VARCHAR(20) CHECK (shared_via IN ('WhatsApp','Email')),
  shared_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- END OF UPDATED SCRIPT
-- ============================================
