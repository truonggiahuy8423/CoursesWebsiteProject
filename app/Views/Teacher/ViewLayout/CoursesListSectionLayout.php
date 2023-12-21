<div class="main">
    <div style="margin-bottom: 30px;" class="title w-100 text-center text-uppercase">
        <h4>Danh sách lớp học</h4>
    </div>

    <div class="class-container">
        <div style="height: 30px;" class="class__search me-2 d-flex justify-content-end">

            <input style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-input" placeholder="Tìm khóa học">
            <button class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon" style=""></i></button>
            

            </div>
            
        </div>

        <div class="class__list p-4 border border-gray rounded-2 m-2 mt-3 shadow-inset" style="margin-top: 8px!important;">
            <div class="row mb-4">
                <?php
                for ($i = 0; $i < count($courses); $i++) {
                    $dsgv = "";
                    $y = 0;
                    foreach ($courses[$i]['lecturers'] as $lecturer) {
                        $dsgv = $dsgv . ($y != 0 ? ', ' : '') . '<a href="' . base_url() . '/profile/lecturer?id=' . $lecturer["id_giang_vien"] . '">' . $lecturer["ho_ten"] . '</a>';
                        $y++;
                    }
                    $status = kiem_tra_tinh_trang($courses[$i]['ngay_bat_dau'], $courses[$i]['ngay_ket_thuc']);
                    $courseid = str_pad($courses[$i]["id_mon_hoc"], 3, "0", STR_PAD_LEFT) . "." . str_pad($courses[$i]["id_lop_hoc"], 6, "0", STR_PAD_LEFT);
                    echo "
            <div class='class__item col-4 col-xxl-4' courseid='{$courses[$i]["id_lop_hoc"]}' >
                <div class='p-3 border border-gray rounded-2 shadow-sm class-div'>
                    <div class='class__item__title mb-5'>
                        <h6>{$courses[$i]["ten_mon_hoc"]} {$courseid}</h6>
                        <p>Giảng viên: {$dsgv}</p>
                    </div>
                    <div class='class__item__state'>
                        <p>Thời gian: {$courses[$i]['ngay_bat_dau']} - {$courses[$i]["ngay_ket_thuc"]}</p>
                        <p>Trạng thái: {$status}</p>
                    </div>  
                    <input type='checkbox' class='delete-checkbox' value='{$courses[$i]["id_lop_hoc"]}'>

                    
                </div>
            </div>
        ";
                }
                function ok($v)
                {
                    return $v;
                }
                function kiem_tra_tinh_trang($ngay_bat_dau, $ngay_ket_thuc)
                {
                    $datetime_bat_dau = DateTime::createFromFormat('d/m/Y', $ngay_bat_dau);

                    $datetime_ket_thuc =  DateTime::createFromFormat('d/m/Y', $ngay_ket_thuc);

                    $datetime_hien_tai = new DateTime();

                    $datetime_bat_dau->setTime(0, 0, 0);
                    $datetime_ket_thuc->setTime(23, 59, 59); // Đặt giờ, phút và giây về cuối ngày
                    // echo $ngay_bat_dau.$ngay_ket_thuc;
                    // echo $datetime_bat_dau->format("Y");
                    // So sánh
                    if ($datetime_bat_dau <= $datetime_hien_tai && $datetime_ket_thuc >= $datetime_hien_tai) {
                        return '<span class="class__item--inprocess">Đang diễn ra</span>';
                    } elseif ($datetime_ket_thuc < $datetime_hien_tai) {
                        return '<span class="class__item--over">Đã kết thúc</span>';
                    } else {
                        return '<span class="class__item--upcoming">Sắp diễn ra</span>';
                    }
                }

                ?>

            </div>
        </div>
    </div>
