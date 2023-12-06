<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style.css">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $('.expand-btn').click(function(){
                etceteraSwitch($(this).attr('aria-expanded'));
            });

            $(document).on('click', '.class-info', function() {
                window.location.href = `<?php echo base_url(); ?>/courses/information?courseid=${$(this).parent().attr('courseid')}`;
            });

            $('.filterStatus').on('change',function(){
                var option = $(this).find('option:checked').val();
                $('.class-info').each(function(){
                    if(option == 0){
                        $(this).parent().css('display','block');
                    }
                    else if(option != $(this).parent().attr('statusCode')){
                        $(this).parent().css('display','none');
                    }

                    if(option == $(this).parent().attr('statusCode')){
                        $(this).parent().css('display','block');
                    }
                });

                if($('.expand-btn').attr('aria-expanded') != 'true'){
                    $('.expand-btn').trigger('click');
                    etceteraSwitch($('.expand-btn').attr('aria-expanded'));
                }
            })

            function etceteraSwitch(checkExpand){
                if(checkExpand == 'true'){
                    $('.expand-btn').html('Thu gọn');
                    $('.et-cetera').css('visibility', 'hidden');
                }
                else if(checkExpand == 'false'){
                    $('.expand-btn').html('Mở rộng');
                    $('.et-cetera').css('visibility', 'visible');
                }
            }
        }) 
    </script>
    <style>
        .edit-profile-btn .edit-profile-icon {
            color: #333;
        }
        .edit-profile-btn:hover .edit-profile-icon::before {
            color: #fff;
        }
    </style>
</head>
<body>
    <?php echo $navbar ?>
    <div class="container-fluid" style="margin-top: 65px;">
        <div class="logo card p-4 m-2 shadow-sm">
            <div class="row">
                <div class="col-2">
                    <img class="logo img-responsive img-thumbnail rounded-circle" src="<?php base_url()?>/assets/img/avatar_blank.jpg" alt="Logo">
                </div>
                <div class="col-4">
                    <div class="card-body">
                        <div class="card-title text text-uppercase fs-3 fw-bold"><?php echo isset($user->ho_ten) ? $user->ho_ten: 'Error' ?></div>
                        <div class="card-subtitle mb-2 text-muted fs-4">ID: <span class="mssv"><?php echo isset($id) ? $id : 'Error' ?></span></div>
                    </div>
                </div>
                <div class="col-6 border-start">
                    <div class="card-body">
                        <div class="card-title mb-1 fs-4 fw-bold">Giới tính: </div>
                        <p class="sex fs-5"><?php echo (isset($user->gioi_tinh) && $user->gioi_tinh == 1) ? 'Nam' : 'Nữ' ?></p>
                        <div class="card-title mb-1 fs-4 fw-bold">Email: </div>
                        <p class="email fs-5"><?php echo isset($user->email) ? $user->email: 'Error' ?></p>
                    </div>
                </div>
                
            </div>
        </div>

        <hr>

        <div class="row ">
            <div class="col-12">
                <div class="card p-4 mb-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="card-title fs-4 fw-bold">Các khóa học đã tham gia</div>
                            </div>
                            <div class="col-6">
                                <div class=" d-flex justify-content-end">
                                    <div>
                                        <select class="form-select filterStatus" aria-label="Default select example">
                                            <option value="0" selected>Tất cả</option>
                                            <option value="1">Kết thúc</option>
                                            <option value="2">Đang diễn ra</option>
                                            <option value="3">Sắp diễn ra</option>
                                        </select>
                                    </div>
                                    <div class="mx-2"></div>
                                    <button class="btn btn-outline-dark expand-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Mở rộng
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php
                            echo "<div class='row'>";
                            $n = count($attend);
                            for ($i = 0; $i < ($n <= 4 ? $n : 4); $i++) {
                                $statusCode = kiem_tra_tinh_trang($attend[$i]->ngay_bat_dau, $attend[$i]->ngay_ket_thuc);
                                $status = getStatus($statusCode);
                                
                                $courseID = str_pad($attend[$i]->id_mon_hoc, 3, "0", STR_PAD_LEFT) . "." . str_pad($attend[$i]->id_lop_hoc, 6, "0", STR_PAD_LEFT);
                                echo "
                                        <div class='col-6 mb-3' courseid='{$attend[$i]->id_lop_hoc}' statusCode='{$statusCode}'>
                                            <div class='p-3 card shadow-sm class-info'>
                                                <div class='card-body'>
                                                    <h3 class='card-title fs-4'>{$attend[$i]->ten_mon_hoc} - {$courseID}</h3>
                                                    <div class='my-3'></div>
                                                    <p class='card-text'>Trạng thái: {$status}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                            }

                            echo "</div>
                                <div class='collapse' id='collapseExample'>
                                    <div class='row'>
                                  ";

                            for ($i = 4; $i < $n; $i++) {
                                $statusCode = kiem_tra_tinh_trang($attend[$i]->ngay_bat_dau, $attend[$i]->ngay_ket_thuc);
                                $status = getStatus($statusCode);
                                $courseID = str_pad($attend[$i]->id_mon_hoc, 3, "0", STR_PAD_LEFT) . "." . str_pad($attend[$i]->id_lop_hoc, 6, "0", STR_PAD_LEFT);
                                echo "
                                        <div class='col-6 mb-3' courseid='{$attend[$i]->id_lop_hoc}' statusCode='{$statusCode}'>
                                            <div class='p-3 card shadow-sm class-info'>
                                                <div class='card-body'>
                                                    <h3 class='card-title fs-4'>{$attend[$i]->ten_mon_hoc} - {$courseID}</h3>
                                                    <div class='my-3'></div>
                                                    <p class='card-text'>Trạng thái: {$status}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                            }
                                  
                            echo    "</div>
                                </div>";
                            echo $n > 4 ?  "<div class='row et-cetera'>
                                                <div class='col-12 text-center'>
                                                <i class='fas fa-ellipsis-h' style='color: #0f0f0f;'></i>
                                                </div>
                                            </div>" : "";

                            function getStatus($statusCode){
                                if($statusCode == 1){
                                    return '<span class="class__item--over">Đã kết thúc</span>';
                                }
                                else if($statusCode == 2){
                                    return '<span class="class__item--inprocess">Đang diễn ra</span>';
                                }
                                else if ($statusCode == 3){
                                    return '<span class="class__item--upcoming">Sắp diễn ra</span>';
                                }
                            }

                            function kiem_tra_tinh_trang($ngay_bat_dau, $ngay_ket_thuc){
                                $currentDate = date('Y-m-d');
                                if (strtotime($ngay_bat_dau) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($ngay_ket_thuc)) {
                                    return 2;
                                } elseif (strtotime($currentDate) < strtotime($ngay_bat_dau)) {
                                    return 1;
                                } else {
                                    return 3;
                                } 
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>