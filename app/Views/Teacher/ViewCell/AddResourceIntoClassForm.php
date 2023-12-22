<div class="form-container">
    <div class="insert-resource-form">
        <div class="insert-resource-form__title-section">
            <h5 class="insert-resource-form__title">Thêm tài nguyên mới</h5>
        </div>
        <!-- <h6>Chọn tài nguyên</h6> -->
        <div class="insert-resource-form__overflow-container">
            <div class="insert-resource-form__option-container">
                <button class="insert-resource-form__option insert-resource-form__option--folder">
                    <img src="<?php base_url() ?>/assets/img/folder_icon.png" alt="">
                    <span>Thêm mục mới</span>
                </button>
                <button class="insert-resource-form__option insert-resource-form__option--file">
                    <img src="<?php base_url() ?>/assets/img/any_file_icon.png" alt="">
                    <span>Đăng tải tệp tin</span>
                </button>
                <button class="insert-resource-form__option insert-resource-form__option--link">
                    <img src="<?php base_url() ?>/assets/img/link_icon.png" alt="">
                    <span>Đường dẫn</span>
                </button>
                <button class="insert-resource-form__option insert-resource-form__option--assignment">
                    <img src="<?php base_url() ?>/assets/img/assignment_icon.png" alt="">
                    <span>Assignment</span>
                </button>
                <button class="insert-resource-form__option insert-resource-form__option--noti">
                    <img src="<?php base_url() ?>/assets/img/noti_icon.png" alt="">
                    <span>Thông báo mới</span>
                </button>

            </div>
        </div>

        <div class="insert-resource-form__main_section">
            <!-- <span class="auth-file-access-noti">Tệp không còn tồn tại hoặc bạn không có quyền sử hữu tệp tin này</span> -->
        </div>
        <div class="insert-resource-form__btn-container">
            <button class="insert-resource-form__cancel-btn">Hủy</button>
            <button class="insert-resource-form__save-btn">Lưu thông tin</button>
        </div>
    </div>
    <script>
        function hlightOption(option) {
            $(`.insert-resource-form__option`).removeClass(`hlight`);
            $(`.insert-resource-form__option`).eq(option).addClass(`hlight`);
        }

        function noti(message) {
            $(`.details-resource-table`).append(
                `
                                        <span id="noti" class="auth-file-access-noti">${message}</span>
                             
                            `
            );
            setTimeout(function() {
                $(`#noti`).css(`opacity`, 0);
            }, 3000);
            setTimeout(function() {
                $(`#noti`).remove();
            }, 4000);
        }
        $(document).ready(function() {
            $(`.insert-resource-form__option--folder`).click(function() {
                hlightOption(0);
                $(`.insert-resource-form__option`).prop(`disabled`, false);
                $(this).prop(`disabled`, true);
                console.log("clicked");
                $(`.insert-resource-form__main_section`).empty();
                $(`.insert-resource-form__main_section`).append(`
            <table class="details-resource-table">
                <tr>
                    <td style="width: 120px">
                        <span class="label">Tiêu đề:</span>
                    </td>
                    <td>
                        <input class="folder-title-input text-input" type="text" placeholder="Nhập tên mục...">
                    </td>
                </tr>
            </table>
            `);
                $(`.insert-resource-form__save-btn`).off("click");
                $(`.insert-resource-form__save-btn`).click(function() {
                    loadingEffect(true);
                    var urlParams = new URLSearchParams(window.location.search);
                    // var param1Value = urlParams.get('courrseid');
                    let id_lop_hoc = urlParams.get('courseid');
                    let id_muc = $(`.insert-resource-form`).attr(`value`);
                    let newFolder = $(`.folder-title-input`).val();
                    if (newFolder === '') {
                        noti("Nhập tiêu đề mục");
                        loadingEffect(false);
                        return;
                    }
                    $.ajax({
                        url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/postNewFolder`,
                        contentType: "text",
                        dataType: "json",
                        data: {
                            ten_muc: newFolder,
                            id_lop_hoc: id_lop_hoc,
                            id_muc: id_muc
                        },
                        success: function(response) {
                            loadingEffect(false);
                            rerenderCourseResources(id_muc);
                            if (response.state) {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Thành công!",
                                    message: response.message,
                                    type: "success",
                                    duration: 100000
                                });
                            } else {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Lỗi!",
                                    message: response.message,
                                    type: "error",
                                    duration: 100000
                                });
                            }
                        }
                    })

                    // noti("OK");
                });
            })
            $(`.insert-resource-form__option--file`).click(function() {
                hlightOption(1);
                $(`.insert-resource-form__option`).prop(`disabled`, false);
                $(this).prop(`disabled`, true);
                $(`.insert-resource-form__main_section`).empty();
                $(`.insert-resource-form__main_section`).append(`
            <table class="details-resource-table">
                <tr>
                    <td style="width: 120px; vertical-align: middle;">
                        <span class="label">Chọn tệp tin:</span>
                    </td>
                    <td style="vertical-align: middle;">
                        <div style="display: flex; align-items: center;">
                            <div class="file-container">
                            </div>
                            <button class="get-file-btn">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>`);
                $('.get-file-btn').click(async function() {
                    $(`.file-container`).text('');
                    let chosenFile = await chooseUserFile();
                    if (chosenFile === false) {
                        return;
                    }
                    console.log(chosenFile);
                    let file = chosenFile;
                    // $(`.file-container`).append();
                    let file_icon = {
                        pdf: 'pdf_icon',
                        docx: 'word_icon',
                        doc: 'word_icon',
                        xlsx: 'excel_icon',
                        pptx: 'ppt_icon'
                    }
                    let fileExtension = ['pdf', 'docx', 'xlsx', 'doc', 'pptx'];
                    $(`.file-container`).append(
                        `
                                <div class='file-item--var' value='${file['id_tep_tin_tai_len']}' isChosen>
                                    <img src='<?php echo base_url(); ?>/assets/img/${fileExtension.indexOf(file['extension']) !== -1 ? file_icon[file['extension']] : 'any_file_icon' }.png' alt=''>
                                    <span>${file['ten_tep']}.${file['extension']}</span>
                                </div>
                                `
                    );

                })
                $(`.insert-resource-form__save-btn`).off("click");
                $(`.insert-resource-form__save-btn`).click(function() {
                    loadingEffect(true);
                    var urlParams = new URLSearchParams(window.location.search);
                    // var param1Value = urlParams.get('courrseid');
                    let id_lop_hoc = urlParams.get('courseid');
                    let id_muc = $(`.insert-resource-form`).attr(`value`);
                    let file = $(`.file-item--var`);
                    if (file.length === 0) {
                        noti("Chưa có tệp tin nào được chọn");
                        loadingEffect(false);
                        return;
                    }
                    let id_tep_tin_tai_len = file.attr(`value`);
                    console.log(id_lop_hoc + " " + id_muc + " " + id_tep_tin_tai_len);

                    // console.log(id_lop_hoc + " " + id_tep_tin_tai_len);
                    $.ajax({
                        url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/postFileOnClass`,
                        contentType: "text",
                        dataType: "json",
                        data: {
                            file_id: id_tep_tin_tai_len,
                            id_lop_hoc: id_lop_hoc,
                            id_muc: id_muc
                        },
                        success: function(response) {
                            loadingEffect(false);
                            rerenderCourseResources(id_muc);
                            if (response.state) {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Thành công!",
                                    message: response.message,
                                    type: "success",
                                    duration: 100000
                                });
                            } else {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Lỗi!",
                                    message: response.message,
                                    type: "error",
                                    duration: 100000
                                });
                            }
                        }
                    })
                })
            })
            $(`.insert-resource-form__option--link`).click(function() {
                hlightOption(2);
                $(`.insert-resource-form__option`).prop(`disabled`, false);
                $(this).prop(`disabled`, true);
                $(`.insert-resource-form__main_section`).empty();
                $(`.insert-resource-form__main_section`).append(`
            <table class="details-resource-table">
                <tr>
                    <td style="width: 120px">
                        <span class="label">Tiêu đề:</span>
                    </td>
                    <td>
                        <input class="link-title-input text-input" type="text" placeholder="Nhập tên đường dẫn...">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Đường dẫn:</span>
                    </td>
                    <td>
                        <input class="link-input text-input" type="text" placeholder="Nhập liên kết...">
                    </td>
                </tr>
            </table>
            `);
                $(`.insert-resource-form__save-btn`).off("click");
                $(`.insert-resource-form__save-btn`).click(function() {
                    loadingEffect(true);
                    var urlParams = new URLSearchParams(window.location.search);
                    // var param1Value = urlParams.get('courrseid');
                    let id_lop_hoc = urlParams.get('courseid');
                    let id_muc = $(`.insert-resource-form`).attr(`value`);
                    let tieu_de = $(`.link-title-input`).val();
                    let link = $(`.link-input`).val();
                    if (link === '' || tieu_de === '') {
                        noti("Hãy nhập đường dẫn hoặc tên đường dẫn");
                        loadingEffect(false);
                        return;
                    }
                    $.ajax({
                        url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/postNewLinkOnClass`,
                        contentType: "text",
                        dataType: "json",
                        data: {
                            tieu_de: tieu_de,
                            link: link,
                            id_lop_hoc: id_lop_hoc,
                            id_muc: id_muc
                        },
                        success: function(response) {
                            loadingEffect(false);
                            rerenderCourseResources(id_muc);
                            if (response.state) {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Thành công!",
                                    message: response.message,
                                    type: "success",
                                    duration: 100000
                                });
                            } else {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Lỗi!",
                                    message: response.message,
                                    type: "error",
                                    duration: 100000
                                });
                            }
                        }
                    })

                    // noti("OK");
                });

            })
            $(`.insert-resource-form__option--assignment`).click(function() {
                hlightOption(3);
                $(`.insert-resource-form__option`).prop(`disabled`, false);
                $(this).prop(`disabled`, true);
                $(`.insert-resource-form__main_section`).empty();
                $(`.insert-resource-form__main_section`).append(`
            <table class="details-resource-table">
                <tr>
                    <td>
                        <span class="label">Tên bài tập:</span>
                    </td>
                    <td>
                        <input class="assignment-title-input text-input" type="text" placeholder="Nhập tên bài tập...">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Nội dung:</span>
                    </td>
                    <td>
                        <textarea class="assignment-content-input" name="message" rows="4" cols="50" placeholder="Nhập nội dung"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Thời hạn:</span>
                    </td>
                    <td>
                        <input class="thoi-han-input text-input datetimelocal" type="datetime-local">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Thời gian đóng bài tập:</span>
                    </td>
                    <td>
                        <input class="thoi-han-nop-input datetimelocal" type="datetime-local">
                    </td>
                </tr>
            </table>
            `);

                $(`.insert-resource-form__save-btn`).off("click");
                $(`.insert-resource-form__save-btn`).click(function() {
                    loadingEffect(true);
                    var urlParams = new URLSearchParams(window.location.search);
                    // var param1Value = urlParams.get('courrseid');
                    let id_lop_hoc = urlParams.get('courseid');
                    let id_muc = $(`.insert-resource-form`).attr(`value`);
                    let ten_bai_tap = $(`.assignment-title-input`).val();
                    let noi_dung = $(`.assignment-content-input`).val();
                    let th = $(`.thoi-han-input`).val();
                    let thn = $(`.thoi-han-nop-input`).val();

                    if (th === null) {
                        noti("Hãy chọn thời hạn của bài tập");
                        loadingEffect(false);
                        return;
                    }
                    if (th === null) {
                        noti("Hãy chọn thời hạn nộp của bài tập");
                        loadingEffect(false);
                        return;
                    }
                    // let id_tep_tin_tai_len = file.attr(`value`);
                    // console.log(id_lop_hoc + " " + id_muc + " " + id_tep_tin_tai_len);

                    // console.log(id_lop_hoc + " " + id_tep_tin_tai_len);
                    $.ajax({
                        url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/postAssignmentOnClass`,
                        contentType: "text",
                        dataType: "json",
                        data: {
                            id_lop_hoc: id_lop_hoc,
                            id_muc: id_muc,
                            ten_bai_tap: ten_bai_tap,
                            noi_dung: noi_dung,
                            thoi_han: replaceLettersWithSpace(th),
                            thoi_han_nop: replaceLettersWithSpace(thn)
                        },
                        success: function(response) {
                            loadingEffect(false);
                            rerenderCourseResources(id_muc);
                            if (response.state) {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Thành công!",
                                    message: response.message,
                                    type: "success",
                                    duration: 100000
                                });
                            } else {
                                $(`.form-container`).remove();
                                toast({
                                    title: "Lỗi!",
                                    message: response.message,
                                    type: "error",
                                    duration: 100000
                                });
                            }
                        }
                    })
                })
            })
            $(`.insert-resource-form__option--noti`).click(function() {
                hlightOption(4);
                $(`.insert-resource-form__option`).prop(`disabled`, false);
                $(this).prop(`disabled`, true);
                $(`.insert-resource-form__main_section`).empty();
                $(`.insert-resource-form__main_section`).append(`
            <table class="details-resource-table">
                <tr>
                    <td>
                        <span class="label">Tiêu đề:</span>
                    </td>
                    <td>
                        <input class="noti-title-input text-input" type="text" placeholder="Nhập tiêu đề thông báo...">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Nội dung:</span>
                    </td>
                    <td>
                        <textarea class="noti-content-input" name="message" rows="4" cols="50" placeholder="Nhập nội dung"></textarea>
                    </td>
                </tr>
            </table>
            `);
                $(document).ready(function() {
                    $(`.insert-resource-form__save-btn`).off("click");
                    $(`.insert-resource-form__save-btn`).click(function() {
                        loadingEffect(true);
                        var urlParams = new URLSearchParams(window.location.search);
                        // var param1Value = urlParams.get('courrseid');
                        let id_lop_hoc = urlParams.get('courseid');
                        let id_muc = $(`.insert-resource-form`).attr(`value`);
                        let tieu_de = $(`.noti-title-input`).val();
                        let noi_dung = document.querySelector(".noti-content-input").value;
                        console.log(noi_dung);
                        if (noi_dung.length === 0 || tieu_de === '') {
                            noti("Hãy nhập nội dung hoặc tiêu đề thông báo");
                            loadingEffect(false);
                            return;
                        }
                        $.ajax({
                            url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/postNewNotiOnClass`,
                            contentType: "text",
                            dataType: "json",
                            data: {
                                tieu_de: tieu_de,
                                noi_dung: noi_dung,
                                id_lop_hoc: id_lop_hoc,
                                id_muc: id_muc
                            },
                            success: function(response) {
                                loadingEffect(false);
                                rerenderCourseResources(id_muc);
                                if (response.state) {
                                    $(`.form-container`).remove();
                                    toast({
                                        title: "Thành công!",
                                        message: response.message,
                                        type: "success",
                                        duration: 100000
                                    });
                                } else {
                                    $(`.form-container`).remove();
                                    toast({
                                        title: "Lỗi!",
                                        message: response.message,
                                        type: "error",
                                        duration: 100000
                                    });
                                }
                            }
                        })

                        // noti("OK");
                    });
                });


            })
            $(`.insert-resource-form__option--folder`).click();
        })

        function replaceLettersWithSpace(inputString) {
            // Sử dụng biểu thức chính quy để thay thế tất cả các ký tự chữ cái thành khoảng trắng
            var replacedString = inputString.replace(/[a-zA-Z]/g, ' ');

            return replacedString;
        }
    </script>
</div>