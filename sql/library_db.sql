
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

DROP TABLE IF EXISTS fees;
DROP TABLE IF EXISTS return_books;
DROP TABLE IF EXISTS issued_books;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
 category_id INT AUTO_INCREMENT PRIMARY KEY,
 category_name VARCHAR(100) NOT NULL,
 description VARCHAR(255)
);

INSERT INTO categories (category_name, description) VALUES
('IT','Information Technology Books'),
('Fiction','Fiction Stories'),
('Science','Science Books'),
('History','History Books'),
('Mathematics','Maths Books');

CREATE TABLE members (
 member_id INT AUTO_INCREMENT PRIMARY KEY,
 member_code VARCHAR(20) UNIQUE,
 full_name VARCHAR(100) NOT NULL,
 nic VARCHAR(20),
 phone VARCHAR(20),
 email VARCHAR(100),
 address VARCHAR(255),
 status VARCHAR(20) DEFAULT 'Active',
 blocked_reason VARCHAR(255) DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO members (member_code, full_name, nic, phone, email, address, status) VALUES
('M001','Saman Perera','200012345678','0771234567','saman@test.com','Colombo','Active'),
('M002','Nimali Fernando','200112345679','0771234568','nimali@test.com','Gampaha','Active'),
('M003','Kamal Silva','200212345680','0771234569','kamal@test.com','Kandy','Blocked'),
('M004','Anura Kumara','200312345681','0771234570','anura@test.com','Galle','Active');

CREATE TABLE books (
 book_id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 author VARCHAR(100),
 isbn VARCHAR(30) UNIQUE,
 category_id INT,
 total_qty INT DEFAULT 1,
 available_qty INT DEFAULT 1,
 shelf_location VARCHAR(20),
 FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

INSERT INTO books (title, author, isbn, category_id, total_qty, available_qty, shelf_location) VALUES
('Introduction to PHP','Devin S','ISBN001',1,5,3,'A1'),
('Learning JavaScript','John Doe','ISBN002',1,4,2,'A2'),
('Harry Potter','J.K. Rowling','ISBN003',2,6,4,'B1'),
('A Brief History of Time','Stephen Hawking','ISBN004',3,3,1,'C1'),
('World History','Saman Silva','ISBN005',4,2,2,'D1');

CREATE TABLE issued_books (
 issue_id INT AUTO_INCREMENT PRIMARY KEY,
 book_id INT,
 member_id INT,
 issue_date DATE,
 due_date DATE,
 return_date DATE DEFAULT NULL,
 status VARCHAR(20) DEFAULT 'Issued',
 FOREIGN KEY (book_id) REFERENCES books(book_id),
 FOREIGN KEY (member_id) REFERENCES members(member_id)
);

INSERT INTO issued_books (book_id, member_id, issue_date, due_date, status) VALUES
(1,1,'2026-10-20','2026-11-03','Issued'),
(2,2,'2026-10-25','2026-11-08','Issued'),
(1,2,'2026-10-10','2026-10-24','Overdue');

CREATE TABLE return_books (
 return_id INT AUTO_INCREMENT PRIMARY KEY,
 issue_id INT,
 book_id INT,
 member_id INT,
 return_date DATE,
 overdue_days INT DEFAULT 0,
 fee_charged INT DEFAULT 0,
 FOREIGN KEY (issue_id) REFERENCES issued_books(issue_id),
 FOREIGN KEY (book_id) REFERENCES books(book_id),
 FOREIGN KEY (member_id) REFERENCES members(member_id)
);

CREATE TABLE fees (
 fee_id INT AUTO_INCREMENT PRIMARY KEY,
 issue_id INT,
 member_id INT,
 overdue_days INT DEFAULT 0,
 total_fee INT DEFAULT 0,
 status VARCHAR(20) DEFAULT 'Unpaid',
 cleared_date DATE DEFAULT NULL,
 FOREIGN KEY (issue_id) REFERENCES issued_books(issue_id),
 FOREIGN KEY (member_id) REFERENCES members(member_id)
);

INSERT INTO fees (issue_id, member_id, overdue_days, total_fee, status) VALUES
(3,2,8,80,'Unpaid'),
(3,2,35,350,'Unpaid');
