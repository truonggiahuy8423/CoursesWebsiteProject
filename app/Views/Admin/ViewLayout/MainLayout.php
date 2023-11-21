
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style.css">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>

</head>

<body>
    <!-- top nav -->
   
    <!-- left nav -->
    <div class="left-nav">
        <img class="left-nav__logo" src="<?php echo base_url(); ?>/assets/img/logo_course.png" alt="">
        <a class="item1" href="<?php echo base_url()."/courses";?>">
            Danh sách lớp học</a>
        <a class="item2"  href="<?php echo base_url()."/lecturers";?>" >
            Giảng viên</a>
        <a class="item3" href="<?php echo base_url()."/students";?>" >
            Học viên</a>
        <a class="item4" href="<?php echo base_url()."/users";?>">
            User
        </a>
    </div>
    <?php echo $navbar?>
    <!-- main section  -->
    <div class="main-content">
        
    </div>
    <!-- footer -->
    <div>

    </div>
    
    <script>
        $(document).ready(function(e) {
                $('.left-nav .item<?php echo $left_nav_chosen_value;?>').addClass('highlight');
        })
    </script>
</html>