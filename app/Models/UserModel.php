<?php

namespace App\Models;

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
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
    }

    public function getUserById($userId) {
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
    
            return $this;
        } else {
            return null;
        }
    
        $stmt->close();
    }
    

    public function getAllUsers() {
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

        return $users;
    }

    public function executeCustomQuery($sql) {
        $result = $this->conn->query($sql);

        $rows = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function insertUser($user) {
        $sql = "INSERT INTO users (anh_dai_dien, tai_khoan, mat_khau, thoi_gian_dang_nhap_gan_nhat, id_ad, id_giang_vien, id_hoc_vien) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("bsssiis", $user->anh_dai_dien, $user->tai_khoan, $user->mat_khau, $user->thoi_gian_dang_nhap_gan_nhat, $user->id_ad, $user->id_giang_vien, $user->id_hoc_vien);

        if ($stmt->execute()) {
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function updateUser($user) {
        $sql = "UPDATE users SET anh_dai_dien = ?, tai_khoan = ?, mat_khau = ?, thoi_gian_dang_nhap_gan_nhat = ?, id_ad = ?, id_giang_vien = ?, id_hoc_vien = ? WHERE id_user = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("bsssiiii", $user->anh_dai_dien, $user->tai_khoan, $user->mat_khau, $user->thoi_gian_dang_nhap_gan_nhat, $user->id_ad, $user->id_giang_vien, $user->id_hoc_vien, $user->id_user);

        if ($stmt->execute()) {
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id_user = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function __destruct() {
        $this->conn->close();
    }
}

?>
