<?php

// var_dump($_GET);
// exit();


include('functions.php');

$user_id = $_GET['user_id'];
$todo_id = $_GET['todo_id'];

$pdo = connect_to_db();

//SELECT文はtableから好きな値を取り出す事ができる
//COUNT(*)はテーブルの値の数の事→この場合はuser_idとtodo_idの2つがある為、「2」となる。
$sql = 'SELECT COUNT(*) FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false){
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
} else {
  $like_count = $stmt->fetch();
  // header('Location:todo_read.php');
}

if ($like_count[0] != 0) {
  //既にいいねされてる場合、$sqlをここで上書きして、DELETEする
$sql ='DELETE FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id';
  //いいねされていない場合、$sqlをここで上書きして、INSERT(追加)でテーブルを追加する
} else {
$sql = 'INSERT INTO like_table(id, user_id, todo_id, created_at) VALUES(NULL, :user_id, :todo_id, sysdate())'; // 1行で記述！ 
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
} else {
  header('Location:Timeline.php');
}