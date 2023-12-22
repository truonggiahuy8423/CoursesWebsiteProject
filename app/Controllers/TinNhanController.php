<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\HocVienModel;
use App\Models\GiangVienModel;
use App\Models\TinNhanChungModel;
use App\Controllers\BaseController;
use DateTime;
use App\Models\TinNhanRiengModel;
class TinNhanController extends BaseController
{
    public function getInBox(){
        $tinNhanRiengModel = new TinNhanRiengModel();
        $chat_box["user_nhan"] = $_GET["chatboxID"];
        $user_nhan = strval($chat_box["user_nhan"]);
        $user_gui = strval(session()->get("id_user"));
        $chat_box["tin_nhans"] = $tinNhanRiengModel->queryDatabase(
            'SELECT noi_dung, thoi_gian, user_gui
            FROM tin_nhan_rieng
            WHERE user_gui IN ('.$user_nhan .','. $user_gui .')
            AND user_nhan IN ('.$user_nhan .','. $user_gui .')
            ORDER BY thoi_gian');
        
        $chat_box['hoTen'] = $tinNhanRiengModel->queryDatabase(
            'SELECT
                CASE
                    WHEN u.id_giang_vien IS NOT NULL THEN gv.ho_ten
                    WHEN u.id_ad IS NOT NULL THEN ad.ho_ten
                    WHEN u.id_hoc_vien IS NOT NULL THEN hv.ho_ten
                END AS ho_ten
            FROM
                users u
            LEFT JOIN giang_vien gv ON u.id_giang_vien = gv.id_giang_vien
            LEFT JOIN ad ON u.id_ad = ad.id_ad
            LEFT JOIN hoc_vien hv ON u.id_hoc_vien = hv.id_hoc_vien
            WHERE u.id_user = ' . $user_nhan);
        return view('Inbox', $chat_box);
    }

    public function sendTinNhanRieng(){
        $messagesData = json_decode(json_encode($this->request->getJSON()), true);
        $messages = new TinNhanRiengModel();
        $model = new TinNhanRiengModel();

        $messages->noi_dung =  $messagesData["noi_dung"];
        $messages->thoi_gian = date("Y/m/d h:i:s");
        $messages->user_nhan = $messagesData["user_nhan"];
        $messages->user_gui = session()->get("id_user");
        
        return $this->response->setJSON($model->insertTinNhanRieng($messages));
    }

    public function getInsertChatBoxForm(){
        $tinNhanRiengModel = new TinNhanRiengModel();
        
        $chat_box = $tinNhanRiengModel->queryDatabase(
            'SELECT DISTINCT user_nhan
            FROM tin_nhan_rieng
            WHERE user_gui = ' . session()->get("id_user"));
        $n = count($chat_box);
        $old_chat_boxes = $n > 0 ? strval(session()->get("id_user")) . "," : strval(session()->get("id_user"));
        for($i = 0; $i < $n - 1; $i++){
            $user_nhan = strval($chat_box[$i]["user_nhan"]);
            $old_chat_boxes = $old_chat_boxes . $user_nhan . ", ";
        }
        $old_chat_boxes = $n > 0 ? $old_chat_boxes . strval($chat_box[$n-1]["user_nhan"]) : $old_chat_boxes;
        $chat_box['old_chat_boxes'] = $old_chat_boxes;
        $chat_box['new_chat_box'] = $tinNhanRiengModel->queryDatabase(
            'SELECT
                id_user,
                    CASE
                        WHEN u.id_giang_vien IS NOT NULL THEN gv.ho_ten
                        WHEN u.id_ad IS NOT NULL THEN ad.ho_ten
                        WHEN u.id_hoc_vien IS NOT NULL THEN hv.ho_ten
                    END AS ho_ten
                FROM
                    users u
                LEFT JOIN giang_vien gv ON u.id_giang_vien = gv.id_giang_vien
                LEFT JOIN ad ON u.id_ad = ad.id_ad
                LEFT JOIN hoc_vien hv ON u.id_hoc_vien = hv.id_hoc_vien
                WHERE u.id_user NOT IN ('. $old_chat_boxes .')');

        return view('Admin\ViewCell\InsertChatBoxForm', $chat_box);
    }

    public function sendTinNhanChung(){
        $messagesData = json_decode(json_encode($this->request->getJSON()), true);
        $messages = new TinNhanChungModel();
        $model = new TinNhanChungModel();

        $messages->noi_dung =  $messagesData["noi_dung"];
        $messages->thoi_gian = date("Y/m/d h:i:s");
        $messages->kenh_nhan = $messagesData["kenh_nhan"];
        $messages->user_gui = session()->get("id_user");
        
        return $this->response->setJSON($model->insertTinNhanChung($messages));
    }

    public function getChatContentCourse(){
        $tinNhanChungModel = new TinNhanChungModel();
        $chat_course["kenh_nhan"] = $_GET["chatid_course"];
        $chat_course["user_main"] = session()->get('id_user');
        $kenh_nhan = strval($chat_course["kenh_nhan"]);
        $user_gui = "";
        $content = "";
        $chat_course["tin_nhans"] = $tinNhanChungModel->queryDatabase(
            'SELECT noi_dung, thoi_gian, user_gui, anh
            FROM tin_nhan_chung
            WHERE kenh_nhan IN ('.$kenh_nhan .')
            ORDER BY thoi_gian');
        $n = count($chat_course["tin_nhans"]);

        for($i = 0; $i < $n-1; $i++){
            $user_gui = $user_gui . strval($chat_course["tin_nhans"][$i]["user_gui"]) . ",";
        }
        $user_gui = $n > 0 ? $user_gui . strval($chat_course["tin_nhans"][$n-1]["user_gui"]) : $user_gui;

        $chat_course['hoTen'] = $tinNhanChungModel->queryDatabase(
            'SELECT
                id_user,
                CASE
                    WHEN u.id_giang_vien IS NOT NULL THEN gv.ho_ten
                    WHEN u.id_ad IS NOT NULL THEN ad.ho_ten
                    WHEN u.id_hoc_vien IS NOT NULL THEN hv.ho_ten
                END AS ho_ten
            FROM
                users u
            LEFT JOIN giang_vien gv ON u.id_giang_vien = gv.id_giang_vien
            LEFT JOIN ad ON u.id_ad = ad.id_ad
            LEFT JOIN hoc_vien hv ON u.id_hoc_vien = hv.id_hoc_vien
            WHERE u.id_user IN (' . $user_gui . ')');
        $previous_user = -1;
                
        for($i = 0; $i < count($chat_course["tin_nhans"]); $i++){

            if($chat_course["tin_nhans"][$i]["user_gui"] != $chat_course["user_main"]){
                if($previous_user == $chat_course["tin_nhans"][$i]["user_gui"]){
                    $content = $content . "<div class='col-7 mb-1'>
                            <div class='card'>
                                <p class='p-1'>{$chat_course["tin_nhans"][$i]["noi_dung"]}</p>
                            </div>
                        </div>
                        <div class='col-5 mb-1'></div>";
                }
                else{
                    $previous_user = $chat_course["tin_nhans"][$i]["user_gui"];
                    $currentName = "";
                    for($j = 0; $j < count($chat_course['hoTen']); $j++){
                        if($chat_course['hoTen'][$j]["id_user"] == $chat_course["tin_nhans"][$i]["user_gui"]){
                            $currentName = $chat_course['hoTen'][$j]["ho_ten"];
                        }
                    }
                    $content = $content .  "<div class='col-7 mt-1 mb-1'>
                            <p class='p-1'>{$currentName}</p>
                            <div class='card'>
                                <p class='p-1'>{$chat_course["tin_nhans"][$i]["noi_dung"]}</p>
                            </div>
                        </div>
                        <div class='col-5 mt-1 mb-1'></div>";
                }
            }
            else{
                if($previous_user == $chat_course["tin_nhans"][$i]["user_gui"]){
                    $content = $content .  "<div class='col-7 offset-5 mb-1'>
                            <div class='card'>
                                <p class='p-1'>{$chat_course["tin_nhans"][$i]["noi_dung"]}</p>
                            </div>
                        </div>";  
                }
                else{
                    $previous_user = $chat_course["tin_nhans"][$i]["user_gui"];
                    $content = $content .  "<div class='col-7 offset-5 mt-1 mb-1'>
                            <p class='p-1'>You</p>
                                <div class='card'>
                                    <p class='p-1'>{$chat_course["tin_nhans"][$i]["noi_dung"]}</p>
                                </div>
                            </div>";  
                }
            }
        }
        return $content;
    }
}