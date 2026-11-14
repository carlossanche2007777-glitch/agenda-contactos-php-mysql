<?php
require_once __DIR__ . '/includes/bootstrap.php';
redirect(logged_in() ? 'dashboard.php' : 'login.php');
