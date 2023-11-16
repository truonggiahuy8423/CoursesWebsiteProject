<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table      = 'students'; // Tên bảng trong CSDL
    protected $primaryKey = 'id'; // Tên trường khoá chính

    protected $allowedFields = ['name', 'email', 'phone']; // Các trường có thể được thêm hoặc cập nhật

    // Các cài đặt khác, ví dụ như timestamps (thời gian tạo và cập nhật)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
?>