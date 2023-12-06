<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;
use mysqli;
use DateTime;
include 'DatabaseConnect.php';

class phan_cong_giang_vienModel
{
    public $id_giang_vien;
    public $id_lop_hoc;
    

    private $conn;
    function __construct(){}

    function getPhanCongByIDGiangVien($id_giang_vien){
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $id_giang_vien = $this->conn->real_escape_string($id_giang_vien);
        $sql = "SELECT lh.id_lop_hoc, mh.id_mon_hoc, mh.ten_mon_hoc, lh.ngay_bat_dau, lh.ngay_ket_thuc
                FROM phan_cong_giang_vien pc, lop_hoc lh , mon_hoc mh
                WHERE pc.id_giang_vien = $id_giang_vien
                AND pc.id_lop_hoc = lh.id_lop_hoc
                AND mh.id_mon_hoc = lh.id_mon_hoc";
        $result = $this->conn->query($sql);
        $phanCongs = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $info = new class{
                    public $id_lop_hoc;
                    public $id_mon_hoc;
                    public $ten_mon_hoc;
                    public $ngay_bat_dau;
                    public $ngay_ket_thuc;
                };

                $info->id_lop_hoc = $row["id_lop_hoc"];
                $info->id_mon_hoc = $row["id_mon_hoc"];
                $info->ten_mon_hoc = $row["ten_mon_hoc"];
                $info->ngay_bat_dau = $row["ngay_bat_dau"];
                $info->ngay_ket_thuc = $row["ngay_ket_thuc"];

                $phanCongs[] = $info;
            }
        }
        $this->conn->close();
        return $phanCongs;
    }

    function getNotPhanCongByIDGiangVien($id_giang_vien){
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $id_giang_vien = $this->conn->real_escape_string($id_giang_vien);
        $sql = "SELECT DISTINCT lh.id_lop_hoc, mh.id_mon_hoc, mh.ten_mon_hoc, lh.ngay_bat_dau, lh.ngay_ket_thuc
                FROM phan_cong_giang_vien pc, lop_hoc lh , mon_hoc mh
                WHERE pc.id_giang_vien <> $id_giang_vien
                AND pc.id_lop_hoc = lh.id_lop_hoc
                AND mh.id_mon_hoc = lh.id_mon_hoc
                AND pc.id_lop_hoc NOT IN (SELECT id_lop_hoc
                                          FROM phan_cong_giang_vien
                                          WHERE id_giang_vien = $id_giang_vien)";
        $result = $this->conn->query($sql);
        $notPhanCongs = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $info = new class{
                    public $id_lop_hoc;
                    public $id_mon_hoc;
                    public $ten_mon_hoc;
                    public $ngay_bat_dau;
                    public $ngay_ket_thuc;
                };

                $info->id_lop_hoc = $row["id_lop_hoc"];
                $info->id_mon_hoc = $row["id_mon_hoc"];
                $info->ten_mon_hoc = $row["ten_mon_hoc"];
                $info->ngay_bat_dau = $row["ngay_bat_dau"];
                $info->ngay_ket_thuc = $row["ngay_ket_thuc"];

                $notPhanCongs[] = $info;
            }
        }
        $this->conn->close();
        return $notPhanCongs;
    }

    function getAllphan_cong_giang_vien()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM phan_cong_giang_vien";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new phan_cong_giang_vienModel();
                $this->id_giang_vien = $row["id_giang_vien"];
                $this->id_lop_hoc = $row["id_lop_hoc"];
                $users[] = $user;
            }
        }
        $this->conn->close();
        return $users;
    }

    function queryDatabase($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $result = $this->conn->query($sql);
        $rows = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }

    function insertphan_cong_giang_vien($pcgv)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }


        $sql = "INSERT INTO phan_cong_giang_vien (id_giang_vien, id_lop_hoc) VALUES ('{$pcgv->id_giang_vien}', '{$pcgv->id_lop_hoc}')";
        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }   
    }

    function deletephan_cong_giang_vien($pc)
{
    $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    if ($this->conn->connect_error) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
    }

    $id_giang_vien = $this->conn->real_escape_string($pc->id_giang_vien);
    $id_lop_hoc = $this->conn->real_escape_string($pc->id_lop_hoc);

    $sql = "DELETE FROM phan_cong_giang_vien WHERE id_giang_vien = $id_giang_vien AND id_lop_hoc = $id_lop_hoc";

    try {
        $this->conn->query($sql);

        // Kiểm tra số dòng bị ảnh hưởng
        $effectedNumRows = $this->conn->affected_rows;

        $this->conn->close();
        return ['state' => true, 'effectedNumRows' => $effectedNumRows, 'message' => 'Xóa thành công'];
    } catch (Exception $e) {
        // Nếu có lỗi, xử lý lỗi
        $this->conn->close();
        return ['state' => false, 'message' => $e->getMessage()];
    } 
}


    function updatephan_cong_giang_vien($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_giang_vien = $this->conn->real_escape_string($user->id_giang_vien);
        $id_lop_hoc = $this->conn->real_escape_string($user->id_lop_hoc);

        $sql = "UPDATE phan_cong_giang_vien SET id_lop_hoc = '$id_lop_hoc' WHERE id_giang_vien = $id_giang_vien";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
