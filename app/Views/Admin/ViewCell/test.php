<script>

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
                            $.ajax({
                                url: '<?php echo base_url(); ?>/Admin/CoursesController/insertCourse', // Đường dẫn tới API hoặc resource bạn muốn gọi
                                method: 'POST', // Phương thức HTTP (GET, POST, PUT, DELETE, vv.)
                                dataType: 'json', // Kiểu dữ liệu bạn mong đợi từ phản hồi (json, html, text, vv.)
                                data: // Dữ liệu bạn muốn gửi đi (nếu có)
                                    jsonData,
                                success: function(response) {
                                    var processResult1 = (response);
                                    if (processResult1.state) {
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
</script>