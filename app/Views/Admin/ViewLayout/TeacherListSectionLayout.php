<div class="main">
    <div style="margin-bottom: 30px;" class="title w-100 text-center text-uppercase">
        <h4>Danh sách giảng viên</h4>
    </div>

    <div class="class-container">
        <div style="height: 30px;" class="class__search me-2 d-flex justify-content-end">
            <input style="border-radius: 0; height: 30px; width: 90px; z-index: 3" type="text" class="w-25 form-control search-input" placeholder="Tìm giảng viên">
            <button class="btn btn-info search-button highlight-button"><i class="fas fa-search icon-search highlight-icon"></i></button>
            <button class="add-teacher-btn highlight-button">
                <i class="fa-solid fa-plus add-class-icon highlight-icon"></i>
            </button>
            <button class="delete-teacher-btn highlight-button">
                <i class="fa-solid fa-trash-can highlight-icon"></i>
            </button>
            <div class="cancel-div">
                <button class="cancel-delete-teacher-btn highlight-button--cancel">
                    <i class="fa-solid fa-x highlight-icon--cancel" style="scale: 0.5;"></i>
                </button>
            </div>
            <div class="save-div">
                <button class="save-delete-teacher-btn highlight-button--save">
                    <i class="fa-solid fa-check highlight-icon--save" style="scale: 0.6;"></i>
                </button>
            </div>

        </div>

        <div class="p-4 card m-2 mt-3 shadow-inset" style="margin-top: 8px!important;">
            <div class="teacher__list row mb-4">
                <?php
                for ($i = 0; $i < count($teachers); $i++) {
                    echo "
                                <div class='col-6 mb-3 teacherCard' teacherid='{$teachers[$i]["id_giang_vien"]}'>
                                    <div class='p-3 card shadow-sm'>
                                        <div class='card-body'>
                                            <h3 class='card-title fs-4'><b>{$teachers[$i]["ho_ten"]}</b> - {$teachers[$i]["id_giang_vien"]}</h3>
                                            <div class='my-5'></div>
                                            <p class='card-subtitle fs-5'><b>Email:</b> {$teachers[$i]["email"]}</p>
                                        </div>
                                        <input type='checkbox' class='delete-checkbox' value='{$teachers[$i]["id_giang_vien"]}'>
                                    </div>
                                </div>  
                            ";
                }
                ?>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.add-teacher-btn').click(function() {
            console.log('add-teacher-btn');
            loadingEffect(true);
            $.ajax({
                url: '<?php echo base_url(); ?>/Admin/TeachersController/getInsertForm',
                method: 'GET',
                success: function(response) {
                    loadingEffect(false);
                    $('body').append(response);

                    $(`.insert-teacher-form__cancel-btn`).click(function() {
                        $('.form-container').remove();
                    });

                    $(`.insert-teacher-form__save-btn`).click(function() {
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
                            success: function(response) {
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

        $('.delete-checkbox').click(function() {
            if ($(this).prop('checked')) {
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
            }
        });

        $('.teacherCard').click(function() {
            if (deleteCheck) {
                if ($(this).children().children('.delete-checkbox').prop('checked')) {
                    $(this).children().children('.delete-checkbox').prop('checked', false);
                } else {
                    $(this).children().children('.delete-checkbox').prop('checked', true);
                }
            } else {
                console.log('update-teacher');
                loadingEffect(true);
                console.log($(this).attr("teacherid"));
                var teacherID = $(this).attr("teacherid");
                $.ajax({
                    url: '<?php echo base_url(); ?>/Admin/TeachersController/getUpdateForm',
                    method: 'GET',
                    data: {
                        teacherID: teacherID
                    },
                    success: function(response) {
                        loadingEffect(false);
                        $('body').append(response);

                        $('.update-teacher-form__cancel-btn').click(function() {
                            $('.form-container').remove();
                        });

                        $('.update-teacher-form__save-btn').click(function() {
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
                                success: function(response) {
                                    if (response.state) {
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
                                        $('.addClassTable .addClasses:checked').each(function() {
                                            list_id_lop_hoc[$(this).val() + ""] = $(this).parent().parent().find('td').eq(0).text();
                                        })
                                        if (Object.keys(list_id_lop_hoc).length > 0) {
                                            var objPc = {
                                                id_giang_vien: teacherID,
                                                list_id_lop_hoc: list_id_lop_hoc
                                            }
                                            var jsonDataPC = JSON.stringify(objPc);
                                            console.log(objPc);
                                            console.log(jsonDataPC);
                                            $.ajax({
                                                url: '<?php echo base_url(); ?>/Admin/TeachersController/addClassesIntoListOfTeachingCourses',
                                                method: 'POST',
                                                contentType: 'application/json',
                                                data: jsonDataPC,
                                                success: function(response) {
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

                                        $('.deleteClassTable .deleteTeachingCourse:checked').each(function() {
                                            list_id_delete_lop_hoc[$(this).val() + ""] = $(this).parent().parent().find('td').eq(0).text();
                                        })

                                        if (Object.keys(list_id_delete_lop_hoc).length > 0) {
                                            var objDeletePc = {
                                                id_giang_vien: teacherID,
                                                list_id_lop_hoc: list_id_delete_lop_hoc
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
    });
    $(document).on('click', '.update-teacher-form__profile-btn', function() {
        window.location.href = `<?php echo base_url(); ?>/profile/lecturer?id=${$(this).attr('teacherID')}`;
    });
</script>