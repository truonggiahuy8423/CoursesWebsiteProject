<?php

namespace App\Controllers\Admin;
use Illuminate\Http\Request;
use App\Controllers\BaseController;
use App\Controllers\LoginController;
use App\Models\BaiNopModel;
use App\Models\BaiTapModel;
use App\Models\BuoiHocModel;
use App\Models\CaModel;
use App\Models\LopModels;
use App\Models\chi_tiet_bai_nopModel;
use App\Models\ClassModel;
use App\Models\diem_danhModel;
use Config\Paths;
use App\Models\FileUploadModel;
use App\Models\GiangVienModel;
use App\Models\hoc_vien_tham_giaModel;
use App\Models\HocVienModel;
use App\Models\LecturersModel;
use App\Models\LinkModel;
use App\Models\LopModel;
use App\Models\MonHocModel;
use App\Models\MucModel;
use App\Models\phan_cong_giang_vienModel;
use App\Models\PhongModel;
use App\Models\ThongBaoModel;
use App\Models\UserModel;
use App\Models\vi_tri_tep_tinModel;
use CodeIgniter\Files\File;
use PHPUnit\Util\Json;
use DateTime;


class CoursesController extends BaseController
{
    private string $path;
    public function __construct()
    {
        $this->path = (new Paths())->filesPath;
    }
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
    public function getListOfSubjects()
    {
        $model = new MonHocModel();
        $subjects = $model->executeCustomQuery(
            "SELECT * FROM mon_hoc"
        );
        return $this->response->setJSON($subjects);
    }
    public function updateCourse()
    {
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $course = new LopModel();
        $course->id_lop_hoc = $courseData['id_lop_hoc'];
        $course->ngay_bat_dau = $courseData['ngay_bat_dau'];
        $course->ngay_ket_thuc = $courseData['ngay_ket_thuc'];
        $course->id_mon_hoc = $courseData['id_mon_hoc'];
        // return $this->response->setJSON($course);
        return $this->response->setJSON($course->updateLop($course));
    }
    public function updateAssignment()
    {
        $assignmentData = json_decode(json_encode($this->request->getJSON()), true);
        // echo $assignmentData["id_bai_tap"];
        // return $this->response->setJSON(["state" => false, "message" => "ok".$assignmentData["id_bai_tap"]]);
        $asm = new BaiTapModel();
        $asm->id_bai_tap = $assignmentData['id_bai_tap'];
        $asm->thoi_han_nop = $assignmentData['thn'];
        $asm->thoi_han = $assignmentData['th'];
        $asm->ten = $assignmentData['ten'];
        $asm->noi_dung = $assignmentData['noi_dung'];

        // return $this->response->setJSON($course);
        return $this->response->setJSON($asm->updateBaiTap($asm));
    }
    public function deleteLecturerFromCourse()
    {
        $id_lop_hoc = $this->request->getVar('id_lop_hoc');
        $id_giang_vien = $this->request->getVar('id_giang_vien');
        $model = new phan_cong_giang_vienModel();
        $model->id_giang_vien = $id_giang_vien;
        $model->id_lop_hoc = $id_lop_hoc;
        return $this->response->setJSON($model->deletephan_cong_giang_vien($model));
    }
    public function getListOfCourses()
    {
        $model = new LopModel();
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
    public function ok()
    {
        echo "kỳ z";
        echo session()->get('id_role');
    }
    public function getListOfCoursesForTeacher()
    {


        $id_giang_vien = session()->get('id_role');

        $model = new LopModel();
        $courses = $model->executeCustomQuery(
            "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
            FROM lop_hoc 
            INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc where lop_hoc.id_lop_hoc in (select phan_cong_giang_vien.id_lop_hoc from phan_cong_giang_vien where phan_cong_giang_vien.id_giang_vien = $id_giang_vien)"
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
    public function getListOfCoursesForStudent()
    {


        $id_hoc_vien = session()->get('id_role');

        $model = new LopModel();
        $courses = $model->executeCustomQuery(
            "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
                FROM lop_hoc 
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc where lop_hoc.id_lop_hoc in (select hoc_vien_tham_gia.id_lop_hoc from hoc_vien_tham_gia where hoc_vien_tham_gia.id_hoc_vien = $id_hoc_vien)"
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
    public function getListOfLecturersByCourseId()
    {
        // $id_lop_hoc = 110;
        $id_lop_hoc = $this->request->getVar("id");
        $model = new GiangVienModel();
        $lecturers = $model->executeCustomQuery(
            "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten, giang_vien.email
                FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
        );
        return $this->response->setJSON($lecturers);
    }
    public function getListOfStudentsByCourseId()
    {
        // $id_lop_hoc = 110;
        $id_lop_hoc = $this->request->getVar("id");
        $model = new GiangVienModel();

        $danh_sach_hoc_vien = $model->executeCustomQuery(
            "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
            WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
        );
        for ($i = 0; $i < count($danh_sach_hoc_vien); $i++) {
            $sbv = $model->executeCustomQuery(
                "SELECT COUNT(buoi_hoc.id_buoi_hoc) as so_buoi_vang FROM buoi_hoc INNER JOIN diem_danh ON buoi_hoc.id_buoi_hoc = diem_danh.id_buoi_hoc
                WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc} AND diem_danh.id_hoc_vien = {$danh_sach_hoc_vien[$i]["id_hoc_vien"]} AND buoi_hoc.trang_thai = 2 AND diem_danh.co_mat = 0;"
            )[0]["so_buoi_vang"];
            $danh_sach_hoc_vien[$i]["so_buoi_vang"] = $sbv;
        }

        return $this->response->setJSON($danh_sach_hoc_vien);
    }
    public function active()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id = session()->get('id_user');
        $model = new UserModel();
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Lấy thời gian hiện tại
        $current_time = date('Y-m-d H:i:s');
        $result = $model->executeCustomDDL(
            "UPDATE users SET thoi_gian_dang_nhap_gan_nhat = '{$current_time}' where users.id_user = $id"
        );
        return $this->response->setJSON($result);

    }
    
    public function getFile2()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar('id_lop_hoc');
        $id_user = session()->get('id_user');
        $id_file = $this->request->getVar('id_file');
        $assignmentid = $this->request->getVar('id_bai_tap');

        // $result = ["state" => false, "message" => $id_lop_hoc."/".$id_file."/".$assignmentid];
        // return $this->response->setJSON($result);
        if (!is_numeric($id_lop_hoc) || !is_numeric($id_file)) {
            // $result = ["state" => false, "message" => "Here"];
            // return $this->response->setJSON($result);
            $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
            return $this->response->setJSON($result);
        }
        // Check xem id có quyền hạn trong lớp hay không
        $userModel = new UserModel();
        $user = $userModel->getUserById($id_user);
        $isHasPermission = false;
        if ($user->id_ad != null) {
            $isHasPermission = true;
        } else if ($user->id_giang_vien != null) {
            $pcgv = new GiangVienModel();
            $lecturers = $pcgv->executeCustomQuery(
                "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten, giang_vien.email
                    FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                    WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
            );
            foreach ($lecturers as $lec) {
                if ($lec["id_giang_vien"] == $user->id_giang_vien) {
                    $isHasPermission = true;
                    break;
                }
            }
        } else if ($user->id_hoc_vien != null) {

        }

        if ($isHasPermission) {
            $accessFile = false;
            $model = new LopModel();
            $rs = $model->executeCustomQuery(
                "SELECT * FROM bai_tap INNER JOIN muc on bai_tap.id_muc = muc.id_muc WHERE bai_tap.id_bai_tap = $assignmentid and muc.id_lop_hoc = $id_lop_hoc;"
                // "SELECT vi_tri_tep_tin.id_tep_tin_tai_len FROM lop_hoc INNER JOIN muc on lop_hoc.id_lop_hoc = muc.id_lop_hoc INNER JOIN vi_tri_tep_tin on muc.id_muc = vi_tri_tep_tin.id_muc WHERE lop_hoc.id_lop_hoc = $id_lop_hoc;"
            );
            if (count($rs) == 0) {
                $result = ["state" => false, "message" => "Here"];
                return $this->response->setJSON($result);
                $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
                return $this->response->setJSON($result);
            }
            $model = new chi_tiet_bai_nopModel();
            $dstt = $model->executeCustomQuery(
                "SELECT DISTINCT chi_tiet_bai_nop.id_tep_tin_tai_len FROM bai_tap INNER JOIN bai_nop on bai_tap.id_bai_tap = bai_nop.id_bai_tap INNER JOIN chi_tiet_bai_nop on bai_nop.id_bai_nop = chi_tiet_bai_nop.id_bai_nop WHERE bai_tap.id_bai_tap = $assignmentid"
            );
            foreach ($dstt as $tt) {
                if ($tt["id_tep_tin_tai_len"] == $id_file) {
                    $accessFile = true;
                    break;
                }
            }
            

            if ($accessFile) {
                $model = new FileUploadModel();
                $file = $model->getFileUploadById($id_file);
                if ($file == null) {
                    $result = ["state" => false, "message" => "Tài nguyên không tồn tại!"];
                    return $this->response->setJSON($result);
                } else {
                    $FilePath = $this->path . "/{$file->du_lieu}.{$file->extension}";
                    // $result = ["state" => false, "message" => "Here"];
                    // return $this->response->setJSON($result);
                    if (file_exists($FilePath)) {
                        // $result = ["state" => false, "message" => "exist!"];
                        // $result = ["state" => false, "message" => "Here"];

                        // return $this->response->setJSON($result);
                        // Đặt tên file khi tải về

                        $result = ["state" => true, "data" => base64_encode(file_get_contents($FilePath)), "nameFile" => $file->ten_tep, "extension" => $file->extension]; // Lỗi ở đây, file_get_contents
                        return $this->response->setJSON($result);
                    } else {
                        $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                        return $this->response->setJSON($result);
                        // $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                    }
                }
            } else {
                
                $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                return $this->response->setJSON($result);
            }
        } else {
            
            $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
            return $this->response->setJSON($result);
        }
        // Check xem file cần get có được đăng vào lớp hay không

    }
    public function getFile()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar('id_lop_hoc');
        $id_user = session()->get('id_user');
        $id_file = $this->request->getVar('id_file');

        // Check xem id có quyền hạn trong lớp hay không
        $userModel = new UserModel();
        $user = $userModel->getUserById($id_user);
        $isHasPermission = false;
        if ($user->id_ad != null) {
            $isHasPermission = true;
        } else if ($user->id_giang_vien != null) {
            $pcgv = new GiangVienModel();
            $lecturers = $pcgv->executeCustomQuery(
                "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten, giang_vien.email
                    FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                    WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
            );
            foreach ($lecturers as $lec) {
                if ($lec["id_giang_vien"] == $user->id_giang_vien) {
                    $isHasPermission = true;
                    break;
                }
            }
        } else if ($user->id_hoc_vien != null) {
            $hvs = new HocVienModel();
            $students = $hvs->executeCustomQuery(
                "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
                WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
            );
            foreach ($students as $stu) {
                if ($stu["id_hoc_vien"] == $user->id_hoc_vien) {
                    $isHasPermission = true;
                    break;
                }
            }
        }

        if ($isHasPermission) {
            $accessFile = false;
            $model = new LopModel();
            $dstt = $model->executeCustomQuery(
                "SELECT vi_tri_tep_tin.id_tep_tin_tai_len FROM lop_hoc INNER JOIN muc on lop_hoc.id_lop_hoc = muc.id_lop_hoc INNER JOIN vi_tri_tep_tin on muc.id_muc = vi_tri_tep_tin.id_muc WHERE lop_hoc.id_lop_hoc = $id_lop_hoc;"
            );
            foreach ($dstt as $tt) {
                if ($tt["id_tep_tin_tai_len"] == $id_file) {
                    $accessFile = true;
                    break;
                }
            }
            if ($accessFile) {
                $model = new FileUploadModel();
                $file = $model->getFileUploadById($id_file);
                if ($file == null) {
                    $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                    return $this->response->setJSON($result);
                } else {
                    $FilePath = $this->path . "/{$file->du_lieu}.{$file->extension}";
                    // $result = ["state" => false, "message" => $file->du_lieu];
                    // return $this->response->setJSON($result);
                    if (file_exists($FilePath)) {
                        // $result = ["state" => false, "message" => "exist!"];
                        // return $this->response->setJSON($result);
                        // Đặt tên file khi tải về

                        $result = ["state" => true, "data" => base64_encode(file_get_contents($FilePath)), "nameFile" => $file->ten_tep, "extension" => $file->extension]; // Lỗi ở đây, file_get_contents
                        return $this->response->setJSON($result);
                    } else {
                        $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                        return $this->response->setJSON($result);
                        // $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                    }
                }
            } else {
                $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                return $this->response->setJSON($result);
            }
        } else {
            $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
            return $this->response->setJSON($result);
        }
        // Check xem file cần get có được đăng vào lớp hay không

    }

    
    // public function getFile2()
    // {
    //     if (!session()->has('id_user')) {
    //         return redirect()->to('/');
    //     }
    //     $id_lop_hoc = $this->request->getVar('id_lop_hoc');
    //     $id_user = session()->get('id_user');
    //     $id_file = $this->request->getVar('id_file');
    //     $assignmentid = $this->request->getVar('id_bai_tap');

    //     // $result = ["state" => false, "message" => $id_lop_hoc."/".$id_file."/".$assignmentid];
    //     // return $this->response->setJSON($result);
    //     if (!is_numeric($id_lop_hoc) || !is_numeric($id_file)) {
    //         // $result = ["state" => false, "message" => "Here"];
    //         // return $this->response->setJSON($result);
    //         $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
    //         return $this->response->setJSON($result);
    //     }
    //     // Check xem id có quyền hạn trong lớp hay không
    //     $userModel = new UserModel();
    //     $user = $userModel->getUserById($id_user);
    //     $isHasPermission = false;
    //     if ($user->id_ad != null) {
    //         $isHasPermission = true;
    //     } else if ($user->id_giang_vien != null) {
    //         $pcgv = new GiangVienModel();
    //         $lecturers = $pcgv->executeCustomQuery(
    //             "SELECT giang_vien.id_giang_vien, giang_vien.ho_ten, giang_vien.email
    //                 FROM phan_cong_giang_vien INNER JOIN giang_vien ON phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
    //                 WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
    //         );
    //         foreach ($lecturers as $lec) {
    //             if ($lec["id_giang_vien"] == $user->id_giang_vien) {
    //                 $isHasPermission = true;
    //                 break;
    //             }
    //         }
    //     } else if ($user->id_hoc_vien != null) {

    //     }

    //     if ($isHasPermission) {
    //         $accessFile = false;
    //         $model = new LopModel();
    //         $rs = $model->executeCustomQuery(
    //             "SELECT * FROM bai_tap INNER JOIN muc on bai_tap.id_muc = muc.id_muc WHERE bai_tap.id_bai_tap = $assignmentid and muc.id_lop_hoc = $id_lop_hoc;"
    //             // "SELECT vi_tri_tep_tin.id_tep_tin_tai_len FROM lop_hoc INNER JOIN muc on lop_hoc.id_lop_hoc = muc.id_lop_hoc INNER JOIN vi_tri_tep_tin on muc.id_muc = vi_tri_tep_tin.id_muc WHERE lop_hoc.id_lop_hoc = $id_lop_hoc;"
    //         );
    //         if (count($rs) == 0) {
    //             $result = ["state" => false, "message" => "Here"];
    //             return $this->response->setJSON($result);
    //             $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
    //             return $this->response->setJSON($result);
    //         }
    //         $model = new chi_tiet_bai_nopModel();
    //         $dstt = $model->executeCustomQuery(
    //             "SELECT DISTINCT chi_tiet_bai_nop.id_tep_tin_tai_len FROM bai_tap INNER JOIN bai_nop on bai_tap.id_bai_tap = bai_nop.id_bai_tap INNER JOIN chi_tiet_bai_nop on bai_nop.id_bai_nop = chi_tiet_bai_nop.id_bai_nop WHERE bai_tap.id_bai_tap = $assignmentid"
    //         );
    //         foreach ($dstt as $tt) {
    //             if ($tt["id_tep_tin_tai_len"] == $id_file) {
    //                 $accessFile = true;
    //                 break;
    //             }
    //         }
            

    //         if ($accessFile) {
    //             $model = new FileUploadModel();
    //             $file = $model->getFileUploadById($id_file);
    //             if ($file == null) {
    //                 $result = ["state" => false, "message" => "Tài nguyên không tồn tại!"];
    //                 return $this->response->setJSON($result);
    //             } else {
    //                 $FilePath = $this->path . "/{$file->du_lieu}.{$file->extension}";
    //                 // $result = ["state" => false, "message" => "Here"];
    //                 // return $this->response->setJSON($result);
    //                 if (file_exists($FilePath)) {
    //                     // $result = ["state" => false, "message" => "exist!"];
    //                     // $result = ["state" => false, "message" => "Here"];

    //                     // return $this->response->setJSON($result);
    //                     // Đặt tên file khi tải về

    //                     $result = ["state" => true, "data" => base64_encode(file_get_contents($FilePath)), "nameFile" => $file->ten_tep, "extension" => $file->extension]; // Lỗi ở đây, file_get_contents
    //                     return $this->response->setJSON($result);
    //                 } else {
    //                     $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
    //                     return $this->response->setJSON($result);
    //                     // $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
    //                 }
    //             }
    //         } else {
                
    //             $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
    //             return $this->response->setJSON($result);
    //         }
    //     } else {
            
    //         $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
    //         return $this->response->setJSON($result);
    //     }
    //     // Check xem file cần get có được đăng vào lớp hay không

    // }
    public function getFile3()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // $id_lop_hoc = $this->request->getVar('id_lop_hoc');
        $id_user = session()->get('id_user');
        $id_file = $this->request->getVar('id_file');
        // $model = new FileUploadModel();
        if (!is_numeric($id_file)) {
            $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
            return $this->response->setJSON($result);
        }

        $model = new FileUploadModel();
        $file = $model->getFileUploadById($id_file);
        if ($file == null || $file->id_user != $id_user) {
            $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
            return $this->response->setJSON($result);
        } else {
            $FilePath = $this->path . "/{$file->du_lieu}.{$file->extension}";
            if (file_exists($FilePath)) {
                $result = ["state" => true, "data" => base64_encode(file_get_contents($FilePath)), "nameFile" => $file->ten_tep, "extension" => $file->extension]; // Lỗi ở đây, file_get_contents
                return $this->response->setJSON($result);
            } else {
                $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
                return $this->response->setJSON($result);
                // $result = ["state" => false, "message" => "Tài nguyên không tồn tại hoặc bạn không có quyền hạn trên tài nguyên này!"];
            }
        }
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
            $result = $model->executeCustomQuery(
                'SELECT giang_vien.ho_ten, giang_vien.id_giang_vien, users.anh_dai_dien
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Giảng viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            // courses
            $courses = $model->executeCustomQuery(
                "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
                FROM lop_hoc 
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc where lop_hoc.id_lop_hoc in (select phan_cong_giang_vien.id_lop_hoc from phan_cong_giang_vien where phan_cong_giang_vien.id_giang_vien = {$result[0]['id_giang_vien']})"
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
            $main_layout_data['mainsection'] = view('Teacher\ViewLayout\CoursesListSectionLayout', $courses_list_section_layout_data);
            return view('Teacher\ViewLayout\MainLayout', $main_layout_data);
        } else if (session()->get('role') == 3) { // Hoc vien
            $result = $model->executeCustomQuery(
                'SELECT hoc_vien.ho_ten, hoc_vien.id_hoc_vien, users.anh_dai_dien
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Học viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
            $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
            // courses
            $courses = $model->executeCustomQuery(
                "SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc
                FROM lop_hoc 
                INNER JOIN mon_hoc on lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc where lop_hoc.id_lop_hoc in (select hoc_vien_tham_gia.id_lop_hoc from hoc_vien_tham_gia where hoc_vien_tham_gia.id_hoc_vien = {$result[0]['id_hoc_vien']})"
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
            $main_layout_data['mainsection'] = view('Student\ViewLayout\CoursesListSectionLayout', $courses_list_section_layout_data);
            return view('Student\ViewLayout\MainLayout', $main_layout_data);
        }


        // return view('ClassPage');
    }
    public function getAssignmentInformation() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // assignmentid: assignmentid * 1
        $assignmentid = null;
        if (isset($_GET)) {
            $assignmentid = $_GET['assignmentid'];
        } else {
            return redirect()->to('/courses');
        }
        if (!is_numeric($assignmentid)) {
            return redirect()->to('/courses');
        }
        $assignmentModel = new BaiTapModel();
        $assignment = $assignmentModel->executeCustomQuery(
            "SELECT id_bai_tap, thoi_han_nop, ten, noi_dung, thoi_han, id_giang_vien, id_muc, ngay_dang FROM bai_tap WHERE bai_tap.id_bai_tap = $assignmentid"
        );
        if (count($assignment) == 0) {
            $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
            return $this->response->setJSON($result);
        }
        $muc = (new MucModel())->getMucById($assignment[0]["id_muc"]);
        $model = new BaiNopModel();
        $assignment[0]["submits"] = $model->executeCustomQuery(
            "SELECT bai_nop.id_bai_nop, 
                bai_nop.thoi_gian_nop,
                bai_nop.id_bai_tap, hoc_vien.id_hoc_vien, hoc_vien.ho_ten FROM hoc_vien inner join hoc_vien_tham_gia on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
                LEFT JOIN bai_nop ON bai_nop.id_hoc_vien = hoc_vien.id_hoc_vien and bai_nop.id_bai_tap = $assignmentid WHERE hoc_vien_tham_gia.id_lop_hoc = $muc->id_lop_hoc;"
        );

        $model = new chi_tiet_bai_nopModel();
        for ($i = 0; $i < count($assignment[0]["submits"]); $i++) {
            if ($assignment[0]['submits'][$i]['id_bai_nop'] != null) {
                $assignment[0]["submits"][$i]["files"] = $model->executeCustomQuery(
                    "SELECT tep_tin_tai_len.* FROM chi_tiet_bai_nop INNER JOIN tep_tin_tai_len on chi_tiet_bai_nop.id_tep_tin_tai_len = tep_tin_tai_len.id_tep_tin_tai_len WHERE chi_tiet_bai_nop.id_bai_nop = {$assignment[0]['submits'][$i]['id_bai_nop']}
                        "
                );
            } else {
                $assignment[0]["submits"][$i]["files"] = [];
            }
        }
        return $this->response->setJSON($assignment[0]);        

    }
    
    public function getAssignmentInformationForStudent() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_hoc_vien = session()->get("id_role");
        
        // assignmentid: assignmentid * 1
        $assignmentid = null;
        if (isset($_GET)) {
            $assignmentid = $_GET['assignmentid'];
        } else {
            return redirect()->to('/courses');
        }
        if (!is_numeric($assignmentid)) {
            return redirect()->to('/courses');
        }
        $assignmentModel = new BaiTapModel();
        $assignment = $assignmentModel->executeCustomQuery(
            "SELECT id_bai_tap, thoi_han_nop, ten, noi_dung, thoi_han, id_giang_vien, id_muc, ngay_dang FROM bai_tap WHERE bai_tap.id_bai_tap = $assignmentid"
        );
        if (count($assignment) == 0) {
            $result = ["state" => false, "message" => "Đã có lỗi xảy ra!"];
            return $this->response->setJSON($result);
        }
        $model = new BaiNopModel();
        $assignment[0]["student_submit"] = ($model->executeCustomQuery(
            "SELECT * FROM bai_nop WHERE bai_nop.id_hoc_vien = $id_hoc_vien and  bai_nop.id_bai_tap = $assignmentid;
        "));
        if (count($assignment[0]["student_submit"]) > 0) {
            $assignment[0]["student_submit"] = $assignment[0]["student_submit"][0];
            // tep
            $model = new chi_tiet_bai_nopModel();
            $id_bai_nop = $assignment[0]['student_submit']['id_bai_nop'];
            $assignment[0]["student_submit"]["files"] = $model->executeCustomQuery(
                "SELECT tep_tin_tai_len.* FROM chi_tiet_bai_nop inner join tep_tin_tai_len on chi_tiet_bai_nop.id_tep_tin_tai_len = tep_tin_tai_len.id_tep_tin_tai_len where chi_tiet_bai_nop.id_bai_nop = $id_bai_nop
            ");
        } else {
            $assignment[0]["student_submit"] = null;
        }


        return $this->response->setJSON($assignment[0]);        

    }
    public function assignment()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $assignmentid = null;
        if (isset($_GET)) {
            $assignmentid = $_GET['assignmentid'];
        } else {
            return redirect()->to('/courses');
        }
        if (!is_numeric($assignmentid)) {
            return redirect()->to('/courses');
        }
        $assignmentModel = new BaiTapModel();
        $assignment = $assignmentModel->executeCustomQuery(
            "SELECT id_bai_tap, thoi_han_nop, ten, noi_dung, thoi_han, id_giang_vien, id_muc, ngay_dang FROM bai_tap WHERE bai_tap.id_bai_tap = $assignmentid"
        );


        if (count($assignment) == 0) {
            return view("CommonViewCell\ExceptionPage", ["message" => "Tài nguyên không tồn tại"]);
        }


        // Query data 
        $model = new UserModel();
        $navbar_data = array();
        $main_layout_data = array();
        $assignment_section_layout_data = array();

        $muc = (new MucModel())->getMucById($assignment[0]["id_muc"]);


        

        if (session()->get('role') == 1) { // Admin

        } else if (session()->get('role') == 2) { // Giang vien
            $id_giang_vien = session()->get('id_role');
            $model = new phan_cong_giang_vienModel();
            $result = $model->executeCustomQuery(
                "SELECT * FROM phan_cong_giang_vien WHERE phan_cong_giang_vien.id_giang_vien = {$id_giang_vien} AND phan_cong_giang_vien.id_lop_hoc = {$muc->id_lop_hoc}"
            );
            if (count($result) == 0) {
                return view("CommonViewCell\ExceptionPage", ["message" => "Tài nguyên không tồn tại hoặc bạn không có quyền truy cập tài nguyên lớp học này"]);
            }


            $course = $model->executeCustomQuery(
                " SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc, COUNT(hoc_vien_tham_gia.id_hoc_vien) as so_luong_hoc_vien 
                FROM lop_hoc 
                INNER JOIN mon_hoc ON lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc 
                LEFT JOIN hoc_vien_tham_gia ON lop_hoc.id_lop_hoc = hoc_vien_tham_gia.id_lop_hoc  
                WHERE lop_hoc.id_lop_hoc = {$muc->id_lop_hoc}
                GROUP BY lop_hoc.id_lop_hoc, lop_hoc.ngay_bat_dau, lop_hoc.ngay_ket_thuc, lop_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc;"
            );    

            $result = $model->executeCustomQuery(
                'SELECT giang_vien.ho_ten, giang_vien.id_giang_vien, users.anh_dai_dien
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Giảng viên';
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
    

            // query information about assignment
            // Here 
            


            $assignment_section_layout_data["assignment"] = $assignment[0];
            $assignment_section_layout_data["id_lop_hoc"] = $muc->id_lop_hoc;
            /////////////////////////////////////
            $main_layout_data['class_name'] = $left_menu_data["class_name"];
            $main_layout_data['contentsection'] = view('Teacher\ViewLayout\AssignmentSection', $assignment_section_layout_data);
            return view('Teacher\ViewLayout\CourseDetailLayout', $main_layout_data);
        } else if (session()->get('role') == 3) { // Hoc vien
            $id_hoc_vien = session()->get('id_role');
            $model = new phan_cong_giang_vienModel();
            $result = $model->executeCustomQuery(
                "SELECT * FROM hoc_vien_tham_gia WHERE hoc_vien_tham_gia.id_hoc_vien = {$id_hoc_vien} AND hoc_vien_tham_gia.id_lop_hoc = {$muc->id_lop_hoc}"
            );
            if (count($result) == 0) {
                return view("CommonViewCell\ExceptionPage", ["message" => "Tài nguyên không tồn tại hoặc bạn không có quyền truy cập tài nguyên lớp học này"]);
            }
            

            $course = $model->executeCustomQuery(
                " SELECT lop_hoc.id_lop_hoc,  DATE_FORMAT(lop_hoc.ngay_bat_dau, '%d/%m/%Y') as ngay_bat_dau,  DATE_FORMAT(lop_hoc.ngay_ket_thuc, '%d/%m/%Y') as ngay_ket_thuc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc, COUNT(hoc_vien_tham_gia.id_hoc_vien) as so_luong_hoc_vien 
                FROM lop_hoc 
                INNER JOIN mon_hoc ON lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc 
                LEFT JOIN hoc_vien_tham_gia ON lop_hoc.id_lop_hoc = hoc_vien_tham_gia.id_lop_hoc  
                WHERE lop_hoc.id_lop_hoc = {$muc->id_lop_hoc}
                GROUP BY lop_hoc.id_lop_hoc, lop_hoc.ngay_bat_dau, lop_hoc.ngay_ket_thuc, lop_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc;"
            );    

            $result = $model->executeCustomQuery(
                'SELECT hoc_vien.ho_ten, hoc_vien.id_hoc_vien, users.anh_dai_dien
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Học viên';
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
    

            // query information about assignment
            // Here 
            


            $assignment_section_layout_data["assignment"] = $assignment[0];
            $assignment_section_layout_data["id_lop_hoc"] = $muc->id_lop_hoc;
            /////////////////////////////////////
            $main_layout_data['class_name'] = $left_menu_data["class_name"];
            $main_layout_data['contentsection'] = view('Student\ViewLayout\AssignmentSection', $assignment_section_layout_data);
            return view('Student\ViewLayout\CourseDetailLayout', $main_layout_data);
        }
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
        } else {
            return redirect()->to('/courses');
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
            return view("CommonViewCell\ExceptionPage", ["message" => "Bạn không có quyền hạn ở lớp học này"]);
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
                "SELECT COUNT(buoi_hoc.id_buoi_hoc) as slbh FROM lop_hoc LEFT JOIN buoi_hoc ON lop_hoc.id_lop_hoc = buoi_hoc.id_lop_hoc and buoi_hoc.trang_thai = 2
                    WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc} 
                    GROUP BY lop_hoc.id_lop_hoc"
            )[0]["slbh"];
            // $course[0]["danh_sach_giang_vien"] = $model->executeCustomQuery(
            //     "SELECT giang_vien.* FROM phan_cong_giang_vien INNER JOIN giang_vien on phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
            //     WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
            // );
            // $course[0]["danh_sach_hoc_vien"] = $model->executeCustomQuery(
            //     "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
            //     WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
            // );
            // for ($i = 0; $i < count($course[0]["danh_sach_hoc_vien"]); $i++) {
            //     $sbv = $model->executeCustomQuery(
            //         "SELECT COUNT(buoi_hoc.id_buoi_hoc) as so_buoi_vang FROM buoi_hoc INNER JOIN diem_danh ON buoi_hoc.id_buoi_hoc = diem_danh.id_buoi_hoc WHERE buoi_hoc.id_lop_hoc = {$course[0]["id_lop_hoc"]} AND diem_danh.id_hoc_vien = {$course[0]["danh_sach_hoc_vien"][$i]["id_hoc_vien"]} AND buoi_hoc.trang_thai = 1 AND diem_danh.co_mat = 1;"
            //     )[0]["so_buoi_vang"];
            //     $course[0]["danh_sach_hoc_vien"][$i]["so_buoi_vang"] = $sbv;
            // }
            // $course[0]["danh_sach_buoi_hoc"] = $model->executeCustomQuery(
            //     "SELECT buoi_hoc.id_buoi_hoc, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') as ngay_hoc, DAYOFWEEK(buoi_hoc.ngay) as thu, buoi_hoc.trang_thai, buoi_hoc.id_phong, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc 
            //     FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc}
            //     ORDER BY buoi_hoc.ngay ASC, buoi_hoc.id_phong ASC, buoi_hoc.id_ca ASC;"
            // );
            $main_layout_data['class_name'] = $left_menu_data["class_name"];
            $main_layout_data['contentsection'] = view('Admin\ViewLayout\CourseInformationSectionLayout', $course[0]);
            return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
        } else if (session()->get('role') == 2) { // Giang vien
            // Kiểm tra xem giảng viên hay học viên có được truy cập vào lớp này hay không
            $id_giang_vien = session()->get('id_role');
            $model = new phan_cong_giang_vienModel();
            $result = $model->executeCustomQuery(
                "SELECT * FROM phan_cong_giang_vien WHERE phan_cong_giang_vien.id_giang_vien = {$id_giang_vien} AND phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc}"
            );
            if (count($result) == 0) {
                return view("CommonViewCell\ExceptionPage", ["message" => "Bạn không có quyền truy cập lớp học này"]);
            } else {
                $result = $model->executeCustomQuery(
                    'SELECT giang_vien.ho_ten, giang_vien.id_giang_vien, users.anh_dai_dien
                    FROM users
                    INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                    WHERE users.id_user = ' . session()->get("id_user")
                );
                $navbar_data['username'] = "{$result[0]['ho_ten']}";
                $navbar_data['role'] = 'Giảng viên';
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
                    "SELECT COUNT(buoi_hoc.id_buoi_hoc) as slbh FROM lop_hoc LEFT JOIN buoi_hoc ON lop_hoc.id_lop_hoc = buoi_hoc.id_lop_hoc and buoi_hoc.trang_thai = 2
                    WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc} 
                    GROUP BY lop_hoc.id_lop_hoc"
                )[0]["slbh"];
                // $course[0]["danh_sach_giang_vien"] = $model->executeCustomQuery(
                //     "SELECT giang_vien.* FROM phan_cong_giang_vien INNER JOIN giang_vien on phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                // WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
                // );
                // $course[0]["danh_sach_hoc_vien"] = $model->executeCustomQuery(
                //     "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
                // WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
                // );
                // for ($i = 0; $i < count($course[0]["danh_sach_hoc_vien"]); $i++) {
                //     $sbv = $model->executeCustomQuery(
                //         "SELECT COUNT(buoi_hoc.id_buoi_hoc) as so_buoi_vang FROM buoi_hoc INNER JOIN diem_danh ON buoi_hoc.id_buoi_hoc = diem_danh.id_buoi_hoc WHERE buoi_hoc.id_lop_hoc = {$course[0]["id_lop_hoc"]} AND diem_danh.id_hoc_vien = {$course[0]["danh_sach_hoc_vien"][$i]["id_hoc_vien"]} AND buoi_hoc.trang_thai = 1 AND diem_danh.co_mat = 1;"
                //     )[0]["so_buoi_vang"];
                //     $course[0]["danh_sach_hoc_vien"][$i]["so_buoi_vang"] = $sbv;
                // }
                // $course[0]["danh_sach_buoi_hoc"] = $model->executeCustomQuery(
                //     "SELECT buoi_hoc.id_buoi_hoc, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') as ngay_hoc, DAYOFWEEK(buoi_hoc.ngay) as thu, buoi_hoc.trang_thai, buoi_hoc.id_phong, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc 
                //     FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc}
                //     ORDER BY buoi_hoc.ngay ASC, buoi_hoc.id_phong ASC, buoi_hoc.id_ca ASC;"
                // );
                $main_layout_data['class_name'] = $left_menu_data["class_name"];
                $main_layout_data['contentsection'] = view('Teacher\ViewLayout\CourseInformationSectionLayout', $course[0]);
                return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
            }

            // Not yet
        } else if (session()->get('role') == 3) { // Hoc vien
            // Kiểm tra xem giảng viên hay học viên có được truy cập vào lớp này hay không
            // Not yet
            $id_hoc_vien = session()->get('id_role');
            $model = new UserModel();
            $result = $model->executeCustomQuery(
                "SELECT * FROM hoc_vien_tham_gia WHERE hoc_vien_tham_gia.id_hoc_vien = {$id_hoc_vien} AND hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc}"
            );
            if (count($result) == 0) {
                return view("CommonViewCell\ExceptionPage", ["message" => "Bạn không có quyền truy cập lớp học này"]);
            } else {
                $result = $model->executeCustomQuery(
                    'SELECT hoc_vien.ho_ten, hoc_vien.id_hoc_vien, users.anh_dai_dien
                    FROM users
                    INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien
                    WHERE users.id_user = ' . session()->get("id_user")
                );
                $navbar_data['username'] = "{$result[0]['ho_ten']}";
                $navbar_data['role'] = 'Học viên';
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
                    "SELECT COUNT(buoi_hoc.id_buoi_hoc) as slbh FROM lop_hoc LEFT JOIN buoi_hoc ON lop_hoc.id_lop_hoc = buoi_hoc.id_lop_hoc and buoi_hoc.trang_thai = 2
                    WHERE lop_hoc.id_lop_hoc = {$id_lop_hoc} 
                    GROUP BY lop_hoc.id_lop_hoc"
                )[0]["slbh"];
                // $course[0]["danh_sach_giang_vien"] = $model->executeCustomQuery(
                //     "SELECT giang_vien.* FROM phan_cong_giang_vien INNER JOIN giang_vien on phan_cong_giang_vien.id_giang_vien = giang_vien.id_giang_vien
                // WHERE phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc};"
                // );
                // $course[0]["danh_sach_hoc_vien"] = $model->executeCustomQuery(
                //     "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
                // WHERE hoc_vien_tham_gia.id_lop_hoc = {$id_lop_hoc};"
                // );
                // for ($i = 0; $i < count($course[0]["danh_sach_hoc_vien"]); $i++) {
                //     $sbv = $model->executeCustomQuery(
                //         "SELECT COUNT(buoi_hoc.id_buoi_hoc) as so_buoi_vang FROM buoi_hoc INNER JOIN diem_danh ON buoi_hoc.id_buoi_hoc = diem_danh.id_buoi_hoc WHERE buoi_hoc.id_lop_hoc = {$course[0]["id_lop_hoc"]} AND diem_danh.id_hoc_vien = {$course[0]["danh_sach_hoc_vien"][$i]["id_hoc_vien"]} AND buoi_hoc.trang_thai = 1 AND diem_danh.co_mat = 1;"
                //     )[0]["so_buoi_vang"];
                //     $course[0]["danh_sach_hoc_vien"][$i]["so_buoi_vang"] = $sbv;
                // }
                // $course[0]["danh_sach_buoi_hoc"] = $model->executeCustomQuery(
                //     "SELECT buoi_hoc.id_buoi_hoc, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') as ngay_hoc, DAYOFWEEK(buoi_hoc.ngay) as thu, buoi_hoc.trang_thai, buoi_hoc.id_phong, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc 
                //     FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc}
                //     ORDER BY buoi_hoc.ngay ASC, buoi_hoc.id_phong ASC, buoi_hoc.id_ca ASC;"
                // );
                $main_layout_data['class_name'] = $left_menu_data["class_name"];
                $main_layout_data['contentsection'] = view('Teacher\ViewLayout\CourseInformationSectionLayout', $course[0]);
                return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
            }
        }
    }
    function uploadFile() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $file = $this->request->getFile('file');

        if ($file->isValid()) {
            $user_id = session()->get("id_user");
            $encoded = md5(uniqid());
            $fileModel = new FileUploadModel();
            $fileModel->du_lieu = $encoded;
            $fileModel->id_user = $user_id;
            $fileModel->ngay_tai_len = $this->getCurrentDateTimeVietnam();
            $fileModel->ten_tep = $this->removeFileExtension($file->getClientName());
            $fileModel->extension = $file->getExtension();

            $rs = $fileModel->insertFileUpload($fileModel);
            if ($rs["state"]) {
                // move file
                // Đường dẫn đầy đủ tới thư mục lưu trữ file
                $ex = $file->getExtension();
                $uploadDir = (new Paths())->filesPath;
                $newFileName = $encoded.'.'.$ex;
                // Di chuyển file tải lên đến thư mục mới
                $file->move($uploadDir, $newFileName);
                $rs["fileName"] = $this->removeFileExtension($file->getClientName());
                $rs["extension"] = $ex;
                return $this->response->setJSON($rs);
            } else {
                return $this->response->setJSON(["state" => false, "message" => "Tệp tải lên không thành công"]);
            }
            return $this->response->setJSON(["state" => false, "message" => "Tệp tải lên không thành công"]);
        } else {
            return $this->response->setJSON(["state" => false, "message" => "Tệp tải lên không thành công"]);
        }
        
        

        // Tạo một mã duy nhất để đặt tên file mới
        $uniqueId = uniqid();
        $newFileName = $uniqueId . '_' . $file->getName();

        // Đường dẫn đầy đủ tới thư mục lưu trữ file
        $uploadDir = WRITEPATH . 'uploads/';

        // Di chuyển file tải lên đến thư mục mới
        $file->move($uploadDir, $newFileName);

    }
    function removeFileExtension($fileName) {
        // Sử dụng pathinfo để lấy thông tin về tên file
        $fileInfo = pathinfo($fileName);
    
        // Trả về phần tên file mà không có extension
        return $fileInfo['filename'];
    }
    

