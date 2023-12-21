<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';
use Exception;
class ThongBaoModel
{
    public $id_thong_bao;
    public $noi_dung;
    public $ngay_dang;
    public $id_giang_vien;
    public $id_muc;
    public $tieu_de;
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
            $this->id_thong_bao = $row["id_thong_bao"];
            $this->noi_dung = $row["noi_dung "];
            $this->ngay_dang = $row["ngay_dang"];
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_muc = $row["id_muc"];
            $this->conn->close();
            return $this;
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
                $thong_bao->id_thong_bao = $row["id_thong_bao"];
                $thong_bao->noi_dung = $row["noi_dung"];
                $thong_bao->ngay_dang = $row["ngay_dang"];
                $thong_bao->id_giang_vien = $row["id_giang_vien"];
                $thong_bao->id_muc = $row["id_muc"];
                $thong_baos[] = $thong_bao;
            }
        }
        $this->conn->close();
        return $thong_baos;
    }
    function getThongBaoByCourseId($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT thong_bao.*, giang_vien.ho_ten FROM thong_bao inner join muc on thong_bao.id_muc = muc.id_muc inner join giang_vien on giang_vien.id_giang_vien = thong_bao.id_giang_vien where muc.id_lop_hoc = ? order by thong_bao.ngay_dang ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_lop_hoc);

        $thong_baos = [];
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $thong_bao = array();
                $thong_bao["id_thong_bao"] = $row["id_thong_bao"];
                $thong_bao["noi_dung"] = $row["noi_dung"];
                $thong_bao["ngay_dang"] = $row["ngay_dang"];
                $thong_bao["id_giang_vien"] = $row["id_giang_vien"];
                $thong_bao["id_muc"] = $row["id_muc"];
                $thong_bao["tieu_de"] = $row["tieu_de"];
                $thong_bao["ho_ten"] = $row["ho_ten"];
                $thong_baos[] = $thong_bao;
            }
            $stmt->close();
            $this->conn->close();
            return $thong_baos;
        } else {
            $stmt->close();
            $this->conn->close();
            return [];
        }
        
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

    function deleteThongBao($tb)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_thong_bao = $this->conn->real_escape_string($tb->id_thong_bao);
        $id_muc = $this->conn->real_escape_string($tb->id_muc);

        $sql = "DELETE FROM thong_bao WHERE id_thong_bao = ? AND id_muc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_thong_bao, $id_muc);

        try {
            $stmt->execute();
            $deletedRows = $stmt->affected_rows;

            $stmt->close();
            $this->conn->close();

            if ($deletedRows > 0) {
                return ['state' => true, 'message' => "Đã xóa thông báo thành công"];
            } else {
                return ['state' => false, 'message' => 'Không có thông báo nào bị xóa'];
            }
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
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
