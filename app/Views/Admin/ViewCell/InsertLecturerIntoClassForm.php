<div class="form-container">
    <div class="insert-lecturer-form">
        <div class="insert-lecturer-form__title-section">
            <h5 class="insert-lecturer-form__title">Thêm giảng viên</h5>
        </div>
        <div class="insert-lecturer-form__search-bar-container">
            <input style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-lecturer-input" placeholder="Tìm giảng viên">
            <button class="btn btn-info search-lecturer-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
        </div>
        <div class="insert-lecturer-form__lecturers-table-container">
            <table class="insert-lecturer-form__lecturers-table">
                <thead>
                    <tr>
                        <td>Mã giảng viên</td>
                        <td>Họ tên</td>
                        <td>Giới tính</td>
                        <td>Ngày sinh</td>
                        <td>Email</td>
                        <td>Chọn</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                        for ($i = 0; $i < count($lecturers); $i++) {
                            $gioi_tinh = $lecturers[$i]->gioi_tinh == 0 ? "Nữ" : "Nam";
                            $id_giang_vien = $lecturers[$i]->id_giang_vien;
                            $ho_ten = $lecturers[$i]->ho_ten;
                            $email = $lecturers[$i]->email;
                            $ns = chuyenDinhDangNgay($lecturers[$i]->ngay_sinh);
                            echo "
                                    <tr>
                                        <td>{$id_giang_vien}</td>
                                        <td>{$ho_ten}</td>
                                        <td>{$gioi_tinh}</td>
                                        <td>{$ns}</td>
                                        <td>{$email}</td>
                                        <td><input class=\"lecturer-checkbox\" type=\"checkbox\" value=\"{$id_giang_vien}\"></td>
                                    </tr>
                                ";
                        }
                        function chuyenDinhDangNgay($ngayInput) {
                            // Chuyển đổi ngày từ chuỗi sang đối tượng DateTime
                            $ngayDateTime = DateTime::createFromFormat('Y-m-d', $ngayInput);
                            
                            // Chuyển định dạng của đối tượng DateTime sang "d/m/Y"
                            $ngayChuyenDoi = $ngayDateTime->format('d/m/Y');
                            
                            return $ngayChuyenDoi;
                        }
                    ?>
                    
                </tbody>
            </table>
        </div>
        <div class="insert-lecturer-form__btn-container">
            <button class="insert-lecturer-form__cancel-btn">Hủy</button>
            <button class="insert-lecturer-form__save-btn">Lưu thông tin</button>
        </div>
    </div>
</div>
