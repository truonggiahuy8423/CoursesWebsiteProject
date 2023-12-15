<div class="inbox__header">
    <div class='row'>
        <div class='col-4 d-flex justify-content-center align-items-center ps-3 pe-0'>
            <img class='logo img-responsive img-thumbnail rounded-circle'  src='<?php echo base_url()."assets/img/avatar_blank.jpg"?>' alt='avatar'>
        </div>
        <div class='col-8 d-flex justify-content-center align-items-start flex-column p-2'>
            <h5 class='card-title'>Nguyễn Duy Khánh</h5>
        </div>
    </div>
</div>
<hr>
<div class="inbox__body card p-2">
    <div class="row inboxContent">
        <?php
            // echo var_dump($user_nhan);
            // echo $tin_nhans[0]["user_gui"];
            // echo var_dump($tin_nhans);
            for($i = 0; $i < count($tin_nhans); $i++){
                if($tin_nhans[$i]["user_gui"] == $user_nhan){
                    echo "<div class='col-6'>
                        <div class='card'>
                            <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                        </div>
                    </div>
                    <div class='col-6'></div>";
                }
                else{
                
                    echo "<div class='col-6 offset-6'>
                            <div class='card'>
                                <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                            </div>
                        </div>";  
                }
            }
            
        ?>
        <div class="col-6">
            <div class="card">
                <p class="p-1">Hello</p>
            </div>
        </div>
        <div class="col-6"></div>
        <div class="col-6 offset-6">
            <div class="card">
                <p class="p-1">Hello</p>
            </div>
        </div>
    </div>
</div>
<div class="inbox__footer mt-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control inputMess" placeholder="Write a message" aria-describedby="button-addon2">
        <button class="btn btn-outline-secondary sendBtn" type="button" id="button-addon2">Send</button>
    </div>
</div>
