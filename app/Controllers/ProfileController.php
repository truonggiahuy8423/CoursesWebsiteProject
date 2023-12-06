<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\GiangVienModel;
use App\Models\phan_cong_giang_vienModel;
use DateTime;
class ProfileController extends BaseController
{
    public function index($role)
    {
        $data = [];
        $model = new UserModel();
        $navbar_data = array();
        if ($role == "true") {
            // giang vien
            $teacherID = $_GET['id'];
            $lecturersModel = new GiangVienModel(); 
            $phancong = new phan_cong_giang_vienModel();
            
            $data['id'] = $teacherID;
            $data['user'] = $lecturersModel->getGiangVienById($teacherID);
            $data['attend'] = $phancong->getPhanCongByIDGiangVien($teacherID);
            if (session()->get('role') == 1) { // Admin
                $result = $model->executeCustomQuery(
                    'SELECT ad.ho_ten, users.anh_dai_dien
                    FROM users
                    INNER JOIN ad ON users.id_ad = ad.id_ad
                    WHERE users.id_user = ' . session()->get("id_user")
                );
                $navbar_data['username'] = "{$result[0]['ho_ten']}";
                $navbar_data['role'] = 'Adminstrator';
                $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
            }
            $data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            return view('ProfilePage', $data);
            
        } else {
            // hoc vien
            $id_hoc_vien = $_GET['id'];
            return view('ProfilePage');
        }
    }
}