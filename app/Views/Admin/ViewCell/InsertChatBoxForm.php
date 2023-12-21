<div class="form-container">
    <div class="insert-chatbox-form bg-white h-75 overflow-auto" style="width: 70%;">
        <div class="insert-chatbox-form__title-section w-100 bg-dark d-flex justify-content-between align-items-center sticky-top" style="height: 60px;">
            <div class="ps-3">
                <h5 class="insert-chatbox-form__title text-white fw-bold">Thêm chat box</h5>
            </div>    
            <div class="insert-chatbox-form__btn-container pe-3 d-flex justify-content-end align-items-center">
                <div class="input-group">
                    <input type="text" class="form-control inputNewChat" placeholder="Write a message" aria-describedby="button-addon2">
                    <button class="btn btn-light sendBtn_newChat" type="button" id="button-addon2">Send</button>
                </div>
                <button class="insert-chatbox-form__cancel-btn btn btn-light border border-gray ms-3 shadow-sm">
                    <i class="fas fa-times" style="color: #333;"></i>
                </button>
            </div>
        </div>
        <?php
            // echo $old_chat_boxes;
            // echo var_dump($new_chat_box);
        ?>
        <div class="insert-chatbox-form__content-section p-3">
            <table class="table  mt-3 table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col">Chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        for($i = 0; $i < count($new_chat_box); $i++){
                            echo "<tr>
                                    <td>{$new_chat_box[$i]["id_user"]}</td>
                                    <td>{$new_chat_box[$i]["ho_ten"]}</td>
                                    <td><input type='radio' name='newChatBox' class='addNewChatBox' value='{$new_chat_box[$i]["id_user"]}'></td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>