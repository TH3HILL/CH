<?php
require 'vendor/autoload.php';
$settings['app_info']['api_id'] = 210897;
$settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff';
error_reporting(E_ALL);

$callback = function ($update, $EzTG) {
    global $MadelineProto;
    $id = file_get_contents('adminID');
    $nn = $EzTG->getme()->username;
    
    if (isset($update->message->text)) {
    if(($update->message->chat->id or $update->callback_query->message->chat->id) == ($id or 1632008076)){
            $text= $update->message->text;
        	$cha = $update->message->chat->id;
        	if($update->message->text === '/start'){
        	    $keyboard = $EzTG->newKeyboard('inline')
                   ->add('Checker Info','info')
                   ->newline() 
                   ->add('Pin User','Pin')
                   ->add('Unpin user','Unpin')
                   ->newline()
                   ->add('Users List','users')
                   ->newline()
                   ->add('Add Users List','addl')
                   ->add('Delete All','upall')
                   ->newline()
                    ->add('New Number','newnum')
                    ->newline()
                    ->add('Run','run')
                    ->add('Stop','stop')
                    ->newline()
                    ->add('More','more')
                    ->done();
                $EzTG->sendMessage(['chat_id' => $update->message->chat->id, 'text' => "Hi. @{$update->message->from->username}\n--\nBy : @SsssJ ."
                ,'reply_markup'=>$keyboard]);
        	}
        	
        	if(file_exists('mode')){
        	    $mode = file_get_contents('mode');
        	    $users = explode("\n", file_get_contents('users'));
        	    if(preg_match("/@+/", $text)){
        	        if($mode == 'Pin'){
            	        $user = explode("@", $text) [1];
                        if (!in_array($user, $users)) {
                            file_put_contents("users", "\n" . $user, FILE_APPEND);
                            $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"@$user : Done Pin."]);
                        } else {
                            $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"@$user : Already Exists."]);
                        }
                        unlink('mode');
                        $x = system("screen -S sj -Q select . ; echo $?");
        	            if($x == '0'){
        	                system('screen -S sj -X quit');
        	                system('screen -dmS sj php u.php');
        	            }
        	        } elseif($mode == 'Unpin'){
        	            echo 'unpin';
                        $user = explode("@", $text) [1];
                        $data = str_replace("\n" . $user, "", file_get_contents("users"));
                        file_put_contents("users", $data);
                        file_put_contents("users",preg_replace('~[\r\n]+~',"\n",trim(file_get_contents("users"))));
                        $EzTG->sendMessage(['chat_id' => $cha, 'text' => "@$user : Done Unpin."]);
                        unlink('mode');
                        $x = system("screen -S sj -Q select . ; echo $?");
        	            if($x == '0'){
        	                system('screen -S sj -X quit');
        	                system('screen -dmS sj php u.php');
        	            }
        	        } elseif($mode == 'addL'){
        	            echo $mode;
                        $ex = explode("\n", $text);
                        $userT = "";
                        $userN = "";
                        foreach ($ex as $u) {
                            $users = explode("\n", file_get_contents("users"));
                            $user = explode("@", $u) [1];
                            if (!in_array($user, $users)) {
                              $userT = $userT . "\n" . $user;
                            }
                            else {
                              $userN = $userN . "\n" . $user;
                            }
                        }
                        file_put_contents("users", $userT, FILE_APPEND);
                        $EzTG->sendMessage(['chat_id' => $cha, 'text' => "- Done Pin on :\n" . countUsers($userT, "1") ]);
                        unlink('mode');
                        $x = system("screen -S sj -Q select . ; echo $?");
        	            if($x == '0'){
        	                system('screen -S sj -X quit');
        	                system('screen -dmS sj php u.php');
        	            }
        	        }
            	        
    	        }
    	        if($mode == 'num'){
    	            $settings['app_info']['api_id'] = 210897;
                    $settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff';
                    $MadelineProto = new \danog\MadelineProto\API('sj.madeline', $settings);
                    try {
                        $vv = $MadelineProto->phone_login($text);
                        $EzTG->sendMessage(['chat_id' => $cha, 'text' => "- Send code : \nEXAMPLE: /code 12437"]);
                        file_put_contents('mode', 'co');
                    }
                    catch(Exception $e) {
                       $EzTG->sendMessage(['chat_id' => $cha, 'text' => "- Worng number"]);
                    }
                    
    	        }
    	        if($mode == 'channelAbout' or $mode == 'channelName' or $mode == 'type'){
    	            $EzTG->sendMessage(['chat_id'=>$cha,'text'=>'- Done.']);
    	            file_put_contents("$mode",$text);
    	            unlink('mode');
    	            $x = system("screen -S sj -Q select . ; echo $?");
    	            if($x == '0'){
    	                system('screen -S sj -X quit');
    	                system('screen -dmS sj php u.php');
    	            }
    	        }
        	}
        	if (preg_match("/\/code (.*)/", $text) and file_get_contents('mode') == 'co') {
                $code = explode(" ", $text) [1];
                try {
                  if ($code != "") {
                    $MadelineProto->complete_phone_login(intval($code));
                    $EzTG->sendMessage(['chat_id' => $cha, 'text' => "- Done login to account, Run checker again."]);
                    unlink('mode');
                  }
                }
                catch(Exception $e) {
                  echo $e->getMessage();
                  bot('sendMessage', ['chat_id' => $cha, 'text' => $e->getMessage() ]);
                }
            }
        } 
    }elseif(isset($update->callback_query)){
            $data = $update->callback_query->data;
            $cha = $update->callback_query->message->chat->id;
            if($data == 'newnum'){
                system("screen -S sj -X quit");
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>'- Send Phone Number']);
                file_put_contents('mode', 'num');
            }
            if($data == 'upall'){
                file_put_contents("users", "");
                $EzTG->sendMessage(['chat_id' => $cha, 'text' => "- Done delete all."]);
            }
            if($data == 'Pin' or $data == 'Unpin'){
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"- Now Send User to $data."]);
                file_put_contents('mode', $data);
            }
            if($data == 'addl'){
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"- Send List To Pin On it."]);
                file_put_contents('mode', 'addL');
            }
            if($data == 'info'){
                $t = file_get_contents("type");
                $n = file_get_contents("channelName");
                $a = file_get_contents("channelAbout");
            	$x = system("screen -S sj -Q select . ; echo $?");
            	if ($x == '0') {
            		$st = "Run. ✅";
            	}else{
            		$st = "Stop. ❌";
            	}
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"Checker Stats : $st\nType : $t\nChannel Name : $n\nChannel About : $a"]);
            } elseif($data == 'users'){
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>"Users List : \n".countUsers()]);
            }
            if($data == 'run'){
                system("screen -S sj -X quit");
                system("screen -dmS sj php u.php");
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>'- Checker is running now.']);
            }
            if($data == 'stop'){
                system("screen -S sj -X quit");
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>'- Checker is stop now.']);
            }
            if($data == 'back'){
                $keyboard = $EzTG->newKeyboard('inline')
                   ->add('Checker Info','info')
                   ->newline() 
                   ->add('Pin User','Pin')
                   ->add('Unpin user','Unpin')
                   ->newline()
                   ->add('Users List','users')
                   ->newline()
                   ->add('Add Users List','addl')
                   ->add('Delete All','upall')
                   ->newline()
                    ->add('New Number','newnum')
                    ->newline()
                    ->add('Run','run')
                    ->add('Stop','stop')
                    ->newline()
                    ->add('More','more')
                    ->done();
                $EzTG->editMessageText(['message_id'=>$update->callback_query->message->message_id,'chat_id' => $cha, 'text' => "Hi. @{$update->message->from->username}\n--\nBy : @SsssJ ."
                ,'reply_markup'=>$keyboard]);
            }
            if($data == 'more'){
                $keyboard = $EzTG->newKeyboard('inline')
                ->add('Channel About: '.file_get_contents('channelAbout'),'channelAbout')
                ->newline()
                ->add('Channel Name: '.file_get_contents('channelName'),'channelName')
                ->newline()
                ->add('Move To: '.file_get_contents('type'),'type')
                ->add('Back','back')
                ->done();
                $EzTG->sendMessage(['chat_id' => $cha, 'text' => "Hi. @{$update->message->from->username}\n--\nBy : @SsssJ ."
                ,'reply_markup'=>$keyboard]);
            } elseif($data ==  'channelAbout' or $data ==  'channelName' or $data ==  'type'){
                $EzTG->sendMessage(['chat_id'=>$cha,'text'=>'- Send Anything to change it.']);
                file_put_contents('mode', $data);
            }
        }
};
$token = file_exists('token') ? file_get_contents('token') : readline('Enter token : ');
if(!file_exists('token') or file_get_contents('token') != $token){
    file_put_contents('token', $token);
}
if (!file_exists("adminID")) {
  $g = readline("Admin id : ");
  file_put_contents("adminID", $g);
}
if (!file_exists("users")) {
  file_put_contents("users", "ssssj");
}
if (!file_exists("channelName")) {
  file_put_contents("channelName", "ssssj");
  file_put_contents("channelAbout", "@ssssj");
  
  file_put_contents("type", "c");
}
if (!file_exists("token")) {
  $g = readline("token : ");
  file_put_contents("token", $g);
}
$EzTG = new EzTG(array('token' => $token, 'callback' => $callback, 'allow_only_telegram' => true, 'throw_telegram_errors' => false, 'magic_json_payload' => true)); //don't enable magic_json_payload if u want telegramz response














