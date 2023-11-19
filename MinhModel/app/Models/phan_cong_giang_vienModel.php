<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;

include 'DatabaseConnect.php';

class phan_cong_giang_vienModel
{
    public $id_giang_vien;
    public $id_lop_hoc;
    

    private $conn;
    function __construct(){}

    function getphan_cong_giang_vienById($lecturerId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM phan_cong_giang_vien WHERE id_giang_vien = $id_giang_vien";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new phan_cong_giang_vienModel();
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_lop_hoc = $row["id_lop_hoc"];
            
            $this->conn->close();
            return $user;
        }
        else{
            $this->conn->close();
            return null;
        }
    }

    function getAllphan_cong_giang_vien()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM phan_cong_giang_vien";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new phan_cong_giang_vienModel();
                $this->id_giang_vien = $row["id_giang_vien"];
                $this->id_lop_hoc = $row["id_lop_hoc"];
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

    function insertphan_cong_giang_vien($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }


        $sql = "INSERT INTO phan_cong_giang_vien (id_giang_vien, id_lop_hoc) VALUES ('$id_giang_vien', '$id_lop_hoc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deletephan_cong_giang_vien($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_giang_vien = $this->conn->real_escape_string($user->id_giang_vien);
        $sql = "DELETE FROM phan_cong_giang_vien WHERE id_giang_vien = $id_giang_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updatephan_cong_giang_vien($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_giang_vien = $this->conn->real_escape_string($user->id_giang_vien);
        $id_lop_hoc = $this->conn->real_escape_string($user->id_lop_hoc);

        $sql = "UPDATE phan_cong_giang_vien SET id_lop_hoc = '$id_lop_hoc'WHERE id_giang_vien = $id_giang_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
