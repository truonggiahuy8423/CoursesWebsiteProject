<div class="form-container">
    <div class="insert-class-form">
        <div class="insert-class-form__title-section">
            <h5 class="insert-class-form__title">Thêm khóa học</h5>
        </div>
        <div class="insert-class-form__date-picker-container">
            <div style="display: inline-block; margin-right: 20%; margin-bottom: 10px;"><span style="margin-right: 2px;">Ngày bắt đầu </span><input class="insert-class-form__begin-date-picker" type="date"></div>
            <div style="display: inline-block;"><span>Ngày kết thúc </span><input class="insert-class-form__end-date-picker" type="date"></div>
        </div>
        <div class="insert-class-form__subject-cbb-container">
            <span style="margin-left: 20px; margin-right: 10px;">Môn học</span>
            <select class="insert-class-form__subject-cbb" name="mon_hoc" size="4" id="">
                
                <?php
                
                for ($i = 0; $i < count($subjects); $i++) {
                    echo '
                        <option value="' . $subjects[$i]->id_mon_hoc . '">
                            ' .  str_pad($subjects[$i]->id_mon_hoc, 3, '0', STR_PAD_LEFT) . ' - ' . $subjects[$i]->ten_mon_hoc . '
                        </option>
                    ';
                }
                
                ?>
            </select>

            <br>
        </div>
        <p class="error-message" style="color: red;"></p>
        <span style="margin-left: 20px; margin-bottom: 10px;">Danh sách giảng viên</span>
        <div class="insert-class-form__table-container">
            <table class="insert-class-form__lecturers-table">
                <thead>
                    <tr style="">
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Giới tính</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($lecturers); $i++) {
                        $gioi_tinh = $lecturers[$i]->gioi_tinh == 0 ? "Nữ" : "Nam";
                        $id_giang_vien = $lecturers[$i]->id_giang_vien;
                        $ho_ten = $lecturers[$i]->ho_ten;
                        $email = $lecturers[$i]->email;
                        echo " 
                                <tr>
                                    <td>{$id_giang_vien}</td>
                                    <td>{$ho_ten}</td>
                                    <td>{$email}</td>
                                    <td>{$gioi_tinh}</td>
                                    <td><input class=\"lecturer-checkbox\" type=\"checkbox\" value=\"{$id_giang_vien}\"></td>
                                </tr>
                            ";
                    }

                    ?>
                    
                    <!-- Dòng 1 -->
                    <!-- Các dòng khác tương tự -->
                </tbody>
            </table>
        </div>
        <div class="insert-class-form__btn-container">
            <button class="insert-class-form__cancel-btn">Hủy</button>
            <button class="insert-class-form__save-btn">Lưu thông tin</button>
        </div>
    </div>

</div>