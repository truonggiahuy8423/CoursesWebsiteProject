<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\LoginController;
use App\Models\BuoiHocModel;
use App\Models\ClassModel;
use App\Models\diem_danhModel;
use App\Models\GiangVienModel;
use App\Models\HocVienModel;
use App\Models\LecturersModel;
use App\Models\LopModel;
use App\Models\MonHocModel;
use App\Models\phan_cong_giang_vienModel;
use App\Models\PhongModel;
use App\Models\UserModel;
use PHPUnit\Util\Json;
use DateTime;

class CoursesController extends BaseController
{
    public static function compareCoursesByBeginDate($a, $b)
    {
        $datetime_a = DateTime::createFromFormat('d/m/Y', $a['ngay_bat_dau']);
        $datetime_b = DateTime::createFromFormat('d/m/Y', $b['ngay_bat_dau']);

        if ($datetime_a < $datetime_b) {
            return 1;
        } else if ($datetime_a > $datetime_b) {
            return -1;
        } else {
            return 0;
        }
    }
    public function getListOfCourses()
    {
        $model = new UserModel();
        $courses = $model->executeCustomQuery(
            "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
            FROM lop_hoc 
            INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc"
        );
        for ($i = 0; $i < count($courses); $i++) {
            $courses[$i]['lecturers'] = $model->executeCustomQuery(
                "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten
                FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                WHERE phan_cong_giang_vien.id_lop_hoc = {$courses[$i]['id_lop_hoc']};"
            );
        }
        usort($courses, [$this, 'compareCoursesByBeginDate']);
        return $this->response->setJSON($courses);
    }
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
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";

