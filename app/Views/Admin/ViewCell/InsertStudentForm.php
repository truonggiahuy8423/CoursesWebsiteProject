<!-- Modal -->
<div class="modal fade" id="themHocVienModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Thêm Học Viên</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Your form goes here -->
        <form>
          <div class="mb-3">
            <label for="hoTen" class="form-label name">Họ Tên</label> <span id="error_name" class="text-danger ms-3"></span>
            <input type="text" class="form-control" id="hoTen" placeholder="Họ tên">
          </div>
          <div class="mb-3">
            <label for="ngaySinh" class="form-label dob">Ngày Sinh</label> <span id="error_dob" class="text-danger ms-3"></span>
            <input type="date" class="form-control" id="ngaySinh">
          </div>
          <div class="mb-3">
            <label for="gioiTinh" class="form-label gender">Giới Tính</label> <span id="error_gender" class="text-danger ms-3"></span>
            <select class="form-select" id="gioiTinh">
              <option value="" disabled selected>--Chọn giới tính--</option>
              <option value="1">Nam</option>
              <option value="2">Nữ</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label mail">Email</label> <span id="error_email" class="text-danger ms-3"></span>
            <input type="email" class="form-control" id="email" placeholder="Email">
          </div>
          <button type="button" class="btn btn-primary save-student-btn">Lưu</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-UG8ao2jwOWB7/oDdObZc6ItJmwUkR/PfMyt9Qs5AwX7PsnYn1CRKCTWyncPTWvaS" crossorigin="anonymous"></script>

<script>
  $(document).ready(function () {
    $(document).on('click', '.save-student-btn', function() {
      var hoTen = document.getElementById('hoTen').value;
      var ngaySinh = document.getElementById('ngaySinh').value;
      var gioiTinh = document.getElementById('gioiTinh').value;
      var email = document.getElementById('email').value;
      
      $('#error_name, #error_dob, #error_gender, #error_email').text('');
      // Perform input validation
      var isValid = true;

      if ($.trim(hoTen) == '') {
        $('#error_name').text('Vui lòng điền họ tên');
        isValid = false;
      }

      if ($.trim(ngaySinh) == '') {
        $('#error_dob').text('Vui lòng chọn ngày sinh');
        isValid = false;
      }

      if ($.trim(gioiTinh) == '') {
        $('#error_gender').text('Vui lòng chọn giới tính');
        isValid = false;
      }

      if ($.trim(email) == '') {
        $('#error_email').text('Vui lòng điền Email');
        isValid = false;
      }

      if (!isValid) {
        return false;
      }

      $.ajax({
        url: '<?php echo base_url(); ?>/Admin/StudentsController/insertStudent',
        method: 'POST',
        //dataType: 'json',
        contentType: "application/json",
        data: JSON.stringify({
          ho_ten: hoTen,
          ngay_sinh: ngaySinh,
          gioi_tinh: gioiTinh,
          email: email
        }),
        success: function(response) {
          $('#themHocVienModal').modal('hide');
          
          toast({
              title: "Thành công!",
              message: "Thêm học viên thành công",
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
              message: "không thể thêm học viên",
              type: "error",
              duration: 3000
          });
          console.error('Error:', status, error);
        }
      });
    });
  });
</script>


<!-- Modal Sửa -->
<!-- <div class="modal fade" id="SuaStudentForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sửa Thông Tin Học Viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form>
                    <div class="mb-3">
                        <label for="hoTenSua" class="form-label">Họ Tên</label>
                        <input type="text" class="form-control" id="hoTenSua">
                    </div>
                    <div class="mb-3">
                        <label for="ngaySinhSua" class="form-label">Ngày Sinh</label>
                        <input type="date" class="form-control" id="ngaySinhSua">
                    </div>
                    <div class="mb-3">
                        <label for="gioiTinhSua" class="form-label">Giới Tính</label>
                        <select class="form-select" id="gioiTinhSua">
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="emailSua" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailSua">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="luuThongTinSua()">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function sua(id_hoc_vien) {
        
       
        var studentInfo = {
            hoTen: "Nguyễn Văn A",
            //ngaySinh: "1990-01-01",
            gioiTinh: "1",
            email: "nguyenvana@example.com"
        };
        
        document.getElementById('hoTenSua').value = studentInfo['ho_ten'];
        //document.getElementById('ngaySinhSua').value = studentInfo['ngay_sinh'];
        document.getElementById('gioiTinhSua').value = studentInfo['gioi_tinh'];
        document.getElementById('emailSua').value = studentInfo['email'];

        
        $('#SuaStudentForm').modal('show');
    }
    function luuThongTinSua() {
       
        var hoTenSua = document.getElementById('hoTenSua').value;
        var ngaySinhSua = document.getElementById('ngaySinhSua').value;
        var gioiTinhSua = document.getElementById('gioiTinhSua').value;
        var emailSua = document.getElementById('emailSua').value;


        $('#SuaStudentForm').modal('hide');
    }
</script> -->

