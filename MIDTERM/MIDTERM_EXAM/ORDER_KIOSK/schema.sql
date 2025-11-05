CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  password VARCHAR(255),
  role ENUM('superadmin', 'admin'),
  status ENUM('active', 'suspended') DEFAULT 'active',
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  price DECIMAL(10,2),
  image_url VARCHAR(255),
  added_by INT,
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (added_by) REFERENCES users(id)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  quantity INT,
  total_price DECIMAL(10,2),
  ordered_by INT,
  date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (ordered_by) REFERENCES users(id)
);