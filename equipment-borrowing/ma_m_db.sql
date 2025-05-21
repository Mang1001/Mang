-- SQL สำหรับฐานข้อมูลระบบยืม/คืน

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100)
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100)
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    quantity INT
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    item_id INT,
    action ENUM('borrow','return'),
    date DATE,
    confirmed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- แอดมิน 3 คน
INSERT INTO admins (name, email, password) VALUES
('Admin1', 'Admin1@gmail.com', 'Admin00001'),
('Admin2', 'Admin2@gmail.com', 'Admin00002'),
('Admin3', 'Admin3@gmail.com', 'Admin00003');

-- ครุภัณฑ์เริ่มต้น
INSERT INTO items (name, quantity) VALUES
('คอมพิวเตอร์', 10),
('โน๊ตบุ๊ค', 6),
('ไอแพด', 15);
