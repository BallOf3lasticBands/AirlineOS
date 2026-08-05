<?php
// App configuration and shared bootstrap. This file is intended to be included
// by every page before any HTML output.
require_once __DIR__ . '/../database/database_conn.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Application defaults
define('APP_NAME', 'AirlineOS');
define('APP_BASE_URL', '/');
