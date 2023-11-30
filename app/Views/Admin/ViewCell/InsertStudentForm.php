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
            <label for="hoTen" class="form-label">Họ Tên</label>
            <input type="text" class="form-control" id="hoTen">
          </div>
          <div class="mb-3">
            <label for="ngaySinh" class="form-label">Ngày Sinh</label>
            <input type="date" class="form-control" id="ngaySinh">
          </div>
          <div class="mb-3">
            <label for="gioiTinh" class="form-label">Giới Tính</label>
            <select class="form-select" id="gioiTinh">
              <option value="1">Nam</option>
              <option value="2">Nữ</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email">
          </div>
          <button type="button" class="btn btn-primary" onclick="saveStudent()">Lưu</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-UG8ao2jwOWB7/oDdObZc6ItJmwUkR/PfMyt9Qs5AwX7PsnYn1CRKCTWyncPTWvaS" crossorigin="anonymous"></script>

<script>
  function saveStudent() {
      // Get values from the form
    var hoTen = document.getElementById('hoTen').value;
    var ngaySinh = document.getElementById('ngaySinh').value;
    var gioiTinh = document.getElementById('gioiTinh').value;
    var email = document.getElementById('email').value;
  
    // Perform validation if needed
    $.ajax({
      url: '<?php echo base_url(); ?>/Admin/StudentsController/insertStudent',
      method: 'GET',
      //dataType: 'json',
      contentType: "json",
      data: JSON.stringify({
        hoTen: hoTen,
        ngaySinh: ngaySinh,
        gioiTinh: gioiTinh,
        email: email
      }),
      success: function(response) {
        if (response.success) {
            
            $('#InsertStudentForm').modal('hide');
            
            // Display success toast
            toast({
                title: "Thành công!",
                message: "Thêm học viên thành công",
                type: "success",
                duration: 3000
            });

            
        } else {
            
            alert('Failed to save student. Please try again.');
        }
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
  }
</script>
