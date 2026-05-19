<?php
class Auth
{
    private $conn;
    private $lastError;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->lastError = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password)
    {
        $this->lastError = '';
        $email = trim($email);
        $password = trim($password);

        // Validasi input
        if (empty($email) || empty($password)) {
            $this->lastError = 'Email dan password harus diisi';
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->lastError = 'Format email tidak valid';
            return false;
        }

        $stmt = $this->conn->prepare("SELECT id_user, username, email, password, role FROM user WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['login'] = true;
                return true;
            } else {
                $this->lastError = 'Password yang Anda masukkan salah';
                return false;
            }
        } else {
            $this->lastError = 'Email tidak ditemukan';
            return false;
        }
    }

    public function register($username, $email, $password, $role = 'pembeli')
    {
        $this->lastError = '';
        $username = preg_replace('/\s+/', ' ', trim((string) $username)) ?? '';
        $email = trim($email);
        $password = trim($password);

        // Validasi input
        if (empty($username) || empty($email) || empty($password)) {
            $this->lastError = 'Semua field harus diisi';
            return false;
        }

        // Validasi username
        if (strlen($username) < 3) {
            $this->lastError = 'Username minimal 3 karakter';
            return false;
        }
        if (strlen($username) > 50) {
            $this->lastError = 'Username maksimal 50 karakter';
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9_ ]+$/', $username)) {
            $this->lastError = 'Username hanya boleh huruf, angka, spasi, dan underscore';
            return false;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->lastError = 'Format email tidak valid';
            return false;
        }

        // Validasi password
        if (strlen($password) < 6) {
            $this->lastError = 'Password minimal 6 karakter';
            return false;
        }
        if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
            $this->lastError = 'Password harus mengandung huruf besar, kecil, dan angka';
            return false;
        }

        // Cek email sudah ada
        $stmt = $this->conn->prepare("SELECT id_user FROM user WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $this->lastError = 'Email sudah terdaftar';
            return false;
        }

        // Cek username sudah ada
        $stmt = $this->conn->prepare("SELECT id_user FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $this->lastError = 'Username sudah terdaftar';
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $stmt = $this->conn->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            return true;
        } else {
            $this->lastError = 'Gagal menyimpan data user';
            return false;
        }
    }

    public function getLastError()
    {
        return $this->lastError;
    }
};
