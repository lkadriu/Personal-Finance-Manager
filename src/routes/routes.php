<?php
require_once __DIR__ . '/../controllers/UsersController.php';
require_once __DIR__ . '/../controllers/ExpensesController.php';
require_once __DIR__ . '/../controllers/IncomesController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$usersController = new UsersController();
$expensesController = new ExpensesController();
$incomesController = new IncomesController();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Kërkesa për regjistrim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents("php://input"), true);
    $response = $usersController->register($data);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kërkesa për login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents("php://input"), true);
    $response = $usersController->login($data);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kërkesa për të shtuar shpenzime
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_expense') {
    $data = json_decode(file_get_contents("php://input"), true);
    $response = $expensesController->addExpense($data);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kërkesa për të shtuar të ardhura
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_income') {
    $data = json_decode(file_get_contents("php://input"), true);
    $response = $incomesController->addIncome($data);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kërkesa për të marrë përdoruesin
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_user') {
    $userId = $_GET['id'];
    $response = $usersController->getUserById($userId);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kontrollo për kërkesat GET të expenses
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_expenses') {
    $userId = $_GET['id'];
    $response = $expensesController->getExpensesByUserId($userId);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Kontrollo për kërkesat GET të incomes
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_incomes') {
    $userId = $_GET['id'];
    $response = $incomesController->getIncomesByUserId($userId);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Delete an expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_expense') {
    $expenseId = $_GET['id'];
    $response = $expensesController->deleteExpense($expenseId);
    echo json_encode($response);
    exit;
}

// Delete an income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_income') {
    $incomeId = $_GET['id'];
    $response = $incomesController->deleteIncome($incomeId);
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update_expense') {
    $input = json_decode(file_get_contents('php://input'), true);
    $expenseId = $input['id']; // Sigurohuni që id të jetë e pranishme
    $response = $expensesController->updateExpense($expenseId, $input);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update_income') {
    $input = json_decode(file_get_contents('php://input'), true);
    $incomeId = $input['id']; 
    $response = $incomesController->updateIncome($incomeId, $input);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
