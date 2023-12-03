<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class LinkModel
{
    public $id_duong_link;
    public $link;
    public $ngay_dang;
    public $id_giang_vien;
    public $id_muc;

    private $conn;
    function __construct(){}


    function getLinkById($id_duong_link)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM hoc_vien WHERE id_duong_link  = $id_duong_link";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id_duong_link = $row["id_duong_link "];
            $this->link = $row["link "];
            $this->ngay_dang = $row["ngay_dang"];
            $this->id_giang_vien = $row["id_giang_vien"];
            $this->id_muc = $row["id_muc"];
            $this->conn->close();
            return $this;
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
        $duong_links = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $duong_link = new LinkModel();
                $this->id_duong_link = $row["id_duong_link "];
                $this->link = $row["link "];
                $this->ngay_dang = $row["ngay_dang"];
                $this->id_giang_vien = $row["id_giang_vien"];
                $this->id_muc = $row["id_muc"];
                $duong_links[] = $duong_link;
            }
        }
        $this->conn->close();
        return $duong_links;
    }

    function executeCustomQuery($sql)
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

    function insertLink($duong_link)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $link = $this->conn->real_escape_string($duong_link->link);
        $ngay_dang = $this->conn->real_escape_string($duong_link->ngay_dang);
        $id_giang_vien = $this->conn->real_escape_string($duong_link->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($duong_link->id_muc);

        $sql = "INSERT INTO duong_link  (link , ngay_dang, id_giang_vien, id_muc ) VALUES ('$link', '$ngay_dang', '$id_giang_vien', '$id_muc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteLink($duong_link)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_duong_link = $this->conn->real_escape_string($duong_link->id);
        $sql = "DELETE FROM hoc_vien WHERE id_duong_link  = $id_duong_link";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateLink($duong_link)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_duong_link = $this->conn->real_escape_string($duong_link->id_duong_link);
        $link = $this->conn->real_escape_string($duong_link->link);
        $ngay_dang = $this->conn->real_escape_string($duong_link->ngay_dang);
        $id_giang_vien = $this->conn->real_escape_string($duong_link->id_giang_vien);
        $id_muc = $this->conn->real_escape_string($duong_link->id_muc);

        $sql = "UPDATE hoc_vien SET link  = '$link', ngay_dang = '$ngay_dang', id_giang_vien = '$id_giang_vien', id_muc = '$id_muc' WHERE id_duong_link  = $id_duong_link";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
