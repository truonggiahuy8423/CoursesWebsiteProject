

<div class="top-nav">
        <a class="top-nav__logo" href="<?php base_url() ?>/courses">
            <img src="<?php base_url() ?>/assets/img/small_logo_course.png" alt="">
        </a>
        <div style="margin-right: 10px; display: flex; justify-content: center; position: relative;">
            <button class="top-nav__notification">
                <img src="<?php base_url() ?>/assets/img/bell_icon.png" alt="">
            </button>
            <div style="position: relative;">
                <div class="notification-pop-up">

                </div>
            </div>
            <button class="top-nav__message">
                <img src="<?php base_url() ?>/assets/img/message_icon.png" alt="">
            </button>
            <div style="position: relative;">
                <div class="message-pop-up card p-2">
                    <h4>Tin nhắn</h4>
                    <hr>
                    <div class="row">
                        <?php
                            // echo var_dump($chatBox);
                            for($i = 0; $i < count($chatBox); $i++){
                                $avaImg = checkImg($chatBox[$i]["lastestTime"][0]["anh"]);
                                echo "
                                <div class='chatBox col-12 mb-2' chatBoxID='{$chatBox[$i]["user_nhan"]}'>
                                    <div class='card'>
                                        <div class='card-body p-3'>
                                            <div class='row'>
                                                <div class='col-4 d-flex justify-content-center align-items-center ps-3 pe-0'>
                                                    <img class='logo img-responsive img-thumbnail rounded-circle'  src='{$avaImg}' alt='avatar'>
                                                </div>
                                                <div class='col-8 d-flex justify-content-center align-items-start flex-column p-2'>
                                                    <h5 class='card-title' style='font-size: 15px;'>{$chatBox[$i]["hoTen"][0]["ho_ten"]}</h5>
                                                    <p class='card-subtitle text-muted'>{$chatBox[$i]["lastestTime"][0]["thoi_gian"]}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                            }

                            function checkImg($avaImg){
                                if ($avaImg != null) {
                                    $base64Image = base64_encode($avaImg);
                                    return "data:image/png;base64," . $base64Image;
                                }  else {
                                    return base_url()."assets/img/avatar_blank.jpg";
                                }
                            }
                        ?>
                        <div class="col-12 mb-2">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-4 d-flex justify-content-center align-items-center ps-3 pe-0">
                                            <img class="logo img-responsive img-thumbnail rounded-circle"  src="<?php base_url() ?>/assets/img/avatar_blank.jpg" alt="avatar">
                                        </div>
                                        <div class="col-8 d-flex justify-content-center align-items-start flex-column p-2">
                                            <h5 class="card-title" style="font-size: 15px;">Nguyễn Duy Khánh</h5>
                                            <p class="card-subtitle text-muted">3 mins ago</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="position: relative;">
                <div class="inbox card p-2" style="background-color: #fff">
                    
                </div>
            </div>
            <div class="top-nav__drop-down">
                <img id="avatar" src="<?php 
                    if ($avatar_data != null) {
                        $base64Image = base64_encode($avatar_data);
                        echo "data:image/png;base64," . $base64Image;
                    }  else {
                        echo base_url()."assets/img/avatar_blank.jpg";
                    }
                    ?>" alt="">
                <span id="name"><?php echo $username?></span>
                <img id="drop-down-icon" src="<?php base_url() ?>/assets/img/caret_down.png" alt="">
            </div>
            <div style="position: relative;">
                <div class="profile-pop-up">
                    <img id="ava" src="<?php 
                    if ($avatar_data != null) {
                        $base64Image = base64_encode($avatar_data);
                        echo "data:image/png;base64," . $base64Image;
                    }  else {
                        echo base_url()."assets/img/avatar_blank.jpg";
                    }
                    ?>
                        " alt="">
                    <p id="name"><?php echo $username?></p>
                    <p id="role"><?php echo $role?></p>
                    <a href="">
                        <img src="" alt="">
                        Hồ sơ của tôi
                    </a>
                    <a href="">
                        <img src="" alt="">
                        Quản lý tài khoản
                    </a>
                    <a href="<?php base_url() ?>/LoginController/logout">
                        <img src="" alt="">
                        Đăng xuất 
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(e) {
            var profile_popup_state = false;
            $('.top-nav__drop-down').click(function(e) {
                if (!profile_popup_state) {
                    if (message_popup_state) {
                        message_popup_state = !message_popup_state;
                        $('.message-pop-up').css('visibility', 'hidden');
                        $('.message-pop-up').css('opacity', '0');
                        $('.inbox').css('visibility', 'hidden');
                        $('.inbox').css('opacity', '0');
                        $('.top-nav__message').css('box-shadow', 'none');
                    } else if (notification_popup_state) {
                        notification_popup_state = !notification_popup_state;
                        $('.notification-pop-up').css('visibility', 'hidden');
                        $('.notification-pop-up').css('opacity', '0');
                        $('.top-nav__notification').css('box-shadow', 'none');
                    }
                    profile_popup_state = !profile_popup_state;
                    $('.top-nav__drop-down #drop-down-icon').css('transform', 'rotate(180deg)');
                    $('.profile-pop-up').css('visibility', 'visible');
                    $('.profile-pop-up').css('opacity', '1');
                    $('.top-nav__drop-down').css('box-shadow', '0 0px 4px rgba(255, 255, 255, 1)');
                } else {
                    profile_popup_state = !profile_popup_state;
                    $('.top-nav__drop-down #drop-down-icon').css('transform', 'rotate(0deg)');
                    $('.profile-pop-up').css('visibility', 'hidden');
                    $('.profile-pop-up').css('opacity', '0');
                    $('.top-nav__drop-down').css('box-shadow', 'none');
                }
            })
            var message_popup_state = false;
            $('.top-nav__message').click(function(e) {
                if (!message_popup_state) {
                    if (profile_popup_state) {
                        profile_popup_state = !profile_popup_state;
                        $('.top-nav__drop-down #drop-down-icon').css('transform', 'rotate(0deg)');
                        $('.profile-pop-up').css('visibility', 'hidden');
                        $('.profile-pop-up').css('opacity', '0');
                        $('.top-nav__drop-down').css('box-shadow', 'none');
                    } else if (notification_popup_state) {
                        notification_popup_state = !notification_popup_state;
                        $('.notification-pop-up').css('visibility', 'hidden');
                        $('.notification-pop-up').css('opacity', '0');
                        $('.top-nav__notification').css('box-shadow', 'none');
                    }
                    message_popup_state = !message_popup_state;
                    $('.message-pop-up').css('visibility', 'visible');
                    $('.message-pop-up').css('right', '0');
                    $('.message-pop-up').css('opacity', '1');
                    $('.top-nav__message').css('box-shadow', '0 0px 4px rgba(255, 255, 255, 1)');
                } else {
                    message_popup_state = !message_popup_state;
                    $('.message-pop-up').css('opacity', '0');
                    $('.message-pop-up').css('right', '-300px');
                    $('.inbox').css('opacity', '0');
                    $('.inbox').css('right', '-300px');
                    $('.top-nav__message').css('box-shadow', 'none');
                }
            })
            var notification_popup_state = false;
            $('.top-nav__notification').click(function(e) {
                if (!notification_popup_state) {
                    if (message_popup_state) {
                        message_popup_state = !message_popup_state;
                        $('.message-pop-up').css('visibility', 'hidden');
                        $('.message-pop-up').css('opacity', '0');
                        $('.inbox').css('visibility', 'hidden');
                        $('.inbox').css('opacity', '0');
                        $('.top-nav__message').css('box-shadow', 'none');
                    } else if (profile_popup_state) {
                        profile_popup_state = !profile_popup_state;
                        $('.top-nav__drop-down #drop-down-icon').css('transform', 'rotate(0deg)');
                        $('.profile-pop-up').css('visibility', 'hidden');
                        $('.profile-pop-up').css('opacity', '0');
                        $('.top-nav__drop-down').css('box-shadow', 'none');
                    }
                    notification_popup_state = !notification_popup_state;
                    $('.notification-pop-up').css('visibility', 'visible');
                    $('.notification-pop-up').css('opacity', '1');
                    $('.top-nav__notification').css('box-shadow', '0 0px 4px rgba(255, 255, 255, 1)');
                } else {
                    notification_popup_state = !notification_popup_state;
                    $('.notification-pop-up').css('visibility', 'hidden');
                    $('.notification-pop-up').css('opacity', '0');
                    $('.top-nav__notification').css('box-shadow', 'none');
                }
            })
            var state2 = false;
            $('.dis').click(function(e) {
                if (state) {
                    state = !state;
                    $('.okbut').css('visibility', 'hidden')
                } else {
                    state = !state;
                    $('.okbut').css('visibility', 'visible')

                }
            });

            $('.chatBox').click(function(){
                $('.inbox').css('visibility', 'visible');
                $('.inbox').css('right', '0');
                $('.inbox').css('opacity', '1');
                var chatboxID = $(this).attr('chatBoxID');
                console.log(chatboxID);
                $.ajax({
                    url: '<?php echo base_url(); ?>/TinNhanController/getInBox',
                    method: 'GET',
                    data: {
                        chatboxID: chatboxID
                    },
                    success: function(response) {
                        $('.inbox').html('');
                        $('.inbox').append(response);
                        console.log(response)
                        $('.sendBtn').click(function() {
                            var noi_dung_tin_nhan = $('.inputMess').val();

                            var obj = {
                                noi_dung: noi_dung_tin_nhan,
                            }

                            var jsonData = JSON.stringify(obj);

                            $.ajax({
                                url: '<?php echo base_url(); ?>/TinNhanController/sendTinNhanRieng',
                                method: 'POST',
                                contentType: 'application/json',
                                data: jsonData,
                                success: function(response) {
                                    if (response.state) {
                                        $('inboxContent').append(`
                                            <div class="col-6 offset-6">
                                                <div class="card">
                                                    <p class="p-1"></p>
                                                </div>
                                            </div>`);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', status, error);
                                }
                            });
                        });
                    },
                    error: function(xhr, status, error) {

                        console.error(xhr);
                        console.error('Lỗi yêu cầu:', status, error);
                    },
                    complete: function() {

                    }
                });
            })

        })
    </script>
</body>