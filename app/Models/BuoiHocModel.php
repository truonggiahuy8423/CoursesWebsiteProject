<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use mysqli;
include 'DatabaseConnect.php';

class BuoiHocModel {

    public $id_buoi_hoc;
    public $trang_thai;
    public $ngay;
    public $id_lop_hoc;
    public $id_ca;
    public $id_phong;
    public $thu;
    
    public $thoi_gian_bat_dau;
    public $thoi_gian_ket_thuc;
    private $conn;

    public function __construct() {

    }


    public function getAllBuoiHocByLopHocId($id_lop_hoc) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
    
        // Sử dụng prepared statement để tránh SQL injection
        $sql = "SELECT bh.*, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc, DAYOFWEEK(bh.ngay) AS thu 
                FROM buoi_hoc bh
                INNER JOIN ca ON bh.id_ca = ca.id_ca
                WHERE bh.id_lop_hoc = ?";
        $stmt = $this->conn->prepare($sql);
    
        // Kiểm tra lỗi trong quá trình chuẩn bị prepared statement
        if ($stmt === false) {
            die("Lỗi trong quá trình chuẩn bị câu truy vấn");
        }
    
        // Bind tham số vào câu truy vấn
        $stmt->bind_param("i", $id_lop_hoc);
    
        // Thực hiện truy vấn
        $stmt->execute();
    
        // Lấy kết quả
        $result = $stmt->get_result();
    
        $buoiHocs = array();
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $buoiHoc = new BuoiHocModel();
                $buoiHoc->id_buoi_hoc = $row['id_buoi_hoc'];
                $buoiHoc->trang_thai = $row['trang_thai'];
                $buoiHoc->ngay = $row['ngay'];
                $buoiHoc->id_lop_hoc = $row['id_lop_hoc'];
                $buoiHoc->id_ca = $row['id_ca'];
                $buoiHoc->id_phong = $row['id_phong'];
                $buoiHoc->thoi_gian_bat_dau = $row['thoi_gian_bat_dau'];
                $buoiHoc->thoi_gian_ket_thuc = $row['thoi_gian_ket_thuc'];
                $buoiHoc->thu = $row['thu'];
    
                $buoiHocs[] = $buoiHoc;
            }
        }
    
        // Đóng kết nối và trả về kết quả
        $stmt->close();
        $this->conn->close();
        return $buoiHocs;
    }

    public function getBuoiHocById($buoiHocId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM buoi_hoc WHERE id_buoi_hoc = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $buoiHocId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $buoiHoc = $result->fetch_assoc();

            $this->id_buoi_hoc = $buoiHoc['id_buoi_hoc'];
            $this->trang_thai = $buoiHoc['trang_thai'];
            $this->ngay = $buoiHoc['ngay'];
            $this->id_lop_hoc = $buoiHoc['id_lop_hoc'];
            $this->id_ca = $buoiHoc['id_ca'];
            $this->id_phong = $buoiHoc['id_phong'];
            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllBuoiHoc() {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM buoi_hoc";
        $result = $this->conn->query($sql);

        $buoiHocs = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $buoiHoc = new BuoiHocModel();
                $buoiHoc->id_buoi_hoc = $row['id_buoi_hoc'];
                $buoiHoc->trang_thai = $row['trang_thai'];
                $buoiHoc->ngay = $row['ngay'];
                $buoiHoc->id_lop_hoc = $row['id_lop_hoc'];
                $buoiHoc->id_ca = $row['id_ca'];
                $buoiHoc->id_phong = $row['id_phong'];

                $buoiHocs[] = $buoiHoc;
            }
        }
        $this->conn->close();
        return $buoiHocs;
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

    public function insertBuoiHoc($buoiHoc) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO buoi_hoc (trang_thai, ngay, id_lop_hoc, id_ca, id_phong) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiii", $buoiHoc->trang_thai, $buoiHoc->ngay, $buoiHoc->id_lop_hoc, $buoiHoc->id_ca, $buoiHoc->id_phong);

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

    public function updateBuoiHoc($buoiHoc) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE buoi_hoc SET trang_thai = ?, ngay = ?, id_lop_hoc = ?, id_ca = ?, id_phong = ? WHERE id_buoi_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiiii", $buoiHoc->trang_thai, $buoiHoc->ngay, $buoiHoc->id_lop_hoc, $buoiHoc->id_ca, $buoiHoc->id_phong, $buoiHoc->id_buoi_hoc);

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
    function executeCustomDDL($sql) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }        
    }
    public function updateIdLopHoc($id_lop_hoc, $id_buoi_hoc) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $stmt = $this->conn->prepare("UPDATE buoi_hoc SET id_lop_hoc = ? WHERE id_buoi_hoc = ?");
        $stmt->bind_param('ii', $id_lop_hoc, $id_buoi_hoc);
        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }  

    }
    public function deleteBuoiHoc($buoiHocId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM buoi_hoc WHERE id_buoi_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $buoiHocId);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }  
    }

    public function __destruct() {
    }
}

?>
