<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class BuoiHocModel {

    public $id_buoi_hoc;
    public $trang_thai;
    public $ngay;
    public $ma_lop_hoc;
    public $so_phong;
    public $ma_ca;
    public $id_lop_hoc;
    public $id_ca;
    public $id_phong;

    private $conn;

    public function __construct() {

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
            $this->ma_lop_hoc = $buoiHoc['ma_lop_hoc'];
            $this->so_phong = $buoiHoc['so_phong'];
            $this->ma_ca = $buoiHoc['ma_ca'];
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
                $buoiHoc->ma_lop_hoc = $row['ma_lop_hoc'];
                $buoiHoc->so_phong = $row['so_phong'];
                $buoiHoc->ma_ca = $row['ma_ca'];
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
        $sql = "INSERT INTO buoi_hoc (trang_thai, ngay, ma_lop_hoc, so_phong, ma_ca, id_lop_hoc, id_ca, id_phong) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiiiiii", $buoiHoc->trang_thai, $buoiHoc->ngay, $buoiHoc->ma_lop_hoc, $buoiHoc->so_phong, $buoiHoc->ma_ca, $buoiHoc->id_lop_hoc, $buoiHoc->id_ca, $buoiHoc->id_phong);

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
        $sql = "UPDATE buoi_hoc SET trang_thai = ?, ngay = ?, ma_lop_hoc = ?, so_phong = ?, ma_ca = ?, id_lop_hoc = ?, id_ca = ?, id_phong = ? WHERE id_buoi_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiiiiiii", $buoiHoc->trang_thai, $buoiHoc->ngay, $buoiHoc->ma_lop_hoc, $buoiHoc->so_phong, $buoiHoc->ma_ca, $buoiHoc->id_lop_hoc, $buoiHoc->id_ca, $buoiHoc->id_phong, $buoiHoc->id_buoi_hoc);

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

    public function deleteBuoiHoc($buoiHocId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM buoi_hoc WHERE id_buoi_hoc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $buoiHocId);

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

    public function __destruct() {
    }
}

?>
