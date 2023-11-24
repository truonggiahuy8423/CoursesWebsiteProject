<div class="main">
    <div style="margin-bottom: 30px;" class="title w-100 text-center text-uppercase">
        <h4>Danh sách lớp học</h4>
    </div>

    <div class="class-container">
        <div style="height: 30px;" class="class__search me-2 d-flex justify-content-end">

            <input style="border-radius: 0; height: 30px; width: 90px;" type="text" class="w-25 form-control search-input" placeholder="Tìm khóa học">
            <button class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
            <button class="add-class-btn highlight-button">
                <i class="fa-solid fa-plus add-class-icon highlight-icon"></i>
            </button>
        </div>

        <div class="class__list p-4 border border-gray rounded-2 m-2 mt-3 shadow-inset" style="margin-top: 8px!important;">
            <div class="row mb-4">
                <?php
                for ($i = 0; $i < count($courses); $i++) {
                    $dsgv = "";
                    $y = 0;
                    foreach ($courses[$i]['lecturers'] as $lecturer) {
                        $dsgv = $dsgv.($y != 0 ? ', ' : '').'<a href="' . base_url() . '/profile?id=' . $lecturer["id_giang_vien"] . '">' . $lecturer["ho_ten"] . '</a>';
                        $y++;
                    }
                    $status = kiem_tra_tinh_trang($courses[$i]['ngay_bat_dau'], $courses[$i]['ngay_ket_thuc']);
                    echo "
            <div class='class__item col-4 col-xxl-4 '>
                <div class='p-3 border border-gray rounded-2 shadow-sm'>
                    <div class='class__item__title mb-5'>
                        <h6>{$courses[$i]["ten_mon_hoc"]}</h6>
                        <p>Giảng viên: {$dsgv}</p>
                    </div>
                    <div class='class__item__state'>
                        <p>Thời gian: {$courses[$i]['ngay_bat_dau']} - {$courses[$i]["ngay_ket_thuc"]}</p>
                        <p>Trạng thái: {$status}</p>
                    </div>  
                </div>
            </div>
        ";
                }
                function ok($v) {
                    return $v;
                }
                function kiem_tra_tinh_trang($ngay_bat_dau, $ngay_ket_thuc) {
                    $datetime_bat_dau = DateTime::createFromFormat('d/m/Y', $ngay_bat_dau);        
                
                    $datetime_ket_thuc =  DateTime::createFromFormat('d/m/Y', $ngay_ket_thuc);  
                
                    $datetime_hien_tai = new DateTime();
                
                    $datetime_bat_dau->setTime(0, 0, 0);
                    $datetime_ket_thuc->setTime(23, 59, 59); // Đặt giờ, phút và giây về cuối ngày
                    // echo $ngay_bat_dau.$ngay_ket_thuc;
                    // echo $datetime_bat_dau->format("Y");
                    // So sánh
                    if ($datetime_bat_dau <= $datetime_hien_tai && $datetime_ket_thuc >= $datetime_hien_tai) {
                        return '<span class="class__item--inprocess">Đang diễn ra</span>';
                    } elseif ($datetime_ket_thuc < $datetime_hien_tai) {
                        return '<span class="class__item--over">Đã kết thúc</span>';
                    } else {
                        return '<span class="class__item--upcoming">Sắp diễn ra</span>';
                    }
                }
                
                ?>

            </div>
        </div>
    </div>
</div>