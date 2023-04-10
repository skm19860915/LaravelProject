<?php
if (!function_exists('CreateCheatRoom')) {

    function CreateCheatRoom($ID, $MSG) {
        date_default_timezone_set("Asia/Kolkata");
        $chat = array();
        $chat['message'] = $MSG;
        $chat['roomid'] = CheatRoomID($ID);
        $ID = explode('-', $ID);
        $chat['to'] = $ID[1];
        $chat['from'] = $ID[0];
        $chat['time'] = time();
        $chat['notify'] = 1;
        $chat['inbrowsernotify'] = 1;
        DB::table('tila_chat_room')->insert($chat);
    }

}

if (!function_exists('CheatRoomID')) {

    function CheatRoomID($ID = 0) {
        $ID = explode('-', $ID);
        sort($ID);
        $room = implode('', $ID);
        for ($i = 0; $i <= 20; $i++) {
            $room .= '--' . md5($room);
        }
        return md5($room);
    }

}

if (!function_exists('GetChatDatasMessageBoxDetail')) {

    function GetChatDatasMessageBoxDetail() {
       
        $chtID = CheatRoomID($_REQUEST['id']);
        
            $query = "select r.*,(select name from users where id=r.to) as toname,(select name from users where id=r.from) as fromname from tila_chat_room as r where roomid='" . $chtID . "' ";
            $Master = DB::select($query);

            $left = explode('-', $_REQUEST['id']);

            //$l['notify'] = 0;
            //DB::table('tila_chat_room')->where('to', $left[0])->where('roomid', $chtID)->update($l);
            $html = '';
            foreach ($Master as $v) {
                $cla = '';
                if ($v->to == $left[1]) {
                    $cla = 'rightalign';
                }
                ?>
                <div class="chtbox <?php echo $cla; ?>">
                    <div>
                        <div class="username">
                            <?php
                            echo $v->fromname;
                            $sortname = GetSOrtNameVALUE($v->fromname);
                            ?>
                        </div>
                        <div>
                            <a class="nameIcon"><?php echo $sortname; ?></a>
                            <div class="msgs">
                                <?php echo base64_decode($v->message); ?>
                                <div class="timing"><?php echo date('d F, y h:i A ', ($v->time)) . '-' . getRangeDateString($v->time); ?>
                                </div></div>

                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <?php
                $html .= '<div class="chtbox ' . $cla . '"><div><div class="username">' . $v->fromname . '</div><div><a class="nameIcon">' . $v->fromname[0] . $v->fromname[1] . '</a><div class="msgs">' . $v->message . '<div class="timing">' . date('d F, y h:i A ', ($v->time)) . '-' . getRangeDateString($v->time) . '</div></div></div></div><div style="clear:both;"></div></div>';
            }
           

        //echo '=============================='.$html.'==============================';
    }

}


if (!function_exists('getRangeDateString')) {

    function getRangeDateString($timestamp) {
        if ($timestamp) {
            $currentTime = strtotime('today');
            // Reset time to 00:00:00
            $timestamp = strtotime(date('Y-m-d 00:00:00', $timestamp));
            $days = round(($timestamp - $currentTime) / 86400);
            switch ($days) {
                case '0';
                    return 'Today';
                    break;
                case '-1';
                    return 'Yesterday';
                    break;
                case '-2';
                    return 'Day before yesterday';
                    break;
                case '1';
                    return 'Tomorrow';
                    break;
                case '2';
                    return 'Day after tomorrow';
                    break;
                default:
                    if ($days > 0) {
                        return 'In ' . $days . ' days';
                    } else {
                        return ($days * -1) . ' days ago';
                    }
                    break;
            }
        }
    }

}

if (!function_exists('ShowChatNotifications')) {

    function ShowChatNotifications($i = '0-0') {
        $data = Auth::User();
        if ($i != '0-0') {
            $_REQUEST['id'] = $i;
        }
        $IDSS = explode('-', $_REQUEST['id']);
        $chtID = CheatRoomID($_REQUEST['id']);
        $query = "select notify,message,(select name from users where id=r.from) as fromname from tila_chat_room as r where r.roomid='" . $chtID . "' and r.to='" . $data->id . "' and r.from='" . $IDSS[0] . "' and r.notify=1 ";
        $Master = DB::select($query);
        //pre($Master);
        // die;

        if (count($Master) > 0 && $Master[0]->notify == 1) {
            $Master[0]->message = base64_decode($Master[0]->message);
            $link = $Master[0]->message;
            preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $link, $result);
            if (!empty($result['href'])) {
                $Master[0]->message = $result['href'][0];
            }
            $l['notify'] = 0;
            DB::table('tila_chat_room')->where('to', $data->id)->where('from', $IDSS[0])->where('roomid', $chtID)->update($l);
        }
        return $Master;
    }

}
if (!function_exists('GetSOrtNameVALUE')) {

    function GetSOrtNameVALUE($i) {
        $name = explode(' ', $i);
        $n = $name[0][0];
        if (count($name) > 1) {
            $n .= $name[1][0];
        }
        return $n;
    }

}


if (!function_exists('hidenotificationmsg')) {

    function hidenotificationmsg() {
        $ID = explode('-', $_REQUEST['id']);
        $data = Auth::User();
        //pre($data->id);
        if (in_array($data->id, $ID)) {
            $ids = array_search($data->id, $ID);
            if ($ids == 0) {
                $ID = $ID[1];
            } else {
                $ID = $ID[0];
            }
        }
        $chtID = CheatRoomID($ID . '-' . $data->id);
        $l['inbrowsernotify'] = 0;
        DB::table('tila_chat_room')->where('to', $data->id)->where('from', $ID)->where('roomid', $chtID)->update($l);
    }

}


if (!function_exists('ChatFileUpload')) {

    function ChatFileUpload() {
        
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $destination = public_path() . '/chat/' . $filename;
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo '/chat/' . $filename;
            } else {
                echo  'Ooops!  Your upload triggered the following error:  ';
            }
        }
        
        
    }

}