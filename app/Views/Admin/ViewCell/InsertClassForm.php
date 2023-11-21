
<div class="form-container">
        <div class="insert-class-form">
            <div class="insert-class-form__title-section">
                <h5 class="insert-class-form__title">Thêm khóa học</h5>
            </div>
            <div class="insert-class-form__date-picker-container">
                <div style="display: inline-block; margin-right: 20%; margin-bottom: 10px;"><span style="margin-right: 2px;">Ngày bắt đầu </span><input class="insert-class-form__begin-date-picker" type="date"></div>
                <div style="display: inline-block;"><span>Ngày kết thúc  </span><input class="insert-class-form__end-date-picker" type="date"></div>
            </div>
            <div class="insert-class-form__subject-cbb-container">
                <span style="margin-left: 20px; margin-right: 10px;">Môn học</span>
                <select class="insert-class-form__subject-cbb" name="mon_hoc" size="4" id="">
                    <option value="">
                        005 - Cơ sở dữ liệu
                    </option>
                    <option value="">
                        001 - Lập trình C++
                    </option>
                    <option value="">
                        008 - Lập trình Java
                    </option>
                    <option value="">
                        005 - Cơ sở dữ liệu
                    </option>
                    <option value="">
                        001 - Lập trình C++
                    </option>
                    <option value="">
                        008 - Lập trình Java
                    </option>
                    <option value="">
                        005 - Cơ sở dữ liệu
                    </option>
                    <option value="">
                        001 - Lập trình C++
                    </option>
                    <option value="">
                        008 - Lập trình Java
                    </option>
                </select>
                <br>
            </div>
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
                        <!-- Dòng 1 -->
                        <tr>
                            <td>1</td>
                            <td>Nguyễn Văn A</td>
                            <td>nguyenvana@example.com</td>
                            <td>Nam</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <!-- Dòng 2 -->
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>tranthib@example.com</td>
                            <td>Nữ</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>tranthib@example.com</td>
                            <td>Nữ</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>tranthib@example.com</td>
                            <td>Nữ</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>tranthib@example.com</td>
                            <td>Nữ</td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Trần Thị B</td>
                            <td>tranthib@example.com</td>
                            <td>Nữ</td>
                            <td><input type="checkbox"></td>
                        </tr>
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