<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Controllers\LoginController;
use App\Models\ClassModel;
use App\Models\GiangVienModel;
use App\Models\LecturersModel;
use App\Models\LopModel;
use App\Models\MonHocModel;
use App\Models\UserModel;
use PHPUnit\Util\Json;

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
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc");
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
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $data = array();
        // Subjects
        $subjectsModel = new MonHocModel();
        $data['subjects'] = $subjectsModel->getAllMonHoc();
        // Lecturers
        $lecturersModel = new GiangVienModel();
        $data['lecturers'] = $lecturersModel->getAllGiangViens();
        // $data[`ok`] = 10;
        return view('Admin\ViewCell\InsertClassForm', $data);
    }
    public function a($id, $b, $c) {
        $model = new LopModel();
        return implode($model->insertLop2($id, $b, $c));
    }
    public function g() {
        $model = new GiangVienModel();
        return $model->getAllGiangViens()[0]->ho_ten;
    }
    public function insertCourse() {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Process
        
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        
        $course = new LopModel();
        $course->ngay_bat_dau = $courseData['ngay_bat_dau'];
        $course->ngay_ket_thuc = $courseData['ngay_ket_thuc'];
        $course->id_mon_hoc = $courseData['id_mon_hoc'];
        
        $model = new LopModel();
        // return implode('|', json_decode(json_encode($this->request->getJSON()), true));
        // $a = array();
        // return $this->response->setJSON(json_encode($a));
        return $this->response->setJSON($model->insertLop($course));
        // return $this->response->setJSON($this->request->getJSON());
    }

    public function insertLecturersIntoClass() {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Process
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $id_lop_hoc = $courseData['id_lop_hoc'];
        $lecturer_id_list = $courseData['lecturer_id_list'];
        
        $processedResult = array();
        foreach ($lecturer_id_list as $id => $name) {
            $processedResult["$name"."($id)"] = ['state' => true, 'message' => 'Update thành công']; // gọi PhanCongGiangVienModel
        }
        return $this->response->setJSON($processedResult);
    }
}
