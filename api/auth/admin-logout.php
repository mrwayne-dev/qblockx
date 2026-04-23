<?php
/**
 * Project: qblockx
 * Created by: Wayne
 */

session_start();
session_destroy();
header('Location: /admin/login');
exit;
