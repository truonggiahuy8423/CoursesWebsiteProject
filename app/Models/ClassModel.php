<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class ClassModel
{
    public $id_lop_hoc;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $id_mon_hoc;

    private $conn;
    function __construct(){}


    function getClassById($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new ClassModel();
            $this->id_lop_hoc = $row["id_lop_hoc"];
            $this->ngay_bat_dau = $row["ngay_bat_dau"];
            $this->ngay_ket_thuc = $row["ngay_ket_thuc"];
            $this->id_mon_hoc = $row["id_mon_hoc"];
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
                $this->id_lop_hoc = $row["id_lop_hoc"];
                $this->ngay_bat_dau = $row["ngay_bat_dau"];
                $this->ngay_ket_thuc = $row["ngay_ket_thuc"];
                $this->id_mon_hoc = $row["id_mon_hoc"];
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

        $ngay_bat_dau = $this->conn->real_escape_string($user->ngay_bat_dau);
        $ngay_ket_thuc = $this->conn->real_escape_string($user->ngay_ket_thuc);
        $id_mon_hoc = $this->conn->real_escape_string($user->id_mon_hoc);
        // $email = $this->conn->real_escape_string($user->email);

        $sql = "INSERT INTO lop_hoc (ngay_bat_dau, ngay_ket_thuc, id_mon_hoc) VALUES ('$ngay_bat_dau', '$ngay_ket_thuc', '$id_mon_hoc')";
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

        $id_lop_hoc = $this->conn->real_escape_string($user->id_lop_hoc);
        $sql = "DELETE FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";

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

        $id_lop_hoc = $this->conn->real_escape_string($user->id_lop_hoc);
        $ngay_bat_dau = $this->conn->real_escape_string($user->ngay_bat_dau);
        $ngay_ket_thuc = $this->conn->real_escape_string($user->ngay_ket_thuc);
        $id_mon_hoc = $this->conn->real_escape_string($user->id_mon_hoc);

        $sql = "UPDATE lop_hoc SET ngay_bat_dau = '$ngay_bat_dau', ngay_ket_thuc = '$ngay_ket_thuc', id_mon_hoc = '$id_mon_hoc' WHERE id_lop_hoc = $id_lop_hoc";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
