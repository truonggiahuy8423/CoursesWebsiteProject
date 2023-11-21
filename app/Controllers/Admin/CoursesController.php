<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Controllers\LoginController;
use App\Models\UserModel;

class CoursesController extends BaseController
{
    public function index()
    {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Query data 
        $model = new UserModel();
        $navbar_data = array();
        $main_layout_data = array();
        $courses_list_section_layout_data = array();
        //left navigation chosen value
        $main_layout_data['left_nav_chosen_value'] = 1;

        if (session()->get('role') == 1) { // Admin
            $result = $model->executeCustomQuery(
                'SELECT ad.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad
                WHERE users.id_user = '.session()->get("id_user"));
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";

            $courses = $model->executeCustomQuery(
                "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
                FROM lop_hoc 
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = lop_hoc.id_mon_hoc");
            for ($i = 0; $i < count($courses); $i++) {
                $courses[$i]['lecturers'] = $model->executeCustomQuery(
                    "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten
                    FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                    WHERE phan_cong_giang_vien.id_lop_hoc = {$courses[$i]['id_lop_hoc']};");
            }
            $courses_list_section_layout_data['courses'] = $courses;
        }
        else if (session()->get('role') == 2) { // Giang vien
 
        }
        else if (session()->get('role') == 3) { // Hoc vien
 
        }
        
        $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
        $main_layout_data['mainsection'] = view('Admin\ViewLayout\CoursesListSectionLayout', $courses_list_section_layout_data);
        return view('Admin\ViewLayout\MainLayout', $main_layout_data);
        // return view('ClassPage');
    }


    public function getCoursesListSection()
    {}

    public function getInsertForm() {
        return view('Admin\ViewCell\InsertClassForm');
    }
}
