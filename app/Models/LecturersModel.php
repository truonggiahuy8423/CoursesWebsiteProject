<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;

include 'DatabaseConnect.php';

class LecturersModel
{
    public $lecturerId;
    public $hoTen;
    public $ngaySinh;
    public $gioiTinh;
    public $email;

    private $conn;
    function __construct(){}

    function getLecturersById($lecturerId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM giang_vien WHERE id_giang_vien = $lecturerId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new LecturersModel();
            $this->lecturerId = $row["id_giang_vien"];
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

    function getAllLecturers()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM giang_vien";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new LecturersModel();
                $this->lecturerId = $row["id_giang_vien"];
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

    function insertLecturers($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $hoTen = $this->conn->real_escape_string($user->hoTen);
        $ngaySinh = $this->conn->real_escape_string($user->ngaySinh);
        $gioiTinh = $this->conn->real_escape_string($user->gioiTinh);
        $email = $this->conn->real_escape_string($user->email);

        $sql = "INSERT INTO giang_vien (ho_ten, ngay_sinh, gioi_tinh, email) VALUES ('$hoTen', '$ngaySinh', '$gioiTinh', '$email')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteLecturers($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $lecturerId = $this->conn->real_escape_string($user->lecturerId);
        $sql = "DELETE FROM giang_vien WHERE id_giang_vien = $lecturerId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateLecturers($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $lecturerId = $this->conn->real_escape_string($user->lecturerId);
        $hoTen = $this->conn->real_escape_string($user->hoTen);
        $ngaySinh = $this->conn->real_escape_string($user->ngaySinh);
        $gioiTinh = $this->conn->real_escape_string($user->gioiTinh);
        $email = $this->conn->real_escape_string($user->email);

        $sql = "UPDATE giang_vien SET ho_ten = '$hoTen', ngay_sinh = '$ngaySinh', gioi_tinh = '$gioiTinh', email = '$email' WHERE id_giang_vien = $lecturerId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
