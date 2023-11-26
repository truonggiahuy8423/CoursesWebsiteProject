<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/style.css">

    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/toast.css') ?>">
    <script src="<?php echo base_url('assets/script.js'); ?>"></script>
</head>

<body>
    <!-- div for containing popup form -->
    <script>

    </script>
    <style>

    </style>

    <!-- top nav -->

    <!-- left nav -->
    <div class="left-nav">
        <img class="left-nav__logo" src="<?php echo base_url(); ?>/assets/img/logo_course.png" alt="">
        <a class="item1" href="<?php echo base_url() . "/courses"; ?>">
            Danh sách lớp học</a>
        <a class="item2" href="<?php echo base_url() . "/lecturers"; ?>">
            Giảng viên</a>
        <a class="item3" href="<?php echo base_url() . "/students"; ?>">
            Học viên</a>
        <a class="item4" href="<?php echo base_url() . "/users"; ?>">
            User
        </a>
    </div>
    <?php echo $navbar ?>
    <!-- main section  -->
    <div class="main-content">
        <?php echo isset($mainsection) ? $mainsection : ''; ?>
        <!--  -->
    </div>

    <div id="toast"></div>

    <!-- footer -->
    <div>

    </div>

    <script>
        $(document).ready(function() {
            $('.left-nav .item<?php echo $left_nav_chosen_value; ?>').addClass('highlight');
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>