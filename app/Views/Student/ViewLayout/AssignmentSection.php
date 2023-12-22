<div class="content-section">
    <a href="<?php base_url() ?>/courses/resource?courseid=<?php echo $id_lop_hoc ?>" class="custom-link">
        <i class="fa-solid fa-angles-left" style="margin-right: 3px;"></i>
        Tài nguyên
    </a>
    <h3 class="assignment-title" style="margin-right: 14px; margin-top: 0px; margin-left: 27px; display: flex; align-items: center;">

        <img src="<?php base_url() ?>/assets/img/assignment_icon.png" alt="" style="height: 32px; margin-left: 10px;">
    </h3>

    <p class="assignment-content">
    </p>

    <h5 style="margin-left: 30px; margin-right: 14px; margin-top: 17px; margin-left: 27px; display: flex; align-items: center; justify-content: space-between;">
        Trạng thái bài tập
    </h5>
    <table class="assignment-infor-table">
        <tr>
            <td>Trạng thái</td>
            <td>
                <span>Chưa đến hạn</span><span>/Hiệu lực</span>
            </td>
        </tr>
        <tr>
            <td>Thời gian nộp bài</td>
            <td>

            </td>
        </tr>
        <tr>
            <td>Thời gian hiệu lực</td>
            <td>

            </td>
        </tr>
        <tr>
            <td>Thời gian còn lại</td>
            <td>
                <!--  -->

            </td>
        </tr>
        <tr>
            <td>Trạng thái nộp</td>
            <td>
                <!--  -->

            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Chi tiết bài nộp</td>
            <td>
                <div style='display: flex; flex-direction: column; align-items: start;'>

                </div>
            </td>
        </tr>
    </table>



    <script>
        function getCurrentDateTimeString() {
            const currentDate = new Date();

            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const day = String(currentDate.getDate()).padStart(2, '0');
            const hours = String(currentDate.getHours()).padStart(2, '0');
            const minutes = String(currentDate.getMinutes()).padStart(2, '0');
            const seconds = String(currentDate.getSeconds()).padStart(2, '0');

            const dateTimeString = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

            return dateTimeString;
        }

        function calculateTimeDifference(inputTime, inputTime2) {
            // Convert input time strings to Date objects
            const inputDateTime = parseDateString(inputTime);
            const currentTime = parseDateString(inputTime2);

            // Compare current time and input time
            if (currentTime < inputDateTime) {
                // If current time is less than input time, calculate remaining time
                const timeDifference = 'Còn ' + formatTimeDifference(inputDateTime - currentTime);
                return timeDifference;
            } else {
                // If current time is greater than input time, calculate overdue time
                const timeDifference = 'Quá hạn ' + formatTimeDifference(currentTime - inputDateTime);
                return timeDifference;
            }
        }

        function calculateTimeDifference2(inputTime, inputTime2) {
            // Convert input time strings to Date objects
            const inputDateTime = parseDateString(inputTime);
            const currentTime = parseDateString(inputTime2);

            // Compare current time and input time
            if (currentTime < inputDateTime) {
                // If current time is less than input time, calculate remaining time
                const timeDifference = 'Sớm ' + formatTimeDifference(inputDateTime - currentTime);
                return timeDifference;
            } else {
                // If current time is greater than input time, calculate overdue time
                const timeDifference = 'Quá hạn ' + formatTimeDifference(currentTime - inputDateTime);
                return timeDifference;
            }
        }

        function calculateTimeDifference3(inputTime, inputTime2) {
            // Convert input time strings to Date objects
            const inputDateTime = parseDateString(inputTime);
            const currentTime = parseDateString(inputTime2);

            // Compare current time and input time
            if (currentTime < inputDateTime) {
                // If current time is less than input time, calculate remaining time
                const timeDifference = 'sớm ' + formatTimeDifference(inputDateTime - currentTime);
                return timeDifference;
            } else {
                // If current time is greater than input time, calculate overdue time
                const timeDifference = 'quá hạn ' + formatTimeDifference(currentTime - inputDateTime);
                return timeDifference;
            }
        }

        // Function to format time difference
        function formatTimeDifference(timeDifference) {
            // Calculate days, hours, minutes, and seconds
            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

            // Format the time string
            const formattedTime = `${days} ngày ${hours} giờ ${minutes} phút ${seconds} giây`;

            // return removeZeroTimeComponents(formattedTime);
            return formattedTime;

        }

        function removeZeroTimeComponents(formattedTime) {
            // Regex pattern to match "0 days," and similar components
            const pattern = /(?:0 [a-zà-ú]+,?\s*)+/iu;

            // Replace matched components with an empty string
            const cleanedTime = formattedTime.replace(pattern, match => {
                // Only replace if the matched component is "0"
                return match.startsWith('0') ? '' : match;
            });

            return cleanedTime;
        }

        function parseDateString(dateString) {
            const [datePart, timePart] = dateString.split(' ');
            const [year, month, day] = datePart.split('-');
            const [hours, minutes, seconds] = timePart.split(':');
            return new Date(year, month - 1, day, hours, minutes, seconds);
        }

        function createFileItems(files) {
            let fileItems = '';

            files.forEach(file => {
                const fileIcon = {
                    'pdf': 'pdf_icon',
                    'docx': 'word_icon',
                    'doc': 'word_icon',
                    'xlsx': 'excel_icon',
                    'pptx': 'ppt_icon'
                };
                const fileExtension = ['pdf', 'docx', 'xlsx', 'doc', 'pptx'];
                const icon = (fileExtension.includes(file.extension)) ? fileIcon[file.extension] : 'any_file_icon';

                fileItems += `
            <div style='margin-left: 0px!important; margin-right: 5px; padding-right: 35px; display: flex; margin-bottom: 8px;' class='file-item--var' value='${file.id_tep_tin_tai_len}' isChosen>
                <img src='<?php echo base_url() ?>/assets/img/${icon}.png' alt=''>
                <span>${file.ten_tep}.${file.extension}</span>
            </div>
        `;
            });

            return fileItems;
        }
        async function reloadAssignmentPage() {
            loadingEffect(true);
            let urlParams = new URLSearchParams(window.location.search);
            let assignmentid = urlParams.get('assignmentid');
            $.ajax({
                url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/getAssignmentInformationForStudent`,
                method: 'GET',
                contentType: "text",
                data: {
                    assignmentid: assignmentid * 1
                },
                dataType: "json",
                success: function(response) {
                    if (response.hasOwnProperty('state')) {
                        console.log(response.message);
                        loadingEffect(false);
                        return;
                    } else {
                        $(`.assignment-title`).html(
                            `<span>${response['ten']}</span> <img src="<?php base_url() ?>/assets/img/assignment_icon.png" alt="" style="height: 32px; margin-left: 10px;">`
                        );
                        $('.assignment-content').text(`${response['noi_dung']}`);

                        let tt;
                        let hl;
                        if (parseDateString(response['thoi_han']) < parseDateString(getCurrentDateTimeString())) {
                            tt = "Đã quá hạn"
                        } else {
                            tt = "Chưa đến hạn"

                        }
                        let o = parseDateString(response['thoi_han_nop']) < parseDateString(getCurrentDateTimeString());
                        if (o) {
                            hl = "Hết hiệu lực";
                        } else {
                            hl = "Còn hiệu lực";
                        }
                        $(`.assignment-infor-table tr`).eq(0).find(`td`).eq(1).text(`${tt}/${hl}`);
                        $(`.assignment-infor-table tr`).eq(1).find(`td`).eq(1).text(`${response['thoi_han']}`);
                        $(`.assignment-infor-table tr`).eq(2).find(`td`).eq(1).text(`${response['thoi_han_nop']}`);

                        // 
                        if (response.student_submit == null) {
                            $(`.assignment-infor-table tr`).eq(4).find(`td`).eq(1).html(`Bạn chưa có bài nộp nào`);
                            $(`.assignment-infor-table tr`).eq(4).find(`td`).eq(1).css("background-color", "#d9edff");
                            $(`.submit-button-container`).remove();

                            $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).text(calculateTimeDifference(response['thoi_han'], getCurrentDateTimeString()));
                            // $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).text("ok");

                            $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css('background-color', 'white');
                            if (parseDateString(response['thoi_han']) < parseDateString(getCurrentDateTimeString())) {
                                $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css("color", "#ff0000");
                            } else {
                                $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css("color", "#52ec4d");
                            }
                            $(`.assignment-infor-table tr`).eq(5).find(`td`).eq(1).html(``);
                            if (!o) {
                                $(`.assignment-infor-table`).after(`
                            <div class="submit-button-container">
                                <button class="submit-btn highlight-button">Thêm bài nộp</button>
                            </div>
                            `);
                            $('.submit-btn').click(function() {
                                    loadingEffect(true);
                                    $.ajax({
                                        url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/getSubmissionForm`,
                                        method: 'GET',
                                        dataType: "text",
                                        success: function(response) {
                                            loadingEffect(false);
                                            $(`body`).append(response);
                                        }
                                    })

                                });
                            }
                            
                        } else {
                            $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).text(calculateTimeDifference(response['thoi_han'], getCurrentDateTimeString()));
                            $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css('background-color', 'white');
                            if (parseDateString(response['thoi_han']) < parseDateString(getCurrentDateTimeString())) {
                                $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css("color", "#ff0000");
                            } else {
                                $(`.assignment-infor-table tr`).eq(3).find(`td`).eq(1).css("color", "#52ec4d");
                            }
                            $(`.assignment-infor-table tr`).eq(4).find(`td`).eq(1).html("Bạn đã nộp " + calculateTimeDifference3(response.thoi_han, response.student_submit.thoi_gian_nop));
                            if (parseDateString(response.thoi_han) < parseDateString(response.student_submit.thoi_gian_nop)) {
                                $(`.assignment-infor-table tr`).eq(4).find(`td`).eq(1).css("background-color", "#ff9494");
                            } else {
                                $(`.assignment-infor-table tr`).eq(4).find(`td`).eq(1).css("background-color", "#c5ffce");
                            }

                            $(`.submit-button-container`).remove();

                            $(`.assignment-infor-table tr`).eq(5).find(`td`).eq(1).html(
                                `<div style='display: flex; flex-direction: column; align-items: start; min-height: 60px; padding: 5px;'>
                                    ${createFileItems(response.student_submit.files)}
                                </div>`
                            );
                            // $(`.assignment-infor-table tr`).eq(3).remove();
                            if (!o) {
                                $(`.assignment-infor-table`).after(`
                            <div class="submit-button-container">
                                <button class="cancel-submit-btn highlight-button">Hủy bài nộp</button>
                                <button style="margin-left: 10px" class="modify-submit-btn highlight-button">Chỉnh sửa bài nộp</button>
                            </div>
                            `);
                                
                                console.log("ppi")
                            }
                        }
                    }
                    loadingEffect(false);

                }
            })
        }

        $(document).on('click', '.cancel-submit-btn', function() {
            if (confirm("Hủy bỏ bài nộp này?")) {
                loadingEffect(true);
                let urlParams = new URLSearchParams(window.location.search);
                let assignmentid = urlParams.get('assignmentid');
                $.ajax({
                    url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/deleteSubmission`,
                    method: 'GET',
                    dataType: "json",
                    contentType: "text",
                    data: {
                        assignmentid: assignmentid * 1
                    },
                    success: function(response) {
                        loadingEffect(false);
                        if (response.state) {
                            toast({
                                title: "Thánh công!",
                                message: response.message,
                                type: "success",
                                duration: 100000
                            });
                        } else {
                            toast({
                                title: "Lỗi!",
                                message: response.message,
                                type: "error",
                                duration: 100000
                            });
                        }
                        reloadAssignmentPage();
                    }
                })
            }
        });
        $(document).on('click', '.modify-submit-btn', function() {
            loadingEffect(true);
            $.ajax({
                url: `${window.location.protocol}//${window.location.hostname}/Admin/CoursesController/getSubmissionForm`,
                method: 'GET',
                dataType: "text",
                success: function(response) {
                    loadingEffect(false);
                    $(`body`).append(response);
                }
            })

        });

        // foreach ($assignment["submits"] as $submit) {
        //         $idhv = str_pad($submit['id_hoc_vien'], 6, '0', STR_PAD_LEFT);
        //         if ($submit["id_bai_nop"] == null) {
        //             $u = count($assignment["submits"]);
        //             echo "
        //                     <tr>
        //                         <td>{$idhv}</td>
        //                         <td>{$submit['ho_ten']}</td>
        //                         <td style='color: #FFA33C;'>Chưa nộp</td>
        //                         <td>--/--</td>
        //                         <td>{$u}</td>
        //                     </tr>
        //                     ";
        //         } else {
        //             $st = calculateTimeDifference2($assignment["thoi_han"], $submit["thoi_gian_nop"]);
        //             if ($assignment["thoi_han"] < $submit["thoi_gian_nop"]) {
        //                 $sty = "style='color: #ff0000;'";
        //             } else {
        //                 $sty = "style='color: #52ec4d;'";
        //             }
        //             $files = "";
        //             foreach ($submit["files"] as $file) {
        //                 $file_icon = [
        //                     'pdf' => 'pdf_icon',
        //                     'docx' => 'word_icon',
        //                     'doc' => 'word_icon',
        //                     'xlsx' => 'excel_icon',
        //                     'pptx' => 'ppt_icon'
        //                 ];
        //                 $burl = base_url();
        //                 $fileExtension = ['pdf', 'docx', 'xlsx', 'doc', 'pptx'];
        //                 $icon = (array_search($file['extension'], $fileExtension) !== false) ? $file_icon[$file['extension']] : "any_file_icon";
        //                 $files = $files . "
        //                 <div style='margin-left: 0px!important; margin-right: 5px; padding-right: 35px; display: flex;' class='file-item--var' value='{$file['id_tep_tin_tai_len']}' isChosen>
        //                     <img src='$burl/assets/img/$icon.png' alt=''>
        //                     <span>{$file['ten_tep']}.{$file['extension']}</span>
        //                 </div>
        //                 ";
        //             }
        //             echo "
        //                 <tr>
        //                         <td>{$idhv}</td>
        //                         <td>{$submit['ho_ten']}</td>
        //                         <td $sty>$st</td>
        //                         <td>{$submit['thoi_gian_nop']}</td>
        //                         <td style='text-align: right;'>
        //                             <div style='display: flex;
        //                             flex-direction: column;
        //                             align-items: start;'>
        //                                 $files
        //                             </div>
        //                         </td>
        //                 </tr>
        //                 ";
        //         }
        //     }

        $(document).ready(function() {
            reloadAssignmentPage();
            $(document).on('click', '.file-item--var', function() {
                let urlParams = new URLSearchParams(window.location.search);
                let assignmentid = urlParams.get('assignmentid');
                let id_lop_hoc = <?php echo $id_lop_hoc ?>;
                $.ajax({
                    url: "<?php echo base_url() ?>/Admin/CoursesController/getFile3",
                    contentType: "text",
                    dataType: "json",
                    data: {
                        id_lop_hoc: id_lop_hoc,
                        id_file: $(this).attr('value'),
                        id_bai_tap: assignmentid
                    },
                    success: function(response) {
                        if (response.state) {
                            var binaryData = atob(response.data);
                            var byteArray = new Uint8Array(binaryData.length);
                            for (var i = 0; i < binaryData.length; i++) {
                                byteArray[i] = binaryData.charCodeAt(i);
                            }
                            var blob = new Blob([byteArray], {
                                type: 'application/octet-stream'
                            });

                            // Create a Blob object from the file data
                            // var blob = new Blob([atob(response.data)], { type: "application/octet-stream" });

                            // Create a link element
                            var link = document.createElement("a");

                            // Set the link's href attribute to a URL representing the Blob object
                            link.href = window.URL.createObjectURL(blob);

                            // Set the download attribute to the desired file name
                            link.download = response.nameFile + "." + response.extension;

                            // Append the link to the document
                            document.body.appendChild(link);

                            // Trigger a click on the link to start the download
                            link.click();

                            // Remove the link from the document
                            document.body.removeChild(link);
                        } else {
                            // Handle the case where the server returns an error
                            toast({
                                title: "Lỗi!",
                                message: response.message,
                                type: "error",
                                duration: 100000
                            });
                        }
                    },
                    error: function() {
                        // Handle AJAX error
                        toast({
                            title: "Lỗi!",
                            message: "Đã có lỗi xảy ra, vui lòng thử lại!",
                            type: "error",
                            duration: 100000
                        });
                    }
                });
            });
        })
    </script>

</div>