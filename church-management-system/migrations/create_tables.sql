-- Church Management System Database Schema
-- PostgreSQL

-- Create users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'member' CHECK (role IN ('admin', 'pastor', 'member')),
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT NOW()
);

-- Create events table
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    created_by INT REFERENCES users(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Create offerings table
CREATE TABLE offerings (
    id SERIAL PRIMARY KEY,
    member_id INT REFERENCES users(id) ON DELETE CASCADE,
    amount NUMERIC(10,2) NOT NULL CHECK (amount > 0),
    date TIMESTAMP DEFAULT NOW()
);

-- Create sermons table
CREATE TABLE sermons (
    id SERIAL PRIMARY KEY,
    pastor_id INT REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    date TIMESTAMP DEFAULT NOW()
);

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_events_date ON events(event_date);
CREATE INDEX idx_events_created_by ON events(created_by);
CREATE INDEX idx_offerings_member_id ON offerings(member_id);
CREATE INDEX idx_offerings_date ON offerings(date);
CREATE INDEX idx_sermons_pastor_id ON sermons(pastor_id);
CREATE INDEX idx_sermons_date ON sermons(date);

-- Insert sample admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Admin', 'User', 'admin@church.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample pastor
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Pastor', 'John', 'pastor@church.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pastor');

-- Insert sample members
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('John', 'Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member'),
('Jane', 'Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member');

-- Insert sample events
INSERT INTO events (title, description, event_date, created_by) VALUES
('Sunday Service', 'Weekly worship service', CURRENT_DATE + INTERVAL '7 days', 1),
('Bible Study', 'Weekly Bible study session', CURRENT_DATE + INTERVAL '3 days', 2);

-- Insert sample offerings
INSERT INTO offerings (member_id, amount) VALUES
(3, 100.00),
(4, 50.00);

-- Insert sample sermon
INSERT INTO sermons (pastor_id, title, message) VALUES
(2, 'Faith in Action', 'Today we explore how faith manifests through our actions...');
