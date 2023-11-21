<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class ClassModel
{
    public $classId;
    public $ngayBatDau;
    public $ngayKetThuc;
    public $idMonHoc;

    private $conn;
    function __construct(){}


    function getClassById($classId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc WHERE id_lop_hoc = $classId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new ClassModel();
            $this->classId = $row["id_lop_hoc"];
            $this->ngayBatDau = $row["ngay_bat_dau"];
            $this->ngayKetThuc = $row["ngay_ket_thuc"];
            $this->idMonHoc = $row["id_mon_hoc"];
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAllClasss()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new ClassModel();
                $this->classId = $row["id_lop_hoc"];
                $this->ngayBatDau = $row["ngay_bat_dau"];
                $this->ngayKetThuc = $row["ngay_ket_thuc"];
                $this->idMonHoc = $row["id_mon_hoc"];
                $users[] = $user;
            }
        }
        $this->conn->close();
        return $users;
    }

    function queryDatabase($sql)
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

    function insertClass($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $ngayBatDau = $this->conn->real_escape_string($user->ngayBatDau);
        $ngayKetThuc = $this->conn->real_escape_string($user->ngayKetThuc);
        $idMonHoc = $this->conn->real_escape_string($user->idMonHoc);
        $email = $this->conn->real_escape_string($user->email);

        $sql = "INSERT INTO lop_hoc (ngay_bat_dau, ngay_ket_thuc, id_mon_hoc) VALUES ('$ngayBatDau', '$ngayKetThuc', '$idMonHoc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteClass($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $classId = $this->conn->real_escape_string($user->classId);
        $sql = "DELETE FROM lop_hoc WHERE id_lop_hoc = $classId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateClass($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $classId = $this->conn->real_escape_string($user->classId);
        $ngayBatDau = $this->conn->real_escape_string($user->ngayBatDau);
        $ngayKetThuc = $this->conn->real_escape_string($user->ngayKetThuc);
        $idMonHoc = $this->conn->real_escape_string($user->idMonHoc);

        $sql = "UPDATE lop_hoc SET ngay_bat_dau = '$ngayBatDau', ngay_ket_thuc = '$ngayKetThuc', id_mon_hoc = '$idMonHoc' WHERE id_lop_hoc = $classId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
