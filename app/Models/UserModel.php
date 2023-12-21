<?php

namespace App\Models;
use Exception;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';
class UserModel {

    public $id_user;
    public $anh_dai_dien;
    public $tai_khoan;
    public $mat_khau;
    public $thoi_gian_dang_nhap_gan_nhat;
    public $id_ad;
    public $id_giang_vien;
    public $id_hoc_vien;


    private $conn;

    public function __construct() {
        
    }

    public function getUserById($userId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE id_user = ?";
        
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("i", $userId);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            $this->id_user = $user['id_user'];
            $this->anh_dai_dien = $user['anh_dai_dien'];
            $this->tai_khoan = $user['tai_khoan'];
            $this->mat_khau = $user['mat_khau'];
            $this->thoi_gian_dang_nhap_gan_nhat = $user['thoi_gian_dang_nhap_gan_nhat'];
            $this->id_ad = $user['id_ad'];
            $this->id_giang_vien = $user['id_giang_vien'];
            $this->id_hoc_vien = $user['id_hoc_vien'];
            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    
       
    }
    
    public function getUserByAccount($tai_khoan) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE tai_khoan = ?";
        
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("s", $tai_khoan);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            $this->id_user = $user['id_user'];
            $this->anh_dai_dien = $user['anh_dai_dien'];
            $this->tai_khoan = $user['tai_khoan'];
            $this->mat_khau = $user['mat_khau'];
            $this->thoi_gian_dang_nhap_gan_nhat = $user['thoi_gian_dang_nhap_gan_nhat'];
            $this->id_ad = $user['id_ad'];
            $this->id_giang_vien = $user['id_giang_vien'];
            $this->id_hoc_vien = $user['id_hoc_vien'];
            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    
       
    }
    

    public function getAllUsers() {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        $users = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new UserModel();
                $user->id_user = $row['id_user'];
                $user->anh_dai_dien = $row['anh_dai_dien'];
                $user->tai_khoan = $row['tai_khoan'];
                $user->mat_khau = $row['mat_khau'];
                $user->thoi_gian_dang_nhap_gan_nhat = $row['thoi_gian_dang_nhap_gan_nhat'];
                $user->id_ad = $row['id_ad'];
                $user->id_giang_vien = $row['id_giang_vien'];
                $user->id_hoc_vien = $row['id_hoc_vien'];

                $users[] = $user;
            }
        }
        $this->conn->close();
        return $users;
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
    public function executeCustomQuery($sql) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $result = $this->conn->query($sql);

        $rows = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }

    public function insertUser($user) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO users (anh_dai_dien, tai_khoan, mat_khau, thoi_gian_dang_nhap_gan_nhat, id_ad, id_giang_vien, id_hoc_vien) VALUES (?, ?, ?, ?, ?, ?, ?)";
        

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssiii", $user->anh_dai_dien, $user->tai_khoan, $user->mat_khau, $user->thoi_gian_dang_nhap_gan_nhat, $user->id_ad, $user->id_giang_vien, $user->id_hoc_vien);
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Thêm mới user thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateUser($user) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE users SET anh_dai_dien = ?, tai_khoan = ?, mat_khau = ?, thoi_gian_dang_nhap_gan_nhat = ?, id_ad = ?, id_giang_vien = ?, id_hoc_vien = ? WHERE id_user = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("bsssiiii", $user->anh_dai_dien, $user->tai_khoan, $user->mat_khau, $user->thoi_gian_dang_nhap_gan_nhat, $user->id_ad, $user->id_giang_vien, $user->id_hoc_vien, $user->id_user);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function deleteUser($userId) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM users WHERE id_user = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function __destruct() {
    }
}

?>