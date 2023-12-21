<div class="inbox__header">
    <div class='row'>
        <div class='col-4 d-flex justify-content-center align-items-center ps-3 pe-0'>
            <img class='logo img-responsive img-thumbnail rounded-circle'  src='<?php echo base_url()."assets/img/avatar_blank.jpg"?>' alt='avatar'>
        </div>
        <div class='col-6 d-flex justify-content-center align-items-start flex-column p-2'>
            <h5 class='card-title'><?php echo $hoTen[0]["ho_ten"] ?></h5>
        </div>
        <div class="col-2 d-flex justify-content-start align-items-start flex-column p-0">
            <button class="btn offInboxBtn"><i class="fa-solid fa-xmark" style="color: #000000;"></i></button>
        </div>
    </div>
</div>
<hr class="hr1">
<div class="inbox__body card p-2">
    <div class="row inboxContent">
        <?php
            // echo var_dump($user_nhan);
            // echo $tin_nhans[0]["user_gui"];
            // echo var_dump($tin_nhans);
            for($i = 0; $i < count($tin_nhans); $i++){
                if($tin_nhans[$i]["user_gui"] == $user_nhan){
                    echo "<div class='col-7 mb-1'>
                        <div class='card'>
                            <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                        </div>
                    </div>
                    <div class='col-5 mb-1'></div>";
                }
                else{
                
                    echo "<div class='col-7 offset-5 mb-1'>
                            <div class='card'>
                                <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                            </div>
                        </div>";  
                }
            }
            
        ?>
    </div>
</div>

