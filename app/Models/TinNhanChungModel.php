<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
use Exception;
include 'DatabaseConnect.php';

class TinNhanChungModel
{
    public $id_tin_nhan;
    public $noi_dung;
    public $thoi_gian;
    public $anh;
    public $user_gui;
    public $kenh_nhan;

    private $conn;
    function __construct(){}


    function getTinNhanChungById($id_tin_nhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $id_tin_nhan = $this->conn->real_escape_string($id_tin_nhan);
        $sql = "SELECT * FROM tin_nhan_chung WHERE id_tin_nhan = $id_tin_nhan";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tinNhan = new TinNhanChungModel();
            $this->id_tin_nhan = $row["id_tin_nhan"];
            $this->noi_dung = $row["noi_dung"];
            $this->thoi_gian = $row["thoi_gian"];
            $this->anh = $row["anh"];
            $this->user_gui = $row["user_gui"];
            $this->kenh_nhan = $row["kenh_nhan"];
            $this->conn->close();
            return $tinNhan;
        }
        $this->conn->close();
        return null;
    }

    function getAllTinNhanChung()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tin_nhan_chung";
        $result = $this->conn->query($sql);
        $tinNhans = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tinNhan = new TinNhanChungModel();
                $this->id_tin_nhan = $row["id_tin_nhan"];
                $this->noi_dung = $row["noi_dung"];
                $this->thoi_gian = $row["thoi_gian"];
                $this->anh = $row["anh"];
                $this->user_gui = $row["user_gui"];
                $this->kenh_nhan = $row["kenh_nhan"];
                $tinNhans[] = $tinNhan;
            }
        }
        $this->conn->close();
        return $tinNhans;
    }

    function queryDatabase($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        // $sql = $this->conn->real_escape_string()
        $result = $this->conn->query($sql);
        $rows = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }
    public function executeCustomQuery($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $result = $this->conn->query($sql);

        $rows = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }
    function insertTinNhanChung($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noi_dung = $this->conn->real_escape_string($tinNhan->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($tinNhan->thoi_gian);
        // $anh = $this->conn->real_escape_string($tinNhan->anh);
        $tinNhan_gui = $this->conn->real_escape_string($tinNhan->user_gui);
        $kenh_nhan = $this->conn->real_escape_string($tinNhan->kenh_nhan);
        $sql = "INSERT INTO tin_nhan_chung (noi_dung, thoi_gian, user_gui, kenh_nhan) VALUES ('$noi_dung', '$thoi_gian', '$tinNhan_gui', '$kenh_nhan')";
        
        try{
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function deleteTinNhanChung($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tin_nhan = $this->conn->real_escape_string($tinNhan->id_tin_nhan);
        $sql = "DELETE FROM tin_nhan_chung WHERE id_tin_nhan = $id_tin_nhan";

        try{
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function updateTinNhanChung($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $id_tin_nhan = $this->conn->real_escape_string($tinNhan->id_tin_nhan);
        $noi_dung = $this->conn->real_escape_string($tinNhan->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($tinNhan->thoi_gian);
        $anh = $this->conn->real_escape_string($tinNhan->anh);
        $tinNhan_gui = $this->conn->real_escape_string($tinNhan->user_gui);
        $kenh_nhan = $this->conn->real_escape_string($tinNhan->kenh_nhan);

        $sql = "UPDATE tin_nhan_chung SET noi_dung = '$noi_dung', thoi_gian = '$thoi_gian', anh = '$anh', user_gui = '$tinNhan_gui', kenh_nhan = '$kenh_nhan' WHERE id_tin_nhan = $id_tin_nhan";

        try{
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }
}
