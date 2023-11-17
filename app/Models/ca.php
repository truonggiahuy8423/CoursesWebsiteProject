<?php

namespace App\Models;

use CodeIgniter\Model;

class CaModel extends Model
{
    protected $table      = 'ca'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_ca'; // Tên trường khoá chính

    protected $allowedFields = ['thoi_gian_bat_dau', 'thoi_gian_ket_thuc']; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng Ca dựa trên ID
    public function getCaById($caId)
    {
        return $this->find($caId);
    }

    // Hàm trả về mảng đối tượng Ca từ bảng ca
    public function getAllCa()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng Ca vào bảng
    public function insertCa($ca)
    {
        try {
            $this->insert($ca);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin Ca trong bảng dựa trên ID
    public function updateCa($caId, $caData)
    {
        try {
            $this->update($caId, $caData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng Ca từ bảng dựa trên ID
    public function deleteCa($caId)
    {
        try {
            $this->delete($caId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
