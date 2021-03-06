<!--shinya-->

<?php
session_start();

function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken()
{
    if (empty($_SESSION['token'])) {
        echo "Sessionが空です";
        exit;
    }

    if (($_SESSION['token']) !== $_POST['token']) {
        echo "不正な投稿です。";
        exit;
    }

    $_SESSION['token'] = null;
}

if (empty($_SESSION['token'])) {
    setToken();
}
?>

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

const THREAD_FILE = 'thread.txt';

require_once './thread.php';
$thread = new Thread('掲示板App');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["method"]) && $_POST["method"] === "DELETE") {
        $thread->delete();
    } else {
        $thread->post($_POST['personal_name'], $_POST['contents']);
    }

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}

echo $thread->getList();

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>