<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class MonHocModel extends Model
{
    public $id_mon_hoc;
    public $ten_mon_hoc;

    private $conn;

    public function __construct()
    {
        
    }

    public function getMonHocById($monHocId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM mon_hoc WHERE id_mon_hoc = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $monHocId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $monHoc = $result->fetch_assoc();

            $this->id_mon_hoc = $monHoc['id_mon_hoc'];
            $this->ten_mon_hoc = $monHoc['ten_mon_hoc'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllMonHoc()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM mon_hoc";
        $result = $this->conn->query($sql);

        $monHocs = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $monHoc = new MonHocModel();
                $monHoc->id_mon_hoc = $row['id_mon_hoc'];
                $monHoc->ten_mon_hoc = $row['ten_mon_hoc'];
                $monHocs[] = $monHoc;
            }
        }
        $this->conn->close();
        return $monHocs;
    }

    public function executeCustomQuery($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $result = $this->conn->query($sql);

        $rows = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }

    public function insertMonHoc($monHoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO mon_hoc (ten_mon_hoc) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $monHoc->ten_mon_hoc);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function updateMonHoc($monHoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE mon_hoc SET ten_mon_hoc = ? WHERE id_mon_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $monHoc->ten_mon_hoc, $monHoc->id_mon_hoc);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function deleteMonHoc($monHocId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM mon_hoc WHERE id_mon_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $monHocId);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function __destruct()
    {
    }
}

?>
