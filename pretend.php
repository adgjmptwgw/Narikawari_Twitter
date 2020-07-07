<?php
session_start();
include("functions.php");
check_session_id();

// $id = $_SESSION["id"];

// DB接続
$pdo = connect_to_db();

// データ取得SQL作成
// $sql = 'SELECT * FROM users_table';
$sql = 'SELECT * FROM users_table WHERE id';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
// $stmt->bindValue(':id', $id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    // fetchAll()関数でSQLで取得したレコードを配列で取得できる
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
    $output = "";
    // <tr><td>deadline</td><td>fake_id</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    // `.=`は後ろに文字列を追加する，の意味
    foreach ($result as $record) {
        $output .= "<tr>";
        $output .= "<td><img src='{$record["image"]}' height=20px></td>";
        // $output .= "<td> $output2 </td>";
        $output .= "<td>{$record["user_id"]}</td>";
        $output .= "<td>{$record["name"]}</td>";
        $output .= "<td><input type='button' value='{$record["id"]}' class='fake_id'></input></td>";
        $output .= "</tr>";
    }
    // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
    // 今回は以降foreachしないので影響なし
    unset($value);
}

?>

<!---------------------
HTML 要素
--------------------->
<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='styles.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <!-- jquery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <title>Narikiri_Tweet</title>
</head>

<body>
    <!-- ヘッダー -->
    <header>
        <a href='tweet.html'><img src='asset/アイコン集/矢印アイコン　左2.png' alt='' style="width:30px; height:30px;"></a>
        <img src='asset/アイコン集/ひよこのシルエットアイコン.png' alt='' style="width:30px; height:30px;">
    </header>

    <!-- なりきり選択肢 -->
    <form action="create_fake.php" method="get">
        <div id="pretenders_list">
            <table>
                <thead>
                    <tr>
                        <th>image</th>
                        <th>user_id</th>
                        <th>name</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?= $output ?>
                </tbody>
            </table>
        </div>

        <input type="text" id="fake_send" name='fake_id' value="">
        <button>決定</button>

    </form>

    <script>
        // なりきりたいプロフィールのボタンをクリックするとinputタグにvalue値が入力される
        $('.fake_id').on('click', function() {
            var value_get = $(this).attr("value");
            $('#fake_send').val(value_get);
        });
    </script>

</body>

</html>