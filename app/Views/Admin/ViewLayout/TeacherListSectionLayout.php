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