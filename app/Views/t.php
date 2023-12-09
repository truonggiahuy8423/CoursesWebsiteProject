<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="<?php echo base_url(); ?>/assets/jquery.js"></script>
    <title>Document</title>
</head>
<body>
    <p>Rs</p>
    <button class="getrs">GetRS</button>

    <button id="y" value="YES">YES</button>
    <button id="n" value="NO">NO</button>
</body>
<script>
    $(document).ready(function(){
        $(`.getrs`).click(async function() {
            var rs = await getConfirm();
            $(`p`).text(rs);
        });
    })

    async function getConfirm() {
        return new Promise(function(resolve, reject) {
            $(`#y`).click(function() {
                resolve(true);
            });
            $(`#n`).click(function() {
                resolve(false);
            })
        })
    }
</script>
</html>
