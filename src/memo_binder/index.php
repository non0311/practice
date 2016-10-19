<?php
//http://192.168.33.10/practice/memo_binder/index.php
//データベース
$user = 'non';      $password = 'qwe';
$dbName = 'memodb'; $host = 'localhost';

$dsn = "mysql:host={$host};dbName={$dbName};charset=utf8";

$pdo = new PDO($dsn,$user,$password);
echo "データベース<{$dbName}>に接続しました";
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

/**初期に実際に打ったコマンド
create database memodb;
grant all on memodb.* to non@localhost identified by 'qwe';
use memodb

create table memos (
    id int(10) unsigned not null auto_increment primary key,
  name varchar(64) not null comment '名前',
  memo text comment 'メモ',
  created timestamp null default null,
  modified timestamp null default null
);
**/

//レコード個数取得 (select.html でforで回す際に必要)
//$count_boxはレコード数(idの合計)をダイレクトで変数に入れられなかったので、
//一度、連想配列で受け取り、そこから$countに入れている
$sql_cou = "SELECT COUNT(id) FROM memodb.memos";
$stm_cou = $pdo->prepare($sql_cou);
$stm_cou->execute();
$count_box = $stm_cou->fetchAll(PDO::FETCH_ASSOC);
//print_r($count_box);
$count = $count_box[0]['COUNT(id)'];
//echo $count;

//既存レコードの取り出し
$sql_sel = "SELECT * FROM memodb.memos";
$stm_sel = $pdo->prepare($sql_sel);
$stm_sel->execute();
$result = $stm_sel->fetchAll(PDO::FETCH_ASSOC);




$form = $_POST; $error = array();
print_r($form);

//①最初の処理。postの値が何もなければ、selectを読む
if (empty($_POST)) {
    include_once("select.html");
    exit;
}

//②『編集』ボタンを押下 && save_edit が空
if (!empty($form['edit']) && empty($form['save_edit'])) {
    include_once("edit.html");
    exit;
}
//save_edit　が押下されたら  アップデート
if (!empty($form['save_edit'])){
    $upd_title = $form['title']; $upd_memo = $form['memo'];
    $sql_upd = "UPDATE memodb.memos SET name = '$upd_title' ,memo = '$upd_memo' WHERE id = 1";
    $stm_upd = $pdo->prepare($sql_upd);
    $stm_upd->execute();
}


//③『メモを追加』を押す！！！！
//$form[add] に "メモを追加" && save_add が空
if (!empty($form['add']) && empty($form['save_add'])) {
    include_once("add.html");
    exit;
}

//『保存』ボタンが押された後の処理  (テキストファイルの追加)
//$filename = "memo/" . $form['title'] . ".txt";
//$write_date = $form['memo'];
//$fileObj = new SplFileObject($filename, "wb");
//$written = $fileObj->fwrite($write_date);


//データベース保存用の変数定義
$name = $form['title'];
$memo = $form['memo'];

//save_addが押下されたら  データベース保存
if (!empty($form['save_add'])) {
    $sql = "INSERT INTO memodb.memos (name, memo) VALUES (:name, :memo)";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':name', $name, PDO::PARAM_STR);
    $stm->bindValue(':memo', $memo, PDO::PARAM_STR);
    $stm->execute();
}

//リダイレクトでpostを空にし、最初の画面へ戻る
header('Location: http://192.168.33.10/practice/memo_binder/index.php');
exit;



//ファイルの新規生成ができないため、vagrantの中の
// /etc/httpd/conf/conf.d/practice.confを作成
//
//User vagrant
//Group vagrant
//
//<Directory "/vagrant/practice">
//AllowOverride all
//    Require all granted
//</Directory>
//
//<IfModule dir_module>
//DirectoryIndex index.html index.php
//</IfModule>