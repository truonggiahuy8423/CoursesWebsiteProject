<div class="content-section">
    <h3 style="margin-top: 17px; margin-left: 27px;">Thông tin chi tiết</h3>
    <table class="class_infor-table">
        <tbody>
            <tr>
                <td>Mã lớp</td>
                <td><?php echo str_pad($id_lop_hoc, 6, "0", STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <td>Mã môn học</td>
                <td><?php echo str_pad($id_mon_hoc, 3, "0", STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <td>Tên môn học</td>
                <td><?php echo $ten_mon_hoc ?></td>
            </tr>
            <tr>
                <td>Ngày bắt đầu</td>
                <td><?php echo $ngay_bat_dau ?></td>
            </tr>
            <tr>
                <td>Ngày kết thúc</td>
                <td><?php echo $ngay_ket_thuc ?></td>
            </tr>
            <tr>
                <td>Tổng số buổi</td>
                <td><?php echo $so_luong_buoi_hoc ?></td>
            </tr>
            <tr>
                <td>Số buổi đã học</td>
                <td><?php echo $so_luong_buoi_hoc_da_hoc ?></td>
            </tr>
            <tr>
                <td>SL thành viên</td>
                <td><?php echo $so_luong_hoc_vien ?></td>
            </tr>
            <tr>
                <td>SL giảng viên</td>
                <td><?php echo $so_luong_giang_vien ?></td>
            </tr>
        </tbody>
    </table>
    <h3 style="margin-left: 27px;">Danh sách giảng viên</h3>
    <div class="list-of-lecturers-container">
        <?php
        if (count($danh_sach_giang_vien) == 0) {
            echo '<p style="color: #e8e8e8;">Chưa có giảng viên</p>';
        } else {
            foreach ($danh_sach_giang_vien as $giang_vien) {
                $profile_url = base_url() . "profile/lecturer?id=" . $giang_vien["id_giang_vien"];
                echo "
                                <a href='{$profile_url}'><span>{$giang_vien['ho_ten']}</span>&nbsp;-&nbsp;<span>{$giang_vien['email']}</span></a><br>
                            ";
            }
        }
        ?>
    </div>
    <h3 style="margin-left: 27px;">Danh sách học viên</h3>
    <table class="students-table">
        <thead>
            <tr>
                <td>Mã học viên</td>
                <td>Họ tên</td>
                <td>Giới tính</td>
                <td>Ngày sinh</td>
                <td>Email</td>
                <td>Số buổi vắng</td>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($danh_sach_hoc_vien) == 0) {
                echo '
                    <tr>
                     <td colspan="6" style="height: 500px;">Chưa có học viên</td>
                    </tr>
                ';
            } else {
                foreach ($danh_sach_hoc_vien as $hoc_vien) {
                    $profile_url = base_url() . "profile/student?id=" . $hoc_vien["id_hoc_vien"];
                    $gioi_tinh = $hoc_vien['gioi_tinh'] == 1 ? "Nam" : "Nữ" ;
                    $id_hoc_vien = str_pad($hoc_vien['id_hoc_vien'], 8,'0', STR_PAD_LEFT);
                    echo "
                    <tr class='student-row'>
                        <td>{$id_hoc_vien}</td>
                        <td> {$hoc_vien['ho_ten']}
                            <a href='{$profile_url}'><i style='margin-left: 4px;' class='fa-solid fa-square-arrow-up-right'></i></a>
                        </td>
                        <td>{$gioi_tinh}</td>
                        <td>{$hoc_vien['ngay_sinh_hv']}</td>
                        <td>{$hoc_vien['email']}</td>
                        <td>{$hoc_vien['so_buoi_vang']}</td>
                    </tr>
                    ";
                                    
                                
                }
            }
            ?>
        </tbody>
    </table>
    <h3 style="margin-left: 27px;">Danh sách buổi học</h3>
    <table class="schedule-table">
        <thead>
            <tr>
                <td>Mã buổi học</td>
                <td>Ngày học</td>
                <td>Phòng</td>
                <td>Giờ bắt đầu</td>
                <td>Giờ kết thúc</td>
                <td>
                    Trạng thái
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($danh_sach_buoi_hoc as $buoi_hoc) {
                    $state = "";
                    switch ($buoi_hoc['trang_thai']) {
                        case 0:
                            $state = "<span style='color: #FFA33C'>Chưa diễn ra</span>";
                            break;
                        case 1:
                            $state = "<span style='color: #52ec4d'>Đã hoàn thành</span>";
                            break;
                        case 2:
                            $state = "<span style='color: #ff0000'>Đã hủy</span>";
                            break;
                    }
                    echo "
                    <tr>
                        <td>{$buoi_hoc['id_buoi_hoc']}</td>
                        <td>Thứ&nbsp{$buoi_hoc['thu']}&nbsp{$buoi_hoc['ngay_hoc']}</td>
                        <td>{$buoi_hoc['id_phong']}</td>
                        <td>{$buoi_hoc['thoi_gian_bat_dau']}</td>
                        <td>{$buoi_hoc['thoi_gian_ket_thuc']}</td>
                        <td>{$state}</td>
                    </tr>
                    ";
                }
            ?>
        </tbody>
    </table>
</div>