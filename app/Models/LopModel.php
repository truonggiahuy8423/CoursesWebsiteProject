<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
Class LopModel
{
    public $id_lop_hoc;
    public $ngay_bat_dau;
    public $ngay_ket_thuc;
    public $id_mon_hoc;

    private $conn;
    function __construct(){}


    function getLopById($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lop = new LopModel();
            $this->id_lop_hoc = $row["id_lop_hoc"];
            $this->ngay_bat_dau = $row["ngay_bat_dau"];
            $this->ngay_ket_thuc = $row["ngay_ket_thuc"];
            $this->id_mon_hoc = $row["id_mon_hoc"];
            $this->conn->close();
            return $lop;
        }
        $this->conn->close();
        return null;
    }

    function getAllLops()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM lop_hoc";
        $result = $this->conn->query($sql);
        $lops = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lop = new LopModel();
                $this->id_lop_hoc = $row["id_lop_hoc"];
                $this->ngay_bat_dau = $row["ngay_bat_dau"];
                $this->ngay_ket_thuc = $row["ngay_ket_thuc"];
                $this->id_mon_hoc = $row["id_mon_hoc"];
                $lops[] = $lop;
            }
        }
        $this->conn->close();
        return $lops;
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

    function insertLop($lop)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $ngay_bat_dau = $this->conn->real_escape_string($lop->ngay_bat_dau);
        $ngay_ket_thuc = $this->conn->real_escape_string($lop->ngay_ket_thuc);
        $id_mon_hoc = $this->conn->real_escape_string($lop->id_mon_hoc);
        $email = $this->conn->real_escape_string($lop->email);

        $sql = "INSERT INTO lop_hoc (ngay_bat_dau, ngay_ket_thuc, id_mon_hoc) VALUES ('$ngay_bat_dau', '$ngay_ket_thuc', '$id_mon_hoc')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteLop($lop)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_lop_hoc = $this->conn->real_escape_string($lop->id_lop_hoc);
        $sql = "DELETE FROM lop_hoc WHERE id_lop_hoc = $id_lop_hoc";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateLop($lop)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_lop_hoc = $this->conn->real_escape_string($lop->id_lop_hoc);
        $ngay_bat_dau = $this->conn->real_escape_string($lop->ngay_bat_dau);
        $ngay_ket_thuc = $this->conn->real_escape_string($lop->ngay_ket_thuc);
        $id_mon_hoc = $this->conn->real_escape_string($lop->id_mon_hoc);

        $sql = "UPDATE lop_hoc SET ngay_bat_dau = '$ngay_bat_dau', ngay_ket_thuc = '$ngay_ket_thuc', id_mon_hoc = '$id_mon_hoc' WHERE id_lop_hoc = $id_lop_hoc";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
