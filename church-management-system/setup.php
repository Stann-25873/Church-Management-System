<?php
/**
 * Setup script to initialize SQLite database
 */

$dbPath = __DIR__ . '/church_management.db';

try {
    // Create SQLite database connection
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "✓ Database connection established\n";

    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'member' CHECK (role IN ('admin', 'pastor', 'member')),
            photo TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created\n";

    // Create events table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            event_date DATE NOT NULL,
            created_by INTEGER REFERENCES users(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Events table created\n";

    // Create offerings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS offerings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            member_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            amount REAL NOT NULL CHECK (amount > 0),
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Offerings table created\n";

    // Create sermons table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS sermons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pastor_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            title TEXT NOT NULL,
            message TEXT NOT NULL,
            date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Sermons table created\n";

    // Check if admin user exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'admin@church.com'");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insert sample users
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        
        $pdo->exec("
            INSERT INTO users (first_name, last_name, email, password, role) VALUES
            ('Admin', 'User', 'admin@church.com', '$password', 'admin'),
            ('Pastor', 'John', 'pastor@church.com', '$password', 'pastor'),
            ('John', 'Doe', 'john@example.com', '$password', 'member'),
            ('Jane', 'Smith', 'jane@example.com', '$password', 'member')
        ");
        echo "✓ Sample users inserted\n";

        // Insert sample events
        $pdo->exec("
            INSERT INTO events (title, description, event_date, created_by) VALUES
            ('Sunday Service', 'Weekly worship service', date('Y-m-d', strtotime('+7 days')), 1),
            ('Bible Study', 'Weekly Bible study session', date('Y-m-d', strtotime('+3 days')), 2)
        ");
        echo "✓ Sample events inserted\n";

        // Insert sample offerings
        $pdo->exec("
            INSERT INTO offerings (member_id, amount) VALUES
            (3, 100.00),
            (4, 50.00)
        ");
        echo "✓ Sample offerings inserted\n";

        // Insert sample sermon
        $pdo->exec("
            INSERT INTO sermons (pastor_id, title, message) VALUES
            (2, 'Faith in Action', 'Today we explore how faith manifests through our actions...')
        ");
        echo "✓ Sample sermon inserted\n";
    }

    echo "\n✓ DATABASE SETUP COMPLETE!\n";
    echo "\nYou can now login with:\n";
    echo "  Email: admin@church.com\n";
    echo "  Password: admin123\n";
    echo "\nOR\n";
    echo "  Email: pastor@church.com\n";
    echo "  Password: admin123\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
