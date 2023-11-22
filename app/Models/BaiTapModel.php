<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class BaiTapModel
{
    public $id_bai_tap;
    public $trang_thai;
    public $ten;
    public $noi_dung;
    public $thoi_han;
    public $id_giang_vien;
    public $id_muc;

    private $conn;
    function __construct(){}


    function getBaiTapById($id)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_bai_tap = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $bai_tap = new BaiTapModel();
            $this->id_bai_tap = $row["id_bai_tap"];
            $this->trang_thai = $row["trang_thai"];
            $this->ten = $row["ten"];
            $this->noi_dung = $row["noi_dung"];
            $this->thoi_han = $row["thoi_han "];
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_muc = $row["id_muc"];
            $this->conn->close();
            return $bai_tap;
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
                $this->trang_thai = $row["trang_thai"];
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

        $trang_thai = $this->conn->real_escape_string($bai_tap->trang_thai);
        $ten = $this->conn->real_escape_string($bai_tap->ten);
        $noi_dung = $this->conn->real_escape_string($bai_tap->noi_dung);
        $thoi_han = $this->conn->real_escape_string($bai_tap->thoi_han);
        $id_giang_vien = $this->conn->real_escape_string($bai_tap->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($bai_tap->id_muc);
        $sql = "INSERT INTO hoc_vien (trang_thai, ten, noi_dung, thoi_han, id_giang_vien, id_muc) VALUES ('$trang_thai', '$ten', '$noi_dung', '$thoi_han', '$id_giang_vien', '$id_muc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteBaiTap($bai_tap)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_tap = $this->conn->real_escape_string($bai_tap->id_bai_tap);
        $sql = "DELETE FROM hoc_vien WHERE id_bai_tap = $id_bai_tap";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateBaiTap($bai_tap)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_tap = $this->conn->real_escape_string($bai_tap->id_bai_tap);
        $trang_thai = $this->conn->real_escape_string($bai_tap->trang_thai);
        $ten = $this->conn->real_escape_string($bai_tap->ten);
        $noi_dung = $this->conn->real_escape_string($bai_tap->noi_dung);
        $thoi_han = $this->conn->real_escape_string($bai_tap->thoi_han);
        $id_giang_vien = $this->conn->real_escape_string($bai_tap->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($bai_tap->id_muc);

        $sql = "UPDATE hoc_vien SET trang_thai = '$trang_thai', ten = '$ten', noi_dung = '$noi_dung', thoi_han = '$thoi_han', id_giang_vien  = '$id_giang_vien', id_muc = '$id_muc' WHERE id_bai_tap = $id_bai_tap";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
