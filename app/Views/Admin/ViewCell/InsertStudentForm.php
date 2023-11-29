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