<?php

$J_file = "chatlog.json";
$filesize = filesize($J_file); // 最新のファイルサイズ

$result ='';

if(isset($_GET['ajax']) && $_GET['ajax'] === "ON"){
    // ファイルサイズが違った時
    if($file = file_get_contents($J_file)){
        // 新しいチャットログのHTMLを構築
        $file = json_decode($file);
        $array = $file->chatlog;
        foreach($array as $object){
          $result =  $result.'<div class="'.$object->person.'"><p class="chat">'.str_replace("\r\n","<br>",$object->text).'<span class="chat-time">'.$object->time.'</span></p><img src="'.$object->imgPath.'"></div>';
        } 
    }
    // チャットリセットされた時もファイルサイズが一瞬違うため9行目にfalseが返ってもinputを表示させる
    $result = $result .'<input  id="preFilesize" type="hidden" value="'.$filesize.'"><input  id="aftFilesize" type="hidden" value="'.$filesize.'">';
    echo $result;
    exit;

}elseif(isset($_GET['ajax']) && $_GET['ajax'] === "OFF"){
    // ファイルサイズが同じ時
    echo $filesize; 
    exit;

}