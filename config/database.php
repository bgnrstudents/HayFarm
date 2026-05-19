<?php
// Configurasi Database OOP
    class Database
    {
        private string $host = "localhost";
        private string $user = "root";
        private string $pass = "";
        private string $db = "hayfarm";

        public mysqli $conn;

        public function __construct()
        {
            mysqli_report(MYSQLI_REPORT_OFF);
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

            if ($this->conn->connect_errno) {
                die('Koneksi database gagal: ' . $this->conn->connect_error);
            }

            $this->conn->set_charset('utf8mb4');
        }

        public function getConnection(): mysqli
        {
            return $this->conn;
        }
    }

if (!isset($db) || !($db instanceof mysqli)) {
    $database = new Database();
    $db = $database->getConnection();
}
?>

