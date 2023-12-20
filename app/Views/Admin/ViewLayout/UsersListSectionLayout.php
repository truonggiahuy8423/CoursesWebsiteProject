<div class="main">
    <div style="margin-bottom: 30px;" class="title w-100 text-center text-uppercase">
        <h4>Danh sách user hệ thống</h4>
    </div>
    <div class="class-container">
        <div style="height: 30px; margin-bottom: 10px;" class="class__search me-2 d-flex justify-content-end">

            <input style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-input" placeholder="Tìm kiếm user">
            <button class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
            <button class="add-class-btn highlight-button">
                <i class="fa-solid fa-plus add-class-icon highlight-icon"></i>
            </button>
            </div>
        </div>
        <?php

        use App\Models\HocVienModel;
use App\Models\UserModel;

        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }

        $model = new UserModel();

        // Pagination settings
        $recordsPerPage = 20;
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($currentPage - 1) * $recordsPerPage;
        $users = $model->executeCustomQuery(
            "(
                SELECT users.*, ad.ho_ten, ad.email
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad AND users.id_ad IS NOT NULL
                ORDER BY users.id_ad ASC
            )
            UNION
            (
                SELECT users.*, giang_vien.ho_ten, giang_vien.email
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien AND users.id_giang_vien IS NOT NULL
                ORDER BY users.id_giang_vien ASC
            )
            UNION
            (
                SELECT users.*, hoc_vien.ho_ten, hoc_vien.email
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien AND users.id_hoc_vien IS NOT NULL
                ORDER BY users.id_hoc_vien ASC
            ) LIMIT $recordsPerPage OFFSET $offset "
        );
        $totalStudents = count($model->executeCustomQuery("(
            SELECT users.*, ad.ho_ten, ad.email
            FROM users
            INNER JOIN ad ON users.id_ad = ad.id_ad AND users.id_ad IS NOT NULL
            ORDER BY users.id_ad ASC
        )
        UNION
        (
            SELECT users.*, giang_vien.ho_ten, giang_vien.email
            FROM users
            INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien AND users.id_giang_vien IS NOT NULL
            ORDER BY users.id_giang_vien ASC
        )
        UNION
        (
            SELECT users.*, hoc_vien.ho_ten, hoc_vien.email
            FROM users
            INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien AND users.id_hoc_vien IS NOT NULL
            ORDER BY users.id_hoc_vien ASC
        )"));
        $totalPages = ceil($totalStudents / $recordsPerPage);
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead style="top: -1px">
                    <tr>
                        <th class="text-center text-white bg-dark">Mã user</th>
                        <th class="text-center text-white bg-dark">Họ tên</th>
                        <th class="text-center text-white bg-dark">Tài khoản</th>
                        <th class="text-center text-white bg-dark">Mật khẩu</th>
                        <th class="text-center text-white bg-dark">Đăng nhập gần nhất</th>
                        <th class="text-center text-white bg-dark">Vai trò</th>
                        <th class="text-center text-white bg-dark"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['id_user']; ?></td>
                            <td><?php echo $user['ho_ten']; ?></td>
                            <td><?php echo $user['tai_khoan'] ?></td>
                            <td><?php echo $user['mat_khau']; ?></td>
                            <td><?php echo $user['thoi_gian_dang_nhap_gan_nhat']; ?></td>
                            <td><?php 
                                if ($user["id_ad"] != null) {
                                    echo "Adminstrator({$user['id_ad']})";
                                } else if ($user["id_giang_vien"] != null) {
                                    echo "Giảng viên({$user['id_giang_vien']})";
                                } else {
                                    echo "Học viên({$user['id_hoc_vien']})";
                                }
                             ?></td>

                            <td class="text-center">
                                <!-- <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" data-bs-toggle="modal" data-bs-target="#SuaStudentForm" onclick="sua(<?php echo $user['id_hoc_vien']; ?>)">Sửa</button> -->
                                <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" data-bs-toggle="modal" data-bs-target="#SuaStudentForm" data-student-id="<?php echo $user['id_hoc_vien']; ?>" onclick="sua(<?php echo $user['id_hoc_vien']; ?>)">Sửa</button>
                                <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" onclick="xoa(<?php echo $user['id_hoc_vien']; ?>)">Xóa</button>
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
</div>