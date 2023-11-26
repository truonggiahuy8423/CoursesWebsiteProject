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
                        <td><?php echo $ten_mon_hoc?></td>
                    </tr>
                    <tr>
                        <td>Ngày bắt đầu</td>
                        <td><?php echo $ngay_bat_dau?></td>
                    </tr>
                    <tr>
                        <td>Ngày kết thúc</td>
                        <td><?php echo $ngay_ket_thuc?></td>
                    </tr>
                    <tr>
                        <td>Tổng số buổi</td>
                        <td><?php echo $so_luong_buoi_hoc?></td>
                    </tr>
                    <tr>
                        <td>Số buổi đã học</td>
                        <td><?php echo $so_luong_buoi_hoc_da_hoc?></td>
                    </tr>
                    <tr>
                        <td>SL thành viên</td>
                        <td><?php echo $so_luong_hoc_vien?></td>
                    </tr>
                    <tr>
                        <td>SL giảng viên</td>
                        <td><?php echo $so_luong_giang_vien?></td>
                    </tr>
                </tbody>
            </table>
            <h3 style="margin-left: 27px;">Danh sách giảng viên</h3>
            <div class="list-of-lecturers-container">
                <a href=""><span>Mai Xuân Hùng </span>&nbsp;-&nbsp;<span> mxhung01@gm.uit.edu.vn</span></a>
                <a href=""><span>Vũ Minh Sang </span>&nbsp;-&nbsp;<span> vmsang01@gm.uit.edu.vn</span></a>
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
                    <tr  class="student-row">
                        <td>00080423</td>
                        <td>Trương Gia Huy
                            <a href=""><i style="margin-left: 4px;" class="fa-solid fa-square-arrow-up-right"></i></a>
                        </td>
                        <td>Nam</td>
                        <td>08/04/2003</td>
                        <td>tghuy01@gmail.com</td>
                        <td>0</td>
                    </tr>
                    <tr  class="student-row">
                        <td>00080423</td>
                        <td>Trương Gia Huy
                            <a href=""><i style="margin-left: 4px;" class="fa-solid fa-square-arrow-up-right"></i></a>
                        </td>
                        <td>Nam</td>
                        <td>08/04/2003</td>
                        <td>tghuy01@gmail.com</td>
                        <td>0</td>
                    </tr>
                    <tr  class="student-row">
                        <td>00080423</td>
                        <td>Trương Gia Huy
                            <a href=""><i style="margin-left: 4px;" class="fa-solid fa-square-arrow-up-right"></i></a>
                        </td>
                        <td>Nam</td>
                        <td>08/04/2003</td>
                        <td>tghuy01@gmail.com</td>
                        <td>0</td>
                    </tr>
                    <tr  class="student-row">
                        <td>00080423</td>
                        <td>Trương Gia Huy
                            <a href=""><i style="margin-left: 4px;" class="fa-solid fa-square-arrow-up-right"></i></a>
                        </td>
                        <td>Nam</td>
                        <td>08/04/2003</td>
                        <td>tghuy01@gmail.com</td>
                        <td>0</td>
                    </tr>
                    <tr  class="student-row">
                        <td>00080423</td>
                        <td>Trương Gia Huy
                            <a href=""><i style="margin-left: 4px;" class="fa-solid fa-square-arrow-up-right"></i></a>
                        </td>
                        <td>Nam</td>
                        <td>08/04/2003</td>
                        <td>tghuy01@gmail.com</td>
                        <td>0</td>
                    </tr>
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
                    <tr>
                        <td>2109213243</td>
                        <td>Thứ 6 20/10/2023</td>
                        <td>101</td>
                        <td>07:00:00</td>
                        <td>11:00:00</td>
                        <td><span style="color: #52ec4d;">Đã diễn ra</span></td>
                    </tr>
                    <tr>
                        <td>2109213243</td>
                        <td>Thứ 6 20/10/2023</td>
                        <td>101</td>
                        <td>07:00:00</td>
                        <td>11:00:00</td>
                        <td><span style="color: #52ec4d;">Đã diễn ra</span></td>
                    </tr>
                    <tr>
                        <td>2109213243</td>
                        <td>Thứ 6 20/10/2023</td>
                        <td>101</td>
                        <td>07:00:00</td>
                        <td>11:00:00</td>
                        <td><span style="color: #52ec4d;">Đã diễn ra</span></td>
                    </tr>
                    <tr>
                        <td>2109213243</td>
                        <td>Thứ 6 20/10/2023</td>
                        <td>101</td>
                        <td>07:00:00</td>
                        <td>11:00:00</td>
                        <td><span style="color: #52ec4d;">Đã diễn ra</span></td>
                    </tr>
                    <tr>
                        <td>2109213243</td>
                        <td>Thứ 6 20/10/2023</td>
                        <td>101</td>
                        <td>07:00:00</td>
                        <td>11:00:00</td>
                        <td><span style="color: #52ec4d;">Đã diễn ra</span></td>
                    </tr>
                </tbody>
            </table>
        </div>