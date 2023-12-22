<div class="content-section">
    <div class="inbox__body_course card p-2">
        <div class="row inboxContent_course">
            <?php
                // echo var_dump($user_nhan);
                // echo $tin_nhans[0]["user_gui"];
                // echo var_dump($tin_nhans);
                // echo var_dump($hoTen);
                $previous_user = -1;
                
                for($i = 0; $i < count($tin_nhans); $i++){

                    if($tin_nhans[$i]["user_gui"] != $user_main){
                        if($previous_user == $tin_nhans[$i]["user_gui"]){
                            echo "<div class='col-7 mb-1'>
                                    <div class='card'>
                                        <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                                    </div>
                                </div>
                                <div class='col-5 mb-1'></div>";
                        }
                        else{
                            $previous_user = $tin_nhans[$i]["user_gui"];
                            $currentName = "";
                            for($j = 0; $j < count($hoTen); $j++){
                                if($hoTen[$j]["id_user"] == $tin_nhans[$i]["user_gui"]){
                                    $currentName = $hoTen[$j]["ho_ten"];
                                }
                            }
                            echo "<div class='col-7 mt-1 mb-1'>
                                    <p class='p-1'>{$currentName}</p>
                                    <div class='card'>
                                        <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                                    </div>
                                </div>
                                <div class='col-5 mt-1 mb-1'></div>";
                        }
                    }
                    else{
                        if($previous_user == $tin_nhans[$i]["user_gui"]){
                            echo "<div class='col-7 offset-5 mb-1'>
                                    <div class='card'>
                                        <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                                    </div>
                                </div>";  
                        }
                        else{
                            $previous_user = $tin_nhans[$i]["user_gui"];
                            echo "<div class='col-7 offset-5 mt-1 mb-1'>
                                    <p class='p-1'>You</p>
                                        <div class='card'>
                                            <p class='p-1'>{$tin_nhans[$i]["noi_dung"]}</p>
                                        </div>
                                    </div>";  
                        }
                    }
                }
            ?>
        </div>
    </div>
    <div class="inbox__footer mt-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control inputChat_course" placeholder="Write a message" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary sendBtn_course" type="button" id="button-addon2" chatBoxID="<?php echo $kenh_nhan?>">Send</button>
        </div>
    </div>
    <script>
         $(document).on('click', '.sendBtn_course', function() {
        var noi_dung_tin_nhan = $('.inputChat_course').val();
        var chatboxID = $(this).attr('chatBoxID');
        $('.inputChat_course').val('');
        var obj = {
            noi_dung: noi_dung_tin_nhan,
            kenh_nhan: chatboxID,
        }
        var jsonData = JSON.stringify(obj);
        console.log(noi_dung_tin_nhan);
        console.log(jsonData);
        if(noi_dung_tin_nhan.length != 0){
            $.ajax({
                url: '<?php echo base_url(); ?>/TinNhanController/sendTinNhanChung',
                method: 'POST',
                contentType: 'application/json',
                data: jsonData,
                success: function(response) {
                    if(response.state) {
                        $('.inboxContent_course').append(`
                        <div class="col-7 offset-5 mb-1">
                            <div class="card">
                                <p class="p-1">${obj.noi_dung}</p>
                            </div>
                        </div>`);
                    }
                    $('.inbox__body_course').scrollTop($('.inboxContent_course').height());
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        }
    });
    setInterval(function(){
        var chatboxID = $('.sendBtn_course').attr('chatBoxID');
        $.ajax({
            url: '<?php echo base_url(); ?>/TinNhanController/getChatContentCourse',
            method: 'GET',
            data:{
                chatid_course: chatboxID
            },
            success: function(response) {
                $('.inbox__body_course').html('');
                $('.inbox__body_course').append(response);
                $('.inbox__body_course').scrollTop($('.inboxContent_course').height());
            },
            error: function(xhr, status, error) {
                console.error('Error:', status, error);
            }
        });
    },1000);
    </script>
</div>