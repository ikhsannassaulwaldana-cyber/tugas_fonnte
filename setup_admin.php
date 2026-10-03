<?php
require_once 'config/config.php';
$name='Administrator'; $email='admin@gmail.com'; $password='admin123';
$check=$conn->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]);
if($check->fetch()){ exit('Admin sudah ada. Email: admin@gmail.com Password: admin123. Hapus file setup_admin.php setelah selesai.'); }
$stmt=$conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'admin')");
$stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
echo 'Admin berhasil dibuat. Email: admin@gmail.com Password: admin123. Hapus file setup_admin.php setelah selesai.';
