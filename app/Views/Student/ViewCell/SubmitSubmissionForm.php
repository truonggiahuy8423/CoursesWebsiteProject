<div class="form-container">
    <div class="submit-assignment-form">
        <div class="submit-assignment-form__title-section">
            <h5 class="submit-assignment-form__title">Tạo bài nộp mới</h5>
        </div>
        <h6 style="margin-left: 10px; margin-top: 10px; margin-bottom: 3px;">Danh sách tệp đã chọn</h6>
        <div class="submit-assignment-form__overflow-container">
            <div class="submit-assignment-form__files-container">
                <div class="submit-assignment-form__add-file-btn">
                    <i class="fa-solid fa-plus add-file-icon" style="margin-left: 0px!important;"></i>
                </div>
            </div>
        </div>
        <div class="submit-assignment-form__btn-container">
            <button class="submit-assignment-form__cancel-btn">Hủy</button>
            <button class="submit-assignment-form__save-btn">Thêm bài nộp</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(`.submit-assignment-form__add-file-btn`).click(async function() {
                let file = await chooseUserFile();
                if ($(`.file-item--var2[value="${file.id_tep_tin_tai_len}"]`).length !== 0) {
                    return;
                }
                let file_icon = {
                pdf: 'pdf_icon',
                docx: 'word_icon',
                doc: 'word_icon',
                xlsx: 'excel_icon',
                pptx: 'ppt_icon'
            }
            let fileExtension = ['pdf', 'docx', 'xlsx', 'doc', 'pptx'];
                $(`.submit-assignment-form__add-file-btn`).before(
                `
                                <div class='file-item--var2' value='${file['id_tep_tin_tai_len']}' isChosen>
                                    <img src='<?php echo base_url(); ?>/assets/img/${fileExtension.indexOf(file['extension']) !== -1 ? file_icon[file['extension']] : 'any_file_icon' }.png' alt=''>
                                    <span>${file['ten_tep']}.${file['extension']}</span>
                                    <i class="fa-solid fa-x remove-chosen-file" style="font-size: 12px!important; margin-left: 0px!important;"></i>
                                </div>
                                `
            );
            })
        })
        $(document).on('click', '.remove-chosen-file', function() {
            $(this).parent().remove();
        });
        $(document).on('click', '.submit-assignment-form__cancel-btn', function() {
            if (confirm("Hủy bỏ tạo bài nộp?")) {
                $(`.form-container`).remove();
            }
            $(this).parent().remove();
        });
        $(document).on('click', '.submit-assignment-form__save-btn', function() {
            loadingEffect(true);
            let urlParams = new URLSearchParams(window.location.search);
            let assignmentid = urlParams.get('assignmentid');
            let data = {
                id_bai_tap: assignmentid,
                list_of_files_id: []
            };
            $(`.file-item--var2`).each(function() {
                data.list_of_files_id.push($(this).attr(`value`) * 1);
            })
            console.log(data.list_of_files_id);
            $.ajax({
            url: "<?php echo base_url() ?>/Admin/CoursesController/submitAssignment",
            contentType: "json",
            type: "POST",
            dataType: "json",
            data: JSON.stringify(data),
            success: function(response) {
                loadingEffect(false);
                if (response.state) {
                    $(`.form-container`).remove();
                    reloadAssignmentPage();
                    toast({
                        title: "Thành công!",
                        message: response.message,
                        type: "success",
                        duration: 100000
                    });
                } else {
                    toast({
                        title: "Lỗi!",
                        message: response.message,
                        type: "error",
                        duration: 100000
                    });
                }
            }});
        });
    </script>
</div>