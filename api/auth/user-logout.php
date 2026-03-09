<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 * Generated: 2026-03-09
 * 
 */

session_start();
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
