<?php
require_once __DIR__ . '/../config.php';
$pdo = db();

// create sample admin and teacher
$adminPass = password_hash('admin123', PASSWORD_DEFAULT);
$teacherPass = password_hash('guru123', PASSWORD_DEFAULT);

$pdo->beginTransaction();
$pdo->exec("INSERT IGNORE INTO users (name,nip,username,password,role) VALUES ('Admin','', 'admin', '$adminPass', 'admin')");
$pdo->exec("INSERT IGNORE INTO users (name,nip,username,password,role) VALUES ('Guru Contoh','12345', 'guru1', '$teacherPass', 'teacher')");

// classes and subjects
$pdo->exec("INSERT IGNORE INTO classes (id,name) VALUES (1,'X-IPA')");
$pdo->exec("INSERT IGNORE INTO subjects (id,name) VALUES (1,'Matematika')");

// academic year
$today = date('Y-m-d');
$end = date('Y-m-d', strtotime('+1 year'));
$pdo->exec("INSERT IGNORE INTO academic_years (id,name,start_date,end_date,is_active) VALUES (1,'2025/2026','$today','$end',1)");

$pdo->commit();

echo "Seed complete\n";
