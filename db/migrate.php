<?php
// db/migrate.php - Run database migrations

require_once __DIR__ . '/../config.php';
$pdo = db();

try {
    // Create settings table
    $pdo->exec('CREATE TABLE IF NOT EXISTS settings (
      id INT AUTO_INCREMENT PRIMARY KEY,
      `key` VARCHAR(255) UNIQUE NOT NULL,
      value LONGTEXT,
      updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )');

    echo "✓ Settings table created/verified\n";

    // Create tasks table
    $pdo->exec('CREATE TABLE IF NOT EXISTS tasks (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      jenjang VARCHAR(50) NOT NULL,
      class_id INT NOT NULL,
      date DATE NOT NULL,
      jam_ke VARCHAR(30),
      file_path VARCHAR(255),
      status ENUM("pending", "verified", "rejected") DEFAULT "pending",
      admin_notes TEXT,
      verified_at TIMESTAMP NULL,
      verified_by INT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
      FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
      INDEX idx_tasks_user (user_id),
      INDEX idx_tasks_status (status),
      INDEX idx_tasks_date (date),
      INDEX idx_tasks_teacher_date (user_id, date)
    )');

    echo "✓ Tasks table created/verified\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Migration complete!\n";
