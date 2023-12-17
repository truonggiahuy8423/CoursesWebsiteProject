<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';
class diem_danhModel
{
    public $id_hoc_vien;
    public $id_buoi_hoc;
    public $ghi_chu;
    public $co_mat;
  

    private $conn;
    function __construct(){}


    // function getdiem_danhModelById($notiId)
    // {
    //     $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    //     if ($this->conn->connect_error) {
    //         die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
    //     }

    //     $sql = "SELECT * FROM diem_danh WHERE id_hoc_vien = $id_hoc_vien";
    //     $result = $this->conn->query($sql);

    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         $user = new diem_danhModel();
    //         $this->id_hoc_vien= $row["id_hoc_vien"];
    //         $this->id_buoi_hoc = $row["id_buoi_hoc"];
    //         $this->ghi_chu = $row["ghi_chu"];
    //         $this->co_mat = $row["co_mat"];
            
    //         $this->conn->close();
    //         return $user;
    //     }
    //     $this->conn->close();
    //     return null;
    // }

    function getAlldiem_danhModels()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM diem_danh";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new diem_danhModel();
                $this->id_hoc_vien= $row["id_hoc_vien"];
                $this->id_buoi_hoc = $row["id_buoi_hoc"];
                $this->ghi_chu = $row["ghi_chu"];
                $this->co_mat = $row["co_mat"];
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

    function insertdiem_danhModel($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_hoc_vien = $this->conn->real_escape_string($user->id_hoc_vien);
        $id_buoi_hoc = $this->conn->real_escape_string($user->id_buoi_hoc);
        $ghi_chu = $this->conn->real_escape_string($user->ghi_chu);
        $co_mat = $this->conn->real_escape_string($user->co_mat);

        $sql = "INSERT INTO diem_danh (id_hoc_vien , id_buoi_hoc, ghi_chu, co_mat) VALUES ('$id_hoc_vien', '$id_buoi_hoc', '$ghi_chu', '$co_mat')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deletediem_danhModel($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $notiId = $this->conn->real_escape_string($user->notiId);
        $sql = "DELETE FROM thong_bao WHERE id_thong_bao = $notiId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }



    function updatediem_danhnModel($usersArray)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
    
        foreach ($usersArray as $user) {
            $id_hoc_vien = $this->conn->real_escape_string($user['id_hoc_vien']);
            $id_buoi_hoc = $this->conn->real_escape_string($user['id_buoi_hoc']);
            $co_mat = $this->conn->real_escape_string($user['co_mat']);
            $ghi_chu = $this->conn->real_escape_string($user['ghi_chu']);
            $sql  = "UPDATE diem_danh 
            SET 
                co_mat = '$co_mat',
                ghi_chu= '$ghi_chu'
            WHERE id_hoc_vien = '$id_hoc_vien' 
              AND id_buoi_hoc = '$id_buoi_hoc'";
            
            if ($this->conn->query($sql) !== TRUE) {
                $this->conn->close();
                return ['state' => false, 'message' => $this->conn->error];
            }
        }
    
        $this->conn->close();
        return ['state' => true, 'message' => ''];
    }



}