</div>
<script>
    
    setInterval(reloadCoursesList, 10000);
        $(document).ready(function() {
            
            $(`.delete-class-btn`).click(function() {
                $(`.delete-checkbox`).css(`visibility`, `visible`);

                $(`.save-div`).css(`position`, `static`);
                $(`.save-div`).css(`z-index`, `1`);
                $(`.cancel-div`).css(`position`, `static`);
                $(`.cancel-div`).css(`z-index`, `1`);

                let addbtn = $(`.add-class-btn`);
                addbtn.prop(`disabled`, true);
                addbtn.removeClass(`highlight-button`);
                addbtn.addClass(`highlight-button--disable`);
                let deletebtn = $(`.delete-class-btn`);
                deletebtn.prop(`disabled`, true);
                deletebtn.removeClass(`highlight-button`);
                deletebtn.addClass(`highlight-button--disable`);
            });
            $(`.cancel-delete-class-btn`).click(function() {
                $(`.delete-checkbox`).css(`visibility`, `hidden`);
                $(`.delete-checkbox`).prop(`checked`, false);

                $(`.save-div`).css(`position`, `absolute`);
                $(`.save-div`).css(`z-index`, `-1`);
                $(`.cancel-div`).css(`position`, `absolute`);
                $(`.cancel-div`).css(`z-index`, `-1`);

                let addbtn = $(`.add-class-btn`);
                addbtn.prop(`disabled`, false);
                addbtn.removeClass(`highlight-button--disable`);
                addbtn.addClass(`highlight-button`);
                let deletebtn = $(`.delete-class-btn`);
                deletebtn.prop(`disabled`, false);
                deletebtn.removeClass(`highlight-button--disable`);
                deletebtn.addClass(`highlight-button`);
            });
            $(`.save-delete-class-btn`).click(function() {
                // check 
                if ($(`.delete-checkbox:checked`).length == 0) {
                    toast({
                        title: 'Thông báo',
                        message: 'Chưa chọn lớp học cần xóa',
                        type: 'warning',
                        duration: 5000
                    });
                } else {
                    loadingEffect(true);
                    let courses = [];
                    $(`.delete-checkbox:checked`).each(function() {

                        courses.push($(this).attr("value"));
                    });
                    console.log(courses);
                    let jsonData = {};
                    jsonData[`courses`] = courses;
                    jsonData = JSON.stringify(jsonData);
                    $.ajax({
                        url: '<?php echo base_url(); ?>/Admin/CoursesController/deleteCourse',
                        method: 'POST',
                        dataType: 'json', // Kiểu dữ liệu bạn mong đợi từ phản hồi (json, html, text, vv.)
                        data: // Dữ liệu bạn muốn gửi đi (nếu có)
                            jsonData,
                        success: function(response) {
                            loadingEffect(false);
                            $(`.delete-checkbox`).css(`visibility`, `hidden`);
                            $(`.delete-checkbox`).prop(`checked`, false);

                            $(`.save-div`).css(`position`, `absolute`);
                            $(`.save-div`).css(`z-index`, `-1`);
                            $(`.cancel-div`).css(`position`, `absolute`);
                            $(`.cancel-div`).css(`z-index`, `-1`);

                            let addbtn = $(`.add-class-btn`);
                            addbtn.prop(`disabled`, false);
                            addbtn.removeClass(`highlight-button--disable`);
                            addbtn.addClass(`highlight-button`);
                            let deletebtn = $(`.delete-class-btn`);
                            deletebtn.prop(`disabled`, false);
                            deletebtn.removeClass(`highlight-button--disable`);
                            deletebtn.addClass(`highlight-button`);
                            for (var [id_lop_hoc, processState] of Object.entries(response)) {
                                if (processState.state) {
                                    toast({
                                        title: "Thành công!",
                                        message: `Xóa lớp học ${id_lop_hoc.toString().padStart(6, '0')} thành công!`,
                                        type: "success",
                                        duration: 100000
                                    });
                                    // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                } else {
                                    toast({
                                        title: `Xóa lớp học ${id_lop_hoc.toString().padStart(6, '0')} thất bại!`,
                                        message: `(${processState.message}).`,
                                        type: "error",
                                        duration: 100000
                                    });

                                    // Gọi hàm in thông báo, type: "error", title: "Thêm giảng viên ${lecturer} thêm vào lớp ${selectedSubjectId}.${processResult1.auto_increment_id} thất bại", content: "processState.message"
                                }
                            }
                        }
                    });
                }
            });
            console.log('ready');

            $(document).on('click', '.class-div', function() {
                window.location.href = `<?php echo base_url(); ?>/courses/information?courseid=${$(this).parent().attr('courseid')}`;
            });
            $(document).on('click', '.delete-checkbox', function(event) {
                event.stopPropagation();
            });
            let loadingState = false;

            $(`.add-class-btn`).click(function() {
                console.log('OK');
                loadingEffect(true);

                // Use jQuery.ajax for the AJAX request
                $.ajax({
                    url: '<?php echo base_url(); ?>/Admin/CoursesController/getInsertClassForm',
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
                                                console.log(className);
                                                className = className.trim();
                                                className = className.substring(6, className.length);
                                                console.log(className);

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
                                                            message: `Thêm giảng viên ${lecturer} vào lớp ${className} ${selectedSubjectId.toString().padStart(3, '0')}.${processResult1.auto_increment_id.toString().padStart(6, '0')} thành công`,
                                                            type: "success",
                                                            duration: 100000
                                                        });
                                                        // Gọi hàm in thông báo, type: "succcess", title: "Thêm lớp thành công", content: "Lớp ${className} ${selectedSubjectId}.${processResult1.auto_increment_id} được thêm thành công"
                                                    } else {
                                                        toast({
                                                            title: "Thất bại!",
                                                            message: `Thêm giảng viên ${lecturer} vào lớp ${className} ${selectedSubjectId.toString().padStart(3, '0')}.${processResult1.auto_increment_id.toString().padStart(6, '0')} thất bại(${processState.message}).`,
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
        });

        function reloadCoursesList() {
            console.log("kkk");
            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/CoursesController/getListOfCoursesForTeacher',
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
                                dsgv += (y !== 0 ? ', ' : '') + '<a href="' + '<?php echo base_url(); ?>' + '/profile/lecturer?id=' + lecturer.id_giang_vien + '">' + lecturer.ho_ten + '</a>';
                                y++;
                            });

                            let status = kiem_tra_tinh_trang(response[i]["ngay_bat_dau"], response[i]["ngay_ket_thuc"]);
                            let courseid = String(response[i].id_mon_hoc).padStart(3, '0') + "." + String(response[i].id_lop_hoc).padStart(6, '0');
                            console.log(`<div class='class__item col-4 col-xxl-4' courseid='${response[i]["id_lop_hoc"]}' >
                                <div class='p-3 border border-gray rounded-2 shadow-sm class-div' style="animation: newClassEffect ease 3s">
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
                                <div class='p-3 border border-gray rounded-2 shadow-sm class-div' style="animation: newClassEffect ease 3s">
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
</script>