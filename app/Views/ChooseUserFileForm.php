<div class="form-container--file">
    <div class="get-file-form">
        <h6 class="get-file-form__title">Chọn tệp</h6>


        <h6 style="font-size: 14px;">
            Tải file
        </h6>
        <div style="width: 100%; display: flex; align-items: center; margin-top: 5px;">
            <input id="fileInput" class="file-upload-input" type="file">
            <label for="fileInput" class="custom-file-input">Chọn tệp</label>
            <div id="filename-display">Chưa tệp nào được chọn</div>
        </div>
        <div class="upload-file-btn-container">
            <!-- <span class="upload-file-noti upload-file-noti--success">Đăng tệp thành công</span> -->
            <!-- <i class='fa-solid fa-spinner upload-file-loading-icon'></i> -->
            <button class="upload-file-btn">Đăng tệp</button>
        </div>
        <h6 style="font-size: 14px; margin-bottom: 5px;">
            File của tôi
        </h6>
        <!-- <div class="file-item-overflow" style="overflow: auto;"> -->
        <div class="file-item-container">
            <?php
            foreach ($listOfUserFile as $file) {
                $burl = base_url();
                $file_icon = [
                    'pdf' => 'pdf_icon',
                    'docx' => 'word_icon',
                    'doc' => 'word_icon',
                    'xlsx' => 'excel_icon',
                    'pptx' => 'ppt_icon'
                ];

                // Giả sử $file->extension chứa phần extension của tệp (ví dụ: 'pdf')
                $icon = (array_key_exists($file->extension, $file_icon) ? $file_icon[$file->extension] : 'any_file_icon');
                echo "
                        <div class='file-item' value='{$file->id_tep_tin_tai_len}' isChosen=''false>
                            <img src='{$burl}/assets/img/{$icon}.png' alt=''>
                            <span>{$file->ten_tep}.{$file->extension}</span>
                        </div>
                    ";
            }
            ?>
        </div>
        <!-- </div> -->


        <div class="get-file-form__btn-container">
            <button class="get-file-form__cancel-btn">Hủy</button>
            <button class="get-file-form__save-btn">Lưu thông tin</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(`.upload-file-btn`).click(function() {
                let fileList = $(`.file-upload-input`).prop('files');
                console.log(fileList);
                if (fileList.length === 0) {
                    uploadingNoti("Chưa có tệp nào được chọn", false);
                    return;
                } else {
                    var file = fileInput.files[0];
                    if (file.size / (1024 * 1024) > 50) {
                        uploadingNoti("Độ lớn file không vượt quá 50MB", false);
                        return;
                    }
                    var formData = new FormData();
                    formData.append('file', file);
                    uploadingEffect(true);
                    $.ajax({
                        url: '<?php echo base_url() ?>/Admin/CoursesController/uploadFile', // Đường dẫn đến script PHP xử lý
                        type: 'POST',
                        data: formData,
                        processData: false, // Không xử lý dữ liệu
                        contentType: false, // Không đặt lại loại nội dung
                        dataType: "json",
                        success: function(response) {
                            uploadingEffect(false);
                            if (response.state) {
                                uploadingNoti(response.message, true);
                                let file_icon = {
                                    pdf: 'pdf_icon',
                                    docx: 'word_icon',
                                    doc: 'word_icon',
                                    xlsx: 'excel_icon',
                                    pptx: 'ppt_icon'
                                }
                                let fileExtension = ['pdf', 'docx', 'xlsx', 'doc', 'pptx'];
                                $(`.file-item-container`).prepend(
                                    `
                                <div class='file-item' value='${response.auto_increment_id}' isChosen>
                                    <img src='<?php echo base_url(); ?>/assets/img/${fileExtension.indexOf(response['extension']) !== -1 ? file_icon[response['extension']] : 'any_file_icon' }.png' alt=''>
                                    <span>${response.fileName}.${response.extension}</span>
                                </div>
                                `
                                );
                                console.log(response);
                            } else {
                                uploadingNoti(response.message, false);
                                console.log(response);
                            }
                        },
                        error: function(error) {
                            uploadingEffect(false);
                            console.error('Lỗi khi tải lên:', error);
                        }
                    });
                }
            });
            // uploadingEffect(true);
            // uploadingNoti("message", true)
            $('#fileInput').change(function() {
                // Lấy tên file đã chọn
                var filename = this.files[0].name;

                // Hiển thị tên file trong phần tử có id="filename-display" bằng jQuery
                $('#filename-display').text(filename);
            });
        })
        $(document).on('click', '.file-item', function() {
            console.log('clicked');
            console.log($(this).attr('isChosen'));
            $(`.file-item`).attr(`isChosen`, "false");
            $(`.file-item`).removeClass(`hilight`);
            $(this).attr(`isChosen`, "true");
            $(this).addClass(`hilight`);
            console.log($(this).attr('isChosen'));
        });


        // let listOfFileItem = [];

        function reloadUserFile() {

        }

        function uploadingEffect(state) {
            if (state) {
                $(`.upload-file-btn-container`).prepend(`
                <i class='fa-solid fa-spinner upload-file-loading-icon'></i>
            `);
            } else {
                $(`.upload-file-loading-icon`).remove();
            }
        }

        function uploadingNoti(message, type) {
            if (type) {
                $(`.upload-file-btn-container`).prepend(`
                <span class="upload-file-noti upload-file-noti--success">${message}</span>
            `);
                $(`#filename-display`).text(`Chưa tệp nào được chọn`);
                $(`.file-upload-input`).val(``);
            } else {
                $(`.upload-file-btn-container`).prepend(`
                <span class="upload-file-noti upload-file-noti--error">${message}</span>
            `);
            }
            setTimeout(function() {
                $(`.upload-file-noti`).css(`opacity`, 0);
            }, 3000);
            setTimeout(function() {
                $(`.upload-file-noti`).remove();
            }, 4000);
        }
    </script>
</div>