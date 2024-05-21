<?php
//UserModel.php
require_once '../config/DatabaseConnection.php';

class UserModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function saveUser($user)
    {
        $connection = $this->db->getConnection();

        $query = "INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $name = $user->getName();
        $surname = $user->getSurname();
        $email = $user->getEmail();
        $hashedPassword = $user->getHashedPassword();
        $hashedPin = $user->getHashedPin();
        $stmt->bind_param("sssss", $name, $surname, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();
    }
    public function userExists($email)
    {
        $connection = $this->db->getConnection();

        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    public function verifyPassword($email, $password)
    {
        $connection = $this->db->getConnection();

        $hashed_password = '';

        $query = "SELECT password_hash FROM users WHERE email = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        return password_verify($password, $hashed_password);
    }
    public function updateUser($first_name, $last_name, $email, $password)
    {
        $connection = $this->db->getConnection();

        $query = "UPDATE users SET first_name=?, last_name=?, password=? WHERE email=?";
        $stmt = $connection->prepare($query);

        if (!$stmt) {
            die("Błąd przygotowania zapytania: " . $connection->error);
        }


        $stmt->bind_param("ssss", $first_name, $last_name, $password, $email);

        $success = $stmt->execute();

        if ($success) {
            echo "Dane użytkownika zostały zaktualizowane pomyślnie.";
        } else {
            die("Błąd wykonania zapytania: " . $stmt->error);
        }
        $stmt->close();
    }
}
