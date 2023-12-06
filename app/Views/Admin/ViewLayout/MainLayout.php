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

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/toast.css') ?>">
    <script src="<?php echo base_url('assets/script.js'); ?>"></script>
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

    <div id="toast"></div>

    <!-- footer -->
    <div>

    </div>

    <script>
        function setDisable() {

        }
        // setInterval(reloadCoursesList, 10000);

        $(document).ready(function() {
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
                                data: // Dữ liệu bạn muốn gửi đi (nếu có)
                                    jsonData,
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
                                                toast({
                                                    title: "Thành công!",
                                                    message: `Lớp ${className} ${selectedSubjectId.toString().padStart(3, '0')}.${processResult1.auto_increment_id.toString().padStart(6, '0')} được thêm thành công`,
                                                    type: "success",
                                                    duration: 100000
                                                });
                                                // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                                for (var [lecturer, processState] of Object.entries(processResult)) { // có vấn đề
                                                    if (processState.state) {
                                                        toast({
                                                            title: "Thành công!",
                                                            message: `Thêm giảng viên ${lecturer} vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thành công`,
                                                            type: "success",
                                                            duration: 100000
                                                        });
                                                        // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                                    } else {
                                                        toast({
                                                            title: "Thất bại!",
                                                            message: `Thêm giảng viên ${lecturer} vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thất bại(${processState.message}).`,
                                                            type: "error",
                                                            duration: 100000
                                                        });

                                                        // Gọi hàm in thông báo, type: "error", title: "Thêm giảng viên ${lecturer} thêm vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thất bại", content: "processState.message"
                                                    }
                                                }
                                                reloadCoursesList();
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

            $('.add-teacher-btn').click(function(){
                console.log('add-teacher-btn');
                loadingEffect(true);
                $.ajax({
                    url: '<?php echo base_url(); ?>/Admin/TeachersController/getInsertForm',
                    method: 'GET',
                    success: function(response){
                        loadingEffect(false);
                        $('body').append(response);

                        $(`.insert-teacher-form__cancel-btn`).click(function() {
                            $('.form-container').remove();
                        });

                        $(`.insert-teacher-form__save-btn`).click(function(){
                            console.log('insert-teacher-form__save-btn');
                            loadingEffect(true);
                            var fullName = $('.insert-teacher-form__fullname').val();
                            var dob = $('.insert-teacher-form__dob').val();
                            var sex = $('.insert-teacher-form__sex option:selected').val();
                            var email = $('.insert-teacher-form__email').val();

                            var obj = {
                                ho_ten: fullName,
                                ngay_sinh: dob,
                                gioi_tinh: sex,
                                email: email,
                            }

                            var jsonData = JSON.stringify(obj);

                            $.ajax({
                                url: '<?php echo base_url(); ?>/Admin/TeachersController/insertTeacher',
                                method: 'POST',
                                contentType: 'application/json',
                                data: jsonData,
                                success: function(response){
                                    loadingEffect(false);
                                    $('.form-container').remove();
                                    toast({
                                        title: "Thành công!",
                                        message: `Giảng viên mới được thêm thành công`,
                                        type: "success",
                                        duration: 5000
                                    });
                                    $('.teacher__list').append(`
                                                    <div class='col-6 mb-3 teacherCard' teacherid='${response.auto_increment_id}'>
                                                        <div class='p-3 card shadow-sm'>
                                                            <div class='card-body'>
                                                                <h3 class='card-title fs-4'>${obj.ho_ten} - ${response.auto_increment_id}</h3>
                                                                <div class='my-5'></div>
                                                                <p class='card-subtitle fs-5'><b>Email:</b> ${obj.email}</p>
                                                            </div>
                                                            <input type='checkbox' class='delete-checkbox' value='${response.auto_increment_id}'>
                                                        </div>
                                                    </div>  
                                                `);
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

            var deleteCheck = false; // Check if delete button is on or not
            $('.delete-teacher-btn').click(function() {
                deleteCheck = true;
                console.log(deleteCheck);
                $(`.delete-checkbox`).css(`visibility`, `visible`);

                $(`.save-div`).css(`position`, `static`);
                $(`.save-div`).css(`z-index`, `1`);
                $(`.cancel-div`).css(`position`, `static`);
                $(`.cancel-div`).css(`z-index`, `1`);

                let addbtn = $(`.add-teacher-btn`);
                addbtn.prop(`disabled`, true);
                addbtn.removeClass(`highlight-button`);
                addbtn.addClass(`highlight-button--disable`);
                let deletebtn = $(`.delete-teacher-btn`);
                deletebtn.prop(`disabled`, true);
                deletebtn.removeClass(`highlight-button`);
                deletebtn.addClass(`highlight-button--disable`);
            });

            $('.cancel-delete-teacher-btn').click(function() {
                deleteCheck = false;
                console.log(deleteCheck);
                $(`.delete-checkbox`).css(`visibility`, `hidden`);
                $(`.delete-checkbox`).prop(`checked`, false);

                $(`.save-div`).css(`position`, `absolute`);
                $(`.save-div`).css(`z-index`, `-1`);
                $(`.cancel-div`).css(`position`, `absolute`);
                $(`.cancel-div`).css(`z-index`, `-1`);

                let addbtn = $(`.add-teacher-btn`);
                addbtn.prop(`disabled`, false);
                addbtn.removeClass(`highlight-button--disable`);
                addbtn.addClass(`highlight-button`);
                let deletebtn = $(`.delete-teacher-btn`);
                deletebtn.prop(`disabled`, false);
                deletebtn.removeClass(`highlight-button--disable`);
                deletebtn.addClass(`highlight-button`);
            });

            $('.save-delete-teacher-btn').click(function() {
                // check 
                if ($(`.delete-checkbox:checked`).length == 0) {
                    toast({
                        title: 'Thông báo',
                        message: 'Chưa chọn giảng viên cần xóa',
                        type: 'warning',
                        duration: 100000
                    });
                } else {
                    loadingEffect(true);
                    let teachers = [];
                    $(`.delete-checkbox:checked`).each(function() {
                        teachers.push($(this).attr("value"));
                    });

                    console.log(teachers);
                    let jsonData = {};
                    jsonData[`teachers`] = teachers;
                    jsonData = JSON.stringify(jsonData);
                    console.log(jsonData);
                    $.ajax({
                        url: '<?php echo base_url(); ?>/Admin/TeachersController/deleteTeacher',
                        method: 'POST',
                        dataType: 'json',
                        data: jsonData,
                        success: function(response) {
                            loadingEffect(false);
                            for (var [id_giang_vien, processState] of Object.entries(response)) {
                                if (processState.state) {
                                    $('.delete-checkbox:checked').each(function() {
                                        $(this).parent().parent().remove();
                                    });
                                    toast({
                                        title: "Thành công!",
                                        message: `Xóa giảng viên ${id_giang_vien.toString().padStart(6, '0')} thành công!`,
                                        type: "success",
                                        duration: 100000
                                    });
                                    
                                } else {
                                    toast({
                                        title: `Xóa giảng viên ${id_giang_vien.toString().padStart(6, '0')} thất bại!`,
                                        message: `(${processState.message}).`,
                                        type: "error",
                                        duration: 100000
                                    });
                                }
                                
                            }
                            
                            $(`.delete-checkbox`).css(`visibility`, `hidden`);
                            $(`.delete-checkbox`).prop(`checked`, false);

                            $(`.save-div`).css(`position`, `absolute`);
                            $(`.save-div`).css(`z-index`, `-1`);
                            $(`.cancel-div`).css(`position`, `absolute`);
                            $(`.cancel-div`).css(`z-index`, `-1`);

                            let addbtn = $(`.add-teacher-btn`);
                            addbtn.prop(`disabled`, false);
                            addbtn.removeClass(`highlight-button--disable`);
                            addbtn.addClass(`highlight-button`);

                            let deletebtn = $(`.delete-teacher-btn`);
                            deletebtn.prop(`disabled`, false);
                            deletebtn.removeClass(`highlight-button--disable`);
                            deletebtn.addClass(`highlight-button`);
                        },
                        error: function(xhr, status, error) {
                            loadingEffect(false);
                            console.error('Lỗi yêu cầu:', status, error);
                        }
                    });
                }
            });

            $('.delete-checkbox').click(function(){
                if($(this).prop('checked')){
                    $(this).prop('checked', false);
                    }
                    else{
                        $(this).prop('checked', true);
                    }
            });

            $('.teacherCard').click(function(){
                if(deleteCheck){
                    if($(this).children().children('.delete-checkbox').prop('checked')){
                        $(this).children().children('.delete-checkbox').prop('checked', false);
                    }
                    else{
                        $(this).children().children('.delete-checkbox').prop('checked', true);
                    }
                }
                else{
                    console.log('update-teacher');
                    loadingEffect(true);
                    console.log($(this).attr("teacherid"));
                    var teacherID = $(this).attr("teacherid");
                    $.ajax({
                        url: '<?php echo base_url(); ?>/Admin/TeachersController/getUpdateForm',
                        method: 'GET',
                        data:   {
                                    teacherID : teacherID
                                },
                        success: function(response){
                            loadingEffect(false);
                            $('body').append(response);

                            $('.update-teacher-form__cancel-btn').click(function() {
                                $('.form-container').remove();
                            });

                            $('.update-teacher-form__save-btn').click(function(){
                                console.log('update-teacher-form__save-btn');
                                loadingEffect(true);
                                var fullName = $('.update-teacher-form__fullname').val();
                                var dob = $('.update-teacher-form__dob').val();
                                var sex = $('.update-teacher-form__sex option:selected').val();
                                var email = $('.update-teacher-form__email').val();

                                var obj = {
                                    id_giang_vien: teacherID,
                                    ho_ten: fullName,
                                    ngay_sinh: dob,
                                    gioi_tinh: sex,
                                    email: email,
                                }
                                
                                var jsonData = JSON.stringify(obj);

                                $.ajax({
                                    url: '<?php echo base_url(); ?>/Admin/TeachersController/updateTeacher',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: jsonData,
                                    success: function(response){
                                        if(response.state){
                                            loadingEffect(false);
                                            $(`[teacherid = ${obj.id_giang_vien}]`).html('');
                                            $(`[teacherid = ${obj.id_giang_vien}]`).append(`
                                                                <div class='p-3 card shadow-sm'>
                                                                    <div class='card-body'>
                                                                        <h3 class='card-title fs-4'><b>${obj.ho_ten}</b> - ${obj.id_giang_vien}</h3>
                                                                        <div class='my-5'></div>
                                                                        <p class='card-subtitle fs-5'><b>Email:</b> ${obj.email}</p>
                                                                    </div>
                                                                    <input type='checkbox' class='delete-checkbox' value='${obj.id_giang_vien}'>
                                                                </div>`);

                                            // Thêm lớp học đang có vào danh sách giảng dạy
                                            var list_id_lop_hoc = {};
                                            $('.addClassTable .addClasses:checked').each(function(){
                                                list_id_lop_hoc[$(this).val() + ""] = $(this).parent().parent().find('td').eq(0).text();
                                            })
                                            if(Object.keys(list_id_lop_hoc).length > 0){
                                                var objPc = {
                                                    id_giang_vien : teacherID,
                                                    list_id_lop_hoc : list_id_lop_hoc
                                                }
                                                var jsonDataPC = JSON.stringify(objPc);
                                                console.log(objPc);
                                                console.log(jsonDataPC);
                                                $.ajax({
                                                    url: '<?php echo base_url(); ?>/Admin/TeachersController/addClassesIntoListOfTeachingCourses',
                                                    method: 'POST',
                                                    contentType: 'application/json',
                                                    data: jsonDataPC,
                                                    success: function(response){
                                                        for (var [course, processState] of Object.entries(response)) {
                                                            if (processState.state) {
                                                                toast({
                                                                    title: "Thành công!",
                                                                    message: `Thêm lớp ${course} vào danh sách giảng dạy thành công`,
                                                                    type: "success",
                                                                    duration: 100000
                                                                });
                                                            } else {
                                                                toast({
                                                                    title: "Thất bại!",
                                                                    message: `Thêm lớp ${course} vào danh sách giảng dạy thành công thất bại(${processState.message}).`,
                                                                    type: "error",
                                                                    duration: 100000
                                                                });
                                                            }
                                                        }
                                                    },
                                                    error: function(xhr, status, error) {
                                                        console.error('Error:', status, error);
                                                    }
                                                });
                                            }

                                            // Xóa lớp học khỏi danh sách giảng dạy
                                            var list_id_delete_lop_hoc = {}

                                            $('.deleteClassTable .deleteTeachingCourse:checked').each(function(){
                                                list_id_delete_lop_hoc[$(this).val() + ""] = $(this).parent().parent().find('td').eq(0).text();
                                            })

                                            if(Object.keys(list_id_delete_lop_hoc).length > 0){
                                                var objDeletePc = {
                                                    id_giang_vien : teacherID,
                                                    list_id_lop_hoc : list_id_delete_lop_hoc
                                                }

                                                var jsonDataDeletePC = JSON.stringify(objDeletePc);
                                                // console.log(objDeletePc);
                                                // console.log(jsonDataDeletePC);
                                                $.ajax({
                                                    url: '<?php echo base_url(); ?>/Admin/TeachersController/deleteClassesFromListOfTeachingCourses',
                                                    method: 'POST',
                                                    dataType: 'json',
                                                    data: jsonDataDeletePC,
                                                    success: function(response) {
                                                        loadingEffect(false);
                                                        for (var [course, processState] of Object.entries(response)) {
                                                            if (processState.state) {
                                                                toast({
                                                                    title: "Thành công!",
                                                                    message: `Xóa ${course} khỏi danh sách giảng dạy thành công!`,
                                                                    type: "success",
                                                                    duration: 100000
                                                                });
                                                            } else {
                                                                toast({
                                                                    title: `Xóa ${course} thất bại!`,
                                                                    message: `(${processState.message}).`,
                                                                    type: "error",
                                                                    duration: 100000
                                                                });
                                                            }
                                                        }
                                                    },
                                                    error: function(xhr, status, error) {
                                                        loadingEffect(false);
                                                        console.error('Lỗi yêu cầu:', status, error);
                                                    }
                                                });
                                            }
                                            toast({
                                                title: "Thành công!",
                                                message: `Cập nhật giáo viên thành công`,
                                                type: "success",
                                                duration: 5000
                                            });
                                            $('.form-container').remove();
                                        }
                                    },
                                    error: function(xhr, status, error) {
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
                }
            })

            $(document).on('click', '.update-teacher-form__profile-btn', function() {
                window.location.href = `<?php echo base_url(); ?>/profile/lecturer?id=${$(this).attr('teacherID')}`;
            });
        });

        function reloadCoursesList() {
            console.log("kkk");
            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/CoursesController/getListOfCourses',
                method: 'GET',
                contentType: 'application/json', // Đặt kiểu dữ liệu của yêu cầu là JSON
                data: null,
                success: function(response) { // Trả về mảng 
                    // jquery từ html tạo mảng current(id-ngaybd) gồm các khóa đang hiển thị
                    console.log("kkk2");
                    console.log(response);
                    let current = [];
                    let toDelete = [];
                    let toAdd = [];
                    $(`.class__list .class__item`).each(function() {
                        current.push({
                            "id_lop_hoc": `${$(this).attr(`courseid`)}`,
                        });
                    })
                    // duyệt từng phần tử ở mảng current so với mảng response, phần tử nào không có(đã xóa) hoặc có nhưng không trùng ngày thì xóa khỏi mảng current và thêm vào mảng toDelete
                    for (let i = 0; i < current.length; i++) {
                        let isAppear = false;
                        for (let j = 0; j < response.length; j++) {
                            if (response[j][`id_lop_hoc`] === current[i][`id_lop_hoc`]) {
                                isAppear = true;
                                break;
                            }
                        }
                        if (!isAppear) {
                            toDelete.push(i);
                        }
                    }
                    for (let i = toDelete.length - 1; i >= 0; i--) {
                        current.splice(toDelete[i], 1);
                        $(`.class__list .class__item`).eq(i).remove();
                    }
                    console.log(current);

                    // duyệt từng phần tử ở mảng response, phần tử nào không có id ở mảng current thì thêm vào mảng toAdd
                    for (let i = 0; i < response.length; i++) {
                        let isAppear = false;
                        for (let j = 0; j < current.length; j++) {
                            if (response[i][`id_lop_hoc`] === current[j][`id_lop_hoc`]) {
                                isAppear = true;
                                break;
                            }
                        }
                        if (!isAppear) {
                            let dsgv = "";
                            let y = 0;
                            response[i]['lecturers'].forEach((lecturer) => {
                                dsgv += (y !== 0 ? ', ' : '') + '<a href="' + '<?php echo base_url(); ?>' + '/profile?id=' + lecturer.id_giang_vien + '">' + lecturer.ho_ten + '</a>';
                                y++;
                            });

                            let status = kiem_tra_tinh_trang(response[i]["ngay_bat_dau"], response[i]["ngay_ket_thuc"]);
                            let courseid = String(response[i].id_mon_hoc).padStart(3, '0') + "." + String(response[i].id_lop_hoc).padStart(6, '0');
                            console.log(`<div class='class__item col-4 col-xxl-4' courseid='${response[i]["id_lop_hoc"]}' >
                                        <div class='p-3 border border-gray rounded-2 shadow-sm' style="animation: newClassEffect ease 3s">
                                            <div class='class__item__title mb-5'>
                                                <h6>${response[i]["ten_mon_hoc"]} ${courseid}</h6>
                                                <p>Giảng viên: ${dsgv}</p>
                                            </div>
                                            <div class='class__item__state'>
                                                <p>Thời gian: ${response[i]['ngay_bat_dau']} - ${response[i]["ngay_ket_thuc"]}</p>
                                                <p>Trạng thái: ${status}</p>
                                            </div>  
                                            <input type='checkbox' class='delete-checkbox'>
                                        </div>
                                    </div>`);
                            $(`.class__list .class__item`).eq(i).before(
                                `
                                    <div class='class__item col-4 col-xxl-4' courseid='${response[i]["id_lop_hoc"]}' >
                                        <div class='p-3 border border-gray rounded-2 shadow-sm' style="animation: newClassEffect ease 3s">
                                            <div class='class__item__title mb-5'>
                                                <h6>${response[i]["ten_mon_hoc"]} ${courseid}</h6>
                                                <p>Giảng viên: ${dsgv}</p>
                                            </div>
                                            <div class='class__item__state'>
                                                <p>Thời gian: ${response[i]['ngay_bat_dau']} - ${response[i]["ngay_ket_thuc"]}</p>
                                                <p>Trạng thái: ${status}</p>
                                            </div>  
                                            <input type='checkbox' class='delete-checkbox' value='${response[i].id_lop_hoc}'>
                    
                                        </div>
                                    </div>
                            `
                            );
                        }
                    }
                    // 
                    // Với mỗi 
                }
            })
        }


        function kiem_tra_tinh_trang(ngay_bat_dau, ngay_ket_thuc) {
            // Chuyển đổi chuỗi ngày thành đối tượng Date
            let datetime_bat_dau = chuyenChuoiThanhDate(ngay_bat_dau);
            let datetime_ket_thuc = chuyenChuoiThanhDate(ngay_ket_thuc);
            let datetime_hien_tai = new Date();

            // Đặt giờ, phút và giây về cuối ngày
            datetime_bat_dau.setHours(0, 0, 0, 0);
            datetime_ket_thuc.setHours(23, 59, 59, 999);

            if (datetime_bat_dau <= datetime_hien_tai && datetime_ket_thuc >= datetime_hien_tai) {
                return '<span class="class__item--inprocess">Đang diễn ra</span>';
            } else if (datetime_ket_thuc < datetime_hien_tai) {
                return '<span class="class__item--over">Đã kết thúc</span>';
            } else {
                return '<span class="class__item--upcoming">Sắp diễn ra</span>';
            }
        }

        // Hàm chuyển đổi chuỗi ngày thành đối tượng Date
        function chuyenChuoiThanhDate(chuoiNgay) {
            let parts = chuoiNgay.split('/');
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }


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