<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style.css">

</head>

<body>
    <!-- div for containing popup form -->
    <script>

    </script>
    <style>

    </style>

    <!-- top nav -->

    <!-- left nav -->
    <div class="left-nav">
        <img class="left-nav__logo" src="<?php echo base_url(); ?>/assets/img/logo_course.png" alt="">
        <a class="item1" href="<?php echo base_url() . "/courses"; ?>">
            Danh sách lớp học</a>
        <a class="item2" href="<?php echo base_url() . "/lecturers"; ?>">
            Giảng viên</a>
        <a class="item3" href="<?php echo base_url() . "/students"; ?>">
            Học viên</a>
        <a class="item4" href="<?php echo base_url() . "/users"; ?>">
            User
        </a>
    </div>
    <?php echo $navbar ?>
    <!-- main section  -->
    <div class="main-content">
        <?php echo isset($mainsection) ? $mainsection : ''; ?>
        <!--  -->
    </div>
    <!-- footer -->
    <div>

    </div>

    <script>
        $(document).ready(function() {
            console.log('ready');

            // $(document).on('click', '.insert-class-form__cancel-btn', function () {
            //     $('.form-container').remove();
            // });

            $('.left-nav .item<?php echo $left_nav_chosen_value; ?>').addClass('highlight');

            let loadingState = false;

            $(`.add-class-btn`).click(function() {
                console.log('OK');
                loadingEffect(true);

                // Use jQuery.ajax for the AJAX request
                $.ajax({
                    url: '<?php echo base_url(); ?>/Admin/CoursesController/getInsertForm',
                    method: 'GET',
                    success: function(response) {
                        loadingEffect(false);
                        $('body').append(response);
                        // Add event handler
                        $(`.insert-class-form__cancel-btn`).click(function() {
                            $('.form-container').remove();
                        });
                        $(`.insert-class-form__save-btn`).click(function() {
                            loadingEffect(true);
                            $(`.insert-class-form .error-message`).html(``);
                            // Get data from html
                            var selectedSubjectId = $('.insert-class-form__subject-cbb option:selected').val();
                            var beginDate = $('.insert-class-form__begin-date-picker').val();
                            var endDate = $('.insert-class-form__end-date-picker').val();
                            var selectedLecturers = {};
                            $(`.insert-class-form__lecturers-table .lecturer-checkbox:checked`).each(function() {
                                selectedLecturers[$(this).val() + ""] = $(this).parent().parent().find(`td`).eq(1).text();
                            });
                            // object -> json
                            var obj = {
                                id_mon_hoc: selectedSubjectId,
                                ngay_bat_dau: beginDate,
                                ngay_ket_thuc: endDate,
                            }
                            console.log($('.insert-class-form__subject-cbb').html());
                            var jsonData = JSON.stringify(obj);
                            console.log(jsonData);
                            // Send insert request with data(Converted to JSON)
                            $.ajax({
                                url: '<?php echo base_url(); ?>/Admin/CoursesController/insertCourse', // Đường dẫn tới API hoặc resource bạn muốn gọi
                                method: 'POST', // Phương thức HTTP (GET, POST, PUT, DELETE, vv.)
                                dataType: 'json', // Kiểu dữ liệu bạn mong đợi từ phản hồi (json, html, text, vv.)
                                data:  // Dữ liệu bạn muốn gửi đi (nếu có)
                                    jsonData
                                ,
                                success: function(response) {
                                    console.log("here");
                                    console.log(response);
                                    var processResult1 = (response);
                                    console.log("huy");
                                    if (processResult1.state) {
                                        // Insert class succesfully
                                        // Prepare data
                                        var obj = {
                                            id_lop_hoc: processResult1.auto_increment_id,
                                            lecturer_id_list: selectedLecturers
                                        }
                                        var jsonData = JSON.stringify(obj);
                                        // Insert list of lecturers's id into phanconggiangvien
                                        $.ajax({
                                            url: '<?php echo base_url(); ?>/Admin/CoursesController/insertLecturersIntoClass',
                                            method: 'POST',
                                            contentType: 'application/json', // Đặt kiểu dữ liệu của yêu cầu là JSON
                                            data: jsonData,
                                            success: function(response) {
                                                // Xử lý in ra thông báo khi request thêm danh sách giảng viên vào lớp
                                                var className = $(`.insert-class-form__subject-cbb`).find(':selected').text();
                                                className = className.substring(6, className.length);
                                                var processResult = (response);
                                                loadingEffect(false);
                                                $('.form-container').remove();
                                                // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                                for (var [lecturer, processState] of Object.entries(processResult)) {           // có vấn đề
                                                    if (processState.state) {
                                                        alert(`Lớp ${className} ${str_pad(selectedSubjectId, 3, '0', STR_PAD_LEFT)}.${str_pad(processResult1.auto_increment_id, 3, '0', STR_PAD_LEFT)} được thêm thành công`);
                                                        // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                                    } else {
                                                        alert(`Thêm giảng viên ${lecturer} thêm vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thất bại", content: "processState.message`);
                                                        // Gọi hàm in thông báo, type: "error", title: "Thêm giảng viên ${lecturer} thêm vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thất bại", content: "processState.message"
                                                    }
                                                }
                                                // Xử lý phản hồi từ máy chủ khi thành công
                                                console.log('Server response:', response);
                                            },
                                            error: function(xhr, status, error) {
                                                // Xử lý lỗi khi gửi yêu cầu
                                                console.error('Error:', status, error);
                                            }
                                        });
                                    } else {
                                        loadingEffect(false);
                                        // Error
                                        $(`.insert-class-form .error-message`).html(processState.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Xử lý lỗi ở đây
                                    console.error('Error:', status, error);
                                }
                            });

                        });
                    },
                    error: function(xhr, status, error) {
                        loadingEffect(false);
                        console.error('Lỗi yêu cầu:', status, error);
                    },
                    complete: function() {
                        loadingEffect(false);
                    }
                });
            });
        });

        function appendElements(selector, element) {
            $(`${selector}`).html(`${ $(`${selector}`).html() + element}`)
        }

        function loadingEffect(state) {
            if (state) {
                $('body').append("<div class='loading-effect'><i class='fa-solid fa-spinner loading-icon'></i></div>");
            } else {
                $('.loading-effect').remove();
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>