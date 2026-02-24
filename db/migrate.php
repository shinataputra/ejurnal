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

    // Extend users role enum with guru_bk
    $pdo->exec("ALTER TABLE users MODIFY role ENUM('teacher','guru_bk','admin') NOT NULL DEFAULT 'teacher'");
    echo "✓ Users role enum updated\n";

    // Add Guru BK journal fields if not exists
    $columns = $pdo->query("SHOW COLUMNS FROM journals")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('target_kegiatan', $columns, true)) {
        $pdo->exec('ALTER TABLE journals ADD COLUMN target_kegiatan TEXT NULL AFTER notes');
    }
    if (!in_array('kegiatan_layanan', $columns, true)) {
        $pdo->exec('ALTER TABLE journals ADD COLUMN kegiatan_layanan VARCHAR(120) NULL AFTER target_kegiatan');
    }
    if (!in_array('hasil_dicapai', $columns, true)) {
        $pdo->exec('ALTER TABLE journals ADD COLUMN hasil_dicapai TEXT NULL AFTER kegiatan_layanan');
    }
    echo "✓ Journal BK columns created/verified\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Migration complete!\n";
