<?php

namespace App\Models;

use CodeIgniter\Model;

class BuoiHocModel extends Model
{
    protected $table      = 'buoi_hoc'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_buoi_hoc'; // Tên trường khoá chính

    protected $allowedFields = [
        'trang_thai', 'ngay', 'ma_lop_hoc',
        'so_phong', 'ma_ca', 'id_lop_hoc',
        'id_ca', 'id_phong'
    ]; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng BuoiHoc dựa trên ID
    public function getBuoiHocById($buoiHocId)
    {
        return $this->find($buoiHocId);
    }

    // Hàm trả về mảng đối tượng BuoiHoc từ bảng buoi_hoc
    public function getAllBuoiHoc()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng BuoiHoc vào bảng
    public function insertBuoiHoc($buoiHoc)
    {
        try {
            $this->insert($buoiHoc);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin BuoiHoc trong bảng dựa trên ID
    public function updateBuoiHoc($buoiHocId, $buoiHocData)
    {
        try {
            $this->update($buoiHocId, $buoiHocData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng BuoiHoc từ bảng dựa trên ID
    public function deleteBuoiHoc($buoiHocId)
    {
        try {
            $this->delete($buoiHocId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
