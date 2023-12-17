<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
use Exception;
include 'DatabaseConnect.php';
class BaiNopModel {

    public $id_bai_nop;
    public $thoi_gian_nop;
    public $id_bai_tap;
    public $id_hoc_vien;

    private $conn;

    public function __construct() {
        
    }

    public function getBaiNopById($baiNopId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM bai_nop WHERE id_bai_nop = ?";
        
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("i", $baiNopId);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $baiNop = $result->fetch_assoc();
    
            $this->id_bai_nop = $baiNop['id_bai_nop'];
            $this->thoi_gian_nop = $baiNop['thoi_gian_nop'];
            $this->id_bai_tap = $baiNop['id_bai_tap'];
            $this->id_hoc_vien = $baiNop['id_hoc_vien'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    
       
    }

    public function getAllBaiNop() {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM bai_nop";
        $result = $this->conn->query($sql);

        $baiNops = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $baiNop = new BaiNopModel();
                $baiNop->id_bai_nop = $row['id_bai_nop'];
                $baiNop->thoi_gian_nop = $row['thoi_gian_nop'];
                $baiNop->id_bai_tap = $row['id_bai_tap'];
                $baiNop->id_hoc_vien = $row['id_hoc_vien'];

                $baiNops[] = $baiNop;
            }
        }
        $this->conn->close();
        return $baiNops;
    }

    public function executeCustomQuery($sql) {
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

    public function insertBaiNop($baiNop) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO bai_nop (thoi_gian_nop, id_bai_tap, id_hoc_vien) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $baiNop->thoi_gian_nop, $baiNop->id_bai_tap, $baiNop->id_hoc_vien);

        try {
            $stmt->execute();
            $id = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Bài nộp đã được thêm thành công', 'id_bai_nop' => $id];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateBaiNop($baiNop) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE bai_nop SET thoi_gian_nop = ?, id_bai_tap = ?, id_hoc_vien = ? WHERE id_bai_nop = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $baiNop->thoi_gian_nop, $baiNop->id_bai_tap, $baiNop->id_hoc_vien, $baiNop->id_bai_nop);

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

    public function deleteBaiNop($baiNopId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM bai_nop WHERE id_bai_nop = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $baiNopId);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Bài nộp đã được xóa thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    public function __destruct() {
    }
}

?>