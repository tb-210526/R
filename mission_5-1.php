<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<?php
//データベースへの接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//データベース内にテーブルを作成する。
$sql = "CREATE TABLE IF NOT EXISTS keiziban"
       ."("
       ."id INT AUTO_INCREMENT PRIMARY KEY,"
       ."name char(32),"
       ."comment TEXT,"
       ."time DATETIME,"
       ."pass TEXT"
       .");";
       $stmt = $pdo->query($sql);
//投稿機能
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
    if(empty($_POST["editNO"])){//投稿
        $sql = $pdo->prepare("INSERT INTO keiziban (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql->bindParam(':time', $time, PDO::PARAM_STR);
        $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $time = date("Y-m-d H:i:s");
        $pass = $_POST["pass"];
        $sql->execute();
   }else{//編集
        $id = $_POST["editNO"];
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $time = date("Y-m-d H:i:s");
        $pass = $_POST["pass"];
        $sql = 'update keiziban set name=:name,comment=:comment,time=:time,pass=:pass where id=:id AND pass=:pass';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
       }
   }
//削除機能
if(!empty($_POST["delete"]) && !empty($_POST["delpass"])){
$id = $_POST["delete"];
$delpass = $_POST["delpass"]; 
         $sql = 'delete from keiziban where id=:id AND pass=:pass';
         $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->bindParam(':pass', $delpass, PDO::PARAM_STR);
         $stmt->execute();
}
//編集選択
if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
        $edit = $_POST["edit"];
        $editpass = $_POST["editpass"];
        //selectで取り出す
        $sql = 'SELECT * FROM keiziban';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id'] == $edit && $row['pass'] == $editpass){
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $editpass = $row['pass'];
            }
        }
    }
?>

<form action="mission_5-1.php" method="post">
<input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;}?>"><br>
<input type="text" name="comment" placeholder="コメント" size="50" value="<?php if(isset($editcomment)) {echo $editcomment;}?>"><br>
<input type="text" name="pass" placeholder="パスワード"><br>
<input type="hidden" name="editNO" value="<?php if(isset($edit)) {echo $edit;}?>">
<input type="submit" name="send" value="送信"><br>
</form>

<form action="mission_5-1.php" method="post">
<input type="text" name="delete" placeholder="削除対象番号"><br>
<input type="text" name="delpass" placeholder="パスワード"><br>
<input type="submit" name="send_delete" value="削除"><br>
</form>

<form action="mission_5-1.php" method="post">
<input type="text" name="edit" placeholder="編集対象番号"><br>
<input type="text" name="editpass" placeholder="パスワード"><br>
<input type="submit" name="send_edit" value="編集"><br>
</form>
<?php
$sql = 'SELECT * FROM keiziban';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['time'].'<br>';
                echo "<hr>";
        }
?>
</body>
</html>