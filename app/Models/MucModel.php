<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use mysqli;

include 'DatabaseConnect.php';

class MucModel
{
    public $id_muc;
    public $ten_muc;
    public $id_lop_hoc;
    public $id_muc_cha;

    private $conn;

    public function __construct()
    {
    }

    public function getMucById($mucId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM muc WHERE id_muc = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $mucId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $muc = $result->fetch_assoc();

            $this->id_muc = $muc['id_muc'];
            $this->ten_muc = $muc['ten_muc'];
            $this->id_lop_hoc = $muc['id_lop_hoc'];
            $this->id_muc_cha = $muc['id_muc_cha'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllMuc()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM muc";
        $result = $this->conn->query($sql);

        $mucs = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $muc = new MucModel();
                $muc->id_muc = $row['id_muc'];
                $muc->ten_muc = $row['ten_muc'];
                $muc->id_lop_hoc = $row['id_lop_hoc'];
                $muc->id_muc_cha = $row['id_muc_cha'];

                $mucs[] = $muc;
            }
        }
        $this->conn->close();
        return $mucs;
    }
    public function getMucByIdLopHoc($id_lop_hoc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM muc WHERE id_lop_hoc = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $id_lop_hoc);

        $stmt->execute();

        $result = $stmt->get_result();
        $folders = array();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // $row = $result->fetch_assoc();
                $muc = new MucModel();
                $muc->id_muc = $row['id_muc'];
                $muc->ten_muc = $row['ten_muc'];
                $muc->id_lop_hoc = $row['id_lop_hoc'];
                $muc->id_muc_cha = $row['id_muc_cha'];
                
                $folders[] = $muc;
            }
            $stmt->close();
            $this->conn->close();
            return $folders;
        } else {
            $stmt->close();
            $this->conn->close();
            return [];
        }
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

    public function insertMuc($muc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO muc (ten_muc, id_lop_hoc, id_muc_cha) VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $muc->ten_muc, $muc->id_lop_hoc, $muc->id_muc_cha);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Mục mới được tạo thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function updateMuc($muc)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE muc SET ten_muc = ?, id_lop_hoc = ?, id_muc_cha = ? WHERE id_muc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $muc->ten_muc, $muc->id_lop_hoc, $muc->id_muc_cha, $muc->id_muc);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function deleteMuc($mucId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM muc WHERE id_muc = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $mucId);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Delete thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function __destruct()
    {
    }
}
