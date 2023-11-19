<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class PhongModel extends Model
{
    public $id_phong;

    private $conn;

    public function __construct()
    {

    }

    public function getPhongById($phongId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM phong WHERE id_phong = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $phongId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $phong = $result->fetch_assoc();

            $this->id_phong = $phong['id_phong'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllPhong()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM phong";
        $result = $this->conn->query($sql);

        $phongs = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $phong = new PhongModel();
                $phong->id_phong = $row['id_phong'];

                $phongs[] = $phong;
            }
        }
        $this->conn->close();
        return $phongs;
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

    public function insertPhong($phong)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO phong () VALUES ()";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $phong->id_phong);

        if ($stmt->execute()) {
            //$this->id_phong = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function updatePhong($phong)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE phong SET id_phong = ? WHERE id_phong = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $phong->id_phong);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
        //return ['state' => false, 'message' => 'Update không được hỗ trợ cho PhongModel'];
    }

    public function deletePhong($phongId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM phong WHERE id_phong = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $phong->id_phong);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
        //return ['state' => false, 'message' => 'Delete không được hỗ trợ cho PhongModel'];
    }

    public function __destruct()
    {

    }
}

?>
