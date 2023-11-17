<?php

namespace App\Models;

use CodeIgniter\Model;

class MucModel extends Model
{
    protected $table      = 'muc'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_muc'; // Tên trường khoá chính

    protected $allowedFields = ['ten_muc', 'id_lop_hoc', 'id_muc_cha']; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng Muc dựa trên ID
    public function getMucById($mucId)
    {
        return $this->find($mucId);
    }

    // Hàm trả về mảng đối tượng Muc từ bảng muc
    public function getAllMuc()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng Muc vào bảng
    public function insertMuc($muc)
    {
        try {
            $this->insert($muc);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin Muc trong bảng dựa trên ID
    public function updateMuc($mucId, $mucData)
    {
        try {
            $this->update($mucId, $mucData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng Muc từ bảng dựa trên ID
    public function deleteMuc($mucId)
    {
        try {
            $this->delete($mucId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
