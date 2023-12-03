<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
use Exception;

include 'DatabaseConnect.php';

class GiangVienModel
{
    public $id_giang_vien;
    public $ho_ten;
    public $ngay_sinh;
    public $gioi_tinh;
    public $email;

    private $conn;
    function __construct(){}

    function getGiangVienById($id_giang_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM giang_vien WHERE id_giang_vien = $id_giang_vien";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $giang_vien = new GiangVienModel();
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->ho_ten = $row["ho_ten"];
            $this->ngay_sinh = $row["ngay_sinh"];
            $this->gioi_tinh = $row["gioi_tinh"];
            $this->email = $row["email"];
            $this->conn->close();
            return $giang_vien;
        }
        else{
            $this->conn->close();
            return null;
        }
    }

    function getAllGiangViens()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM giang_vien";
        $result = $this->conn->query($sql);
        $giang_viens = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $giang_vien = new GiangVienModel();
                $giang_vien->id_giang_vien = $row["id_giang_vien"];
                $giang_vien->ho_ten = $row["ho_ten"];
                $giang_vien->ngay_sinh = $row["ngay_sinh"];
                $giang_vien->gioi_tinh = $row["gioi_tinh"];
                $giang_vien->email = $row["email"];
                $giang_viens[] = $giang_vien;
            }
            
        }
        $this->conn->close();
        return $giang_viens;
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

    function insertGiangVien($giang_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $ho_ten = $this->conn->real_escape_string($giang_vien->ho_ten);
        $ngay_sinh = $this->conn->real_escape_string($giang_vien->ngay_sinh);
        $gioi_tinh = $this->conn->real_escape_string($giang_vien->gioi_tinh);
        $email = $this->conn->real_escape_string($giang_vien->email);

        $sql = "INSERT INTO giang_vien (ho_ten, ngay_sinh, gioi_tinh, email) VALUES ('$ho_ten', '$ngay_sinh', '$gioi_tinh', '$email')";
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

    function deleteGiangVien($giang_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_giang_vien = $this->conn->real_escape_string($giang_vien->id_giang_vien);
        $sql = "DELETE FROM giang_vien WHERE id_giang_vien = $id_giang_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateGiangVien($giang_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_giang_vien = $this->conn->real_escape_string($giang_vien->id_giang_vien);
        $ho_ten = $this->conn->real_escape_string($giang_vien->ho_ten);
        $ngay_sinh = $this->conn->real_escape_string($giang_vien->ngay_sinh);
        $gioi_tinh = $this->conn->real_escape_string($giang_vien->gioi_tinh);
        $email = $this->conn->real_escape_string($giang_vien->email);

        $sql = "UPDATE giang_vien SET ho_ten = '$ho_ten', ngay_sinh = '$ngay_sinh', gioi_tinh = '$gioi_tinh', email = '$email' WHERE id_giang_vien = $id_giang_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
