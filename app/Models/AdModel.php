<?php

namespace App\Models;

use CodeIgniter\Model;
use mysqli;
include 'DatabaseConnect.php';

class AdModel extends Model
{
    public $id_ad;
    public $ho_ten;
    public $email;
    private $conn;

    public function __construct()
    {

    }

    public function getAdById($adId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM ad WHERE id_ad = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("i", $adId);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $ad = $result->fetch_assoc();

            $this->id_ad = $ad['id_ad'];
            $this->ho_ten = $ad['ho_ten'];

            $stmt->close();
            $this->conn->close();
            return $this;
        } else {
            $stmt->close();
            $this->conn->close();
            return null;
        }
    }

    public function getAllAd()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "SELECT * FROM ad";
        $result = $this->conn->query($sql);

        $ads = array();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ad = new AdModel();
                $ad->id_ad = $row['id_ad'];
                $ad->ho_ten = $row['ho_ten'];
                $ad->email = $row['email'];
                $ads[] = $ad;
            }
        }
        $this->conn->close();
        return $ads;
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

    public function insertAd($ad)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "INSERT INTO ad (ho_ten) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ad->ho_ten);

        if ($stmt->execute()) {
            //$this->id_ad = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Insert thành công'];
        } else {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }
    }

    public function updateAd($ad)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "UPDATE ad SET ho_ten = ? WHERE id_ad = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $ad->ho_ten, $ad->id_ad);

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

    public function deleteAd($adId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $sql = "DELETE FROM ad WHERE id_ad = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $adId);

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

?>
