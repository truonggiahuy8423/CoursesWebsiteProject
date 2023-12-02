<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\GiangVienModel;
use App\Models\phan_cong_giang_vienModel;

class TeachersController extends BaseController
{
    public function index(): string
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        
        $model = new GiangVienModel();  
        $navbar_data = array();
        $main_layout_data = array();
        $teachers_list_section_layout_data = array();
        $main_layout_data['left_nav_chosen_value'] = 2; //left navigation chosen value

        if (session()->get('role') == 1) { // Admin
            $result = $model->executeCustomQuery(
                    "SELECT ad.ho_ten, users.anh_dai_dien
                    FROM users
                    INNER JOIN ad ON users.id_ad = ad.id_ad
                    WHERE users.id_user = ".session()->get("id_user"));

            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";

            $teacher = $model->executeCustomQuery(
                "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten, giang_vien.email FROM giang_vien");

            $teachers_list_section_layout_data['teachers'] = $teacher;
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            $main_layout_data['mainsection'] = view('Admin\ViewLayout\TeacherListSectionLayout', $teachers_list_section_layout_data);
            return view('Admin\ViewLayout\MainLayout', $main_layout_data);
        }
        else if (session()->get('role') == 2){

        }
        else if(session()->get('role') == 3){
        }
    }

    public function getInsertForm() {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $data = array();
        $lecturersModel = new GiangVienModel();

        $data['lecturers'] = $lecturersModel->getAllGiangViens();

        return view('Admin\ViewCell\InsertTeacherForm', $data);
    }

    public function insertTeacher(){
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $teacherData = json_decode(json_encode($this->request->getJSON()), true);
        $teacher = new GiangVienModel();
        $model = new GiangVienModel();

        $teacher->ho_ten = $teacherData['ho_ten'];
        $teacher->ngay_sinh = $teacherData['ngay_sinh'];
        $teacher->gioi_tinh = $teacherData['gioi_tinh'];
        $teacher->email = $teacherData['email'];

        return $this->response->setJSON($model->insertGiangVien($teacher));
    }

    public function deleteTeacher() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $data = json_decode(json_encode($this->request->getJSON()), true);
        $teachers = $data["teachers"];
        $response = array();
        
        for ($i = 0; $i < count($teachers); $i++) {
            $model = new GiangVienModel();
            $response[$teachers[$i]] = $model->deleteGiangVien($teachers[$i]);
        }

        return $this->response->setJSON($response);
    }

    public function getUpdateForm() {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $data = json_decode(json_encode($this->request->getJSON()), true);
        // $data;
        $teacherID = $_GET['teacherID'];
        $lecturersModel = new GiangVienModel(); 
        $phancong = new phan_cong_giang_vienModel();
        
        $data['lecturer'] = $lecturersModel->getGiangVienById($teacherID);
        $data['phancongs'] = $phancong->getPhanCongByIDGiangVien($teacherID);
        $data['notphancongs'] = $phancong->getNotPhanCongByIDGiangVien($teacherID);
        return view('Admin\ViewCell\UpdateTeacherForm', $data);
    }

    public function updateTeacher(){
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $teacherData = json_decode(json_encode($this->request->getJSON()), true);
        $teacher = new GiangVienModel();
        $model = new GiangVienModel();

        $teacher->id_giang_vien = $teacherData['id_giang_vien'];
        $teacher->ho_ten = $teacherData['ho_ten'];
        $teacher->ngay_sinh = $teacherData['ngay_sinh'];
        $teacher->gioi_tinh = $teacherData['gioi_tinh'];
        $teacher->email = $teacherData['email'];
        
        return $this->response->setJSON($model->updateGiangVien($teacher));
    }

    public function addClassesIntoListOfTeachingCourses(){
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $id_giang_vien = $courseData['id_giang_vien'];
        $list_id_lop_hoc = $courseData['list_id_lop_hoc'];
        $model = new phan_cong_giang_vienModel();

        $processedResult = array();
        foreach ($list_id_lop_hoc as $id => $name) {
            $data = new phan_cong_giang_vienModel();
            $data->id_giang_vien = $id_giang_vien;
            $data->id_lop_hoc = $id;
            $processedResult["$name"."($id)"] = $model->insertphan_cong_giang_vien($data); // gọi PhanCongGiangVienModel
        }
        return $this->response->setJSON($processedResult);   
    }

    public function deleteClassesFromListOfTeachingCourses(){
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $id_giang_vien = $courseData['id_giang_vien'];
        $list_id_lop_hoc = $courseData['list_id_lop_hoc'];
        $model = new phan_cong_giang_vienModel();

        $processedResult = array();
        foreach ($list_id_lop_hoc as $id => $name) {
            $data = new phan_cong_giang_vienModel();
            $data->id_giang_vien = $id_giang_vien;
            $data->id_lop_hoc = $id;
            $processedResult["$name"."($id)"] = $model->deletephan_cong_giang_vien($data); // gọi PhanCongGiangVienModel
        }
        return $this->response->setJSON($processedResult);   
    }    

}
