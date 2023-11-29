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
// Include the form file
echo view('Admin\ViewCell\InsertStudentForm');
?>
<div class="students-list-section">
    <div>
        <h2 class="text-center mt-4 mb-4">Danh sách học viên</h2>
    </div>
    <div class="button-container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#themHocVienModal">Thêm</button>
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id_hoc_vien']; ?></td>
                        <td><?php echo $student['ho_ten']; ?></td>
                        <td><?php echo ($student['gioi_tinh'] == 1) ? 'Nam' : 'Nữ'; ?></td>
                        <td><?php echo $student['ngay_sinh']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td>
                                <button class="btn btn-warning" onclick="sua(<?php echo $student['id_hoc_vien']; ?>)">Sửa</button>
                                <button class="btn btn-danger" onclick="xoa(<?php echo $student['id_hoc_vien']; ?>)">Xóa</button>
                            </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<style>
    .table-responsive {
        height: 500px; 
        overflow: auto; 
    }

    .button-container {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

    .btn {
        width: 60px;
        height: 30px; 
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script>

<script>
    // // Add click event handler for "Thêm" button
    // $(document).on('click', '#them-button', function() {
    //     $.ajax({
    //         url: '<?php echo base_url(); ?>/Admin/StudentsController/getInsertStudent',
    //         method: 'GET',
    //         success: function(response) {
    //             $('body').append(response);

    //             $('.btn btn-primary').click(function() {
    //                 $('.form-container').remove();
    //             });

    //             // Add event handler for save button
    //             $('.insert-class-form__save-btn').click(function() {
    //                 // Add your logic for saving the new student here

    //                 // Example: Display a success message
    //                 alert('New student added successfully!');

    //                 // Remove the form container
    //                 $('.form-container').remove();
    //             });
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('Error:', status, error);
    //         }
    //     });
    // });
</script>