<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';
use Exception;
class LinkModel
{
    public $id_duong_link;
    public $link;
    public $ngay_dang;
    public $id_giang_vien;
    public $id_muc;
    public $tieu_de;
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

        $sql = "SELECT * FROM duong_link";
        $result = $this->conn->query($sql);
        $duong_links = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $duong_link = new LinkModel();
                $duong_link->id_duong_link = $row["id_duong_link "];
                $duong_link->link = $row["link "];
                $duong_link->ngay_dang = $row["ngay_dang"];
                $duong_link->id_giang_vien = $row["id_giang_vien"];
                $duong_link->id_muc = $row["id_muc"];
                $duong_links[] = $duong_link;
            }
        }
        $this->conn->close();
        return $duong_links;
    }
    function getAllLinksByCourseId($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT duong_link.*, giang_vien.ho_ten FROM duong_link inner join muc on duong_link.id_muc = muc.id_muc inner join giang_vien on giang_vien.id_giang_vien = duong_link.id_giang_vien where muc.id_lop_hoc = ? order by duong_link.ngay_dang ASC";
        // $result = $this->conn->query($sql);
        $duong_links = [];
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id_lop_hoc);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $duong_link = array(
                    'id_duong_link' => $row["id_duong_link"],
                    'link' => $row["link"],
                    'ngay_dang' => $row["ngay_dang"],
                    'id_giang_vien' => $row["id_giang_vien"],
                    'id_muc' => $row["id_muc"],
                    'tieu_de' => $row["tieu_de"],
                    'ho_ten' => $row["ho_ten"]
                );
                
                $duong_links[] = $duong_link;
            }
            $stmt->close();
            $this->conn->close();
            return $duong_links;
        }
        $stmt->close();
        $this->conn->close();
        return [];
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
    public function insertLink($dlink)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO duong_link(link, ngay_dang, id_giang_vien, id_muc, tieu_de) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiis", $dlink->link, $dlink->ngay_dang, $dlink->id_giang_vien, $dlink->id_muc, $dlink->tieu_de);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Đường dẫn được thêm thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    function deleteLink($duong_link)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_duong_link = $this->conn->real_escape_string($duong_link->id_duong_link);
        $id_muc = $this->conn->real_escape_string($duong_link->id_muc);
        $sql = "DELETE FROM duong_link WHERE id_duong_link  = ? AND id_muc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_duong_link, $id_muc);

        try {
            $stmt->execute();
            $deletedRows = $stmt->affected_rows;

            $stmt->close();
            $this->conn->close();

            if ($deletedRows > 0) {
                return ['state' => true, 'message' => "Đã xóa đường dẫn thành công"];
            } else {
                return ['state' => false, 'message' => 'Không có đường dẫn nào bị xóa'];
            }
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
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
