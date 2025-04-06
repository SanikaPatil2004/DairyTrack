create database milkdairy;
use milkdairy;
create table farmer(id int auto_increment primary key,username varchar(30),email varchar(50),pass varchar(20),confpass varchar(20),mobno varchar(10));
ALTER TABLE farmer
ADD COLUMN profile_photo VARCHAR(255) AFTER mobno,
ADD COLUMN address TEXT AFTER profile_photo;

drop table farmer;
desc farmer;
select * from farmer;
CREATE TABLE milk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_fat DECIMAL(3, 1) NOT NULL,
    max_fat DECIMAL(3, 1) NOT NULL,
    rate DECIMAL(10, 2) NOT NULL
);
drop table milk_rates;
CREATE TABLE IF NOT EXISTS milk_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fat_percentage DECIMAL(3,1) NOT NULL,
    snf_percentage DECIMAL(3,1) NOT NULL,
    rate DECIMAL(6,2) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (fat_percentage, snf_percentage)
);
INSERT INTO milk_rates (fat_percentage, snf_percentage, rate) VALUES
(4.0, 8.0, 0.0),
(4.0, 9.0, 0.0),
(4.0, 10.0, 0.0),
(5.0, 8.0, 0.0),
(5.0, 9.0, 0.0),
(5.0, 10.0, 0.0),
(6.0, 8.0, 0.0),
(6.0, 9.0, 0.0),
(6.0, 10.0, 0.0),
(7.0, 8.0, 0.0),
(7.0, 9.0, 0.0),
(7.0, 10.0, 0.0),
(8.0, 8.0, 0.0),
(8.0, 9.0, 0.0),
(8.0, 10.0, 0.0),
(9.0, 8.0, 0.0),
(9.0, 9.0, 0.0),
(9.0, 10.0, 0.0),
(10.0, 8.0, 0.0),
(10.0, 9.0, 0.0),
(10.0, 10.0, 0.0)
ON DUPLICATE KEY UPDATE rate=VALUES(rate);
select * from milk_rates;

CREATE TABLE milkproduction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    milk DECIMAL(10, 2) NOT NULL,       -- Milk quantity in liters
    fat DECIMAL(5, 2) NOT NULL,         -- Fat percentage
    snf DECIMAL(5, 2) NOT NULL,         -- SNF percentage
    production_date DATE NOT NULL,      -- Date of production entry
    rate decimal(5,2) NOT NULL,
    total_rs DECIMAL(10, 2) NOT NULL,   -- Total Rs calculated based on milk and fat rate
    FOREIGN KEY (farmer_id) REFERENCES farmer(id) ON DELETE CASCADE
);

select * from milkproduction;
select * from staff;

CREATE TABLE loan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    reason TEXT,
    amount DECIMAL(10,2),
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmer(id) ON DELETE CASCADE
);
select * from loan;
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    disease VARCHAR(255) NOT NULL,
    symptoms TEXT NOT NULL,
    appointment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmer(id) ON DELETE CASCADE
);
select * from appointments;

CREATE TABLE buffalo_species (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    species VARCHAR(50),
    count INT,
    FOREIGN KEY (farmer_id) REFERENCES farmer(id)
);

select * from buffalo_species;
CREATE TABLE cow_species (
    id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT,
    species VARCHAR(50),
    count INT,
    FOREIGN KEY (farmer_id) REFERENCES farmer(id)
);
select * from cow_species;
