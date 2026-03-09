<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

require_once '../../config/database.php';
require_once '../../api/utilities/auth-check.php';
header('Content-Type: application/json');

requireAdmin();

// TODO: Query your transactions table
// Example:
// $db   = Database::getInstance()->getConnection();
// $stmt = $db->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 50");
// echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);

echo json_encode(['success' => true, 'data' => []]);
