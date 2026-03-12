<?php
/**
 * Project: arqoracapital
 * Created by: Wayne
 */

session_start();
session_destroy();
header('Location: /admin/login');
exit;
