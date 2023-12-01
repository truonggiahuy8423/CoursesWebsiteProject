<?php
namespace App\Models;
use DateTime;
use CodeIgniter\Model;
use Exception;
use mysqli;
include 'DatabaseConnect.php';

class HocVienModel
{
    public $id_hoc_vien;
    public $ho_ten;
    public $ngay_sinh;
    public $gioi_tinh;
    public $email;

    private $conn;
    function __construct(){}

    function getHocVienById($id_hoc_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_hoc_vien = $id_hoc_vien";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hoc_vien = new HocVienModel();
            $this->id_hoc_vien = $row["id_hoc_vien"];
            $this->ho_ten = $row["ho_ten"];
            $this->ngay_sinh = $row["ngay_sinh"];
            $this->gioi_tinh = $row["gioi_tinh"];
            $this->email = $row["email"];
            $this->conn->close();
            return $hoc_vien;
        }
        $this->conn->close();
        return null;
    }

    public static function compareStudentsById($a, $b) {
        $idA = is_array($a) ? $a['id_hoc_vien'] : $a->id_hoc_vien;
        $idB = is_array($b) ? $b['id_hoc_vien'] : $b->id_hoc_vien;

        if ($idA > $idB) {
            return 1;
        } else if ($idA < $idB) {
            return -1;
        } else {
            return 0;
        }
    }

    function getAllHocViens()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien";
        $result = $this->conn->query($sql);
        $hoc_viens = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hoc_vien = new HocVienModel();
                $hoc_vien->id_hoc_vien = $row["id_hoc_vien"];
                $hoc_vien->ho_ten = $row["ho_ten"];
                $hoc_vien->ngay_sinh = $row["ngay_sinh"];
                $hoc_vien->gioi_tinh = $row["gioi_tinh"];
                $hoc_vien->email = $row["email"];
                $hoc_viens[] = $hoc_vien;
            }
        }
        usort($hoc_viens, [$this, 'compareStudentsById']);
        $this->conn->close();
        return $hoc_viens;
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

    // function insertHocVien($hoc_vien)
    // {
    //     $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    //     if ($this->conn->connect_error) {
    //         return ['state' => false, 'message' => 'Kết nối đến cơ sở dữ liệu thất bại: ' . $this->conn->connect_error];
    //     }
    //     if (isset($hoc_vien->ho_ten) && isset($hoc_vien->ngay_sinh) && isset($hoc_vien->gioi_tinh) && isset($hoc_vien->email)) {
    //         $ho_ten = $this->conn->real_escape_string($hoc_vien->ho_ten);
    //         $ngay_sinh = $this->conn->real_escape_string($hoc_vien->ngay_sinh);
    //         $gioi_tinh = $this->conn->real_escape_string($hoc_vien->gioi_tinh);
    //         $email = $this->conn->real_escape_string($hoc_vien->email);

    //         $sql = "INSERT INTO hoc_vien (ho_ten, ngay_sinh, gioi_tinh, email) VALUES ('$ho_ten', '$ngay_sinh', '$gioi_tinh', '$email')";
            
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bind_param("ssss", $ho_ten, $ngay_sinh, $gioi_tinh, $email);

    //         if ($stmt->execute()) {
    //             $insertedId = $this->conn->insert_id;
    //             $stmt->close();
    //             $this->conn->close();
    //             return ['state' => true, 'message' => 'Insert thành công', 'auto_increment_id' => $insertedId];
    //         } else {
    //             $error_message = $this->conn->error;
    //             $stmt->close();
    //             $this->conn->close();
    //             return ['state' => false, 'message' => $error_message];
    //         }
    //     } else {
    //         return ['state' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
    //     }
    // }

    function insertHocVien($hoc_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            return ['state' => false, 'message' => 'Kết nối đến cơ sở dữ liệu thất bại: ' . $this->conn->connect_error];
        }
        if (isset($hoc_vien->ho_ten) && isset($hoc_vien->ngay_sinh) && isset($hoc_vien->gioi_tinh) && isset($hoc_vien->email)) {

            $sql = "INSERT INTO hoc_vien (ho_ten, ngay_sinh, gioi_tinh, email) VALUES (?, ?, ?, ?);";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $hoc_vien->ho_ten, $hoc_vien->ngay_sinh, $hoc_vien->gioi_tinh, $hoc_vien->email);

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

    function deleteHocVien($id_hoc_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_hoc_vien = $this->conn->real_escape_string($id_hoc_vien);
        $sql = "DELETE FROM hoc_vien WHERE id_hoc_vien = $id_hoc_vien";
        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } catch (Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function updateHocVien($hoc_vien)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_hoc_vien = $this->conn->real_escape_string($hoc_vien->id_hoc_vien);
        $ho_ten = $this->conn->real_escape_string($hoc_vien->ho_ten);
        $ngay_sinh = $this->conn->real_escape_string($hoc_vien->ngay_sinh);
        $gioi_tinh = $this->conn->real_escape_string($hoc_vien->gioi_tinh);
        $email = $this->conn->real_escape_string($hoc_vien->email);

        $sql = "UPDATE hoc_vien SET ho_ten = '$ho_ten', ngay_sinh = '$ngay_sinh', gioi_tinh = '$gioi_tinh', email = '$email' WHERE id_hoc_vien = $id_hoc_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
    




