
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem dữ liệu POST đã được gửi chưa
    if (!empty($_POST['idBuoiHoc'])) {
        $idBuoiHoc = $_POST['idBuoiHoc']; // Lấy dữ liệu từ POST request
        
        // Xử lý dữ liệu ở đây
        
        // Ví dụ: In ra dữ liệu đã nhận được
        echo "Dữ liệu đã nhận được từ idbuoihoc là: " . $idBuoiHoc;
    } else {
        // Nếu không nhận được dữ liệu POST
        echo "Không nhận được dữ liệu từ yêu cầu POST!";
    }
} else {
    // Nếu không phải yêu cầu POST
    echo "Đây không phải là yêu cầu POST!";
}    
$currentFile = $_SERVER['PHP_SELF'];
echo "Đường dẫn tới file hiện tại: $currentFile"; 

    
echo 'smile'
?>
<script>
let currentUrl = window.location.href;

// Kiểm tra nếu idbuoihoc chưa được thêm vào URL
if (currentUrl.indexOf('idbuoihoc=') === -1) {
    // Thêm idbuoihoc=110 vào URL
    let newUrl = currentUrl + (currentUrl.indexOf('?') !== -1 ? '&' : '?') + 'idbuoihoc=110';

    // Thay đổi URL trong lịch sử trình duyệt mà không tải lại trang
    window.history.replaceState({}, document.title, newUrl);

    console.log('URL đã được thay đổi thành:', newUrl);
}


document.addEventListener("DOMContentLoaded", function() {
    let urlParams = new URLSearchParams(window.location.search);
    let idBuoiHoc = urlParams.get('idbuoihoc');

    if (idBuoiHoc) {
        let xhr = new XMLHttpRequest();
        let url = "/PA.php"; // Thay đổi thành đường dẫn tới file PHP của bạn

        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Xử lý phản hồi từ file PHP
            }
        };

        xhr.send("idBuoiHoc=" + idBuoiHoc);
    }
});
</script>