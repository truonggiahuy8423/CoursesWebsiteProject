<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class tin_nhan_chungModel
{
    public $id_tin_nhan;
    public $noi_dung;
    public $thoi_gian;
    public $anh;
    public $user_gui;
    public $kenh_nhan;

    private $conn;
    function __construct(){}


    function gettin_nhan_chungById($classId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tin_nhan_chung WHERE id_tin_nhan = $id_tin_nhan";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new tin_nhan_chungModel();
            $this->id_tin_nhan = $row["id_tin_nhan"];
            $this->noi_dung = $row["noi_dung"];
            $this->thoi_gian = $row["thoi_gian"];
            $this->anh = $row["anh"];
            $this->user_gui = $row["user_gui"];
            $this->kenh_nhan = $row["kenh_nhan"];
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAlltin_nhan_chung()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tin_nhan_chung";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new tin_nhan_chungModel();
                $this->id_tin_nhan = $row["id_tin_nhan"];
                $this->noi_dung = $row["noi_dung"];
                $this->thoi_gian = $row["thoi_gian"];
                $this->anh = $row["anh"];
                $this->user_gui = $row["user_gui"];
                $this->kenh_nhan = $row["kenh_nhan"];
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

    function inserttin_nhan_chung($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noi_dung = $this->conn->real_escape_string($user->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($user->thoi_gian);
        $anh = $this->conn->real_escape_string($user->anh);
        $user_gui = $this->conn->real_escape_string($user->user_gui);
        $kenh_nhan = $this->conn->real_escape_string($user->kenh_nhan);
        $sql = "INSERT INTO tin_nhan_chung (noi_dung, thoi_gian, anh, user_gui, kenh_nhan) VALUES ('$noi_dung', '$thoi_gian', '$anh', '$user_gui', '$kenh_nhan')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deletetin_nhan_chung($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tin_nhan = $this->conn->real_escape_string($user->id_tin_nhan);
        $sql = "DELETE FROM tin_nhan_chung WHERE id_tin_nhan = $id_tin_nhan";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updatetin_nhan_chung($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noi_dung = $this->conn->real_escape_string($user->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($user->thoi_gian);
        $anh = $this->conn->real_escape_string($user->anh);
        $user_gui = $this->conn->real_escape_string($user->user_gui);
        $kenh_nhan = $this->conn->real_escape_string($user->kenh_nhan);

        $sql = "UPDATE tin_nhan_chung SET noi_dung = '$noi_dung', thoi_gian = '$thoi_gian', anh = '$anh', user_gui = '$user_gui', kenh_nhan = '$kenh_nhan' WHERE id_tin_nhan = $id_tin_nhan";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
