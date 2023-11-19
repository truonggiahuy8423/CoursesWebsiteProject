

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
                <div class="message-pop-up">

                </div>
            </div>
            <div class="top-nav__drop-down">
                <img id="avatar" src="<?php             
                        $base64Image = base64_encode($avatar_data);
                        echo "data:image/jpg;base64," . $base64Image;?>" alt="">
                <span id="name"><?php echo $username?></span>
                <img id="drop-down-icon" src="<?php base_url() ?>/assets/img/caret_down.png" alt="">
            </div>
            <div style="position: relative;">
                <div class="profile-pop-up">
                    <img id="ava" src="<?php             
                        $base64Image = base64_encode($avatar_data);
                        echo "data:image/png;base64," . $base64Image;?>
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
                    $('.message-pop-up').css('opacity', '1');
                    $('.top-nav__message').css('box-shadow', '0 0px 4px rgba(255, 255, 255, 1)');
                } else {
                    message_popup_state = !message_popup_state;
                    $('.message-pop-up').css('visibility', 'hidden');
                    $('.message-pop-up').css('opacity', '0');
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
        })
    </script>
</body>