            $courses = $model->executeCustomQuery(
                "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
                FROM lop_hoc 
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc"
            );
            for ($i = 0; $i < count($courses); $i++) {
                $courses[$i]['lecturers'] = $model->executeCustomQuery(
                    "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten
                    FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                    WHERE phan_cong_giang_vien.id_lop_hoc = {$courses[$i]['id_lop_hoc']};"
                );
            }
            usort($courses, [$this, 'compareCoursesByBeginDate']);
            $courses_list_section_layout_data['courses'] = $courses;
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            $main_layout_data['mainsection'] = view('Admin\ViewLayout\CoursesListSectionLayout', $courses_list_section_layout_data);
            return view('Admin\ViewLayout\MainLayout', $main_layout_data);
        } else if (session()->get('role') == 2) { // Giang vien

        } else if (session()->get('role') == 3) { // Hoc vien

        }


        // return view('ClassPage');
    }

    public function information()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new UserModel();
        $navbar_data = array();
        $id_lop_hoc = null;
        if (isset($_GET)) {
            $id_lop_hoc = $_GET['courseid'];
        }
        $model = new LopModel();
        $course = $model->executeCustomQuery(
            " SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc, COUNT(hoc_vien_tham_gia.id_hoc_vien) as so_luong_hoc_vien 
            FROM lop_hoc 
            INNER JOIN mon_hoc ON lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc 
            LEFT JOIN hoc_vien_tham_gia ON lop_hoc.id_lop_hoc = hoc_vien_tham_gia.id_lop_hoc  
            WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc}
            GROUP BY lop_hoc.id_lop_hoc, lop_hoc.ngay_bat_dau, lop_hoc.ngay_ket_thuc, lop_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc;"
        );
        $isExist = count($course) > 0 ? true : false;
        if (!$isExist) {
            return view("CommonViewCell\ClassNotFound");
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


            $model = new LopModel();
            // $result = $model->executeCustomQuery('');
            $left_menu_data = array();
            $course_id_mon_hoc = str_pad($course[0]["id_mon_hoc"], 3, "0", STR_PAD_LEFT);
            $course_id_lop_hoc = str_pad($course[0]["id_lop_hoc"], 6, "0", STR_PAD_LEFT);
            $left_menu_data["class_name"] = $course[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
            $left_menu_data["student_quantity"] = $course[0]["so_luong_hoc_vien"];
            $left_menu_data["state"] = $this->kiem_tra_tinh_trang($course[0]["ngay_bat_dau"], $course[0]["ngay_ket_thuc"]);
            $left_menu_data["id_lop_hoc"] = $course[0]["id_lop_hoc"];
            $main_layout_data['leftmenu'] = view('Admin\ViewCell\LeftMenuInCourseDetail', $left_menu_data);


            $course[0]["so_luong_giang_vien"] = $model->executeCustomQuery(
                "SELECT COUNT(phan_cong_giang_vien.id_giang_vien) as slgv FROM lop_hoc LEFT JOIN phan_cong_giang_vien ON lop_hoc.id_lop_hoc = phan_cong_giang_vien.id_lop_hoc
                WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc}
                GROUP BY lop_hoc.id_lop_hoc"
            )[0]["slgv"];
            $course[0]["so_luong_buoi_hoc"] = $model->executeCustomQuery(
                "SELECT COUNT(buoi_hoc.id_buoi_hoc) as slbh FROM lop_hoc LEFT JOIN buoi_hoc ON lop_hoc.id_lop_hoc = buoi_hoc.id_lop_hoc
                WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc}
                GROUP BY lop_hoc.id_lop_hoc"
            )[0]["slbh"];
            $course[0]["so_luong_buoi_hoc_da_hoc"] = $model->executeCustomQuery(
                "SELECT COUNT(buoi_hoc.id_buoi_hoc) as slbh FROM lop_hoc LEFT JOIN buoi_hoc ON lop_hoc.id_lop_hoc = buoi_hoc.id_lop_hoc and buoi_hoc.trang_thai = 1
                    WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc} 
                    GROUP BY lop_hoc.id_lop_hoc"
            )[0]["slbh"];
            $course[0]["danh_sach_giang_vien"] = $model->executeCustomQuery(
                "SELECT giang_vien.* FROM phan_cong_giang_vien INNER JOIN giang_vien on phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
            );
            $course[0]["danh_sach_hoc_vien"] = $model->executeCustomQuery(
                "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
                WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
            );
            for ($i = 0; $i< count($course[0]["danh_sach_hoc_vien"]); $i++) {
                $sbv = $model->executeCustomQuery(
                    "SELECT COUNT(buoi_hoc.id_buoi_hoc) as so_buoi_vang FROM buoi_hoc INNER JOIN diem_danh ON buoi_hoc.id_buoi_hoc = diem_danh.id_buoi_hoc WHERE buoi_hoc.id_lop_hoc = {$course[0]["id_lop_hoc"]} AND diem_danh.id_hoc_vien = {$course[0]["danh_sach_hoc_vien"][$i]["id_hoc_vien"]} AND buoi_hoc.trang_thai = 1 AND diem_danh.co_mat = 1;"
                )[0]["so_buoi_vang"];
                $course[0]["danh_sach_hoc_vien"][$i]["so_buoi_vang"] = $sbv;
            }
            $course[0]["danh_sach_buoi_hoc"] = $model->executeCustomQuery(
                "SELECT buoi_hoc.id_buoi_hoc, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') as ngay_hoc, DAYOFWEEK(buoi_hoc.ngay) as thu, buoi_hoc.trang_thai, buoi_hoc.id_phong, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc};"
            );
            $main_layout_data['class_name'] = $left_menu_data["class_name"];
            // SELECT * FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca WHERE buoi_hoc.id_lop_hoc = 110 ORDER BY id_buoi_hoc DESC
            $main_layout_data['contentsection'] = view('Admin\ViewLayout\CourseInformationSectionLayout', $course[0]);
            return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
        } else if (session()->get('role') == 2) { // Giang vien

        } else if (session()->get('role') == 3) { // Hoc vien

        }
    }

    public function getCoursesListSection()
    {
        $model = new UserModel();
        $courses = $model->executeCustomQuery(
            "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
            FROM lop_hoc 
            INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc"
        );
        for ($i = 0; $i < count($courses); $i++) {
            $courses[$i]['lecturers'] = $model->executeCustomQuery(
                "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten
                FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                WHERE phan_cong_giang_vien.id_lop_hoc = {$courses[$i]['id_lop_hoc']};"
            );
        }
        usort($courses, [$this, 'compareCoursesByBeginDate']);
        return $this->response->setJSON($courses);
    }

    public function getInsertForm()
    {
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
    public function deleteCourse()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $data = json_decode(json_encode($this->request->getJSON()), true);
        // $data = ["courses" => [106, 2, 5]];
        $courses = $data["courses"];
        $response = array();
        for ($i = 0; $i < count($courses); $i++) {
            $model = new LopModel();
            $response[$courses[$i]] = $model->deleteLop($courses[$i]);
        }

        return $this->response->setJSON($response);
    }
    public function a($id, $b, $c)
    {
        $model = new LopModel();
        return implode($model->insertLop2($id, $b, $c));
    }
    // public function h() {
    //     $model = new phan_cong_giang_vienModel();
    //     return implode($model);
    // }
    public function g()
    {
        $model = new GiangVienModel();
        return $model->getAllGiangViens()[0]->ho_ten;
    }
    public function kiem_tra_tinh_trang($ngay_bat_dau, $ngay_ket_thuc)
    {
        $datetime_bat_dau = DateTime::createFromFormat('d/m/Y', $ngay_bat_dau);

        $datetime_ket_thuc =  DateTime::createFromFormat('d/m/Y', $ngay_ket_thuc);

        $datetime_hien_tai = new DateTime();

        $datetime_bat_dau->setTime(0, 0, 0);
        $datetime_ket_thuc->setTime(23, 59, 59); // Đặt giờ, phút và giây về cuối ngày
        // echo $ngay_bat_dau.$ngay_ket_thuc;
        // echo $datetime_bat_dau->format("Y");
        // So sánh
        if ($datetime_bat_dau <= $datetime_hien_tai && $datetime_ket_thuc >= $datetime_hien_tai) {
            return '<span class="class__item--inprocess">Đang diễn ra</span>';
        } elseif ($datetime_ket_thuc < $datetime_hien_tai) {
            return '<span class="class__item--over">Đã kết thúc</span>';
        } else {
            return '<span class="class__item--upcoming">Sắp diễn ra</span>';
        }
    }
    public function fillData()
    {
        set_time_limit(0);
        $id_ca_array = array(1, 2, 3);
        $phong_array = (new PhongModel())->getAllPhong();
        $id_phong_array = array();
        foreach ($phong_array as $phong) {
            $id_phong_array[] = $phong->id_phong;
        }
        $this->generateClassSchedule("2022-01-01", "2026-01-01", $id_phong_array, $id_ca_array);
    }
    public function fillData2() {
        $model = new BuoiHocModel();
        $model2 = new HocVienModel();
        $dsbh = $model->executeCustomQuery(
            "SELECT * FROM buoi_hoc WHERE buoi_hoc.id_lop_hoc = 110;
        ");
        $dshv = $model2->executeCustomQuery(
            "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
            WHERE hoc_vien_tham_gia.id_lop_hoc = 110;"
        );
        foreach ($dsbh as $bh) {
            foreach($dshv as $hv) {
                $diem_danh = new diem_danhModel();
                $diem_danh->id_buoi_hoc = $bh['id_buoi_hoc'];
                $diem_danh->id_hoc_vien = $hv['id_hoc_vien'];
                $diem_danh->ghi_chu = "";
                $diem_danh->co_mat = 0;
                $diem_danh->insertdiem_danhModel($diem_danh);
            }
        }

    }

    function generateClassSchedule($startDate, $endDate, $roomIds, $caIds)
    {
        // Iterate through the date range
        $currentDate = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        while ($currentDate <= $endDateTime) {
            $currentDateString = $currentDate->format('Y-m-d');
            //echo $currentDateString;
            //Iterate through rooms
            foreach ($roomIds as $roomId) {
                // Iterate through time slots
                foreach ($caIds as $caId) {
                    // Generate and execute the SQL query to insert into buoi_hoc table
                    $buoihoc = new BuoiHocModel();
                    $buoihoc->trang_thai = 0;
                    $buoihoc->ngay = $currentDateString;
                    $buoihoc->id_lop_hoc = NULL;
                    $buoihoc->id_ca = $caId;
                    $buoihoc->id_phong = $roomId;
                    $buoihoc->insertBuoiHoc($buoihoc);
                }
            }
            // Move to the next date
            $currentDate->modify('+1 day');
        }
    }
    public function insertCourse()
    {
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

    public function insertLecturersIntoClass()
    {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Process
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $id_lop_hoc = $courseData['id_lop_hoc'];
        $lecturer_id_list = $courseData['lecturer_id_list'];

        $model = new phan_cong_giang_vienModel();

        $processedResult = array();
        foreach ($lecturer_id_list as $id => $name) {
            $data = new phan_cong_giang_vienModel();
            $data->id_giang_vien = $id;
            $data->id_lop_hoc = $id_lop_hoc;
            $processedResult["$name" . "($id)"] = $model->insertphan_cong_giang_vien($data); // gọi PhanCongGiangVienModel
        }
        return $this->response->setJSON($processedResult);
    }
}
