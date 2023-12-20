<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <title>Danh sách học viên</title>
</head>

<body>

    <?php

    use App\Models\HocVienModel;

    if (!session()->has('id_user')) {
        return redirect()->to('/');
    }

    $model = new HocVienModel();

    // Pagination settings
    $recordsPerPage = 20;
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;
    $students = $model->executeCustomQuery(
        "SELECT hoc_vien.id_hoc_vien, hoc_vien.ho_ten, hoc_vien.gioi_tinh, DATE_FORMAT(hoc_vien.ngay_sinh, '%d/%m/%Y') as ngay_sinh, hoc_vien.email FROM hoc_vien LIMIT $recordsPerPage OFFSET $offset"
    );
    $totalStudents = $model->executeCustomQuery("SELECT COUNT(*) as total FROM hoc_vien")[0]['total'];
    $totalPages = ceil($totalStudents / $recordsPerPage);

    echo view('Admin\ViewCell\InsertStudentForm');
    echo view('Admin\ViewCell\UpdateStudentForm');
    ?>
    <div class="students-list-section">
        <div>
            <h2 class="text-center mt-4 mb-4">Danh sách học viên</h2>
        </div>

        <div style="height: 30px;" class="class__search me-2 d-flex justify-content-end">
            <div class="input-group">
                <input id="searchInput" style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-input" placeholder="Tìm kiếm theo tên học viên" name="search" aria-label="Tìm kiếm" aria-describedby="basic-addon2">
                <button id="searchButton" class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
            </div>
        </div>


        <div class="button-container d-flex justify-content-end pe-5">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#themHocVienModal">Thêm</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center text-white bg-dark">Mã học viên</th>
                        <th class="text-center text-white bg-dark">Họ tên</th>
                        <th class="text-center text-white bg-dark">Giới tính</th>
                        <th class="text-center text-white bg-dark">Ngày sinh</th>
                        <th class="text-center text-white bg-dark">Email</th>
                        <th class="text-center text-white bg-dark"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?php echo $student['id_hoc_vien']; ?></td>
                            <td><?php echo $student['ho_ten']; ?></td>
                            <td><?php echo ($student['gioi_tinh'] == 1) ? 'Nam' : 'Nữ'; ?></td>
                            <td><?php echo $student['ngay_sinh']; ?></td>
                            <td><?php echo $student['email']; ?></td>
                            <td class="text-center">
                                <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" data-bs-toggle="modal" data-bs-target="#SuaStudentForm" data-student-id="<?php echo $student['id_hoc_vien']; ?>" onclick="sua(<?php echo $student['id_hoc_vien']; ?>)">Sửa</button>
                                <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" onclick="xoa(<?php echo $student['id_hoc_vien']; ?>)">Xóa</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($currentPage - 1); ?>" tabindex="-1" aria-disabled="true">Trang trước</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($currentPage + 1); ?>">Trang sau</a>
                </li>
            </ul>
        </nav>
    </div>
</body>

</html>

<style>
    .table-responsive {
        max-height: 500px;
        overflow: auto;
    }

    .button-container {
        display: flex;
        margin-left: auto;
        gap: 10px;
        margin-bottom: 10px;
    }

    .table-container,
    thead,
    th {
        position: sticky;
        top: 0;
        background-color: #343a40;
        /* Dark background color */
        color: white;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    function xoa(id) {
        var confirmation = confirm("Bạn có chắc chắn muốn xóa học viên " + id);
        if (confirmation) {
            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/StudentsController/deleteStudent',
                method: 'POST',
                contentType: "application/json",
                data: JSON.stringify({
                    id_hoc_vien: id
                }),
                success: function(response) {

                    //$('#row_' + id).remove();
                    toast({
                        title: 'Thành công',
                        message: 'Xóa học viên thành công',
                        type: 'success',
                        duration: 5000
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', status, error);
                    toast({
                        title: 'Thất bại',
                        message: 'Có lỗi xảy ra, vui lòng liên hệ quản trị viên',
                        type: 'error',
                        duration: 5000
                    });
                }
            });
        }
    }

    $(document).ready(function () {
        $('#searchButton').on('click', function () {
            var searchTerm = $('#searchInput').val();

            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/StudentsController/searchStudents',
                method: 'GET',
                data: { search: searchTerm },
                success: function(response) {
                    // Update the student list with the search results
                    // ...

                    // Example: Assuming your response is an array of student names
                    var studentList = $('#studentList'); // Update with your actual table ID
                    studentList.empty();

                    response.forEach(function(student) {
                        studentList.append('<tr><td>' + student.name + '</td></tr>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', status, error);
                }
            });
        });
    });
</script>