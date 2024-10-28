<?php
// controllers/ExpensesController.php
require_once __DIR__ . '/../config/database.php';

class ExpensesController {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->getConnection();
    }

    public function addExpense($data) {
        // Kontrollo nëse të dhënat janë të plota
        if (empty($data['user_id']) || empty($data['amount']) || empty($data['category']) || empty($data['date'])) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Përgatitja e SQL për të shtuar shpenzimin
        $query = "INSERT INTO expenses (user_id, amount, category, date, description) VALUES (:user_id, :amount, :category, :date, :description)";
        $stmt = $this->db->conn->prepare($query);
        
        // Bindi parametrat
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':date', $data['date']);
        $stmt->bindParam(':description', $data['description']);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Expense added successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Expense addition failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Method to fetch expenses by user ID
    public function getExpensesByUserId($userId) {
        // Përgatitja e SQL për të marrë shpenzimet e përdoruesit
        $query = "SELECT * FROM expenses WHERE user_id = :user_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'expenses' => $expenses];
        } else {
            return ['status' => 'error', 'message' => 'Error fetching expenses: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Method to delete an expense by its ID
    public function deleteExpense($expenseId) {
        // Përgatitja e SQL për të fshirë shpenzimin
        $query = "DELETE FROM expenses WHERE id = :expense_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':expense_id', $expenseId);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Expense deleted successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Expense deletion failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }


    public function updateExpense($expenseId, $data) {
    // Kontrollo nëse të dhënat janë të plota
    if (empty($data['amount']) || empty($data['category']) || empty($data['date'])) {
        return ['status' => 'error', 'message' => 'All fields are required.'];
    }

    // Përgatitja e SQL për të përditësuar shpenzimin
    $query = "UPDATE expenses SET amount = :amount, category = :category, date = :date, description = :description WHERE id = :expense_id";
    $stmt = $this->db->conn->prepare($query);

    // Bindi parametrat
    $stmt->bindParam(':amount', $data['amount']);
    $stmt->bindParam(':category', $data['category']);
    $stmt->bindParam(':date', $data['date']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':expense_id', $expenseId);

    // Ekzekuto kërkesën
    if ($stmt->execute()) {
        return ['status' => 'success', 'message' => 'Expense updated successfully.'];
    } else {
        return ['status' => 'error', 'message' => 'Expense update failed: ' . implode(", ", $stmt->errorInfo())];
    }
}

}
?>
