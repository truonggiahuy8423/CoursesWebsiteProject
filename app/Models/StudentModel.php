<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class StudentModel
{
    public $studentId;
    public $hoTen;
    public $ngaySinh;
    public $gioiTinh;
    public $email;

    private $conn;
    function __construct(){}

    function getStudentById($studentId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_hoc_vien = $studentId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new StudentModel();
            $this->studentId = $row["id_hoc_vien"];
            $this->hoTen = $row["ho_ten"];
            $this->ngaySinh = $row["ngay_sinh"];
            $this->gioiTinh = $row["gioi_tinh"];
            $this->email = $row["email"];
            $this->conn->close();
            return $user;
        }
        else{
            $this->conn->close();
            return null;
        }
    }

    function getAllStudents()
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
                $user = new StudentModel();
                $this->studentId = $row["id_hoc_vien"];
                $this->hoTen = $row["ho_ten"];
                $this->ngaySinh = $row["ngay_sinh"];
                $this->gioiTinh = $row["gioi_tinh"];
                $this->email = $row["email"];
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

    function insertStudent($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $hoTen = $this->conn->real_escape_string($user->hoTen);
        $ngaySinh = $this->conn->real_escape_string($user->ngaySinh);
        $gioiTinh = $this->conn->real_escape_string($user->gioiTinh);
        $email = $this->conn->real_escape_string($user->email);

        $sql = "INSERT INTO hoc_vien (ho_ten, ngay_sinh, gioi_tinh, email) VALUES ('$hoTen', '$ngaySinh', '$gioiTinh', '$email')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteStudent($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $studentId = $this->conn->real_escape_string($user->studentId);
        $sql = "DELETE FROM hoc_vien WHERE id_hoc_vien = $studentId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateStudent($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $studentId = $this->conn->real_escape_string($user->studentId);
        $hoTen = $this->conn->real_escape_string($user->hoTen);
        $ngaySinh = $this->conn->real_escape_string($user->ngaySinh);
        $gioiTinh = $this->conn->real_escape_string($user->gioiTinh);
        $email = $this->conn->real_escape_string($user->email);

        $sql = "UPDATE hoc_vien SET ho_ten = '$hoTen', ngay_sinh = '$ngaySinh', gioi_tinh = '$gioiTinh', email = '$email' WHERE id_hoc_vien = $studentId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
    



