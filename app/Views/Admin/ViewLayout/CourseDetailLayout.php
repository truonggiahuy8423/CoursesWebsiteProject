<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style.css">

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/toast.css') ?>">
    <script src="<?php echo base_url('assets/script.js'); ?>"></script>
</head>

<body>
    <div id="toast"></div>

    <?php echo $navbar ?>
    <div class="header-container">
        <h1><?php echo $class_name ?></h1>
    </div>
    <div class="grid-layout">
        <?php echo $leftmenu;?>
        <?php echo $contentsection?>
    </div>
</body>
<script>
    // Information Section Script
    // $(document).on('click', '.class-div', function() {
    //         });
    /////////////////////////////
    //Chat Section Script
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
        // setInterval(function(){
        //     var chatboxID = $('.sendBtn_course').attr('chatBoxID');
        //     $.ajax({
        //         url: '<?php echo base_url(); ?>/TinNhanController/getChatContentCourse',
        //         method: 'GET',
        //         data:{
        //             chatid_course: chatboxID
        //         },
        //         success: function(response) {
        //             $('.inbox__body_course').html('');
        //             $('.inbox__body_course').append(response);
        //             $('.inbox__body_course').scrollTop($('.inboxContent_course').height());
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', status, error);
        //         }
        //     });
        // },1000);
    });
</script>
<style>
   
</style>
</html>