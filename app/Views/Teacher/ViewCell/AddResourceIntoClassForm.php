<div class="form-container">
    <div class="insert-resource-form">
        <div class="insert-resource-form__title-section">
            <h5 class="insert-resource-form__title">Thêm tài nguyên mới</h5>
        </div>
        <!-- <h6>Chọn tài nguyên</h6> -->
        <div class="insert-resource-form__overflow-container">
            <div class="insert-resource-form__option-container">
                <div class="insert-resource-form__option insert-resource-form__option--folder">
                    <img src="<?php base_url() ?>/assets/img/folder_icon.png" alt="">
                    <span>Thêm mục mới</span>
                </div>
                <div class="insert-resource-form__option insert-resource-form__option--file">
                    <img src="<?php base_url() ?>/assets/img/any_file_icon.png" alt="">
                    <span>Đăng tải tệp tin</span>
                </div>
                <div class="insert-resource-form__option insert-resource-form__option--link">
                    <img src="<?php base_url() ?>/assets/img/link_icon.png" alt="">
                    <span>Đường dẫn</span>
                </div>
                <div class="insert-resource-form__option insert-resource-form__option--assignment">
                    <img src="<?php base_url() ?>/assets/img/assignment_icon.png" alt="">
                    <span>Assignment</span>
                </div>
                <div class="insert-resource-form__option insert-resource-form__option--noti">
                    <img src="<?php base_url() ?>/assets/img/noti_icon.png" alt="">
                    <span>Thông báo mới</span>
                </div>

            </div>
        </div>

        <div class="insert-resource-form__main_section">
            <span class="label-for-folder-title">Tiêu đề:</span> <input class="folder-title-input" type="text" placeholder="Nhập tên mục...">
        </div>
        <div class="insert-resource-form__main_section">
            <span class="label-for-folder-title">Tiêu đề:</span> <input class="folder-title-input" type="text" placeholder="Nhập tên mục..."> <br>
            <span class="label-for-folder-title">Liên kết:</span> <input class="folder-title-input" type="text" placeholder="Nhập đường dẫn...">
        </div>
        <div class="insert-resource-form__btn-container">
            <button class="insert-resource-form__cancel-btn">Hủy</button>
            <button class="insert-resource-form__save-btn">Lưu thông tin</button>
        </div>
    </div>
</div>
<script>
    function hlightOption(option) {
        $(`.insert-resource-form__option`).removeClass(`hlight`);
        $(`.insert-resource-form__option`).eq(option).addClass(`hlight`);
    }
    $(document).ready(function() {
        $(`.insert-resource-form__option`).click(function() {

        })
        $(`.insert-resource-form__option`).click(function() {
            
        })
        $(`.insert-resource-form__option`).click(function() {
            
        })
        $(`.insert-resource-form__option`).click(function() {
            
        })
        $(`.insert-resource-form__option`).click(function() {
            
        })
    })
</script>