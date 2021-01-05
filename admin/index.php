<?php
session_start();

require_once('../config.php');

if (!isset($_SESSION['data']['connected'])) {
    header('Location: ../admin/login');
} else {
    header('Location : ../admin/add');
}