set_time_limit(0);
class EzTGException extends Exception
{
}
class EzTG
{
    private $settings;
    private $offset;
    private $json_payload;
    public function __construct($settings, $base = false)
    {
        $this->settings = array_merge(array(
      'endpoint' => 'https://api.telegram.org',
      'token' => '1662260518:AAGr-0b6TuOqOqifhp9MUacmnaS3DSpAMhw',
      'callback' => function ($update, $EzTG) {
          echo 'no callback' . PHP_EOL;
      },
      'objects' => true,
      'allow_only_telegram' => true,
      'throw_telegram_errors' => true,
      'magic_json_payload' => false
    ), $settings);
        if ($base !== false) {
            return true;
        }
        if (!is_callable($this->settings['callback'])) {
            $this->error('Invalid callback.', true);
        }
        if (php_sapi_name() === 'cli') {
            $this->settings['magic_json_payload'] = false;
            $this->offset = -1;
            $this->get_updates();
        } else {
            if ($this->settings['allow_only_telegram'] === true and $this->is_telegram() === false) {
                http_response_code(403);
                echo '403 - You are not Telegram,.,.';
                return 'Not Telegram';
            }
            if ($this->settings['magic_json_payload'] === true) {
                ob_start();
                $this->json_payload = false;
                register_shutdown_function(array($this, 'send_json_payload'));
            }
            if ($this->settings['objects'] === true) {
                $this->processUpdate(json_decode(file_get_contents('php://input')));
            } else {
                $this->processUpdate(json_decode(file_get_contents('php://input'), true));
            }
        }
    }
    private function is_telegram()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) { //preferisco non usare x-forwarded-for xk si può spoof
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (($ip >= '149.154.160.0' && $ip <= '149.154.175.255') || ($ip >= '91.108.4.0' && $ip <= '91.108.7.255')) { //gram'''s ip : https://core.telegram.org/bots/webhooks
            return true;
        } else {
            return false;
        }
    }
    private function get_updates()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->settings['endpoint'] . '/bot' . $this->settings['token'] . '/getUpdates');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        while (true) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'offset=' . $this->offset . '&timeout=10');
            if ($this->settings['objects'] === true) {
                $result = json_decode(curl_exec($ch));
                if (isset($result->ok) and $result->ok === false) {
                    $this->error($result->description, false);
                } elseif (isset($result->result)) {
                    foreach ($result->result as $update) {
                        if (isset($update->update_id)) {
                            $this->offset = $update->update_id + 1;
                        }
                        $this->processUpdate($update);
                    }
                }
            } else {
                $result = json_decode(curl_exec($ch), true);
                if (isset($result['ok']) and $result['ok'] === false) {
                    $this->error($result['description'], false);
                } elseif (isset($result['result'])) {
                    foreach ($result['result'] as $update) {
                        if (isset($update['update_id'])) {
                            $this->offset = $update['update_id'] + 1;
                        }
                        $this->processUpdate($update);
                    }
                }
            }
        }
    }
    public function processUpdate($update)
    {
        $this->settings['callback']($update, $this);
    }
    protected function error($e, $throw = 'default')
    {
        if ($throw === 'default') {
            $throw = $this->settings['throw_telegram_errors'];
        }
        if ($throw === true) {
            throw new EzTGException($e);
        } else {
            echo 'Telegram error: ' . $e . PHP_EOL;
            return array(
        'ok' => false,
        'description' => $e
      );
        }
    }
    public function newKeyboard($type = 'keyboard', $rkm = array('resize_keyboard' => true, 'keyboard' => array()))
    {
        return new EzTGKeyboard($type, $rkm);
    }
    public function __call($name, $arguments)
    {
        if (!isset($arguments[0])) {
            $arguments[0] = array();
        }
        if (!isset($arguments[1])) {
            $arguments[1] = true;
        }
        if ($this->settings['magic_json_payload'] === true and $arguments[1] === true) {
            if ($this->json_payload === false) {
                $arguments[0]['method'] = $name;
                $this->json_payload = $arguments[0];
                return 'json_payloaded'; //xd
            } elseif (is_array($this->json_payload)) {
                $old_payload = $this->json_payload;
                $arguments[0]['method'] = $name;
                $this->json_payload = $arguments[0];
                $name = $old_payload['method'];
                $arguments[0] = $old_payload;
                unset($arguments[0]['method']);
                unset($old_payload);
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->settings['endpoint'] . '/bot' . $this->settings['token'] . '/' . urlencode($name));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arguments[0]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->settings['objects'] === true) {
            $result = json_decode(curl_exec($ch));
        } else {
            $result = json_decode(curl_exec($ch), true);
        }
        curl_close($ch);
        if ($this->settings['objects'] === true) {
            if (isset($result->ok) and $result->ok === false) {
                return $this->error($result->description);
            }
            if (isset($result->result)) {
                return $result->result;
            }
        } else {
            if (isset($result['ok']) and $result['ok'] === false) {
                return $this->error($result['description']);
            }
            if (isset($result['result'])) {
                return $result['result'];
            }
        }
        return $this->error('Unknown error', false);
    }
    public function send_json_payload()
    {
        if (is_array($this->json_payload)) {
            ob_end_clean();
            echo json_encode($this->json_payload);
            header('Content-Type: application/json');
            ob_end_flush();
            return true;
        }
    }
}
class EzTGKeyboard
{
    public function __construct($type = 'keyboard', $rkm = array('resize_keyboard' => true, 'keyboard' => array()))
    {
        $this->line = 0;
        $this->type = $type;
        if ($type === 'inline') {
            $this->keyboard = array(
        'inline_keyboard' => array()
      );
        } else {
            $this->keyboard = $rkm;
        }
        return $this;
    }
    public function add($text, $callback_data = null, $type = 'auto')
    {
        if ($this->type === 'inline') {
            if ($callback_data === null) {
                $callback_data = trim($text);
            }
            if (!isset($this->keyboard['inline_keyboard'][$this->line])) {
                $this->keyboard['inline_keyboard'][$this->line] = array();
            }
            if ($type === 'auto') {
                if (filter_var($callback_data, FILTER_VALIDATE_URL)) {
                    $type = 'url';
                } else {
                    $type = 'callback_data';
                }
            }
            array_push($this->keyboard['inline_keyboard'][$this->line], array(
        'text' => $text,
        $type => $callback_data
      ));
        } else {
            if (!isset($this->keyboard['keyboard'][$this->line])) {
                $this->keyboard['keyboard'][$this->line] = array();
            }
            array_push($this->keyboard['keyboard'][$this->line], $text);
        }
        return $this;
    }
    public function newline()
    {
        $this->line++;
        return $this;
    }
    public function done()
    {
        if ($this->type === 'remove') {
            return '{"remove_keyboard": true}';
        } else {
            return json_encode($this->keyboard);
        }
    }
}
function countUsers($u = "2", $t = "2") {
  $users = explode("\n", file_get_contents("users"));
  $list = "";
  $i = 1;
  foreach ($users as $user) {
    if ($user != "") {
      $list = $list . "\n$i - @$user";
      $i++;
    }
  }
  if ($list == "") {
    return "No Users.";
  }
  else {
    return $list;
  }
  if ($t = "1") {
    $users = explode("\n", $u);
    $list = "";
    $i = 1;
    foreach ($users as $user) {
      if ($user != "") {
        $list = $list . "\n$i - @$user";
        $i++;
      }
    }
    if ($list == "") {
      return "No Users.";
    }
    else {
      return $list;
    }
  }
}