<?php
namespace App\Models;

use CodeIgniter\Model;
use Exception;
use mysqli;
include 'DatabaseConnect.php';

class FileUploadModel
{
    public $id_tep_tin_tai_len;
    public $du_lieu;
    public $ngay_tai_len;
    public $id_user;
    public $extension;
    public $ten_tep;
    private $conn;
    function __construct(){}


    function getFileUploadById($id_tep_tin_tai_len)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tep_tin_tai_len WHERE id_tep_tin_tai_len = $id_tep_tin_tai_len";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
            $this->du_lieu = $row["decoded"];
            $this->ngay_tai_len = $row["ngay_tai_len"];
            $this->id_user = $row["id_user"];
            $this->extension = $row["extension"];
            $this->ten_tep = $row["ten_tep"];
            $this->conn->close();
            return $this;
        }
        $this->conn->close();
        return null;
    }
    function getListOfFilesByUserId($id_user) {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }
        $id_user = $this->conn->real_escape_string($id_user);
        $sql = "SELECT * FROM tep_tin_tai_len where id_user = $id_user order by ngay_tai_len DESC";
        $result = $this->conn->query($sql);
        $files = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $file = new FileUploadModel();
                $file->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
                $file->du_lieu = $row["decoded"];
                $file->ngay_tai_len = $row["ngay_tai_len"];
                $file->id_user = $row["id_user"];
                $file->ten_tep = $row["ten_tep"];
                $file->extension = $row["extension"];
                $files[] = $file;
            }
        }
        $this->conn->close();
        return $files;
    }
    function getAllFileUploads()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM tep_tin_tai_len";
        $result = $this->conn->query($sql);
        $files = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $file = new FileUploadModel();
                $this->id_tep_tin_tai_len = $row["id_tep_tin_tai_len"];
                $this->du_lieu = $row["du_lieu"];
                $this->ngay_tai_len = $row["ngay_tai_len"];
                $this->id_user = $row["id_user"];
                $this->conn->close();
                $files[] = $file;
            }
        }
        $this->conn->close();
        return $files;
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

    function insertFileUpload($file)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $decoded = $this->conn->real_escape_string($file->du_lieu);
        $ngay_tai_len = $this->conn->real_escape_string($file->ngay_tai_len);
        $id_user = $this->conn->real_escape_string($file->id_user);
        $ten_tep = $this->conn->real_escape_string($file->ten_tep);
        $extension = $this->conn->real_escape_string($file->extension);
        // $id_user = $this->conn->real_escape_string($file->id_user);

        $sql = "INSERT INTO tep_tin_tai_len (decoded, ngay_tai_len, id_user, ten_tep, extension) VALUES (?, ?, ?, ?, ?)";



        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssiss', $decoded, $ngay_tai_len, $id_user, $ten_tep, $extension);

        try {
            $stmt->execute();
            $insertedId = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Tệp tin tải lên thành công', 'auto_increment_id' => $insertedId];
        } catch (Exception $e) {
            $error_message = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $error_message];
        }
    }

    function deleteFileUpload($file)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tep_tin_tai_len = $this->conn->real_escape_string($file->id_tep_tin_tai_len);
        $sql = "DELETE FROM tep_tin_tai_len WHERE id_tep_tin_tai_len = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssiss', $decoded, $ngay_tai_len, $id_user, $ten_tep, $extension);

        try {
            $stmt->execute();
            $insertedId = $this->conn->insert_id;
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Tệp tin tải lên thành công', 'auto_increment_id' => $insertedId];
        } catch (Exception $e) {
            $error_message = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $error_message];
        }
    }

    function updateFileUpload($file)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_tep_tin_tai_len = $this->conn->real_escape_string($file->id_tep_tin_tai_len);
        $du_lieu = $this->conn->real_escape_string($file->du_lieu);
        $ngay_tai_len = $this->conn->real_escape_string($file->ngay_tai_len);
        $id_user = $this->conn->real_escape_string($file->id_user);

        $sql = "UPDATE tep_tin_tai_len SET du_lieu = '$du_lieu', ngay_tai_len = '$ngay_tai_len', id_user = '$id_user' WHERE id_tep_tin_tai_len = $id_tep_tin_tai_len";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => 'Update thành công'];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    }
}
