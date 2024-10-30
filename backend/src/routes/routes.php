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

// Function to handle JSON response
function respond($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Register user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents("php://input"), true);
    respond($usersController->register($data));
}

// Login user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents("php://input"), true);
    respond($usersController->login($data));
}

// Add expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_expense') {
    $data = json_decode(file_get_contents("php://input"), true);
    respond($expensesController->addExpense($data));
}

// Add income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_income') {
    $data = json_decode(file_get_contents("php://input"), true);
    respond($incomesController->addIncome($data));
}

// Get user by ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_user') {
    $userId = $_GET['id'];
    respond($usersController->getUserById($userId));
}

// Get expenses by user ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_expenses') {
    $userId = $_GET['id'];
    respond($expensesController->getExpensesByUserId($userId));
}

// Get incomes by user ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_incomes') {
    $userId = $_GET['id'];
    respond($incomesController->getIncomesByUserId($userId));
}

// Delete an expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_expense') {
    $expenseId = $_GET['id'];
    respond($expensesController->deleteExpense($expenseId));
}

// Delete an income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_income') {
    $incomeId = $_GET['id'];
    respond($incomesController->deleteIncome($incomeId));
}

// Update expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update_expense') {
    $input = json_decode(file_get_contents('php://input'), true);
    $expenseId = $input['id'];
    respond($expensesController->updateExpense($expenseId, $input));
}

// Update income
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update_income') {
    $input = json_decode(file_get_contents('php://input'), true);
    $incomeId = $input['id'];
    respond($incomesController->updateIncome($incomeId, $input));
}
?>