    function getCurrentDateTimeVietnam()
    {
        // Tạo đối tượng DateTimeZone cho múi giờ Việt Nam
        $vietnamTimeZone = new \DateTimeZone('Asia/Ho_Chi_Minh');

        // Tạo đối tượng DateTime sử dụng múi giờ Việt Nam
        $currentDateTime = new DateTime('now', $vietnamTimeZone);

        // Định dạng ngày giờ theo ý muốn (ví dụ: "Y-m-d H:i:s")
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

        return $formattedDateTime;
    }

    function promiseTest() {
        return view('t');
    }
    function getChooseUserFileForm() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $model = new FileUploadModel();
        $data["listOfUserFile"] = $model->getListOfFilesByUserId(session()->get("id_user"));
        return view("ChooseUserFileForm", $data);
    }
    public function getAddResourceIntoCourseForm() {
        return view("Teacher\ViewCell\AddResourceIntoClassForm");
    }
    public function resource()
    {

        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new UserModel();
        $navbar_data = array();
        $id_lop_hoc = null;
        if (isset($_GET)) {
            $id_lop_hoc = $_GET['courseid'];
        } else {
            return redirect()->to('/courses');
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
            return view("CommonViewCell\ExceptionPage", ["message" => "Bạn không có quyền hạn ở lớp học này"]);
        }
        // Kiểm tra xem giảng viên hay học viên có được truy cập vào lớp này hay không
        // Not yet
        if (session()->get('role') == 1) { // Admin
            $main_layout_data = array();
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
            $main_layout_data['class_name'] = $left_menu_data["class_name"];


            // Mục
            // 
            // $mucModel = new MucModel();
            $resourceSectionData = array();
            $resourceSectionData['id_lop_hoc'] = $id_lop_hoc;
            // $resourceSectionData['folders'] = $mucModel->getMucByIdLopHoc($id_lop_hoc);
            $main_layout_data['contentsection'] = view('Admin\ViewLayout\CourseResourceSectionLayout', $resourceSectionData);
            return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
        } else if (session()->get('role') == 2) { // Giang vien
            // Kiểm tra xem giảng viên hay học viên có được truy cập vào lớp này hay không
            $id_giang_vien = session()->get('id_role');
            $model = new phan_cong_giang_vienModel();
            $result = $model->executeCustomQuery(
                "SELECT * FROM phan_cong_giang_vien WHERE phan_cong_giang_vien.id_giang_vien = {$id_giang_vien} AND phan_cong_giang_vien.id_lop_hoc = {$id_lop_hoc}"
            );
            if (count($result) == 0) {
                return view("CommonViewCell\ClassNotFound");
            } else {
                $result = $model->executeCustomQuery(
                    'SELECT giang_vien.ho_ten, giang_vien.id_giang_vien, users.anh_dai_dien
                    FROM users
                    INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                    WHERE users.id_user = ' . session()->get("id_user")
                );
                $navbar_data['username'] = "{$result[0]['ho_ten']}";
                $navbar_data['role'] = 'Giảng viên';
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
                $main_layout_data['class_name'] = $left_menu_data["class_name"];
    
    
                // Mục
                // 
                // $mucModel = new MucModel();
                $resourceSectionData = array();
                $resourceSectionData['id_lop_hoc'] = $id_lop_hoc;
                $main_layout_data['contentsection'] = view('Teacher\ViewLayout\CourseResourceSectionLayout', $resourceSectionData);
                return view('Admin\ViewLayout\CourseDetailLayout', $main_layout_data);
            }
            // Not yet
        } else if (session()->get('role') == 3) { // Hoc vien
            // Kiểm tra xem giảng viên hay học viên có được truy cập vào lớp này hay không
            // Not yet
            $main_layout_data = array();
            
            $result = $model->executeCustomQuery(
                'SELECT hoc_vien.ho_ten, hoc_vien.id_hoc_vien, users.anh_dai_dien
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Học viên';
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
            $main_layout_data['class_name'] = $left_menu_data["class_name"];


            // Mục
            // 
            // $mucModel = new MucModel();
            $resourceSectionData = array();
            $resourceSectionData['id_lop_hoc'] = $id_lop_hoc;
            // $resourceSectionData['folders'] = $mucModel->getMucByIdLopHoc($id_lop_hoc);
            $main_layout_data['contentsection'] = view('Student\ViewLayout\CourseResourceSectionLayout', $resourceSectionData);
            return view('Student\ViewLayout\CourseDetailLayout', $main_layout_data);
        }
        // return view('Admin/ViewLayout/CourseResourceSectionLayout');
    }
    public function getFileById() {
        $file_id = $this->request->getVar("file_id");

        //auth
        $model = new FileUploadModel();
        $id_user = session()->get('id_user');
        $listOfUserFile = $model->getListOfFilesByUserId($id_user);
        $isHasPermission = false;
        foreach ($listOfUserFile as $file) {
            if ($file_id === $file->id_tep_tin_tai_len) {
                $isHasPermission = true;
            }
        }
        if (!$isHasPermission) {
            return $this->response->setJSON(["state" => false]);
        }

        $model = new FileUploadModel();
        $rs = $model->getFileUploadById($file_id);

        if ($rs === null) { // khong bao gio
            return $this->response->setJSON(["state" => false]);
        } else {
            return $this->response->setJSON(["state" => true, "file" => $rs]);
        }
    }
    
    public function getResources()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getPost('id_lop_hoc');
        if (!is_numeric($id_lop_hoc)) {
            // Xử lý lỗi hoặc trả về kết quả không hợp lệ
            return $this->response->setJSON(['error' => 'Invalid id_lop_hoc']);
        }
        $mucModel = new MucModel();
        $rs = array();
        $rs['folders'] = $mucModel->getMucByIdLopHoc($id_lop_hoc);

        $thongBaoModel = new ThongBaoModel();
        $rs['notis'] = $thongBaoModel->getThongBaoByCourseId($id_lop_hoc);

        $linkModel = new LinkModel();
        $rs['links'] = $linkModel->getAllLinksByCourseId($id_lop_hoc);

        $viTriTepModel = new vi_tri_tep_tinModel();
        $rs['files'] = $viTriTepModel->executeCustomQuery(
            "SELECT 
            v.*, 
            m.id_muc, 
            g.id_giang_vien, 
            g.ho_ten,
            t.*
        FROM 
            vi_tri_tep_tin v
        INNER JOIN 
            muc m ON v.id_muc = m.id_muc
        INNER JOIN 
            tep_tin_tai_len t ON t.id_tep_tin_tai_len = v.id_tep_tin_tai_len
        INNER JOIN 
            users u ON u.id_user = t.id_user
        INNER JOIN 
            giang_vien g ON g.id_giang_vien = u.id_giang_vien
        WHERE 
            m.id_lop_hoc = {$id_lop_hoc}
        ORDER BY
            v.ngay_dang ASC;
        "
        );

        $baiTapModel = new BaiTapModel();
        $rs["assignments"] = $baiTapModel->getBaiTapByIdLopHoc($id_lop_hoc);
        return $this->response->setJSON($rs);
    }
    
    public function deleteScheduleFromCourse()
    {
        $array = json_decode(json_encode($this->request->getJSON()), true);
        $dsbh = $array["danh_sach_id_buoi_hoc"];
        // $dsbh = [77320];
        // $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $result = array();
        $model = new BuoiHocModel();
        foreach ($dsbh as $id_buoi_hoc) {
            $result[$id_buoi_hoc] = $model->executeCustomDDL(
                "UPDATE buoi_hoc SET buoi_hoc.id_lop_hoc = NULL WHERE buoi_hoc.id_buoi_hoc = {$id_buoi_hoc}"
            );
        }
        return $this->response->setJSON($result);
    }
    public function deleteStudentFromCourse()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $array = json_decode(json_encode($this->request->getJSON()), true);
        $dshv = $array["danh_sach_id_hoc_vien"];
        // $dsbh = [77320];
        // $dshv = [1];
        $id_lop_hoc = $array["id_lop_hoc"];
        $result = array();
        $model = new hoc_vien_tham_giaModel();
        foreach ($dshv as $id_hoc_vien) {
            $hvtg = new hoc_vien_tham_giaModel();
            $hvtg->id_hoc_vien = $id_hoc_vien;
            $hvtg->id_lop_hoc = $id_lop_hoc;
            $result[$id_hoc_vien] = $model->deletehoc_vien_tham_gia(
                $hvtg
            );
        }
        return $this->response->setJSON($result);
    }

    public function test()
    {
        $this->deleteScheduleFromCourse();
    }
    public function getCoursesListSection()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
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
    public function getInsertLecturerForm()
    {
        // if (!session()->has('id_user')) {
        //     return redirect()->to('/');
        // }

        $data = array();
        $lecturersModel = new GiangVienModel();
        $data['lecturers'] = $lecturersModel->getAllGiangViens();
        return view('Admin\ViewCell\InsertLecturerIntoClassForm', $data);
    }
    public function submitAssignment() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $submission = json_decode(json_encode($this->request->getJSON()), true);

        // $submission = [
        //     "id_bai_tap" => "3",
        //     "list_of_files_id" => [1, 2,34, 4, 53, 34]
        // ];
        $id_bai_tap = $submission["id_bai_tap"];
        $dstep = $submission["list_of_files_id"];
        
        
        $bai_nop = new BaiNopModel();
        $bai_nop->id_bai_tap = $id_bai_tap;
        $bai_nop->id_hoc_vien = session()->get("id_role");
        $bai_nop->thoi_gian_nop = $this->getCurrentDateTimeInVietnam();
        $rs = $bai_nop->insertBaiNop($bai_nop);
        $id_bai_nop = $rs["id_bai_nop"];
        // return $this->response->setJSON(["state" => true, "message" => $rs['id_bai_nop']]);
        if ($rs["state"]) {
            $dstt = $this->filterAndFormatArray($dstep);
            $id_user = session()->get("id_user");
            $dstt = (new chi_tiet_bai_nopModel())->executeCustomQuery(
                "SELECT id_tep_tin_tai_len FROM tep_tin_tai_len WHERE id_user = $id_user $dstt;
            ");

            foreach($dstt as $tt) {
                        // return $this->response->setJSON(["state" => true, "message" => true]);
                $ctbn = new chi_tiet_bai_nopModel();
                $ctbn->id_bai_nop = $id_bai_nop;
                $ctbn->id_tep_tin_tai_len = $tt["id_tep_tin_tai_len"];
                $ctbn->insertchi_tiet_bai_nop($ctbn);
                // return $this->response->setJSON($rs);
            }

        } 
        return $this->response->setJSON($rs);
    }
    function deleteSubmission() {
        $id_bai_tap = $this->request->getVar("assignmentid");
        $id_hoc_vien = session()->get("id_role");
        $model = new BaiNopModel();
        $rs = $model->executeCustomQuery(
            "SELECT * FROM bai_nop WHERE id_bai_tap = $id_bai_tap and id_hoc_vien = $id_hoc_vien"
        );
        if (count($rs) > 0) {
            return $this->response->setJSON($model->deleteBaiNop($rs[0]["id_bai_nop"]));
        }
       return $this->response->setJSON(['state' => false, 'message' => "Đã có lỗi xảy ra"]);
    }
    function filterAndFormatArray($arr) {
        // Lọc các phần tử không phải số
        $filteredArray = array_filter($arr, 'is_numeric');
    
        // Nếu mảng sau khi lọc không có phần tử nào, trả về chuỗi rỗng
        if (empty($filteredArray)) {
            return '';
        }
    
        // Format chuỗi theo dạng "IN (phần tử 1, phần tử 2,...)"
        $formattedString = 'and id_tep_tin_tai_len IN (' . implode(', ', $filteredArray) . ')';
    
        return $formattedString;
    }
    public function getSubmissionForm() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        return view('Student\ViewCell\SubmitSubmissionForm');

    }
    public function getInsertStudentForm()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $data = array();
        $studentsModel = new HocVienModel();
        $data['students'] = $studentsModel->getAllHocViens();
        return view('Admin\ViewCell\InsertStudentIntoClassForm', $data);
        // return $this->response->setJSON($data['students']);
        // return view('LoginPage');
    }
    public function schedule()
    {
        return view("SchedulePage");
    }
    public function getScheduleList()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $data = json_decode($this->request->getVar("json"), true);
        // $data = json_encode();
        $model = new CaModel();
        $ca = ($data['id_ca'] == -1 ? "" : "AND buoi_hoc.id_ca = {$data['id_ca']}");
        $phong = ($data['id_phong'] == -1 ? "" : "AND buoi_hoc.id_phong = {$data['id_phong']}");
        $thu = ($data['thu_trong_tuan'] == -1 ? "" : "AND DAYOFWEEK(buoi_hoc.ngay) = {$data['thu_trong_tuan']}");


        $result = $model->executeCustomQuery(
            "SELECT buoi_hoc.id_buoi_hoc, buoi_hoc.trang_thai, buoi_hoc.id_phong, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') AS ngay, DAYOFWEEK(buoi_hoc.ngay) AS thu, 
            ca.id_ca, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc, a.id_lop_hoc, a.id_mon_hoc, a.ten_mon_hoc 
            FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca 
            LEFT JOIN ( 
                SELECT lop_hoc.id_lop_hoc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc 
                FROM lop_hoc INNER JOIN mon_hoc ON lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc 
            ) AS a ON a.id_lop_hoc = buoi_hoc.id_lop_hoc 
            WHERE buoi_hoc.ngay >= '{$data['ngay_bat_dau']}' AND buoi_hoc.ngay <= '{$data['ngay_ket_thuc']}' {$ca} {$phong} {$thu} ORDER BY buoi_hoc.ngay ASC, buoi_hoc.id_phong ASC, buoi_hoc.id_ca ASC"
        );
        return $this->response->setJSON($result);
        // return $this->response->setJSON($data);
    }
    public function getScheduleListByLCourseId()
    {
        $id_lop_hoc = $this->request->getVar("id");
        // $data = json_encode();
        $model = new CaModel();


        $result = $model->executeCustomQuery(
            "SELECT buoi_hoc.id_buoi_hoc, buoi_hoc.trang_thai, buoi_hoc.id_phong, DATE_FORMAT(buoi_hoc.ngay, '%d/%m/%Y') AS ngay, DAYOFWEEK(buoi_hoc.ngay) AS thu, 
            ca.id_ca, ca.thoi_gian_bat_dau, ca.thoi_gian_ket_thuc, a.id_lop_hoc, a.id_mon_hoc, a.ten_mon_hoc 
            FROM buoi_hoc INNER JOIN ca ON buoi_hoc.id_ca = ca.id_ca 
            LEFT JOIN ( 
                SELECT lop_hoc.id_lop_hoc, mon_hoc.id_mon_hoc, mon_hoc.ten_mon_hoc 
                FROM lop_hoc INNER JOIN mon_hoc ON lop_hoc.id_mon_hoc = mon_hoc.id_mon_hoc 
            ) AS a ON a.id_lop_hoc = buoi_hoc.id_lop_hoc 
            WHERE buoi_hoc.id_lop_hoc = {$id_lop_hoc} ORDER BY buoi_hoc.ngay ASC"
        );
        return $this->response->setJSON($result);
        // return $this->response->setJSON($data);
    }
    public function insertScheduleIntoClass()
    {
        $data = json_decode($this->request->getVar("json"), true);
        $id_lop_hoc = json_decode($this->request->getVar("id"), true);

        $result = array();
        $model = new BuoiHocModel();
        foreach ($data as $id_buoi_hoc) {
            $result["{$id_buoi_hoc}"] = $model->updateIdLopHoc($id_lop_hoc, $id_buoi_hoc);
        }
        return $this->response->setJSON($result);
    }
    public function getInsertScheduleForm()
    {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $data = array();
        $model = new CaModel();
        $data['shifts'] = $model->getAllCa();
        $model = new PhongModel();
        $data['rooms'] = $model->getAllPhong();
        return view('Admin\ViewCell\InsertScheduleIntoClassForm', $data);
    }
    public function getInsertClassForm()
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
    public function fillData2()
    {
        $model = new BuoiHocModel();
        $model2 = new HocVienModel();
        $dsbh = $model->executeCustomQuery(
            "SELECT * FROM buoi_hoc WHERE buoi_hoc.id_lop_hoc = 110;
        "
        );
        $dshv = $model2->executeCustomQuery(
            "SELECT hoc_vien.*, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh_hv FROM hoc_vien_tham_gia INNER JOIN hoc_vien on hoc_vien.id_hoc_vien = hoc_vien_tham_gia.id_hoc_vien
            WHERE hoc_vien_tham_gia.id_lop_hoc = 110;"
        );
        foreach ($dsbh as $bh) {
            foreach ($dshv as $hv) {
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

    public function insertStudentsIntoClass()
    {
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Process
        $courseData = json_decode(json_encode($this->request->getJSON()), true);
        $id_lop_hoc = $courseData['id_lop_hoc'];
        $student_id_list = $courseData['student_id_list'];

        $model = new hoc_vien_tham_giaModel();

        $processedResult = array();
        foreach ($student_id_list as $id => $name) {
            $data = new hoc_vien_tham_giaModel();
            $data->id_hoc_vien = $id;
            $data->id_lop_hoc = $id_lop_hoc;
            // $processedResult["$name" . "($id)"] = ['state' => true, 'message' => "ok"];
            $processedResult["$name" . "($id)"] = $model->inserthoc_vien_tham_gia($data);
        }
        return $this->response->setJSON($processedResult);
    }


    public function attendance()
    {   
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // navbar
        $model = new UserModel();
        $navbar_data = array();
        $id_lop_hoc = null;
        if (isset($_GET)) {
            $id_lop_hoc = $_GET['courseid'];
        }
        else {
            return redirect()->to('/courses');
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
            return view("CommonViewCell\ExceptionPage", ["message" => "Lớp học không tồn tại"]);
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


            
            $tatCaBuoiHoc['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
        
        // endnabar
        // leftmenu
        $model = new LopModel();
        // $result = $model->executeCustomQuery('');
        $left_menu_data = array();
        $course_id_mon_hoc = str_pad($course[0]["id_mon_hoc"], 3, "0", STR_PAD_LEFT);
        $course_id_lop_hoc = str_pad($course[0]["id_lop_hoc"], 6, "0", STR_PAD_LEFT);
        $left_menu_data["class_name"] = $course[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
        $left_menu_data["student_quantity"] = $course[0]["so_luong_hoc_vien"];
        $left_menu_data["state"] = $this->kiem_tra_tinh_trang($course[0]["ngay_bat_dau"], $course[0]["ngay_ket_thuc"]);
        $left_menu_data["id_lop_hoc"] = $course[0]["id_lop_hoc"];
        $tatCaBuoiHoc['leftmenu'] = view('Admin\ViewCell\LeftMenuInCourseDetail', $left_menu_data);
        // endleftmenu
        if (isset($_GET['courseid'])) {
            $courseId = $_GET['courseid'];}

        $hocvien_tg=new diem_danhModel();
        $buoihoc=new BuoiHocModel();
        $sql=new diem_danhModel();
        $idBuoiHoc=new diem_danhModel();
        
        $tatCaBuoiHoc['tatCaBuoiHoc'] = $buoihoc->getAllBuoiHocByLopHocId($courseId);
        if (is_array($tatCaBuoiHoc['tatCaBuoiHoc'])) {
            foreach ($tatCaBuoiHoc['tatCaBuoiHoc'] as $buoiHoc) {
                // Kiểm tra xem thuộc tính id_buoi_hoc có tồn tại trong từng đối tượng không
                if (is_object($buoiHoc) && property_exists($buoiHoc, 'id_buoi_hoc')) {
                    $idBuoiHoc = $buoiHoc->id_buoi_hoc;
                    // Sử dụng $idBuoiHoc ở đây hoặc thực hiện các xử lý khác
                    $sql = "SELECT dd.*, hv.* 
                    FROM diem_danh dd
                    INNER JOIN hoc_vien hv ON dd.id_hoc_vien = hv.id_hoc_vien
                    WHERE dd.id_buoi_hoc = $idBuoiHoc;
                    ";
                    $tatCaBuoiHoc['hv_tg'] = $hocvien_tg->queryDatabase($sql);
                    $left_menu_data["class_name"] = $course[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
                    $tatCaBuoiHoc['class_name'] = $left_menu_data["class_name"];
                    return view('SchedulePage', $tatCaBuoiHoc);
                   
                }else
                {

                    return view('SchedulePageNone');
                    
                }

            }
        }


        //testcoed




        }
        else if (session()->get('role') == 2) { // Giang vien
            $result = $model->executeCustomQuery(
                'SELECT giang_vien.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";


            
            $tatCaBuoiHoc['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
        
        // endnabar
        // leftmenu
        $model = new LopModel();
        // $result = $model->executeCustomQuery('');
        $left_menu_data = array();
        $course_id_mon_hoc = str_pad($course[0]["id_mon_hoc"], 3, "0", STR_PAD_LEFT);
        $course_id_lop_hoc = str_pad($course[0]["id_lop_hoc"], 6, "0", STR_PAD_LEFT);
        $left_menu_data["class_name"] = $course[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
        $left_menu_data["student_quantity"] = $course[0]["so_luong_hoc_vien"];
        $left_menu_data["state"] = $this->kiem_tra_tinh_trang($course[0]["ngay_bat_dau"], $course[0]["ngay_ket_thuc"]);
        $left_menu_data["id_lop_hoc"] = $course[0]["id_lop_hoc"];
        $tatCaBuoiHoc['leftmenu'] = view('Admin\ViewCell\LeftMenuInCourseDetail', $left_menu_data);
        // endleftmenu
        if (isset($_GET['courseid'])) {
            $courseId = $_GET['courseid'];}

        $hocvien_tg=new diem_danhModel();
        $buoihoc=new BuoiHocModel();
        $sql=new diem_danhModel();
        $idBuoiHoc=new diem_danhModel();
        
        $tatCaBuoiHoc['tatCaBuoiHoc'] = $buoihoc->getAllBuoiHocByLopHocId($courseId);
        if (is_array($tatCaBuoiHoc['tatCaBuoiHoc'])) {
            foreach ($tatCaBuoiHoc['tatCaBuoiHoc'] as $buoiHoc) {
                // Kiểm tra xem thuộc tính id_buoi_hoc có tồn tại trong từng đối tượng không
                if (is_object($buoiHoc) && property_exists($buoiHoc, 'id_buoi_hoc')) {
                    $idBuoiHoc = $buoiHoc->id_buoi_hoc;
                    // Sử dụng $idBuoiHoc ở đây hoặc thực hiện các xử lý khác
                   
                }
            }
        }


        //testcoed



        $sql = "SELECT dd.*, hv.* 
        FROM diem_danh dd
        INNER JOIN hoc_vien hv ON dd.id_hoc_vien = hv.id_hoc_vien
        WHERE dd.id_buoi_hoc = $idBuoiHoc;
        ";
        $tatCaBuoiHoc['hv_tg'] = $hocvien_tg->queryDatabase($sql);
        $left_menu_data["class_name"] = $course[0]["ten_mon_hoc"] . " " . $course_id_mon_hoc . "." . $course_id_lop_hoc;
        $tatCaBuoiHoc['class_name'] = $left_menu_data["class_name"];
        return view('SchedulePage', $tatCaBuoiHoc);

        }       else if (session()->get('role') == 3) { // Hoc vien
            return view('SchedulePageNone');

     }
        
        
        }


        public function getAttByIDBuoi(){
        $hocvien_tgbybuoi=new diem_danhModel();
        $sqlbyid=new diem_danhModel();
            // echo json_encode($this->request->getJSON());
        $Buoihocbyid= json_decode(json_encode($this->request->getJSON()), true);
        $idofBuoihoc = $Buoihocbyid["idBuoiHoc"];

        if (!isset($idofBuoihoc) || empty($idofBuoihoc)) {
            // If $idofBuoihoc is not set or empty, return an empty result
            return $this->response->setJSON([]);
                } else {
            // If $idofBuoihoc is available, proceed with the SQL query
            $sqlbyid = "SELECT dd.*, hv.*, bh.*, DAYOFWEEK(bh.ngay),c.*
                        FROM diem_danh dd
                        INNER JOIN hoc_vien hv ON dd.id_hoc_vien = hv.id_hoc_vien
                        INNER JOIN buoi_hoc bh ON dd.id_buoi_hoc = bh.id_buoi_hoc
                        INNER JOIN ca c ON c.id_ca = bh.id_ca
                        WHERE dd.id_buoi_hoc = $idofBuoihoc;";
        
            $ResAtten = $hocvien_tgbybuoi->queryDatabase($sqlbyid);
            return $this->response->setJSON($ResAtten);
        }

        }


        public function CheckingAtt()
        {
            // $AttenCheckin[]=new diem_danhModel();
            $ConfirmAtten=new diem_danhModel();
            $RQget= json_decode(json_encode($this->request->getJSON()), true);
            $MangD=new diem_danhModel();
            $sql=new diem_danhModel();            
            $capnhatbuoihoc=new BuoiHocModel();
            $idBuoi=$RQget[0]["type"];
            $sql="UPDATE buoi_hoc SET trang_thai = 2 WHERE id_buoi_hoc = $idBuoi";
            $rs = $capnhatbuoihoc->executeCustomDDL($sql);
            // return $this->response->setJSON(["state" => true, "message" => $idBuoi]);

            if (!$rs["state"]) {
                return $this->response->setJSON($rs);
            }
            $AttenCheckin = []; // Khởi tạo mảng rỗng để chứa các đối tượng diem_danhModel

            foreach ($RQget as $Getdata) {
                $newDiemDanh = new diem_danhModel(); // Tạo một đối tượng diem_danhModel mới
                
                // Gán giá trị từ mảng $Getdata vào từng thuộc tính của đối tượng diem_danhModel
                $newDiemDanh->id_hoc_vien = $Getdata["id"];
                $newDiemDanh->id_buoi_hoc = $Getdata["type"];
                $newDiemDanh->co_mat = $Getdata["comat"];
                $newDiemDanh->ghi_chu = $Getdata["ghichu"];
                $formattedData = [
                    'id_hoc_vien' => $newDiemDanh->id_hoc_vien,
                    'id_buoi_hoc' => $newDiemDanh->id_buoi_hoc,
                    'co_mat'      => $newDiemDanh->co_mat,
                    'ghi_chu'      => $newDiemDanh->ghi_chu
                    
                ];

                $AttenCheckin[] = $formattedData;
            }

            $ConfirmAtten->updatediem_danhnModel($AttenCheckin);

            return $this->response->setJSON($AttenCheckin);
        }

        public function Getbuoihocdautien(){
            $buoidautien=new diem_danhModel();
            $ID= json_decode(json_encode($this->request->getJSON()), true);
            $getIDbuoi=new BuoiHocModel();
            $buoi=new BuoiHocModel();
            $sqlio=new diem_danhModel();
            $sqlp=new BuoiHocModel();
            $subquery = "SELECT id_buoi_hoc
            FROM buoi_hoc
            WHERE id_lop_hoc =  $ID 
            ORDER BY ngay ASC
            LIMIT 1";
            $getIDbuoi=$buoi->executeCustomQuery($subquery);
            $IDok= $getIDbuoi[0]["id_buoi_hoc"];
// Truy vấn chính sử dụng subquery
$sqlio = "SELECT dd.*, hv.*, bh.* FROM diem_danh dd
         INNER JOIN hoc_vien hv ON dd.id_hoc_vien = hv.id_hoc_vien
         INNER JOIN buoi_hoc bh ON dd.id_buoi_hoc = bh.id_buoi_hoc
         WHERE dd.id_buoi_hoc =  $IDok";

// Thực hiện truy vấn SQL
$datatrave = $buoidautien->queryDatabase($sqlio);
            return $this->response->setJSON($datatrave);
        }
        




    function postNewFolder() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $ten_muc = $this->request->getVar("ten_muc");
        $id_muc = $this->request->getVar("id_muc");

        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            $muc = new MucModel();
            $muc->id_lop_hoc = $id_lop_hoc;
            $muc->ten_muc = $ten_muc;
            $muc->id_muc_cha = $id_muc;
            $rs2 = $muc->insertMuc($muc);
            return $this->response->setJSON($rs2);
            
        }  else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
    }
    function postNewLinkOnClass() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $dlink = $this->request->getVar("link");
        $tieu_de = $this->request->getVar("tieu_de");
        $id_muc = $this->request->getVar("id_muc");

        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            $link = new LinkModel();
            $link->id_muc = $id_muc;
            $link->tieu_de = $tieu_de;
            $link->link = $dlink;

            $link->ngay_dang = $this->getCurrentDateTimeInVietnam();

            
            $model = new UserModel();
            $model->getUserById(session()->get("id_user"));
            if ($model == null || $model->id_giang_vien == null) {
                return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
            }
            
            $link->id_giang_vien = $model->id_giang_vien;
            // var_dump("ok");
            $rs2 = $link->insertLink($link);
            return $this->response->setJSON($rs2);
            
        }  else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
    }
    
    function postNewNotiOnClass() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $noi_dung = $this->request->getVar("noi_dung");
        $tieu_de = $this->request->getVar("tieu_de");
        $id_muc = $this->request->getVar("id_muc");

        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            $noti = new ThongBaoModel();
            $noti->id_muc = $id_muc;
            $noti->tieu_de = $tieu_de;
            $noti->noi_dung = $noi_dung;

            $noti->ngay_dang = $this->getCurrentDateTimeInVietnam();

            
            $model = new UserModel();
            $model->getUserById(session()->get("id_user"));
            if ($model == null || $model->id_giang_vien == null) {
                return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
            }
            
            $noti->id_giang_vien = $model->id_giang_vien;
            // var_dump("ok");
            $rs2 = $noti->insertThongBao($noti);
            return $this->response->setJSON($rs2);
            
        }  else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
    }

    function postAssignmentOnClass() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // id_lop_hoc: id_lop_hoc,
        // id_muc: id_muc,
        // ten_bai_tap: ten_bai_tap,
        // noi_dung: noi_dung,
        // thoi_han: replaceLettersWithSpace(th),
        // thoi_han_nop: replaceLettersWithSpace(thn)
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $noi_dung = $this->request->getVar("noi_dung");
        $ten_bai_tap = $this->request->getVar("ten_bai_tap");
        $id_muc = $this->request->getVar("id_muc");
        $th = $this->request->getVar("thoi_han");
        $thn = $this->request->getVar("thoi_han_nop");

        $model = new UserModel();
        $model->getUserById(session()->get("id_user"));
        if ($model == null || $model->id_giang_vien == null) {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra"]);
        }
        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            $bt = new BaiTapModel();
            $bt->id_muc = $id_muc;
            $bt->ten = $ten_bai_tap;
            $bt->noi_dung = $noi_dung;
            $bt->thoi_han = $th;
            $bt->thoi_han_nop = $thn;
            $bt->ngay_dang = $this->getCurrentDateTimeInVietnam();
            $bt->id_giang_vien = $model->id_giang_vien;

            // var_dump("ok");
            $rs2 = $bt->insertBaiTap($bt);
            return $this->response->setJSON($rs2);
            
        }  else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
    }
    function postFileOnClass() {
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $file_id = $this->request->getVar("file_id");
        $id_muc = $this->request->getVar("id_muc");

        $model = new FileUploadModel();
        $id_user = session()->get('id_user');
        $listOfUserFile = $model->getListOfFilesByUserId($id_user);
        $isHasPermission = false;
        foreach ($listOfUserFile as $file) {
            if ($file_id === $file->id_tep_tin_tai_len) {
                $isHasPermission = true;
            }
        }
        if (!$isHasPermission) {
            return $this->response->setJSON(["state" => false, "message" => "Bạn không có quyền trên tệp tin này hoặc tệp tin không tồn tại"]);
        }

        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            $vttt = new vi_tri_tep_tinModel();
            $vttt->id_muc = $id_muc;
            $vttt->id_tep_tin_tai_len = $file_id;
            $vttt->ngay_dang = $this->getCurrentDateTimeInVietnam();
            $rs2 = $vttt->insertvi_tri_tep_tin($vttt);
            return $this->response->setJSON($rs2);
        } else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
        // $vttt->id_muc;
        
    }
    function getCurrentDateTimeInVietnam() {
        // Tạo đối tượng DateTime với múi giờ là Asia/Ho_Chi_Minh
        $vietnamTimeZone = new \DateTimeZone('Asia/Ho_Chi_Minh');
        $dateTime = new DateTime('now', $vietnamTimeZone);
    
        // Định dạng ngày giờ theo định dạng mong muốn (ví dụ: 'Y-m-d H:i:s')
        $formattedDateTime = $dateTime->format('Y-m-d H:i:s');
    
        return $formattedDateTime;
    }
    public function removeResource() {
        $id_lop_hoc = $this->request->getVar("id_lop_hoc");
        $id = $this->request->getVar("id");
        $id_muc = $this->request->getVar("id_muc");
        $type = $this->request->getVar("type");

        $muc = new MucModel();
        $rs = $muc->getMucById($id_muc);
        // return $this->response->setJSON(["state" => true, "message" => $id_lop_hoc ]);
        // if ($rs-)
        if ($rs != null && $rs->id_lop_hoc == $id_lop_hoc) {
            switch ($type) {
                case "file";
                    $model = new vi_tri_tep_tinModel();
                    $model->id_muc = $id_muc;
                    $model->id_tep_tin_tai_len = $id;
                    return $this->response->setJSON($model->deletevi_tri_tep_tin($model));
                    break;
                case "noti";
                    $model = new ThongBaoModel();
                    $model->id_thong_bao = $id;
                    $model->id_muc = $id_muc;
                    return $this->response->setJSON($model->deleteThongBao($model));
                    break;
                case "link";
                    $model = new LinkModel();
                    $model->id_duong_link = $id;
                    $model->id_muc = $id_muc;
                    return $this->response->setJSON($model->deleteLink($model));
                    break;
                case "asssignment";
                    $model = new BaiTapModel();
                    $model->id_bai_tap = $id;
                    $model->id_muc = $id_muc;
                    return $this->response->setJSON($model->deleteBaiTap($model));
                    break;

            }
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
            // return $this->response->setJSON([]);
        } else {
            return $this->response->setJSON(["state" => false, "message" => "Đã có lỗi xảy ra" ]);
        }
        
        

    }



}