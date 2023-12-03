










<div class="menu">
            <div class="class-infor-section">
                <div style="display: flex; justify-content: center;">
                    <button class="class-infor-section__back-btn highlight-button--reverse" href="">
                        <i class="fa-solid fa-arrow-left highlight-icon--reverse"></i>
                    </button>
                </div>
                <div>
                    <h5 class="class-infor-section__class-name"><?php echo $class_name?></h5>
                    <p style="font-size: 13px; line-height: 16px;">Thành viên: <span class="class-infor-section__members-quantity"><?php echo $student_quantity?></span></p>
                    <p style="font-size: 13px; line-height: 16px;">Trạng thái: <?php echo $state ?></p>
                </div>
            </div>
            <a href="<?php echo base_url().'/courses/information?courseid="'?><?php echo $id_lop_hoc;?>">Thông tin lớp học</a>
            <a href="<?php echo base_url().'/courses/attendance?courseid="'?><?php echo $id_lop_hoc;?>">Điểm danh</a>
            <a href="<?php echo base_url().'/courses/resourse?courseid="'?><?php echo $id_lop_hoc;?>">Tài nguyên</a>
            <a href="<?php echo base_url().'/courses/chat?courseid="'?><?php echo $id_lop_hoc;?>">Kênh chat</a>
</div>

<script>
    $(document).on('click', '.class-infor-section__back-btn', function() {
                window.location.href = `<?php echo base_url(); ?>/courses`;
            });
</script>