<?php

namespace App\Models;

use CodeIgniter\Model;

class MonHocModel extends Model
{
    protected $table      = 'mon_hoc'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_mon_hoc'; // Tên trường khoá chính

    protected $allowedFields = ['ten_mon_hoc']; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng MonHoc dựa trên ID
    public function getMonHocById($monHocId)
    {
        return $this->find($monHocId);
    }

    // Hàm trả về mảng đối tượng MonHoc từ bảng mon_hoc
    public function getAllMonHoc()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng MonHoc vào bảng
    public function insertMonHoc($monHoc)
    {
        try {
            $this->insert($monHoc);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin MonHoc trong bảng dựa trên ID
    public function updateMonHoc($monHocId, $monHocData)
    {
        try {
            $this->update($monHocId, $monHocData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng MonHoc từ bảng dựa trên ID
    public function deleteMonHoc($monHocId)
    {
        try {
            $this->delete($monHocId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
