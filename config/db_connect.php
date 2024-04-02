<?php



class db_connect
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "1234567890-=";
    private $db_connect = "library_management";
    private $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->servername . ";dbname=" . $this->db_connect . "", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Không thể kết nối với CSDL" . $e->getMessage();
        }
        return $this->conn;
    }
}
