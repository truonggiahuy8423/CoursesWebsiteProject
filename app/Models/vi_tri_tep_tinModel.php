<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;
use mysqli;
include 'DatabaseConnect.php';

class vi_tri_tep_tinModel
{
    

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

    public $id_muc;
    public $id_tep_tin_tai_len;
    public $ngay_dang;
   
    function insertvi_tri_tep_tin($vttt)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_muc = $this->conn->real_escape_string($vttt->id_muc);
        $id_tep_tin_tai_len = $this->conn->real_escape_string($vttt->id_tep_tin_tai_len);
        $ngay_dang = $this->conn->real_escape_string($vttt->ngay_dang);
       

        $sql = "INSERT INTO vi_tri_tep_tin (id_muc, id_tep_tin_tai_len, ngay_dang) VALUES (?, ?, ?)";

        

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iis', $id_muc, $id_tep_tin_tai_len, $ngay_dang);

        try {
            $stmt->execute();
            // $insertedId = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Tệp tin đã được đăng'];
        } catch (Exception $e) {
            $error_message = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $error_message];
        }
        
    }

    function deletevi_tri_tep_tin($vttt)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tep_tin_tai_len = $this->conn->real_escape_string($vttt->id_tep_tin_tai_len);
        $id_muc = $this->conn->real_escape_string($vttt->id_muc);

        $sql = "DELETE FROM vi_tri_tep_tin WHERE id_muc = ? AND id_tep_tin_tai_len = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $id_muc, $id_tep_tin_tai_len);

        try {
            $stmt->execute();
            // $insertedId = $this->conn->insert_id;
            
            $deletedRows = $stmt->affected_rows;

            $stmt->close();
            $this->conn->close();

            if ($deletedRows > 0) {
                return ['state' => true, 'message' => "Đã xóa tệp tin thành công"];
            } else {
                return ['state' => false, 'message' => 'Không có tệp tin nào bị xóa'];
            }
        } catch (Exception $e) {
            $error_message = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $error_message];
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