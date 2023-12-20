<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\LoginController;
use App\Models\HocVienModel;
use App\Models\diem_danhModel;
use App\Models\hoc_vien_tham_giaModel;
use App\Models\PhongModel;
use App\Models\UserModel;
use PHPUnit\Util\Json;
use DateTime;

class StudentsController extends BaseController
{
    public static function compareStudentsById($a, $b) {
        $idA = is_array($a) ? $a['id_hoc_vien'] : $a->id_hoc_vien;
        $idB = is_array($b) ? $b['id_hoc_vien'] : $b->id_hoc_vien;

        if ($idA > $idB) {
            return 1;
        } else if ($idA < $idB) {
            return -1;
        } else {
            return 0;
        }
    }
    public function getListOfStudents() {
        $model = new UserModel();
        $students = $model->executeCustomQuery(
            "SELECT hoc_vien.id_hoc_vien, hoc_vien.ho_ten, hoc_vien.gioi_tinh, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh, hoc_vien.email FROM hoc_vien");
        usort($students, [$this, 'compareStudentsById']);
        return $this->response->setJSON($students);
    }
    public function index(): string
    {
        
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Query data 
        $model = new UserModel();
        $navbar_data = array();
        $main_layout_data = array();
        $students_list_section_layout_data = array();

        //left navigation chosen value
        $main_layout_data['left_nav_chosen_value'] = 3;

        if (session()->get('role') == 1) { // Admin
            $result = $model->executeCustomQuery(
                'SELECT ad.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad
                WHERE users.id_user = '.session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";

            // $students = $model->executeCustomQuery(
            //     "SELECT hoc_vien.id_hoc_vien, hoc_vien.ho_ten, hoc_vien.gioi_tinh, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh, hoc_vien.email FROM hoc_vien");
            // usort($students, [$this, 'compareStudentsById']);
            // $students_list_section_layout_data['students'] = $students;
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            $main_layout_data['mainsection'] = view('Admin\ViewLayout\StudentsListSectionLayout', $students_list_section_layout_data);
            return view('Admin\ViewLayout\MainLayout', $main_layout_data);
        }
        else if (session()->get('role') == 2) { // Giang vien
 
        }
        else if (session()->get('role') == 3) { // Hoc vien
 
        }
        
       
    }

    public function information()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new UserModel();
        $navbar_data = array();
        $id_hoc_vien = null;
        if (isset($_GET)) {
            $id_hoc_vien = $_GET['studentid'];
        }
        $model = new HocVienModel();
        $student = $model->executeCustomQuery(
            "SELECT hoc_vien.id_hoc_vien, hoc_vien.ho_ten, hoc_vien.gioi_tinh, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh, hoc_vien.email FROM hoc_vien
            WHERE hoc_vien.id_hoc_vien = {$id_hoc_vien};"
        );
        $isExist = count($student) > 0 ? true : false;
        if (!$isExist) {
            return view("CommonViewCell\StudentNotFound");
        }
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
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);


            $model = new HocVienModel();
            // $result = $model->executeCustomQuery('');
            $left_menu_data = array();
            $course_id_mon_hoc = str_pad($student[0]["id_mon_hoc"], 3, "0", STR_PAD_LEFT);
            $course_id_lop_hoc = str_pad($student[0]["id_hoc_vien"], 6, "0", STR_PAD_LEFT);
            $left_menu_data["class_name"] = $student[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
            $left_menu_data["student_quantity"] = $student[0]["so_luong_hoc_vien"];
            $left_menu_data["id_hoc_vien"] = $student[0]["id_hoc_vien"];
            $main_layout_data['leftmenu'] = view('Admin\ViewCell\LeftMenuInCourseDetail', $left_menu_data);

            $main_layout_data['contentsection'] = view('Admin\ViewLayout\StudentInformationSectionLayout', $student[0]);
            return view('Admin\ViewLayout\StudentDetailLayout', $main_layout_data);
        } else if (session()->get('role') == 2) { // Giang vien

        } else if (session()->get('role') == 3) { // Hoc vien

        }
    }

    public function getStudentListSection()
    {
        $model = new UserModel();
        $courses = $model->executeCustomQuery(
            "SELECT hoc_vien.id_hoc_vien,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
            FROM lop_hoc 
            INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc"
        );
        for ($i = 0; $i < count($courses); $i++) {
            $courses[$i]['lecturers'] = $model->executeCustomQuery(
                "SELECT hoc_vien.id_hoc_vien, hoc_vien.ho_ten, hoc_vien.gioi_tinh, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh, hoc_vien.email FROM hoc_vien
                WHERE hoc_vien.id_hoc_vien = {$id_hoc_vien};"
            );
        }
        usort($courses, [$this, 'compareStudentsById']);
        return $this->response->setJSON($courses);
    }

    public function getStudentInfo($id)
    {
        $model = new HocVienModel();
        $studentData = $model->getHocVienById($id);

        if ($studentData) {
            return $this->response->setJSON($studentData);
        } else {
            return $this->response->setJSON(['error' => 'Student not found']);
        }
    }


    public function getInsertStudent()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $data = array();
        // Subjects
        $studentsModel = new HocVienModel();
        $data['students'] = $studentsModel->getAllHocViens();
        return view('Admin\ViewCell\InsertStudentForm', $data);
    }
    // public function deleteStudent()
    // {
    //     if (!session()->has('id_user')) {
    //         return redirect()->to('/');
    //     }
    //     $data = json_decode(json_encode($this->request->getJSON()), true);
        
    //     $students = $data["students"];
    //     $response = array();
    //     for ($i = 0; $i < count($students); $i++) {
    //         $model = new HocVienModel();
    //         $response[$students[$i]] = $model->deleteHocVien($students[$i]);
    //     }

    //     return $this->response->setJSON($response);
    // }

    // public function deleteStudent($id)
    // {
    //     if (!session()->has('id_user')) {
    //         return redirect()->to('/');
    //     }
    
    //     $model = new HocVienModel();
    //     $response = $model->deleteHocVien($id);
    
    //     return $this->response->setJSON(['result' => $response]);
    // }
    

    public function insertStudent()
    {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $studentData = json_decode(json_encode($this->request->getJSON()), true);

        $student = new HocVienModel();
        $student->ho_ten = $studentData['ho_ten'];
        $student->ngay_sinh = $studentData['ngay_sinh'];
        $student->gioi_tinh = $studentData['gioi_tinh'];
        $student->email = $studentData['email'];

        $model = new HocVienModel();
        return $this->response->setJSON($model->insertHocVien($student));
    }

    public function storeStudent() 
    {
        $student = new HocVienModel();
        $data = [
            'ho_ten' => $this->request->getPost('ho_ten'),
            'ngay_sinh' => $this->request->getPost('ngay_sinh'),
            'gioi_tinh' => $this->request->getPost('gioi_tinh'),
            'email' => $this->request->getPost('email')
        ];
        $student->save($data);
        $data = ['status'=>'Student Inserted Successfully'];
        return $this->response->setJSON($data);

    }

    public function updateStudent()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        if ($this->request->isAJAX()) {
            $data = $this->request->getJSON();
            
            $model = new HocVienModel();
            $result = $model->updateHocVien($data);

            return $this->response->setJSON($result);
        }
    }

    public function deleteStudent()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
    
        if ($this->request->isAJAX()) {
            $data = $this->request->getJSON();
            
            $model = new HocVienModel();
            $result = $model->deleteHocVien($data->id_hoc_vien);

            return $this->response->setJSON($result);
        }
    }
}





