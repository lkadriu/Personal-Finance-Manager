<?php
// controllers/UsersController.php
require_once __DIR__ . '/../config/database.php';

class UsersController {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db->getConnection();
    }

    public function register($data) {
        // Kontrollo nëse të dhënat janë të plota
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Kontrollo nëse email-i ekziston
        $queryCheck = "SELECT id FROM users WHERE email = :email";
        $stmtCheck = $this->db->conn->prepare($queryCheck);
        $stmtCheck->bindParam(':email', $data['email']);
        $stmtCheck->execute();
        if ($stmtCheck->rowCount() > 0) {
            return ['status' => 'error', 'message' => 'Email already exists.'];
        }

        $query = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password_hash', password_hash($data['password'], PASSWORD_BCRYPT));

        if ($stmt->execute()) {
            // Merr ID-në e sapo regjistruar
            $userId = $this->db->conn->lastInsertId();
            return ['status' => 'success', 'message' => 'User registered successfully.', 'userId' => $userId];
        } else {
            return ['status' => 'error', 'message' => 'User registration failed: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    public function login($data) {
        // Kontrollo nëse email dhe password janë të dhëna
        if (empty($data['email']) || empty($data['password'])) {
            return ['status' => 'error', 'message' => 'Email and password are required.'];
        }

        // Gjej përdoruesin në bazë të email-it
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':email', $data['email']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifiko nëse përdoruesi ekziston dhe nëse password-i përputhet
        if ($user && password_verify($data['password'], $user['password_hash'])) {
            // Kthe ID-në dhe emrin e përdoruesit si përgjigje të suksesshme
            return [
                'status' => 'success',
                'message' => 'Login successful.',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name']
                ]
            ];
        } else {
            return ['status' => 'error', 'message' => 'Invalid email or password.'];
        }
    }

    // Add a method to fetch user information by ID
    public function getUserById($userId) {
        $query = "SELECT id, name, email FROM users WHERE id = :id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return ['status' => 'success', 'user' => $user];
            } else {
                return ['status' => 'error', 'message' => 'User not found.'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Error fetching user: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Add a method to fetch incomes by user ID
    public function getIncomesByUserId($userId) {
        $query = "SELECT * FROM incomes WHERE user_id = :user_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'incomes' => $incomes];
        } else {
            return ['status' => 'error', 'message' => 'Error fetching incomes: ' . implode(", ", $stmt->errorInfo())];
        }
    }

    // Add a method to fetch expenses by user ID
    public function getExpensesByUserId($userId) {
        $query = "SELECT * FROM expenses WHERE user_id = :user_id";
        $stmt = $this->db->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);

        if ($stmt->execute()) {
            $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'expenses' => $expenses];
        } else {
            return ['status' => 'error', 'message' => 'Error fetching expenses: ' . implode(", ", $stmt->errorInfo())];
        }
    }
}
?>
