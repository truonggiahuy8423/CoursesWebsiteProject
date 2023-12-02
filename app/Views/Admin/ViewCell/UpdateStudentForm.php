<div class="modal fade" id="SuaStudentForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa Thông Tin Học Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form>
                    <div class="mb-3">
                        <label for="hoTenSua" class="form-label">Họ Tên</label> <span id="error_fixname" class="text-danger ms-3"></span>
                        <input type="text" class="form-control" id="hoTenSua" placeholder="Họ tên">
                    </div>
                    <div class="mb-3">
                        <label for="ngaySinhSua" class="form-label">Ngày Sinh</label> <span id="error_fixdob" class="text-danger ms-3"></span>
                        <input type="date" class="form-control" id="ngaySinhSua">
                    </div>
                    <div class="mb-3">
                        <label for="gioiTinhSua" class="form-label">Giới Tính</label> <span id="error_fixgender" class="text-danger ms-3"></span>
                        <select class="form-select" id="gioiTinhSua">
                            <option value="" disabled selected>--Chọn giới tính--</option>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="emailSua" class="form-label">Email</label> <span id="error_fixmail" class="text-danger ms-3"></span>
                        <input type="email" class="form-control" id="emailSua" placeholder="Email">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="luuThongTinSua()">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var currentStudentId;
    function sua(id) {
        currentStudentId = id;
        // Fetch student information using AJAX
        $.ajax({
            url: '/Admin/StudentsController/getStudentInfo/' + id,
            method: 'GET',
            success: function(response) {
                // Populate the form fields with the fetched data
                $('#hoTenSua').val(response.ho_ten);
                $('#ngaySinhSua').val(response.ngay_sinh);
                $('#gioiTinhSua').val(response.gioi_tinh);
                $('#emailSua').val(response.email);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching student information:', status, error);
            }
        });
    }

    function luuThongTinSua() {
        var hoTen = document.getElementById('hoTenSua').value;
        var ngaySinh = document.getElementById('ngaySinhSua').value;
        var gioiTinh = document.getElementById('gioiTinhSua').value;
        var email = document.getElementById('emailSua').value;
        
        $('#error_fixname, #error_fixdob, #error_fixgender, #error_fixmail').text('');
        // Perform input validation
        var isValid = true;

        if ($.trim(hoTen) == '') {
            $('#error_fixname').text('Vui lòng điền họ tên');
            isValid = false;
        }

        if ($.trim(ngaySinh) == '') {
            $('#error_fixdob').text('Vui lòng chọn ngày sinh');
            isValid = false;
        }

        if ($.trim(gioiTinh) == '') {
            $('#error_fixgender').text('Vui lòng chọn giới tính');
            isValid = false;
        }

        if ($.trim(email) == '') {
            $('#error_fixmail').text('Vui lòng điền Email');
            isValid = false;
        }

        if (!isValid) {
            return false;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/Admin/StudentsController/updateStudent',
            method: 'POST',
            contentType: "application/json",
            data: JSON.stringify({
                id_hoc_vien:  currentStudentId,
                ho_ten: hoTen,
                ngay_sinh: ngaySinh,
                gioi_tinh: gioiTinh,
                email: email
            }),
            success: function(response) {
                $('#suaStudentForm').modal('hide');
                
                toast({
                    title: "Thành công!",
                    message: "Cập nhật thông tin học viên thành công",
                    type: "success",
                    duration: 3000
                });

                setTimeout(function() {
                    location.reload();
                }, 2000);

            },
            error: function(xhr, status, error) {
                toast({
                    title: "Thất bại!",
                    message: "Cập nhật thất bại",
                    type: "error",
                    duration: 3000
                });
                console.error('Error:', status, error);
            }
        });
    }
</script>
