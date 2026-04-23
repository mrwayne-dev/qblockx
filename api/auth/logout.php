<?php
/**
 * Project: qblockx
 * Created by: Wayne
 */

session_start();
$role = $_SESSION['role'] ?? 'user';
session_destroy();

if ($role === 'admin') {
    header('Location: /admin/login');
} else {
    header('Location: /login?loggedout=1');
}
exit;
