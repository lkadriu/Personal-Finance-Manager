<?php
// controllers/IncomesController.php
require_once __DIR__ . '/../config/database.php';

class IncomesController {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->getConnection();
    }

    public function addIncome($data) {
        // Kontrollo nëse të dhënat janë të plota
        if (empty($data['user_id']) || empty($data['amount']) || empty($data['source']) || empty($data['date'])) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Përgatitja e SQL për të shtuar të ardhurat
        $query = "INSERT INTO incomes (user_id, amount, source, date, description) VALUES (:user_id, :amount, :source, :date, :description)";
        $stmt = $this->db->conn->prepare($query);
        
        // Bindi parametrat
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':source', $data['source']);
        $stmt->bindParam(':date', $data['date']);
        $stmt->bindParam(':description', $data['description']);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Income added successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Income addition failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Method to fetch incomes by user ID
    public function getIncomesByUserId($userId) {
        // Përgatitja e SQL për të marrë të ardhurat e përdoruesit
        $query = "SELECT * FROM incomes WHERE user_id = :user_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'incomes' => $incomes];
        } else {
            return ['status' => 'error', 'message' => 'Error fetching incomes: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Method to delete an income by its ID
    public function deleteIncome($incomeId) {
        // Përgatitja e SQL për të fshirë të ardhurat
        $query = "DELETE FROM incomes WHERE id = :income_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':income_id', $incomeId);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Income deleted successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Income deletion failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }


    // Method to edit an income
    public function updateIncome($incomeId, $data) {
        // Kontrollo nëse të dhënat janë të plota
        if (empty($data['amount']) || empty($data['source']) || empty($data['date'])) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Përgatitja e SQL për të përditësuar të ardhurat
        $query = "UPDATE incomes SET amount = :amount, source = :source, date = :date, description = :description WHERE id = :income_id";
        $stmt = $this->db->conn->prepare($query);

        // Bindi parametrat
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':source', $data['source']);
        $stmt->bindParam(':date', $data['date']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':income_id', $incomeId);

        // Ekzekuto kërkesën
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Income updated successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Income update failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }
}
?>
