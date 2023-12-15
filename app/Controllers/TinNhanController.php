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
    public function loadInbox()
    {
        
    }

    public function getInBox(){
        $tinNhanRiengModel = new TinNhanRiengModel();
        $chat_box = json_decode(json_encode($this->request->getJSON()), true);
        $temp = array();
        $chat_box["user_nhan"] = $_GET["chatboxID"];
        $user_nhan = strval($chat_box["user_nhan"]);
        // $user_nhan = $chat_box["user_nhan"];
        // $user_gui = strval(session()->get("id_user"));
        // $user_gui = session()->get("id_user");
        // $chat_box["tin_nhans"] = $tinNhanRiengModel->queryDatabase(
        //     "SELECT noi_dung, user_gui
        //     FROM tin_nhan_rieng
        //     WHERE user_gui IN ('$user_nhan','$user_gui')
        //     AND user_nhan IN ('$user_nhan','$user_gui')
        //     ORDER BY thoi_gian");

        $chat_box["tin_nhans"] = $tinNhanRiengModel->queryDatabase(
            'SELECT noi_dung, thoi_gian, user_gui
            FROM tin_nhan_rieng
            WHERE user_gui IN ('.$user_nhan .','.session()->get("id_user").')
            AND user_nhan IN ('.$user_nhan .','.session()->get("id_user").')
            ORDER BY thoi_gian');

        // $chat_box["tin_nhans"] = $tinNhanRiengModel->getInBox($user_nhan, $user_gui);

        // $chat_box["tin_nhans"] = $tinNhanRiengModel->test('SELECT thoi_gian, user_gui
        // FROM tin_nhan_rieng
        // WHERE user_gui IN ('.$user_nhan .','.session()->get("id_user").')
        // AND user_nhan IN ('.$user_nhan .','.session()->get("id_user").')
        // ORDER BY thoi_gian');
        return view('Inbox', $chat_box);
        // return view('Inbox');
    }

}