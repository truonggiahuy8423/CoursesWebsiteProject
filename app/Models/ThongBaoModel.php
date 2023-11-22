<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class ThongBaoModel
{
    public $id_thong_bao;
    public $noi_dung;
    public $ngay_dang;
    public $id_giang_vien;
    public $id_muc;

    private $conn;
    function __construct(){}


    function getThongBaoById($id_thong_bao)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM thong_bao WHERE id_thong_bao = $id_thong_bao";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $thong_bao = new ThongBaoModel();
            $this->id_thong_bao = $row["id_thong_bao"];
            $this->noi_dung = $row["noi_dung "];
            $this->ngay_dang = $row["ngay_dang"];
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_muc = $row["id_muc"];
            $this->conn->close();
            return $thong_bao;
        }
        $this->conn->close();
        return null;
    }

    function getAllThongBaos()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM thong_bao";
        $result = $this->conn->query($sql);
        $thong_baos = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $thong_bao = new ThongBaoModel();
                $this->id_thong_bao = $row["id_thong_bao"];
                $this->noi_dung = $row["noi_dung "];
                $this->ngay_dang = $row["ngay_dang"];
                $this->id_giang_vien = $row["id_giang_vien"];
                $this->id_muc = $row["id_muc"];
                $thong_baos[] = $thong_bao;
            }
        }
        $this->conn->close();
        return $thong_baos;
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

    function insertThongBao($thong_bao)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noi_dung = $this->conn->real_escape_string($thong_bao->noi_dung);
        $ngay_dang = $this->conn->real_escape_string($thong_bao->ngay_dang);
        $id_giang_vien = $this->conn->real_escape_string($thong_bao->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($thong_bao->id_muc);

        $sql = "INSERT INTO thong_bao (noi_dung , ngay_dang, id_giang_vien, id_muc) VALUES ('$noi_dung', '$ngay_dang', '$id_giang_vien', '$id_muc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteThongBao($thong_bao)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_thong_bao = $this->conn->real_escape_string($thong_bao->id_thong_bao);
        $sql = "DELETE FROM thong_bao WHERE id_thong_bao = $id_thong_bao";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateThongBao($thong_bao)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_thong_bao = $this->conn->real_escape_string($thong_bao->id_thong_bao);
        $noi_dung = $this->conn->real_escape_string($thong_bao->noi_dung);
        $ngay_dang = $this->conn->real_escape_string($thong_bao->ngay_dang);
        $id_giang_vien = $this->conn->real_escape_string($thong_bao->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($thong_bao->id_muc);

        $sql = "UPDATE thong_bao SET noi_dung  = '$noi_dung', ngay_dang = '$ngay_dang', id_giang_vien = '$id_giang_vien', id_muc = '$id_muc' WHERE id_thong_bao = $id_thong_bao";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
