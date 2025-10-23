CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    password VARCHAR(255) NOT NULL,  -- Hashed password
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);