<?php
session_start();
require_once __DIR__ . '/../../src/Core/Session.php';

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$csrfToken = $_POST['csrf_token'] ?? '';

if (!$session->validateCsrfToken($csrfToken)) {
    $session->set('error', 'Invalid security token.');
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $db = (new Database())->getConnection();
        $countryModel = new Country($db);
        
        if ($countryModel->delete($id)) {
            $session->set('success', 'Country deleted successfully!');
        } else {
            $session->set('error', 'Failed to delete country.');
        }
    } catch (Exception $e) {
        error_log("Error deleting country: " . $e->getMessage());
        $session->set('error', 'An error occurred while deleting.');
    }
}

header('Location: index.php');
exit;