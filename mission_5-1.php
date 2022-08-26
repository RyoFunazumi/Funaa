<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>mission_5-1</title>
</head>
<body>
<?php

//データベースへの接続設定
$dsn = 'mysql:dbname=co_***_it_2723_com;host=localhost';
$user = 'co-***.it.2723.c';
$password = 'PASSWOR';
$pdo = new PDO($dsn, $user, $password);


//データベース内にテーブルを作成
$sql = "CREATE TABLE IF NOT EXISTS tb5"
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name TEXT,"
. "comment TEXT,"
. "date DATETIME,"
. "pass TEXT"
. ");";
$stmt = $pdo->query($sql);

//新規投稿
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["hensyu"]) && $_POST["pass"]) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/n/j G:i:s");
    $pass = $_POST["pass"];

    $sql = $pdo -> prepare("INSERT INTO tb5 (name, comment, date, pass) VALUES (:name, :comment, now(), :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql -> execute();   
}

//編集対象番号が入力された時　
if (!empty($_POST["edit"]) && $_POST["epass"]) {
    //編集対象番号を代入
    $edit = $_POST["edit"];

    $sql = "SELECT * FROM tb5 WHERE id=$edit";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        $ename=$row["name"];
        $ecomment=$row["comment"];
    }
}

?>

<!-- フォームを作って送信する　名前とコメント-->
<form action="" method="post">
<input type="text" name="name" placeholder="名前" value= "<?php if(!empty($ename)) {echo $ename;} ?>" >
<input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($ecomment)) {echo $ecomment;} ?>" >

<!-- 編集対象番号をフォームに表示させ、フォームは隠れるように-->
<input type="hidden" name="hensyu" value="<?php if(!empty($edit) && $_POST["epass"]) {echo $edit;} ?>">
<input type="password" name="pass" placeholder="パスワード">
<input type="submit" name="submit" value="送信">
</form>

<!-- 削除対象番号-->
<form action="" method="post">
<input type="number" name="delete" placeholder="削除対象番号">
<input type="password" name="dpass" placeholder="パスワード">
<input type="submit" value="削除">
</form>

<!-- 編集対象番号-->
<form action="" method="post">
<input type="number" name="edit" placeholder="編集対象番号">
<input type="password" name="epass" placeholder="パスワード">
<input type="submit" value="編集">
</form>

<?php
//編集の実行
if(!empty($_POST["hensyu"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && $_POST["pass"]) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/n/j G:i:s");
    $hensyu = $_POST["hensyu"];
    $pass = $_POST["pass"];

    $id = $hensyu;
    $sql = 'UPDATE tb5 SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();    
}

//削除対象番号が入力された時
if(!empty($_POST["delete"]) && $_POST["dpass"]) {

    $delete = $_POST["delete"];

    $id = $delete;
    $sql = 'delete from tb5 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
} 

//すべての処理のあと、入力したデータレコードを抽出し、表示する
    $sql = 'SELECT * FROM tb5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
}
?>
</body>
</html>