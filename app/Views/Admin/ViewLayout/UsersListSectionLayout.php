<div class="main">
    <div style="margin-bottom: 30px;" class="title w-100 text-center text-uppercase">
        <h4>Danh sách user hệ thống</h4>
    </div>
    <div class="class-container">
        <div style="height: 30px; margin-bottom: 8px;" class="class__search me-2 d-flex justify-content-end">

            <input style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-input" placeholder="Tìm kiếm user">
            <button class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
            <button class="add-class-btn highlight-button add-user-btn">
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
    function timeDifference($inputDatetime)
    {
        // Chuyển đổi chuỗi datetime thành đối tượng DateTime
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $inputTime = new DateTime($inputDatetime);

        // Lấy thời gian hiện tại ở múi giờ của Việt Nam
        $now = new DateTime();

        // Tính khoảng thời gian giữa hai thời điểm
        $interval = $now->diff($inputTime);

        // Lấy số giây tổng cộng
        $totalSeconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;

        if ($totalSeconds >= 86400) {
            // Nếu thời gian lớn hơn hoặc bằng 1 ngày, trả về chính chuỗi input
            return $inputDatetime;
        } elseif ($totalSeconds < 60) {
            // Nếu thời gian dưới 30 giây, trả về chuỗi "đang hoạt động"
            return "<div class='active-icon'></div> Đang hoạt động";
        } elseif ($totalSeconds < 3600) {
            // Nếu thời gian dưới 1 giờ, tính và trả về phút trước
            $minutes = floor($totalSeconds / 60);
            return "Hoạt động " . $minutes . ' phút trước';
        } else {
            // Nếu thời gian dưới 1 ngày nhưng lớn hơn 1 giờ, tính và trả về giờ trước
            $hours = floor($totalSeconds / 3600);
            return  "Hoạt động " . $hours . ' giờ trước';
        }
    }

    // Sử dụng hàm với một chuỗi datetime bất kỳ


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
        <table class="table table-striped table-bordered user-table">
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
                        <td><?php echo str_pad($user['id_user'], 7, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <img src="<?php if ($user['anh_dai_dien'] != null) {
                                            $base64Image = base64_encode($user['anh_dai_dien']);
                                            echo "data:image/png;base64," . $base64Image;
                                        } else {
                                            echo base_url() . "assets/img/avatar_blank.jpg";
                                        } ?>" alt="" style="height: 20px; border-radius: 100px;">
                            <?php echo $user['ho_ten']; ?>
                        </td>
                        <td><?php echo $user['tai_khoan'] ?></td>
                        <td><?php echo $user['mat_khau']; ?></td>
                        <td><?php


                            // Sử dụng hàm với một chuỗi datetime bất kỳ
                            $inputDatetime = '2023-01-01 12:34:56';
                            $result = timeDifference($user['thoi_gian_dang_nhap_gan_nhat']);

                            // In kết quả
                            echo $result;
                            ?></td>
                        <td><?php
                            if ($user["id_ad"] != null) {
                                $rs = str_pad($user['id_ad'], 3, '0', STR_PAD_LEFT);
                                echo "Adminstrator({$rs})";
                            } else if ($user["id_giang_vien"] != null) {
                                $rs = str_pad($user['id_giang_vien'], 6, '0', STR_PAD_LEFT);
                                echo "Giảng viên({$rs})";
                            } else {
                                $rs = str_pad($user['id_hoc_vien'], 6, '0', STR_PAD_LEFT);
                                echo "Học viên({$rs})";
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
    <nav id="bottom-nav" aria-label="Page navigation example">
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
<script>
    function timeDifference(inputDatetime) {
        if (inputDatetime == null) {
            return "Chưa đăng nhập lần nào";
        }
        // Chuyển đổi chuỗi datetime thành đối tượng Date
        var inputTime = new Date(inputDatetime);

        // Lấy thời gian hiện tại
        var now = new Date();

        // Tính khoảng thời gian giữa hai thời điểm
        var timeDiff = Math.abs(now.getTime() - inputTime.getTime());

        // Tính số giây tổng cộng
        var totalSeconds = Math.floor(timeDiff / 1000);

        if (totalSeconds >= 86400) {
            // Nếu thời gian lớn hơn hoặc bằng 1 ngày, trả về chính chuỗi input
            return inputDatetime;
        } else if (totalSeconds < 30) {
            // Nếu thời gian dưới 30 giây, trả về chuỗi "đang hoạt động"
            return '<div class="active-icon"></div> Đang hoạt động';
        } else if (totalSeconds < 3600) {
            // Nếu thời gian dưới 1 giờ, tính và trả về phút trước
            var minutes = Math.floor(totalSeconds / 60);
            return "Hoạt động " + minutes + ' phút trước';
        } else {
            // Nếu thời gian dưới 1 ngày nhưng lớn hơn 1 giờ, tính và trả về giờ trước
            var hours = Math.floor(totalSeconds / 3600);
            return "Hoạt động " + hours + ' giờ trước';
        }
    }
    function generateAsterisks(n) {
    return '*'.repeat(n);
}
    function reloadUssers() {
        loadingEffect(true);
        let urlParams = new URLSearchParams(window.location.search);
        let currentPage = urlParams.get('page') == null ? 1 : urlParams.get('page');
        // console.log(page)
        // let page = 1;
        $.ajax({

            url: '<?php echo base_url(); ?>/Admin/UsersController/getUsersList',
            method: 'GET',
            contentType: "text",
            dataType: "json",
            data: {
                page: currentPage
            },
            success: function(response) {
                loadingEffect(false);
                console.log(response);
                $(`.user-table tbody`).empty();
                $(`#bottom-nav`).remove();
                let tbody = $(`.user-table tbody`);
                for (let user of response.users) {
                    let rs = '';
                    if (user.id_ad !== null) {

                        rs = "Adminstrator(" + String(user["id_ad"]).padStart(3, '0') + ")";
                        // console.log("Adminstrator(" + rs + ")");
                    } else if (user['id_giang_vien'] !== null) {
                        rs = "Giảng viên(" + String(user.id_giang_vien).padStart(6, '0') + ")";
                        // console.log("Giảng viên(" + rs + ")");
                    } else {
                        rs = "Học viên(" + String(user['id_hoc_vien']).padStart(6, '0') + ")";
                        // console.log("Học viên(" + rs + ")");
                    }
                    let imgSource = (user['anh_dai_dien'] !== null) ? ("data:image/png;base64," + user['anh_dai_dien']) : ("<?php base_url() ?>" + "/assets/img/avatar_blank.jpg");
                    tbody.append(`
                    <tr>
        <td>${String(user['id_user']).padStart(8, '0')}</td>
        <td>
            <img src="${imgSource}" alt='' style='height: 20px; border-radius: 100px;'>
            ${user['ho_ten']} 
        </td>
        <td>${user['tai_khoan']}</td>
        <td class="password-cell">
         <div style="width: 100px"> 
        <span class="hidden-password">${generateAsterisks(user['mat_khau'].length)}</span>
        <span class="visible-password">${user['mat_khau']}</span>
        </div>
    </td>
        <td>${timeDifference(user['thoi_gian_dang_nhap_gan_nhat'])}</td>
        <td>${rs}</td>
        <td class="text-center">
            <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" data-bs-toggle="modal" data-bs-target="#SuaStudentForm" data-student-id="" onclick="sua()">Sửa</button>
            <button class="btn-link text-primary border-0 btn-sm" style="background-color: transparent;" onclick="xoa()">Xóa</button>
        </td>
    </tr>
                            `);
                }
                let totalUsers = response['totalUsers'];
                var totalPages = Math.ceil(totalUsers / 20);

                let sth = '';
                for (let i = 1; i <= totalPages; i++) {
                    sth += `<li class="page-item ${(i == currentPage) ? 'active' : ''}">
                    <a class="page-link" href="?page=${i}">${i}</a>
                </li>`
                }
                $(`.main`).append(`
                    <nav aria-label="Page navigation example">
        <ul id="bottom-nav" class="pagination justify-content-center">
            <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
                <a class="page-link" href="?page=${currentPage - 1}" tabindex="-1" aria-disabled="true">Trang trước</a>
            </li>
            ${sth}
            <li class="page-item ${(currentPage >= totalPages) ? 'disabled' : '' }">
                <a class="page-link" href="?page=${currentPage + 1}">Trang sau</a>
            </li>
        </ul>
    </nav>
                    `)


            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
            }
        })
    }
    function showPassword(cell) {
    cell.querySelector('.hidden-password').style.display = 'none';
    cell.querySelector('.visible-password').style.display = 'inline';
}

function hidePassword(cell) {
    cell.querySelector('.hidden-password').style.display = 'inline';
    cell.querySelector('.visible-password').style.display = 'none';
}
    $(document).ready(function() {
        
        reloadUssers();
        $(`.add-user-btn`).click(function() {
            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/UsersController/getInsertUserForm',
                method: 'GET',
                contentType: "text",
                success: function(response) {
                    $(`body`).append(response);
                }
            })
        })
    })
</script>
</div>