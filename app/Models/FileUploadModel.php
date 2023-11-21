<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
class FileUploadModel
{
    public $fileId;
    public $duLieu;
    public $ngayTai;
    public $idUser;

    private $conn;
    function __construct($fileId, $duLieu, $ngayTai, $idUser)
    {
        $this->fileId = $fileId;
        $this->duLieu = $duLieu;
        $this->ngayTai = $ngayTai;
        $this->idUser = $idUser;
    }


    function getFileUploadById($fileId)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tep_tin_tai_len WHERE id_tep_tin_tai_len = $fileId";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = new FileUploadModel($row["id_tep_tin_tai_len"], $row["du_lieu"], $row["ngay_tai_len"], $row["id_user"]);
            $this->conn->close();
            return $user;
        }
        $this->conn->close();
        return null;
    }

    function getAllFileUploads()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tep_tin_tai_len";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new FileUploadModel($row["id_tep_tin_tai_len"], $row["du_lieu"], $row["ngay_tai_len"], $row["id_user"]);
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

    function insertFileUpload($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $duLieu = $this->conn->real_escape_string($user->duLieu);
        $ngayTai = $this->conn->real_escape_string($user->ngayTai);
        $idUser = $this->conn->real_escape_string($user->idUser);

        $sql = "INSERT INTO tep_tin_tai_len (du_lieu, ngay_tai_len, id_user) VALUES ('$duLieu', '$ngayTai', '$idUser')";
        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function deleteFileUpload($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $fileId = $this->conn->real_escape_string($user->fileId);
        $sql = "DELETE FROM tep_tin_tai_len WHERE id_tep_tin_tai_len = $fileId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }

    function updateFileUpload($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $fileId = $this->conn->real_escape_string($user->fileId);
        $duLieu = $this->conn->real_escape_string($user->duLieu);
        $ngayTai = $this->conn->real_escape_string($user->ngayTai);
        $idUser = $this->conn->real_escape_string($user->idUser);

        $sql = "UPDATE tep_tin_tai_len SET du_lieu = '$duLieu', ngay_tai_len = '$ngayTai', id_user = '$idUser' WHERE id_tep_tin_tai_len = $fileId";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
