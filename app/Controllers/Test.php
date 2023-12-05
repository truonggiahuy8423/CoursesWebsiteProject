<?php
namespace App\Controllers;
use Config\Paths;
use CodeIgniter\HTTP\Response;

class Test extends BaseController
{
    public string $path;

    public function __construct()
    {
        $this->path = (new Paths())->filesPath;
    }

    public function index()
    {
        // Đường dẫn đầy đủ đến file PDF
        $pdfFilePath = $this->path . '/21522172QLDA.pdf';

        // Kiểm tra xem tệp có tồn tại không
        if (file_exists($pdfFilePath)) {
            // Đặt tên file khi tải về
            $downloadFileName = '21522172QLDA.pdf';

            // Thiết lập header cho tệp PDF
            $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'attachment;filename="' . $downloadFileName . '"')
                ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->setHeader('Pragma', 'no-cache')
                ->setHeader('Expires', '0');

            // Đọc nội dung của file PDF và truyền vào response
            $pdfContent = file_get_contents($pdfFilePath);
            $this->response->setBody($pdfContent);
            echo "Done";

            // Gửi response
            return $this->response;
        } else {
            // Trường hợp file không tồn tại
            return $this->response->setJSON(['error' => 'File not found']);
        }
    }
}
?>