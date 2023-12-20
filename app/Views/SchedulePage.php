<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/schedule.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" />

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

    <div id="toast"></div>

    <?php echo $navbar ?>
    <div class="header-container">
        <h1><?php echo $class_name ?></h1>
    </div>
    <div class="grid-layout">
        <?php echo $leftmenu; ?>
        <div class="content-section">
            <h3 style="margin-right: 14px; margin-top: 17px; margin-left: 27px; display: flex; align-items: center; justify-content: space-between;">Lịch học</h3>
            <div class='container-pr1' >
                <?php foreach ($tatCaBuoiHoc as $buoiHoc) : ?>
                    <div class='rectangle-parent1' data-buoihoc='<?= $buoiHoc->id_buoi_hoc ?>'>
                        <div class='th-6-201024-container'>
                            <p class='thnh-ph-th'>
                                <b class='datetime'>
                                    Thứ <?= $buoiHoc->thu ?> <?= date('d/m/y', strtotime($buoiHoc->ngay)) ?>
                                </b>
                            </p>
                            <p class='thnh-ph'><?= $buoiHoc->id_phong ?></p>
                            <p class='thnh'><?= substr($buoiHoc->thoi_gian_bat_dau, 0, 5) ?>-<?= substr($buoiHoc->thoi_gian_ket_thuc, 0, 5) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <h3 style="margin-right: 14px; margin-top: 17px; margin-left: 27px; display: flex; align-items: center; justify-content: space-between;">Thông tin điểm danh</h3>
            <h3 id="container" style="margin-right: 14px; margin-top: 17px; margin-left: 27px; display: flex; align-items: center; justify-content: space-between;"></h3>
            <div class="group-parent">

                <div class="button-container">
                    <button class="save-button" style="display: none;" id="save_btn">
                        <div class="save-button-background"></div>
                        <div class="save">Lưu thông tin</div>
                    </button>
                    <button class="cancel-button" style="display: none;" id="cancel_btn">
                        <div class="cancel-button-background"></div>
                        <div class="cancel">Hủy bỏ</div>
                    </button>
                </div>

                <button class="show-buttons" id="show_btn">Chỉnh sửa</button>
                <!-- b -->

                <div class="container-tg">
                    <div class="MaHV">
                        <div class="Hvien"> Mã học viên</div>
                    </div>
                    <div class="HoVTen">
                        <div class="Hotena"> Họ tên</div>
                    </div>
                    <div class="Aten">
                        <div class="diemanh">Điểm danh</div>
                    </div>
                    <div class="ghichu">
                        <div class="note">Ghi chú</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js bắt đầu từ đây -->
    <script>
        let globalCheckbox = [];
        globalCheckbox.id = 'myGlobalCheckboxArray'
        let checkbox;
        let inputText;
        // ajax
        $(document).ready(function() {





            $('.rectangle-parent1').click(function(e) {
                    globalCheckbox.splice(0, globalCheckbox.length);

                    // Lấy giá trị của data-buoihoc từ thuộc tính data của div
                    let idBuoiHoc = $(this).data('buoihoc');
                    console.log(idBuoiHoc);
                    // Tạo đối tượng dữ liệu để gửi đi
                    let dataToSend = {
                        idBuoiHoc: idBuoiHoc
                    };
                    console.log(dataToSend);



                    // Gửi request AJAX
                    $.ajax({
                        url: "<?php echo base_url() ?>Admin/CoursesController/getAttByIDBuoi",
                        method: "POST",
                        contentType: 'application/json',
                        dataType: "json",
                        data: JSON.stringify(dataToSend), // Chuyển đổi dữ liệu sang chuỗi JSON
                        success: function(response) {
                            // Xử lý phản hồi từ server
                            let ArrListAtten = response;
                            let newElement = `
    
                                    <div class="MaHV">
                                        <div class="Hvien"> Mã học viên</div>
                                    </div>
                                    <div class="HoVTen">
                                        <div class="Hotena"> Họ tên</div>
                                    </div>
                                    <div class="Aten">
                                        <div class="diemanh">Điểm danh</div> 
                                    </div>
                                    <div class="ghichu">
                                        <div class="note">Ghi chú</div>
                                    </div>
                                `;
                            //     console.log(ArrListAtten);
                            //     <div class="thng-tin-im">Thông tin điểm danh</div>

                            // <div class="bui-hc-th">
                            //     Buổi học: Thứ 6 03/11/2023 P.101 07:00 - 11:00 - 2809232092
                            // </div>
                            const firstElement = ArrListAtten[0]; // Lấy phần tử đầu tiên của mảng

                            const ngay = new Date(firstElement.ngay);
                            const dayOfWeek = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][ngay.getDay()];
                            const ngayFormatted = `${ngay.getDate()}/${ngay.getMonth() + 1}/${ngay.getFullYear()}`;
                            const gioBD = firstElement.thoi_gian_bat_dau;
                            const gioKT = firstElement.thoi_gian_ket_thuc;
                            const phong = `P.${firstElement.id_phong}`;
                            const id = `-${firstElement.id_buoi_hoc}`;

                            const htmlString = `<div class="bui-hc-th">
                                        Buổi học: ${dayOfWeek} ${ngayFormatted} ${phong} ${gioBD} - ${gioKT} ${id}
                                    </div>`;
                            document.getElementById('container').innerHTML = htmlString;

                            console.log(htmlString);

                            // Xóa nội dung cũ của container-tg trước khi thêm danh sách mới

                            // Kiểm tra xem có phần tử con nào có class là container-tg không
                            var lammoi = $('.group-child4 .container-tg');
                            if (lammoi.length > 0) {
                                lammoi.remove(); // Loại bỏ thẻ div ra khỏi DOM và xóa nó khỏi bộ nhớ
                            }
                            // Nếu tồn tại phần tử container-tg, loại bỏ nó

                            $('.container-tg').empty();
                            console.log(ArrListAtten)
                            // Tạo container-tg mới và thêm danh sách học viên vào
                            let containerTG = $('<div>').addClass('container-tg');
                            ArrListAtten.forEach(function(hocvien) {
                                let idBuoiHoc = hocvien.id_buoi_hoc;
                                let idSinhVien = hocvien.id_hoc_vien;
                                let hoVaTen = hocvien.ho_ten;
                                let ghiChu = hocvien.ghi_chu;
                                let coMat = hocvien.co_mat === '1'; // Kiểm tra nếu có mặt
                                let strId = idSinhVien.toString();
                                let paddedId = '0000' + strId;
                                let displayedId = paddedId.slice(-5);
                                let groupChild5 = $('<div>').addClass('group-child5');
                                let groupChild6 = $('<div>').addClass('group-child6');
                                let groupChild7 = $('<div>').addClass('group-child7');
                                let groupChild8 = $('<div>').addClass('group-child8');

                                let divIdSinhVien = $('<div>').text(displayedId).addClass('div');
                                let divHoVaTen = $('<div>').text(hoVaTen).addClass('trng-gia-huy');

                                let checkbox = $('<input>').attr({
                                    type: 'checkbox',
                                    id: idSinhVien,
                                    idbuoihoc: idBuoiHoc,
                                    checked: coMat // Thiết lập trạng thái checked dựa trên co_mat
                                });

                                let inputText = $('<input>').attr({
                                    type: 'text',
                                    placeholder: '',
                                    id: idSinhVien,
                                    idbuoihoc: idBuoiHoc,
                                }).val(ghiChu); // Hiển thị ghi chú trong input text

                                groupChild8.append(inputText);
                                groupChild7.append(checkbox);
                                groupChild5.append(divIdSinhVien);
                                groupChild6.append(divHoVaTen);

                                globalCheckbox.push(checkbox);
                                globalCheckbox.push(inputText);
                                containerTG.append(groupChild5, groupChild6, groupChild7, groupChild8);

                            });
                            var container = $('.group-child4');
                            container.append(containerTG);
                            $('.container-tg').prepend(newElement);

                            // globalCheckbox.forEach(function(checkbox) {
                            // checkbox.prop('disabled', true);
                            // inputText.prop('disabled',true)
                            // });

                            globalCheckbox.forEach(function(element) {
                                element.prop('disabled', true);
                            });

                            console.log(globalCheckbox);

                            // Disable checkbox khi lấy được

                            $('.container-tg').append(containerTG);
                            console.log(containerTG);

                            // Thêm sự kiện change để theo dõi sự thay đổi trạng thái của checkbox

                        },
                        error: function(xhr, status, error) {
                            // Xử lý lỗi nếu có
                            console.error(status + ': ' + error);
                        }

                    });
                    setTimeout(() => {
                        console.log('First event completed');
                    }, 2000);
                    // $('.showButtons').trigger('click');

                    $('show-buttons').trigger('click');
                }

            );
        });




        //tạo checkbox
        document.addEventListener("DOMContentLoaded", function() {
            const showButtons = document.getElementById('show_btn');
            const buttonContainer = document.querySelector('.button-container');
            const cancelButton = document.getElementById('cancel_btn');
            const saveButton = document.getElementById('save_btn');

            function enableCheckboxes() {
                globalCheckbox.forEach(function(element) {
                    element.prop('disabled', false);
                });
            }

            showButtons.onclick = function() {
                saveButton.style.display = 'block';
                cancelButton.style.display = 'block';
                showButtons.style.display = 'none';
                console.log('đã click');
                console.log(globalCheckbox);
                enableCheckboxes();

            };


            function ConfirmCheckbox() {
                globalCheckbox.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        if (this.checked) {
                            // Gán thuộc tính khi checkbox được chọn
                            this.setAttribute('data-state', '1');
                            console.log('Checkbox được chọn');
                        } else {
                            // Gán thuộc tính khi checkbox không được chọn
                            this.removeAttribute('data-state');
                            console.log('Checkbox không được chọn');
                        }
                    });
                });

            }



            cancelButton.addEventListener('click', function() {
                saveButton.style.display = 'none';
                cancelButton.style.display = 'none';
                showButtons.style.display = 'block';
                globalCheckbox.forEach(function(element) {
                    element.prop('checked', element.prop('defaultChecked'));
                    element.prop('value', element.prop('defaultValue'));
                    element.prop('disabled', true);
                });
            });



            saveButton.addEventListener('click', function() {
                // let checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]');

                // Thay đổi animation đã tick và thêm thuộc tính checked cho các checkbox đã chọn
                saveButton.style.display = 'none';
                cancelButton.style.display = 'none';
                showButtons.style.display = 'block';
                globalCheckbox.forEach(function(element) {
                    element.prop('disabled', true);
                });


                let attributesArray = [];

                // Duyệt qua từng cặp đối tượng checkbox và inputText trong globalCheckbox
                for (let i = 0; i < globalCheckbox.length; i += 2) {
                    let checkbox = globalCheckbox[i];
                    let inputText = globalCheckbox[i + 1];

                    let idValue = checkbox.prop('id');
                    let typeValue = checkbox.attr('idbuoihoc');
                    let comat;
                    let ghichu = inputText.prop('value');

                    if (checkbox.prop('checked')) {
                        comat = '1';
                    } else {
                        comat = '0';
                    }

                    // Tạo đối tượng chứa các thuộc tính của checkbox và inputText tương ứng và push vào mảng attributesArray
                    let checkboxAttributes = {
                        id: idValue,
                        type: typeValue,
                        comat: comat,
                        ghichu: ghichu
                    };

                    attributesArray.push(checkboxAttributes);
                }

                console.log(attributesArray);
                if (globalCheckbox.length > 0) { // Kiểm tra xem mảng có phần tử không
                    let firstCheckboxId = globalCheckbox[0].attr('idbuoihoc'); // Lấy id của phần tử đầu tiên trong mảng

                    // Gán id của phần tử đầu tiên cho mảng globalCheckbox
                    globalCheckbox.id = firstCheckboxId;

                    console.log(globalCheckbox); // In ra mảng globalCheckbox sau khi gán id
                } else {
                    console.log("Mảng globalCheckbox không có phần tử."); // Nếu mảng không có phần tử
                }


                let datachecked = attributesArray;
                console.log(datachecked);
                let jsonts = JSON.stringify(datachecked)
                console.log(jsonts);


                // xử lý chuỗi
                $.ajax({
                    url: "<?php echo base_url() ?>Admin/CoursesController/CheckingAtt",
                    method: "POST",
                    contentType: 'application/json',
                    dataType: "json",
                    data: jsonts, // Chuyển đổi dữ liệu sang chuỗi JSON
                    success: function(response) {
                        console.log(response);
                        globalCheckbox.length = 0;
                        // Xử lý phản hồi thành công từ máy chủ
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });


                // gửi về response
            })


        })










        // let  globalCheckbox = globalCheckbox; // Thay thế phần này bằng mảng chứa các checkbox cần kiểm tra
        console.log(globalCheckbox);

        globalCheckbox.forEach(function(checkbox) {
            console.log(checkbox); // In ra từng phần tử checkbox
        });



        window.addEventListener("load", function() {
            let currentURL = window.location.href;

            // Tạo một đối tượng URL để trích xuất các tham số
            let urlObject = new URL(currentURL);

            // Lấy giá trị của tham số 'id' từ URL
            let id = urlObject.searchParams.get('courseid');

            console.log(id); // In ra ID từ URL 
            $.ajax({
                url: "<?php echo base_url() ?>Admin/CoursesController/Getbuoihocdautien",
                method: "POST",
                contentType: 'application/json',
                dataType: "json",
                data: JSON.stringify(id), // Chuyển đổi dữ liệu sang chuỗi JSON
                success: function(response) {
                    // Xử lý phản hồi từ server
                    let ArrListAtten = response;
                    let newElement = `
    
                            <div class="MaHV">
                                <div class="Hvien"> Mã học viên</div>
                            </div>
                            <div class="HoVTen">
                                <div class="Hotena"> Họ tên</div>
                            </div>
                            <div class="Aten">
                                <div class="diemanh">Điểm danh</div> 
                            </div>
                            <div class="ghichu">
                                <div class="note">Ghi chú</div>
                            </div>
                        `;
                    //     console.log(ArrListAtten);
                    //     <div class="thng-tin-im">Thông tin điểm danh</div>

                    // <div class="bui-hc-th">
                    //     Buổi học: Thứ 6 03/11/2023 P.101 07:00 - 11:00 - 2809232092
                    // </div>
                    const firstElement = ArrListAtten[0]; // Lấy phần tử đầu tiên của mảng

                    const ngay = new Date(firstElement.ngay);
                    const dayOfWeek = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][ngay.getDay()];
                    const ngayFormatted = `${ngay.getDate()}/${ngay.getMonth() + 1}/${ngay.getFullYear()}`;
                    const gioBD = firstElement.thoi_gian_bat_dau;
                    const gioKT = firstElement.thoi_gian_ket_thuc;
                    const phong = `P.${firstElement.id_phong}`;
                    const id = `-${firstElement.id_buoi_hoc}`;

                    const htmlString = `<div class="bui-hc-th">
                                        Buổi học: ${dayOfWeek} ${ngayFormatted} ${phong} ${gioBD} - ${gioKT} ${id}
                                    </div>`;
                    document.getElementById('container').innerHTML = htmlString;

                    console.log(htmlString);

                    // Xóa nội dung cũ của container-tg trước khi thêm danh sách mới

                    // Kiểm tra xem có phần tử con nào có class là container-tg không
                    var lammoi = $('.group-child4 .container-tg');
                    if (lammoi.length > 0) {
                        lammoi.remove(); // Loại bỏ thẻ div ra khỏi DOM và xóa nó khỏi bộ nhớ
                    }
                    // Nếu tồn tại phần tử container-tg, loại bỏ nó

                    $('.container-tg').empty();
                    console.log(ArrListAtten)
                    // Tạo container-tg mới và thêm danh sách học viên vào
                    let containerTG = $('<div>').addClass('container-tg');
                    ArrListAtten.forEach(function(hocvien) {
                        let idBuoiHoc = hocvien.id_buoi_hoc;
                        let idSinhVien = hocvien.id_hoc_vien;
                        let hoVaTen = hocvien.ho_ten;
                        let ghiChu = hocvien.ghi_chu;
                        let coMat = hocvien.co_mat === '1'; // Kiểm tra nếu có mặt
                        let strId = idSinhVien.toString();
                        let paddedId = '0000' + strId;
                        let displayedId = paddedId.slice(-5);
                        let groupChild5 = $('<div>').addClass('group-child5');
                        let groupChild6 = $('<div>').addClass('group-child6');
                        let groupChild7 = $('<div>').addClass('group-child7');
                        let groupChild8 = $('<div>').addClass('group-child8');

                        let divIdSinhVien = $('<div>').text(displayedId).addClass('div');
                        let divHoVaTen = $('<div>').text(hoVaTen).addClass('trng-gia-huy');

                        let checkbox = $('<input>').attr({
                            type: 'checkbox',
                            id: idSinhVien,
                            idbuoihoc: idBuoiHoc,
                            checked: coMat // Thiết lập trạng thái checked dựa trên co_mat
                        });

                        let inputText = $('<input>').attr({
                            type: 'text',
                            placeholder: '',
                            id: idSinhVien,
                            idbuoihoc: idBuoiHoc,
                        }).val(ghiChu); // Hiển thị ghi chú trong input text

                        groupChild8.append(inputText);
                        groupChild7.append(checkbox);
                        groupChild5.append(divIdSinhVien);
                        groupChild6.append(divHoVaTen);

                        globalCheckbox.push(checkbox);
                        globalCheckbox.push(inputText);
                        containerTG.append(groupChild5, groupChild6, groupChild7, groupChild8);

                    });
                    var container = $('.group-child4');
                    container.append(containerTG);
                    $('.container-tg').prepend(newElement);

                    // globalCheckbox.forEach(function(checkbox) {
                    // checkbox.prop('disabled', true);
                    // inputText.prop('disabled',true)
                    // });

                    globalCheckbox.forEach(function(element) {
                        element.prop('disabled', true);
                    });

                    console.log(globalCheckbox);

                    // Disable checkbox khi lấy được

                    $('.container-tg').append(containerTG);
                    console.log(containerTG);

                    // Thêm sự kiện change để theo dõi sự thay đổi trạng thái của checkbox

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });
    </script>

</body>


</html>