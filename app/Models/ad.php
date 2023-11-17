<?php

namespace App\Models;

use CodeIgniter\Model;

class AdModel extends Model
{
    protected $table      = 'ad'; // Tên bảng trong CSDL
    protected $primaryKey = 'id_ad'; // Tên trường khoá chính

    protected $allowedFields = ['ho_ten', 'id_user']; // Các trường có thể được thêm hoặc cập nhật

    // Nếu bạn muốn sử dụng timestamps, bạn có thể đặt các thuộc tính sau:
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Hàm trả về đối tượng Ad dựa trên ID
    public function getAdById($adId)
    {
        return $this->find($adId);
    }

    // Hàm trả về mảng đối tượng Ad từ bảng ad
    public function getAllAd()
    {
        return $this->findAll();
    }

    // Hàm trả về mảng 2 chiều của các dòng sau khi thực hiện truy vấn SQL
    public function queryDatabase($sql)
    {
        $query = $this->query($sql);
        return $query->getResultArray();
    }

    // Hàm thêm mới đối tượng Ad vào bảng
    public function insertAd($ad)
    {
        try {
            $this->insert($ad);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm cập nhật thông tin Ad trong bảng dựa trên ID
    public function updateAd($adId, $adData)
    {
        try {
            $this->update($adId, $adData);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    // Hàm xóa đối tượng Ad từ bảng dựa trên ID
    public function deleteAd($adId)
    {
        try {
            $this->delete($adId);
            return ['state' => true, 'message' => ''];
        } catch (\Exception $e) {
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
