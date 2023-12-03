<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class CaModel extends Model
{
    public $id_ca;
    public $thoi_gian_bat_dau;
    public $thoi_gian_ket_thuc;

    private $conn;

    public function __construct()
    {

    }

    public function getCaById($caId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM ca WHERE id_ca = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $caId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $ca = $result->fetch_assoc();

            $this->id_ca = $ca['id_ca'];
            $this->thoi_gian_bat_dau = $ca['thoi_gian_bat_dau'];
            $this->thoi_gian_ket_thuc = $ca['thoi_gian_ket_thuc'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllCa()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM ca";
        $result = $this->conn->query($sql);

        $cas = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ca = new CaModel();
                $ca->id_ca = $row['id_ca'];
                $ca->thoi_gian_bat_dau = $row['thoi_gian_bat_dau'];
                $ca->thoi_gian_ket_thuc = $row['thoi_gian_ket_thuc'];
                $cas[] = $ca;
            }
        }
        $this->conn->close();
        return $cas;
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

    public function insertCa($ca)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO ca (thoi_gian_bat_dau, thoi_gian_ket_thuc) VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $ca->thoi_gian_bat_dau, $ca->thoi_gian_ket_thuc);

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

    public function updateCa($ca)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE ca SET thoi_gian_bat_dau = ?, thoi_gian_ket_thuc = ? WHERE id_ca = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $ca->thoi_gian_bat_dau, $ca->thoi_gian_ket_thuc, $ca->id_ca);

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
    
    public function deleteCa($caId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM ca WHERE id_ca = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $caId);

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
