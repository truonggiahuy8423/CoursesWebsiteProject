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
</script>
<style>
    .list-of-lecturers-container {
        margin-top: 12px;
        margin-left: 50px;
        margin-bottom: 26px;
    }
    .list-of-lecturers-container a {
        display: inline-flex;
        color: #35abff!important;
        text-decoration: none;
        font-size: 14px;
    }
    .list-of-lecturers-container span {
        display: flex;
        color: #35abff!important;
        text-decoration: none;
        font-size: 14px;
    }
    .list-of-lecturers-container a+a {
        margin-top: 2px;
    }
    .class-infor-table td {
        font-size: 13px;
        padding: 5px;
        padding-left: 10px;
        width: 50%;
        height: 34px!important;
    }

    .class-infor-section__class-name {
        /* margin-bottom: 2px; */
    }

    .header-container {
        margin: 12px;
        margin-top: 62px;
        border-style: solid;
        border-width: 0.3px;
        border-color: #cecece;
        height: 130px;
        border-radius: 10px;
        display: flex;
        align-items: center;
    }

    .header-container h1 {
        margin-left: 50px;
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 370px 1fr;
        /* border-style: solid; */
        height: 100px;

    }

    .menu {
        border-style: solid;
        border-width: 0.3px;
        border-color: #cecece;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 12px;
        margin-right: 12px;
        padding-bottom: 40px;
        height: 270px;
    }

    .menu a {
        height: 38px;
        width: 100%;
        text-decoration: none;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2C2C2C;
        font-size: 14px;
        border-left: none;
        border-right: none;
        transition: 0.4s;
    }

    .menu a:hover {
        background-color: #e8e8e8;
    }

    /* .menu a+a {
        border-top-style: solid;
        border-top-width: 0.05px;
        border-top-color: #cecece;
    }
    .menu a:first-of-type {
        border-top-style: solid;
        border-top-width: 0.05px;
        border-top-color: #cecece;
    } 
    .menu a:last-of-type {
        border-bottom-style: solid;
        border-bottom-width: 0.05px;
        border-bottom-color: #cecece;
    } */
    .content-section {
        border-style: solid;
        border-width: 0.3px;
        border-color: #cecece;
        border-radius: 10px;
        margin-right: 12px;
    }

    .class-infor-section {
        width: 100%;
        display: grid;
        grid-template-columns: 70px 1fr;
        margin-top: 12px;
        margin-bottom: 20px;
    }

    .class-infor-section__back-btn {
        height: 28px !important;
        width: 44px !important;
        border-radius: 4px;
        margin-top: 10px;
    }
    .class-infor-table, .students-table, .schedule-table {
        width: 98%;
        margin: auto 1%;
        margin-top: 12px;
        margin-bottom: 26px;
    }
    .students-table {
        margin-top: 12px;
        margin-bottom: 26px;
    }
    .students-table td {
        font-size: 13px;
        padding: 5px;
        height: 34px;
        padding-left: 10px;
    }
    .students-table thead td {
        background-color: #2C2C2C;
        text-align: center;
        color: white;
    }
    .schedule-table {
        margin-top: 12px;
        margin-bottom: 26px;
    }
    .schedule-table td {
        font-size: 13px;
        padding: 5px;
        height: 34px;
        padding-left: 10px;
    }
    .schedule-table {
        margin-top: 12px;
        margin-bottom: 26px;
    }
    .schedule-table thead td {
        background-color: #2C2C2C;
        text-align: center;
        color: white;
    }
    .schedule-table tr:hover {
        background-color: #e8e8e8;
    }
    .student-row a {
        visibility: hidden;
    }
    .student-row:hover {
        background-color: #e8e8e8;
    }
    .student-row:hover a {
        visibility: visible;
    }
</style>
</html>