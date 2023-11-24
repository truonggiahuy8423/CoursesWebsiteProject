<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class chi_tiet_bai_nopModel
{
    public $id_bai_nop;
    public $id_tep_tin_tai_len;

    private $conn;
    function __construct(){}

    function get_bainop_ById($studentId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM chi_tiet_bai_nop WHERE id_bai_nop = $id_bai_nop";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new chi_tiet_bai_nopModel();
            $this->id_bai_nop = $row["id_bai_nop"];
            $this->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
            $this->conn->close();
            return $user;
        }
        else{
            $this->conn->close();
            return null;
        }
    }

    function getAll_bainop()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM chi_tiet_bai_nop";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new chi_tiet_bai_nopModel();
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

    function insertchi_tiet_bai_nop($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }


        $sql = "INSERT INTO chi_tiet_bai_nop (id_bai_nop, id_tep_tin_tai_len) VALUES ('$id_bai_nop', '$id_tep_tin_tai_len')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deletechi_tiet_bai_nop($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_nop = $this->conn->real_escape_string($user->id_bai_nop);
        $sql = "DELETE FROM chi_tiet_bai_nop WHERE id_bai_nop = $id_bai_nop";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updatechi_tiet_bai_nop($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_bai_nop = $this->conn->real_escape_string($user->id_bai_nop);
        $id_tep_tin_tai_len = $this->conn->real_escape_string($user->id_tep_tin_tai_len);

        $sql = "UPDATE chi_tiet_bai_nop SET id_bai_nop = '$id_bai_nop', id_tep_tin_tai_len = '$id_tep_tin_tai_len' WHERE id_bai_nop = $id_bai_nop";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}