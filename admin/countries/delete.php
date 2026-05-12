<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $db = (new Database())->getConnection();
    $countryModel = new Country($db);
    $countryModel->delete($id);
}

header('Location: index.php');
exit;