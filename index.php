<!--shinya-->

<html>
<head>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<title>掲示板</title>


</head>
<body>

<h1 class="text-center">掲示板App</h1>

<h2 class="text-center">投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>" class="text-center">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する" class="btn btn-primary">
</form>

<h2 class="text-center">スレッド</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF'])?>" class="text-center">
    <input type="hidden" name="method" value="DELETE">
    <button type="submit" class="btn btn-danger">投稿内容を全削除</button>
</form>

<?php

//data_default_timzone_set('Asia/Tokyo');
const THREAD_FILE = 'thread.txt';

function readData() {
    // ファイルが存在しなければデフォルト空文字のファイルを作成する
    if (! file_exists(THREAD_FILE)) {
        $fp = fopen(THREAD_FILE, 'w');
        fwrite($fp, '');
        fclose($fp);
    }

    $thread_text = file_get_contents(THREAD_FILE);
    echo $thread_text;
}

function writeData() {
    $personal_name = $_POST['personal_name'];
    $contents = $_POST['contents'];
    $contents = nl2br($contents);

    $data = "<hr>\n";
    $data = $data."<p>投稿日時:".date('Y/m/d H:i:s')."</p>\n";
    $data = $data."<p>投稿者:".$personal_name."</p>\n";
    $data = $data."<p>内容:</p>\n";
    $data = $data."<p>".$contents."</p>\n";

    $fp = fopen(THREAD_FILE, 'a');

    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp,  $data) === FALSE){
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }else{
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);

    
}

function clearData () {
    file_put_contents(THREAD_FILE, "");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["method"]) && $_POST["method"] === "DELETE"){
        clearData();
    } else {
        writeData();
    }

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}
readData();

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>