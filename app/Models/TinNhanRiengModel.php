<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
use Exception;
include 'DatabaseConnect.php';
class TinNhanRiengModel
{
    public $id_tin_nhan;
    public $noi_dung;
    public $thoi_gian;
    public $anh;
    public $user_gui;
    public $user_nhan;

    private $conn;
    function __construct(){}


    function getTinNhanRiengById($id_tin_nhan, )
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tin_nhan_rieng WHERE id_tin_nhan = $id_tin_nhan";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tinNhan = new TinNhanRiengModel();
            $this->id_tin_nhan = $row["id_tin_nhan"];
            $this->noi_dung = $row["noi_dung"];
            $this->thoi_gian = $row["thoi_gian"];
            $this->anh = $row["anh"];
            $this->user_gui = $row["user_gui"];
            $this->user_nhan = $row["user_nhan"];
            $this->conn->close();
            return $tinNhan;
        }
        $this->conn->close();
        return null;
    }
    
    function getAllTinNhanRieng()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tin_nhan_rieng";
        $result = $this->conn->query($sql);
        $tinNhans = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tinNhan = new TinNhanRiengModel();
                $this->id_tin_nhan = $row["id_tin_nhan"];
                $this->noi_dung = $row["noi_dung"];
                $this->thoi_gian = $row["thoi_gian"];
                $this->anh = $row["anh"];
                $this->user_gui = $row["user_gui"];
                $this->user_nhan = $row["user_nhan"];
                $tinNhans[] = $tinNhan;
            }
        }
        $this->conn->close();
        return $tinNhans;
    }
    function test($sql){
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        // if ($this->conn->connect_error) {
        //     die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        // }
        // $sql = $this->conn->real_escape_string()
        // $result = $this->conn->query($sql);
        $rows = array();

        // if ($result && $result->num_rows > 0) {
        //     while ($row = $result->fetch_assoc()) {
        //         $rows[] = $row;
        //     }
        // }
        // $this->conn->close();
        return $sql;
    }
    function getInBox($user_gui, $user_nhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $user_gui = $this->conn->real_escape_string($user_gui);
        $user_nhan = $this->conn->real_escape_string($user_nhan);
        $sql = "SELECT noi_dung, user_gui
             FROM tin_nhan_rieng
             WHERE user_gui IN (' $user_nhan ','$user_gui ')
             AND user_nhan IN (' $user_nhan ',' $user_gui ')
             ORDER BY thoi_gian";
        $result = $this->conn->query($sql);
        $tinNhans = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tinNhan = new TinNhanRiengModel();
                $this->noi_dung = $row["noi_dung"];
                $this->user_gui = $row["user_gui"];
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

    function insertTinNhanRieng($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $noi_dung = $this->conn->real_escape_string($tinNhan->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($tinNhan->thoi_gian);
        $anh = $this->conn->real_escape_string($tinNhan->anh);
        $user_gui = $this->conn->real_escape_string($tinNhan->user_gui);
        $user_nhan = $this->conn->real_escape_string($tinNhan->kenh_nhan);
        $sql = "INSERT INTO tin_nhan_rieng (noi_dung, thoi_gian, anh, user_gui, kenh_nhan) VALUES ('$noi_dung', '$thoi_gian', '$anh', '$user_gui', '$user_nhan')";
        try{
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function deleteTinNhanRieng($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tin_nhan = $this->conn->real_escape_string($tinNhan->id_tin_nhan);
        $sql = "DELETE FROM tin_nhan_rieng WHERE id_tin_nhan = $id_tin_nhan";

        try{
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } catch(Exception $e) {
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }
    }

    function updateTinNhanRieng($tinNhan)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tin_nhan = $this->conn->real_escape_string($tinNhan->id_tin_nhan);
        $noi_dung = $this->conn->real_escape_string($tinNhan->noi_dung);
        $thoi_gian = $this->conn->real_escape_string($tinNhan->thoi_gian);
        $anh = $this->conn->real_escape_string($tinNhan->anh);
        $user_gui = $this->conn->real_escape_string($tinNhan->user_gui);
        $user_nhan = $this->conn->real_escape_string($tinNhan->user_nhan);

        $sql = "UPDATE tin_nhan_rieng SET noi_dung = '$noi_dung', thoi_gian = '$thoi_gian', anh = '$anh', user_gui = '$user_gui', kenh_nhan = '$user_nhan' WHERE id_tin_nhan = $id_tin_nhan";

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
