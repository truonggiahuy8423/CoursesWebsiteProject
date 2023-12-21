<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\GiangVienModel;
use App\Models\hoc_vien_tham_giaModel;
use App\Models\HocVienModel;
use App\Models\TinNhanRiengModel;
use App\Models\phan_cong_giang_vienModel;
use DateTime;
class ProfileController extends BaseController
{
    public function index($role)
    {
        $data = [];
        $model = new UserModel();
        $navbar_data = array();
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
        } else if (session()->get('role') == 2) {
            $result = $model->executeCustomQuery(
                'SELECT giang_vien.ho_ten, giang_vien.id_giang_vien, users.anh_dai_dien
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Giảng viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";

        } else if (session()->get('role') == 3) {
            $result = $model->executeCustomQuery(
                'SELECT hoc_vien.ho_ten, hoc_vien.id_hoc_vien, users.anh_dai_dien
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Học viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
        }


        if ($role == "true") {
            // giang vien
            $teacherID = $_GET['id'];
            $lecturersModel = new GiangVienModel(); 
            $phancong = new phan_cong_giang_vienModel();
            
            $data['id'] = $teacherID;
            $data['user'] = $lecturersModel->getGiangVienById($teacherID);
            $data["avatar_data"] = ((new UserModel())->getUserByLecturerId($data['user']->id_giang_vien))->anh_dai_dien;
            $data['attend'] = $phancong->getPhanCongByIDGiangVien($teacherID);
            $navbar_data['chatBox'] = $this->getChatBox();
            $data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            $data['role'] = true;
            return view('ProfilePage', $data);
        } else {
            // hoc vien

            $id_hoc_vien = $_GET['id'];
            $studentModel = new HocVienModel(); 
            $phancong = new hoc_vien_tham_giaModel();
            
            $data['id'] = $id_hoc_vien;
            $data['user'] = $studentModel->getHocVienById($id_hoc_vien);
            $data["avatar_data"] = ((new UserModel())->getUserByStudentId($data['user']->id_hoc_vien))->anh_dai_dien;
            $data['attend'] = $phancong->getHocVienThamGiaByHocVienID($id_hoc_vien);
            $navbar_data['chatBox'] = $this->getChatBox();
            $data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            $data['role'] = false;
            return view('ProfilePage', $data);
        }
    }

    public function getChatBox(){
        $tinNhanRiengModel = new TinNhanRiengModel();
        $chat_box = $tinNhanRiengModel->queryDatabase(
            'SELECT DISTINCT user_nhan
            FROM tin_nhan_rieng
            WHERE user_gui = ' . session()->get("id_user"));

        for($i = 0; $i < count($chat_box); $i++){
            $user_nhan = strval($chat_box[$i]["user_nhan"]);
            $chat_box[$i]['lastestTime'] = $tinNhanRiengModel->queryDatabase(
                'SELECT thoi_gian, anh
                FROM tin_nhan_rieng
                WHERE user_gui IN ('.$user_nhan .','.session()->get("id_user").')
                AND user_nhan IN ('.$user_nhan .','.session()->get("id_user").')
                ORDER BY thoi_gian DESC
                LIMIT 1');
            $chat_box[$i]['hoTen'] = $tinNhanRiengModel->queryDatabase(
                'SELECT
                    CASE
                        WHEN u.id_giang_vien IS NOT NULL THEN gv.ho_ten
                        WHEN u.id_ad IS NOT NULL THEN ad.ho_ten
                        WHEN u.id_hoc_vien IS NOT NULL THEN hv.ho_ten
                    END AS ho_ten
                FROM
                    users u
                LEFT JOIN giang_vien gv ON u.id_giang_vien = gv.id_giang_vien
                LEFT JOIN ad ON u.id_ad = ad.id_ad
                LEFT JOIN hoc_vien hv ON u.id_hoc_vien = hv.id_hoc_vien
                WHERE u.id_user = ' . $user_nhan);
        }
        return $chat_box;
    }
}