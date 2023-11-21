<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class NotificationModel
{
    public $notiId;
    public $noiDung;
    public $ngayDang;
    public $idGiangVien;
    public $idMuc;

    private $conn;
    function __construct(){}


    function getNotificationModelById($notiId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM thong_bao WHERE id_thong_bao = $notiId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new NotificationModel();
            $this->notiId = $row["id_thong_bao"];
            $this->noiDung = $row["noi_dung "];
            $this->ngayDang = $row["ngay_dang"];
            $this->idGiangVien = $row["id_giang_vien"];
            $this->idMuc = $row["id_muc"];
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAllNotificationModels()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM thong_bao";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new NotificationModel();
                $this->notiId = $row["id_thong_bao"];
                $this->noiDung = $row["noi_dung "];
                $this->ngayDang = $row["ngay_dang"];
                $this->idGiangVien = $row["id_giang_vien"];
                $this->idMuc = $row["id_muc"];
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

    function insertNotificationModel($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noiDung = $this->conn->real_escape_string($user->noiDung);
        $ngayDang = $this->conn->real_escape_string($user->ngayDang);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);

        $sql = "INSERT INTO thong_bao (noi_dung , ngay_dang, id_giang_vien, id_muc) VALUES ('$noiDung', '$ngayDang', '$idGiangVien', '$idMuc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteNotificationModel($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $notiId = $this->conn->real_escape_string($user->notiId);
        $sql = "DELETE FROM thong_bao WHERE id_thong_bao = $notiId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateNotificationModel($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $notiId = $this->conn->real_escape_string($user->notiId);
        $noiDung = $this->conn->real_escape_string($user->noiDung);
        $ngayDang = $this->conn->real_escape_string($user->ngayDang);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);

        $sql = "UPDATE thong_bao SET noi_dung  = '$noiDung', ngay_dang = '$ngayDang', id_giang_vien = '$idGiangVien', id_muc = '$idMuc' WHERE id_thong_bao = $notiId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
