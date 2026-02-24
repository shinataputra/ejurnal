<?php
require_once __DIR__ . '/../config.php';
$pdo = db();

// create sample admin and teacher
$adminPass = password_hash('admin123', PASSWORD_DEFAULT);
$teacherPass = password_hash('guru123', PASSWORD_DEFAULT);

$pdo->beginTransaction();
$pdo->exec("INSERT IGNORE INTO users (name,nip,username,password,role) VALUES ('Admin','', 'admin', '$adminPass', 'admin')");
$pdo->exec("INSERT IGNORE INTO users (name,nip,username,password,role) VALUES ('Guru Contoh','12345', 'guru1', '$teacherPass', 'teacher')");
$pdo->exec("INSERT IGNORE INTO users (name,nip,username,password,role) VALUES ('Guru BK Contoh','54321', 'gurubk1', '$teacherPass', 'guru_bk')");

// classes and subjects
$pdo->exec("INSERT IGNORE INTO classes (id,name) VALUES (1,'X RPL1'), (2,'XI RPL1'), (3,'XII RPL1')");
$pdo->exec("INSERT IGNORE INTO subjects (id,name) VALUES (1,'Matematika'), (2,'Bahasa Indonesia'), (3,'Bahasa Inggris'), (4,'Bimbingan Konseling')");

// academic year
$today = date('Y-m-d');
$end = date('Y-m-d', strtotime('+1 year'));
$pdo->exec("INSERT IGNORE INTO academic_years (id,name,start_date,end_date,is_active) VALUES (1,'2025/2026','$today','$end',1)");

$pdo->commit();

echo "Seed complete\n";
