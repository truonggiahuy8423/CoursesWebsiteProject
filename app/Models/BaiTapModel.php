<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';
use Exception;

class BaiTapModel
{
    public $id_bai_tap;
    public $thoi_han_nop;
    public $ten;
    public $noi_dung;
    public $thoi_han;
    public $id_giang_vien;
    public $id_muc;
    public $ngay_dang;
    private $conn;
    function __construct(){}


    function getBaiTapById($id)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM bai_tap WHERE id_bai_tap = ?";
         
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("s", $id);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        // $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id_bai_tap = $row["id_bai_tap"];
            $this->thoi_han_nop = $row["thoi_han_nop"];
            $this->ten = $row["ten"];
            $this->noi_dung = $row["noi_dung"];
            $this->thoi_han = $row["thoi_han"];
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_muc = $row["id_muc"];
            $this->conn->close();
            return $this;
        }
        $this->conn->close();
        return null;
    }

    function getAllBaiTaps()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien";
        $result = $this->conn->query($sql);
        $bai_taps = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bai_tap = new BaiTapModel();
                $this->id_bai_tap = $row["id_bai_tap"];
                $this->thoi_han_nop = $row["thoi_han_nop"];
                $this->ten = $row["ten"];
                $this->noi_dung = $row["noi_dung"];
                $this->thoi_han = $row["thoi_han "];
                $this->id_giang_vien = $row["id_giang_vien"];
                $this->id_muc = $row["id_muc"];
                $this->conn->close();                
                $bai_taps[] = $bai_tap;
            }
        }
        $this->conn->close();
        return $bai_taps;
    }

    function getBaiTapByIdLopHoc($id_lop_hoc) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT bai_tap.*, giang_vien.ho_ten FROM bai_tap inner join giang_vien on bai_tap.id_giang_vien = giang_vien.id_giang_vien inner join muc on muc.id_muc = bai_tap.id_muc where muc.id_lop_hoc = ? order by bai_tap.ngay_dang ASC";
        // $result = $this->conn->query($sql);
        $bai_taps = [];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_lop_hoc);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bai_tap = array();
                $bai_tap["id_bai_tap"] = $row["id_bai_tap"];
                $bai_tap["thoi_han_nop"] = $row["thoi_han_nop"];
                $bai_tap["ten"] = $row["ten"];
                $bai_tap["noi_dung"] = $row["noi_dung"];
                $bai_tap["thoi_han"] = $row["thoi_han"];
                $bai_tap["id_giang_vien"] = $row["id_giang_vien"];
                $bai_tap["ho_ten"] = $row["ho_ten"];
                $bai_tap["id_muc"] = $row["id_muc"];
                $bai_tap["ngay_dang"] = $row["ngay_dang"];
                // $this->conn->close();                
                $bai_taps[] = $bai_tap;
            }
            $stmt->close();
            $this->conn->close();
            return $bai_taps;
        }
        $stmt->close();
        $this->conn->close();
        return [];

    }
    function executeCustomQuery($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $result = $this->conn->query($sql);
        $rows = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }

    function insertBaiTap($bai_tap)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $ten = $this->conn->real_escape_string($bai_tap->ten);
        $noi_dung = $this->conn->real_escape_string($bai_tap->noi_dung);
        $thoi_han = $this->conn->real_escape_string($bai_tap->thoi_han);
        $id_giang_vien = $this->conn->real_escape_string($bai_tap->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($bai_tap->id_muc);
        $thoi_han_nop = $this->conn->real_escape_string($bai_tap->thoi_han_nop);
        $ngay_dang = $this->conn->real_escape_string($bai_tap->ngay_dang);

        $sql = "INSERT INTO bai_tap (thoi_han_nop, ten, noi_dung, thoi_han, id_giang_vien, id_muc, ngay_dang) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiis", $thoi_han_nop, $ten, $noi_dung, $thoi_han, $id_giang_vien, $id_muc, $ngay_dang);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Bài tập đã được thêm thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    public function insertThongBao($tb)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO thong_bao (noi_dung , ngay_dang, id_giang_vien, id_muc, tieu_de) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiis", $tb->noi_dung, $tb->ngay_dang, $tb->id_giang_vien, $tb->id_muc, $tb->tieu_de);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Thông báo được thêm thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }
    function deleteBaiTap($bai_tap)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_tap = $this->conn->real_escape_string($bai_tap->id_bai_tap);
        $id_muc =  $this->conn->real_escape_string($bai_tap->id_muc);
        $sql = "DELETE FROM hoc_vien WHERE id_bai_tap = ? and id_muc = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_thong_bao, $id_muc);

        try {
            $stmt->execute();
            $deletedRows = $stmt->affected_rows;

            $stmt->close();
            $this->conn->close();

            if ($deletedRows > 0) {
                return ['state' => true, 'message' => "Đã xóa bài tập thành công"];
            } else {
                return ['state' => false, 'message' => 'Không có bài tập nào bị xóa'];
            }
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }
    
    function updateBaiTap($bai_tap)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_tap = $this->conn->real_escape_string($bai_tap->id_bai_tap);
        $thoi_han_nop = $this->conn->real_escape_string($bai_tap->thoi_han_nop);
        $ten = $this->conn->real_escape_string($bai_tap->ten);
        $noi_dung = $this->conn->real_escape_string($bai_tap->noi_dung);
        $thoi_han = $this->conn->real_escape_string($bai_tap->thoi_han);

        $sql = "UPDATE bai_tap SET thoi_han_nop = '$thoi_han_nop', ten = '$ten', noi_dung = '$noi_dung', thoi_han = '$thoi_han' WHERE id_bai_tap = $id_bai_tap";

        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
