<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class LinkModel
{
    public $linkId;
    public $link;
    public $ngayDang;
    public $idGiangVien;
    public $idMuc;

    private $conn;
    function __construct(){}


    function getLinkById($linkId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_duong_link  = $linkId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new LinkModel();
            $this->linkId = $row["id_duong_link "];
            $this->link = $row["link "];
            $this->ngayDang = $row["ngay_dang"];
            $this->idGiangVien = $row["id_giang_vien"];
            $this->idMuc = $row["id_muc"];
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAllLinks()
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
                $user = new LinkModel();
                $this->linkId = $row["id_duong_link "];
                $this->link = $row["link "];
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

    function insertLink($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $link = $this->conn->real_escape_string($user->link);
        $ngayDang = $this->conn->real_escape_string($user->ngayDang);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);

        $sql = "INSERT INTO duong_link  (link , ngay_dang, id_giang_vien, id_muc ) VALUES ('$link', '$ngayDang', '$idGiangVien', '$idMuc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteLink($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $linkId = $this->conn->real_escape_string($user->id);
        $sql = "DELETE FROM hoc_vien WHERE id_duong_link  = $linkId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateLink($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $linkId = $this->conn->real_escape_string($user->linkId);
        $link = $this->conn->real_escape_string($user->link);
        $ngayDang = $this->conn->real_escape_string($user->ngayDang);
        $idGiangVien = $this->conn->real_escape_string($user->idGiangVien);
        $idMuc = $this->conn->real_escape_string($user->idMuc);

        $sql = "UPDATE hoc_vien SET link  = '$link', ngay_dang = '$ngayDang', id_giang_vien = '$idGiangVien', id_muc = '$idMuc' WHERE id_duong_link  = $linkId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
