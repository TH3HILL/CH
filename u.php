<?php
date_default_timezone_set("Asia/Baghdad");
require 'vendor/autoload.php';
function bot($method,$datas=[]){
    $datas = http_build_query($datas);
    $url = "https://api.telegram.org/bot".file_get_contents('token')."/".$method."?$datas";
    $get = file_get_contents($url);
    return json_decode($get);
}
    

$settings['app_info']['api_id'] = 210897;
$settings['app_info']['api_hash'] = 'c7d2d161d83ce18d56c1a8a54437f5ff';
$MadelineProto = new \danog\MadelineProto\API('sj.madeline', $settings);
$MadelineProto->start();
$type = file_get_contents("type");
$users = explode("\n", file_get_contents('users'));
if($type == "c"){
    $channel = $MadelineProto->channels->createChannel(['broadcast' => true,'megagroup' => false,'title' => file_get_contents("channelName"), ])['updates'][1];;
    $i = 0;
    while(1){
        foreach($users as $user){
            try{
            	$MadelineProto->messages->getPeerDialogs(['peers' => [$user]]);
            } catch (Exception $e) {
                try {
                    $MadelineProto->channels->updateUsername(['channel' => $channel, 'username' => $user]);
                    bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Done, @$user\n- Loops : $i\n- Moved to: Channel",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                    $MadelineProto->messages->sendMessage(['peer' => $channel, 'message' => file_get_contents('channelName')]);
                    file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                    file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                    exit;
                } catch(Exception $ee){
                    if($ee->getMessage() == "The provided username is not valid"){
                            file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                            file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                            bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Username banned : @$user",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                            exit;
                            }elseif($ee->getMessage() == "USERNAME_OCCUPIED"){
                                file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                                file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                                bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Username not save : @$user",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                                exit;
                            }else{
                              bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>$ee->getMessage(),json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                              exit;
                            }
                }
            }
            $i++;
            echo "- [@$user] : $i : [".date('i:s')."]\n";
        }
    }
}
if($type == "a"){
    $i = 0;
    while(1){
        foreach($users as $user){
            try{
            	$MadelineProto->messages->getPeerDialogs(['peers' => [$user]]);
            } catch (Exception $e) {
                try {
                    $MadelineProto->account->updateUsername([ 'username' => $user]);
                    bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Done, @$user\n- Loops : $i\n- Moved to: Account",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                    file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                    file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                    exit;
                } catch(Exception $ee){
                    if($ee->getMessage() == "The provided username is not valid"){
                            file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                            file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                            bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Username banned : @$user",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                            exit;
                            }elseif($ee->getMessage() == "USERNAME_OCCUPIED"){
                                file_put_contents('users', str_replace($user, '', file_get_contents('users')));
                                file_put_contents('users',preg_replace('~[\r\n]+~',"\n",trim(file_get_contents('users'))));
                                bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>"- Username not save : @$user",json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                                exit;
                            }else{
                              bot('sendMessage',['chat_id'=>file_get_contents('adminID'),'text'=>$ee->getMessage(),json_encode(['inline_keyboard'=>[[['text'=>'Run','callback_data'=>'run']]]])]);
                              exit;
                            }
                }
            }
            $i++;
            echo "- [@$user] : $i : [".date('i:s')."]\n";
        }
    }
}