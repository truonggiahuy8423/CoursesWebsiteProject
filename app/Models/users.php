<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_user'; // Tên trường khoá chính

    protected $allowedFields = [
        'anh_dai_dien', 'tai_khoan', 'mat_khau',
        'thoi_gian_dang_nhap_gan_nhat', 'id_ad', 'id_giang_vien', 'id_hoc_vien'
    ]; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng User dựa trên ID
    public function getUserById($userId)
    {
        return $this->find($userId);
    }

    // Hàm trả về mảng đối tượng User từ bảng user
    public function getAllUsers()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng User vào bảng
    public function insertUser($user)
    {
        try {
            $this->insert($user);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin User trong bảng dựa trên ID
    public function updateUser($userId, $userData)
    {
        try {
            $this->update($userId, $userData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng User từ bảng dựa trên ID
    public function deleteUser($userId)
    {
        try {
            $this->delete($userId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>

