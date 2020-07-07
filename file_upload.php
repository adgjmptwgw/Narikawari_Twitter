<!-- ここでは入力したデータをサーバーに送る役割がある -->
<?php
  // var_dump($_FILES);
  // exit();

// $todo = $_POST['todo'];
// $deadline = $_POST['deadline'];

// DB接続


if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  //ファイルがある ＆ ファイルにエラーがない（0） → 処理実行 

  //下記は一時保管場所
  $uploadedFileName = $_FILES['upfile']['name'];
  $tempPathName = $_FILES['upfile']['tmp_name'];
  // アップロード先のファイルは自分で決める。この場合は'upload/'
  $fileDirectoryPath = 'upload/'; 
  // pathinfoは拡張子を取得する関数。この場合はjpg。
  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
  $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
  $fileNameToSave = $fileDirectoryPath . $uniqueName;
  // var_dump($fileNameToSave);
  // exit();

  $img = '';
  if (is_uploaded_file($tempPathName)) {
    if (move_uploaded_file($tempPathName, $fileNameToSave)) {
      chmod($fileNameToSave, 0644);
      $img = '<img src={$fileNameToSave}>';
    } else {
      exit('Error:アップロードできませんでした');
    }
  } else {
    exit('Error:画像がありません');
  }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>file_upload</title>
</head>

<body>

  <?= $img ?>
</body>

</html>

