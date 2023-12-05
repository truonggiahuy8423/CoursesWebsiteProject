<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class vi_tri_tep_tinModel
{
    public $id_muc;
    public $id_tep_tin_tai_len;
    public $ngay_dang;
   

    private $conn;
    function __construct(){}

    function get_vi_tri_tep_tin_ById($studentId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM vi_tri_tep_tin WHERE id_muc = $studentId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new vi_tri_tep_tinModel();
            $this->id_muc = $row["id_muc"];
            $this->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
            $this->ngay_dang = $row["ngay_dang"];
            
            $this->conn->close();
            return $user;
        }
        else{
            $this->conn->close();
            return null;
        }
    }

    function getAllvi_tri_tep_tin()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM vi_tri_tep_tin";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new vi_tri_tep_tinModel();
                $this->id_muc = $row["id_muc"];
                $this->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
                $this->ngay_dang = $row["ngay_dang"];
                $users[] = $user;
            }
        }
        $this->conn->close();
        return $users;
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

    function insertvi_tri_tep_tin($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_muc = $this->conn->real_escape_string($user->id_muc);
        $id_tep_tin_tai_len = $this->conn->real_escape_string($user->id_tep_tin_tai_len);
        $ngay_dang = $this->conn->real_escape_string($user->ngay_dang);
       

        $sql = "INSERT INTO vi_tri_tep_tin (id_muc, id_tep_tin_tai_len, ngay_dang) VALUES ('$id_muc', '$id_tep_tin_tai_len', '$ngay_dang')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deletevi_tri_tep_tin($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $studentId = $this->conn->real_escape_string($user->studentId);
        $sql = "DELETE FROM vi_tri_tep_tin WHERE id_muc = $user";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updatevi_tri_tep_tin($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_muc = $this->conn->real_escape_string($user->id_muc);
        $id_tep_tin_tai_len = $this->conn->real_escape_string($user->id_tep_tin_tai_len);
        $ngay_dang = $this->conn->real_escape_string($user->ngay_dang);

        $sql = "UPDATE vi_tri_tep_tin SET id_muc = '$id_muc', id_tep_tin_tai_len = '$id_tep_tin_tai_len', ngay_dang = '$ngay_dang'";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}