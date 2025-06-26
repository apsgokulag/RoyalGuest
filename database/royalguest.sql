-- Create database
CREATE DATABASE IF NOT EXISTS royalguest;
USE royalguest;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Guests table
CREATE TABLE guests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    room_number VARCHAR(10),
    check_in_date DATE,
    check_out_date DATE,
    status ENUM('checked_in', 'checked_out', 'reserved') DEFAULT 'reserved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Service requests table
CREATE TABLE service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_id INT,
    service_type VARCHAR(100) NOT NULL,
    description TEXT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_to INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@royalguest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample data
INSERT INTO guests (first_name, last_name, email, phone, room_number, check_in_date, check_out_date, status) VALUES
('John', 'Doe', 'john.doe@email.com', '+1234567890', '101', '2024-06-20', '2024-06-25', 'checked_in'),
('Jane', 'Smith', 'jane.smith@email.com', '+1234567891', '102', '2024-06-22', '2024-06-28', 'checked_in'),
('Bob', 'Johnson', 'bob.johnson@email.com', '+1234567892', '103', '2024-06-25', '2024-06-30', 'reserved');

INSERT INTO service_requests (guest_id, service_type, description, priority, status) VALUES
(1, 'Room Cleaning', 'Please clean room 101', 'medium', 'pending'),
(1, 'Maintenance', 'AC not working properly', 'high', 'in_progress'),
(2, 'Room Service', 'Extra towels needed', 'low', 'completed');