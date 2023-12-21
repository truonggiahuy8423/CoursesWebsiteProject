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
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Tin nhắn</h4>
                        <button class="btn addChatBoxBtn"><i class="fa-solid fa-plus" style="color: #000000;"></i></button>
                    </div>
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
                    <!-- inbox_header -->
                    
                    <!-- inbox_body -->

                    <div class="inbox__footer mt-3">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control inputChat" placeholder="Write a message" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary sendBtn" type="button" id="button-addon2">Send</button>
                        </div>
                    </div>
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
                        $('.top-nav__message').css('box-shadow', 'none');
                        $('.inbox').css('opacity', '0');
                        $('.inbox').css('right', '-300px');
                        $('.inbox__header').remove();
                        $('.hr1').remove();
                        $('.inbox__body').remove();
                        isInboxOpen = false;
                        isScrollTop = false;
                        currentScrollTop;
                        previousInputChat = '';
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
                    $('.inbox__header').remove();
                    $('.hr1').remove();
                    $('.inbox__body').remove();
                    $('.top-nav__message').css('box-shadow', 'none');
                    isInboxOpen = false;
                }
            })
            var notification_popup_state = false;
            $('.top-nav__notification').click(function(e) {
                if (!notification_popup_state) {
                    if (message_popup_state) {
                        message_popup_state = !message_popup_state;
                        $('.message-pop-up').css('visibility', 'hidden');
                        $('.message-pop-up').css('opacity', '0');
                        $('.top-nav__message').css('box-shadow', 'none');
                        $('.inbox').css('opacity', '0');
                        $('.inbox').css('right', '-300px');
                        $('.inbox__header').remove();
                        $('.hr1').remove();
                        $('.inbox__body').remove();
                        isInboxOpen = false;
                        isScrollTop = false;
                        currentScrollTop;
                        previousInputChat = '';
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
            
            $('.addChatBoxBtn').click(function(){
                $.ajax({
                    url: '<?php echo base_url(); ?>/TinNhanController/getInsertChatBoxForm',
                    method: 'GET',
                    success: function(response) {
                        $('body').append(response);

                        $(`.insert-chatbox-form__cancel-btn`).click(function() {
                            $('.form-container').remove();
                        });

                        $('.sendBtn_newChat').click(function() {
                                var noi_dung_tin_nhan = $('.inputNewChat').val();
                                var chatBoxID = $('.addNewChatBox:checked').val();
                                console.log(chatBoxID);
                                $('.inputNewChat').val('');
                                var obj = {
                                    noi_dung: noi_dung_tin_nhan,
                                    user_nhan: chatBoxID,
                                }
                                var jsonData = JSON.stringify(obj);

                                $.ajax({
                                    url: '<?php echo base_url(); ?>/TinNhanController/sendTinNhanRieng',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: jsonData,
                                    success: function(response) {
                                        
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error:', status, error);
                                    }
                                });
                            });
                    }
                })
            })

        })
        var isInboxOpen = false;
        var isScrollTop = false;
        var currentScrollTop;
        var previousInputChat = '';
        $(document).on('click', '.offInboxBtn', function(){
            $('.inbox').css('opacity', '0');
            $('.inbox').css('right', '-300px');
            $('.inbox__header').remove();
            $('.hr1').remove();
            $('.inbox__body').remove();
            isInboxOpen = false;
            isScrollTop = false;
            currentScrollTop;
            previousInputChat = '';
        })
        $(document).on('click', '.chatBox', function(){
                $('.inbox').css('visibility', 'visible');
                $('.inbox').css('right', '0');
                $('.inbox').css('opacity', '1');
                var chatboxID = $(this).attr('chatBoxID');
                console.log(chatboxID);
                if(!isInboxOpen){
                    isInboxOpen = true;
                    (function update() {
                        $.ajax({
                            url: '<?php echo base_url(); ?>/TinNhanController/getInBox',
                            method: 'GET',
                            data: {
                                chatboxID: chatboxID
                            },
                            success: function(response) {
                                $('.inbox__header').remove();
                                $('.hr1').remove();
                                $('.inbox__body').remove();

                                $('.inbox__footer').before(response);
                                console.log(response);
                                if(!isScrollTop){
                                    currentScrollTop = $('.inboxContent').height();
                                }
                                $('.inbox__body').scroll(function(){
                                    isScrollTop = true;
                                    currentScrollTop = $(this).scrollTop();
                                })
                                $('.inbox__body').scrollTop(currentScrollTop);


                                console.log("inbox refresh");
                                $('.sendBtn').click(function() {
                                    var noi_dung_tin_nhan = $('.inputChat').val();
                                    $('.inputChat').val('');
                                    var obj = {
                                        noi_dung: noi_dung_tin_nhan,
                                        user_nhan: chatboxID,
                                    }
                                    var jsonData = JSON.stringify(obj);
                                    // console.log(noi_dung_tin_nhan);
                                    // console.log(jsonData);
                                    if(noi_dung_tin_nhan.length != 0){
                                        $.ajax({
                                            url: '<?php echo base_url(); ?>/TinNhanController/sendTinNhanRieng',
                                            method: 'POST',
                                            contentType: 'application/json',
                                            data: jsonData,
                                            success: function(response) {
                                                if(response.state) {
                                                    $('.inboxContent').append(`
                                                    <div class="col-7 offset-5 mb-1">
                                                        <div class="card">
                                                            <p class="p-1">${obj.noi_dung}</p>
                                                        </div>
                                                    </div>`);
                                                }
                                                $('.inbox__body').scrollTop($('.inboxContent').height());
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error:', status, error);
                                            }
                                        });
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr);
                                console.error('Lỗi yêu cầu:', status, error);
                            }
                        }).then(function() {           // on completion, restart
                            if(isInboxOpen) setTimeout(update, 1000);  // function refers to itself
                        });
                    })();  
                }else{
                    isInboxOpen = false;
                    isScrollTop = false;
                }
                
            });
    </script>
</body>