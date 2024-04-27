<?
date_default_timezone_set('Asia/Jizzakh');
define('API_KEY', '6775851127:AAHLysVS2bxa7qYn5QUt0eN9Vuvdq3R8Zf4');
$Manager = "5269228873";
$compane = "Berkus.uz ";
function bot($method, $datas = []){
    $url = "https://api.telegram.org/bot".API_KEY."/" . $method;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    curl_close($ch);
    if (!curl_error($ch)) return json_decode($res);
};
function html($text){
    return str_replace(['<','>'],['&#60;','&#62;'],$text);
};

$update = json_decode(file_get_contents('php://input'));
// testlog
file_put_contents("log.txt",file_get_contents('php://input'));
// message variables
$message = $update->message;
$text = html($message->text);
$chat_id = $message->chat->id;
$from_id = $message->from->id;
$message_id = $message->message_id;
$first_name = $message->from->first_name;
$last_name = $message->from->last_name;
$full_name = html($first_name . " " . $last_name);

// replymessage
$reply_to_message = $message->reply_to_message;
$reply_chat_id = $message->reply_to_message->forward_from->Manager_id;
$reply_text = $message->text;


// Agar yozgan odam $Manager bo'lmasa ushbu kod qismiga kiramiz
if ($chat_id != $Manager) {
    // Agar yozilgan habar /start bolsa, yani yangi foydalanuvchi
    //  botni ishga tushursa ushbu kod bajariladi
    if ($text == "/start") {
        // Foydalanuvchiga Manager  yoki kompaniya nomidan salom yo'llaymiz.
        $reply = "Assalomu Alaykum <b>" . $full_name . "</b>, " . $compane . " Qabul Botiga Xush Kelibsiz !\nMurojat Yo'llashingiz Mumkin 👇";
        bot('sendmessage', [ // maxsus bot funksiyamiz orqali sendmessage ga
            'chat_id' => $chat_id, //foydalanuvchi id raqami va
            'text' => $reply, // habar matnini
            'parse_mode' => "HTML", //html formatda yuboramiz.
        ]);
        //  Yangi foydalanuvchi malumotlarini manajerga aniq vaqt bilan yuboramiz.
        $reply = "Yangi mijoz:\n" . $full_name . "\nUser id<a href='tg://user?id=" . $from_id . "'>" . $from_id . "</a>\n" . date('Y-m-d H:i:s') . "";
        bot('sendmessage', [ // maxsus bot funksiyamiz orqali sendmessage ga
            'chat_id' => $Manager, //Manager id raqami va
            'text' => $reply, // habar matnini
            'parse_mode' => "HTML", //html formatda yuboramiz.
        ]);
        // Foydalanuvchidan kelgan ilk /start habarini javob bera olishi uchun managerga yuboramiz.
        bot('forwardMessage', [ // maxsus bot funksiyamiz orqali forwardMessage ga
            'chat_id' => $Manager, //Manager id raqami va
            'from_chat_id' => $chat_id, // foydalanuvchi bilan bot o'rtasidagi chat id raqami
            'message_id' => $message_id, // va foydalanuvchi yuborgan habar id raqamini yuboramiz.
        ]);
        // Tekshiramiz foydalanuvchi /start komandasidan boshqa narsa yozgan bo'lsa
    }else if ($text != "/start"){
        // Foydalanuvchidan kelgan habarni javob bera olishi uchun managerga yuboramiz.
        bot('forwardMessage', [ // maxsus bot funksiyamiz orqali forwardMessage ga
            'chat_id' => $Manager, //Manager id raqami va
            'from_chat_id' => $chat_id, // foydalanuvchi bilan bot o'rtasidagi chat id raqami
            'message_id' => $message_id, // va foydalanuvchi yuborgan habar id raqamini yuboramiz.
        ]);
    }
    // Yoki agar $Manager yozgan bo'lsa ushbu kod qismiga kiramiz
} if($chat_id == $Manager){
    // Agar manager bot qayta yuborgan hatga javob berish orqali habar yuborsa,
    if(isset($reply_to_message)){
        // Manager habarini bot qayta yuborgan habar egasiga bot nomidan yuboramiz
        bot('sendmessage', [ // maxsus bot funksiyamiz orqali sendmessage ga
            'chat_id' => $chat_id, // bot qayta yuborgan habar id raqami va
            'text' => $Manager_reply_text, // manager yuborgan habarni
            'parse_mode' => "HTML", //html formatda yuboramiz.
        ]);
    }
    // Manager profilidan botni tekshirib ko'rish uchun botdan managerga salom !
    if($text == "Hello" or $text == "/start"){
        bot('sendmessage', [
            'chat_id' => $Manager,
            'text' => "Salom Manager! 🫡",
        ]);
    }
}