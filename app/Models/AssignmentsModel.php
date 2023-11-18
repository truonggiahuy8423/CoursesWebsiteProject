<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class AssignmentModel
{
    public $assignmentId;
    public $trangThai;
    public $ten;
    public $noiDung;
    public $thoiHan;
    public $idGiangVien;
    public $idMuc;

    private $conn;
    function __construct(){}


    function getAssignmentById($id)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_bai_tap = $id";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new AssignmentModel();
            $this->assignmentId = $row["id_bai_tap"];
            $this->trangThai = $row["trang_thai"];
            $this->ten = $row["ten"];
            $this->noiDung = $row["noi_dung"];
            $this->thoiHan = $row["thoi_han "];
            $this->idGiangVien = $row["id_giang_vien"];
            $this->idMuc = $row["id_muc"];
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAllAssignments()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new AssignmentModel();
                $this->assignmentId = $row["id_bai_tap"];
                $this->trangThai = $row["trang_thai"];
                $this->ten = $row["ten"];
                $this->noiDung = $row["noi_dung"];
                $this->thoiHan = $row["thoi_han "];
                $this->idGiangVien = $row["id_giang_vien"];
                $this->idMuc = $row["id_muc"];
                $this->conn->close();                
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

    function insertAssignment($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $trangThai = $this->conn->real_escape_string($user->trangThai);
        $ten = $this->conn->real_escape_string($user->ten);
        $noiDung = $this->conn->real_escape_string($user->noiDung);
        $thoiHan = $this->conn->real_escape_string($user->thoiHan);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);
        $sql = "INSERT INTO hoc_vien (trang_thai, ten, noi_dung, thoi_han, id_giang_vien, id_muc) VALUES ('$trangThai', '$ten', '$noiDung', '$thoiHan', '$idGiangVien', '$idMuc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteAssignment($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $assignmentId = $this->conn->real_escape_string($user->assignmentId);
        $sql = "DELETE FROM hoc_vien WHERE id_bai_tap = $assignmentId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateAssignment($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $assignmentId = $this->conn->real_escape_string($user->assignmentId);
        $trangThai = $this->conn->real_escape_string($user->trangThai);
        $ten = $this->conn->real_escape_string($user->ten);
        $noiDung = $this->conn->real_escape_string($user->noiDung);
        $thoiHan = $this->conn->real_escape_string($user->thoiHan);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);

        $sql = "UPDATE hoc_vien SET trang_thai = '$trangThai', ten = '$ten', noi_dung = '$noiDung', thoi_han = '$thoiHan', id_giang_vien  = '$idGiangVien', id_muc = '$idMuc' WHERE id_bai_tap = $assignmentId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
