<?php
namespace App\Models;
use DateTime;
use CodeIgniter\Model;
use Exception;
use mysqli;
include 'DatabaseConnect.php';

Class LopModel
{
    public $id_lop_hoc;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $id_mon_hoc;

    private $conn;
    function __construct(){}


    function getLopById($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id_lop_hoc = $row["id_lop_hoc"];
            $this->ngay_bat_dau = $row["ngay_bat_dau"];
            $this->ngay_ket_thuc = $row["ngay_ket_thuc"];
            $this->id_mon_hoc = $row["id_mon_hoc"];
            $this->conn->close();
            return $this;
        }
        $this->conn->close();
        return null;
    }
    public static function compareCoursesByBeginDate($a, $b) {
        $datetime_a = DateTime::createFromFormat('d/m/Y', $a->ngay_bat_dau);
        $datetime_b = DateTime::createFromFormat('d/m/Y', $b->ngay_bat_dau);
    
        if ($datetime_a > $datetime_b) {
            return 1;
        } else if ($datetime_a < $datetime_b) {
            return -1;
        } else {
            return 0;
        }
    }
    
    function getAllLops()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc";
        $result = $this->conn->query($sql);
        $lops = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lop = new LopModel();
                $lop->id_lop_hoc = $row["id_lop_hoc"];
                $lop->ngay_bat_dau = $row["ngay_bat_dau"];
                $lop->ngay_ket_thuc = $row["ngay_ket_thuc"];
                $lop->id_mon_hoc = $row["id_mon_hoc"];
                $lops[] = $lop;
            }
        }
        usort($lops, [$this, 'compareCoursesByBeginDate']);
        $this->conn->close();
        return $lops;
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
    function executeCustomDDL($sql) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }        
    }

    function insertLop($lop)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        
        if ($this->conn->connect_error) {
            return ['state' => false, 'message' => 'Kết nối đến cơ sở dữ liệu thất bại: ' . $this->conn->connect_error];
        }
    
        if (isset($lop->ngay_bat_dau) && isset($lop->ngay_ket_thuc) && isset($lop->id_mon_hoc)) {
            $ngay_bat_dau = $this->conn->real_escape_string($lop->ngay_bat_dau);
            $ngay_ket_thuc = $this->conn->real_escape_string($lop->ngay_ket_thuc);
            $id_mon_hoc = $this->conn->real_escape_string($lop->id_mon_hoc);
    
            $sql = "INSERT INTO lop_hoc (ngay_bat_dau, ngay_ket_thuc, id_mon_hoc) VALUES (?, ?, ?)";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $ngay_bat_dau, $ngay_ket_thuc, $id_mon_hoc);
    
            if ($stmt->execute()) {
                $insertedId = $this->conn->insert_id;
                $stmt->close();
                $this->conn->close();
                return ['state' => true, 'message' => 'Insert thành công', 'auto_increment_id' => $insertedId];
            } else {
                $error_message = $this->conn->error;
                $stmt->close();
                $this->conn->close();
                return ['state' => false, 'message' => $error_message];
            }
        } else {
            return ['state' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
        }
    }
    function insertLop2($id_lop_hoc, $ngay_bat_dau, $ngay_ket_thuc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        
        if ($this->conn->connect_error) {
            return ['state' => false, 'message' => 'Kết nối đến cơ sở dữ liệu thất bại: ' . $this->conn->connect_error];
        }
    
        if (isset($ngay_bat_dau) && isset($ngay_ket_thuc) && isset($id_mon_hoc)) {
            $ngay_bat_dau = $this->conn->real_escape_string($ngay_bat_dau);
            $ngay_ket_thuc = $this->conn->real_escape_string($ngay_ket_thuc);
            $id_mon_hoc = $this->conn->real_escape_string($id_mon_hoc);
    
            $sql = "INSERT INTO lop_hoc (ngay_bat_dau, ngay_ket_thuc, id_mon_hoc) VALUES (?, ?, ?)";
    
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $ngay_bat_dau, $ngay_ket_thuc, $id_mon_hoc);
    
            if ($stmt->execute()) {
                $insertedId = $this->conn->insert_id;
                $stmt->close();
                $this->conn->close();
                return ['state' => true, 'message' => 'Insert thành công', 'auto_increment_id' => $insertedId];
            } else {
                $error_message = $this->conn->error;
                $stmt->close();
                $this->conn->close();
                return ['state' => false, 'message' => $error_message];
            }
        } else {
            return ['state' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
        }
    }

    function deleteLop($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_lop_hoc = $this->conn->real_escape_string($id_lop_hoc);
        $sql = "DELETE FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";
        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function updateLop($lop)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_lop_hoc = $this->conn->real_escape_string($lop->id_lop_hoc);
        $ngay_bat_dau = $this->conn->real_escape_string($lop->ngay_bat_dau);
        $ngay_ket_thuc = $this->conn->real_escape_string($lop->ngay_ket_thuc);
        $id_mon_hoc = $this->conn->real_escape_string($lop->id_mon_hoc);

        $sql = "UPDATE lop_hoc SET ngay_bat_dau = '$ngay_bat_dau', ngay_ket_thuc = '$ngay_ket_thuc', id_mon_hoc = '$id_mon_hoc' WHERE id_lop_hoc = $id_lop_hoc";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
