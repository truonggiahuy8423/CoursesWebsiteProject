<?php

namespace App\Models;

use CodeIgniter\Model;

class PhongModel extends Model
{
    protected $table      = 'phong'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_phong'; // Tên trường khoá chính

    //protected $allowedFields = ['id_phong']; // Chỉ có một trường ID, vì đây là bảng chỉ chứa ID và sử dụng AUTO_INCREMENT

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng Phong dựa trên ID
    public function getPhongById($phongId)
    {
        return $this->find($phongId);
    }

    // Hàm trả về mảng đối tượng Phong từ bảng phong
    public function getAllPhong()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng Phong vào bảng
    public function insertPhong($phong)
    {
        try {
            $this->insert($phong);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin Phong trong bảng dựa trên ID
    public function updatePhong($phongId, $phongData)
    {
        try {
            $this->update($phongId, $phongData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng Phong từ bảng dựa trên ID
    public function deletePhong($phongId)
    {
        try {
            $this->delete($phongId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
