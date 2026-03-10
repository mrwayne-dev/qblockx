<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 */

session_start();
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
