<!DOCTYPE html>
<html lang="ja">
<head>
    <meta name="viewport" 
    content="width=320, height=480,initial-scale=1.0, 
                minimum-scale=1.0, maximum-scale=2.0,
                user-scalable=yes"
    >
    <meta charset="UTF-8">
    <title>掲示板</title>
    <style>
        body{
            background-color: #99FFFF;
        }
        
        form dl dt{
            width: 100px;
            float: left;
        }
    </style>
</head>
<body>
    <?php
        //データベースアクセス
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user,$password,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                    
        //作成
        $sql = "CREATE TABLE IF NOT EXISTS mission52"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pass TEXT"
        . ");";
        $stmt = $pdo->query($sql);

        
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["toukoupass"])) {
            if(empty($_POST["toukouNO"])) {
                $sql = $pdo -> prepare("INSERT INTO mission52 (name, comment, date, pass) 
                                        VALUES (:name, :comment, :date, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = date("Y/m/d H:i:s");
                $pass = $_POST["toukoupass"];
                $sql -> execute();
            } else {
                $id = $_POST["toukouNO"];
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $date = date("Y/m/d H:i:s");
                $pass = $_POST["toukoupass"];                                                                     
                $sql = 'UPDATE mission52 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        if(!empty($_POST["sakujo"]) && !empty($_POST["sakujopass"])) {
            $id = $_POST["sakujo"];
            $sakujopass = $_POST["sakujopass"];
            $sql = 'SELECT * FROM mission52';
            $stmt = $pdo->query($sql);
            $results = $stmt -> fetchAll();
            foreach ($results as $row){
                if($row['id'] == $id && $row['pass'] == $sakujopass){
                    $sql = 'delete from mission52 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
              
        if(!empty($_POST["edit"]) && !empty($_POST["editpass"])) {
            $edit = $_POST["edit"];
            $editpass = $_POST["editpass"];
            $sql = 'SELECT * FROM mission52';
            $stmt = $pdo->query($sql);
            $results = $stmt -> fetchAll();
            foreach ($results as $row){
                if($row['id'] == $edit && $row['pass'] == $editpass) {
                    $editnumber = $row['id'];
                    $editname = $row['name'];
                    $editkome = $row['comment'];
                }
            }
        }
    ?>
    <h1>掲示板</h1>
    <form action = " " method = "post" autocomplete = "off">
        <dl>
            【 投稿フォーム 】<br>
            <input type = "hidden" name = "toukouNO" placeholder = "投稿番号" value = "<?php echo $editnumber;?>"> 
            <dt>名前:</dt><input type = "text" name = "name" value = "<?php echo $editname;?>"><br>
            <dt>コメント:</dt><input type = "text" name = "comment" value = "<?php echo $editkome;?>"><br>
            <dt>パスワード:</dt><input type = "text" name = "toukoupass"><br>
            <input type = "submit" name = "submit"><br><br>
            【 削除フォーム 】<br>
            <dt>削除番号:</dt><input type = "text" name = "sakujo"><br>
            <dt>パスワード:</dt><input type = "text" name = "sakujopass"><br>
            <input type = "submit" value = "削除"><br><br>
            【 編集フォーム 】<br>
            <dt>編集番号:</dt><input type = "text" name = "edit"><br>
            <dt>パスワード:</dt><input type = "text" name = "editpass"><br>
            <input type = "submit" value = "編集"><br><br>
        </dl>
    </form>
    <?php
        echo "<hr>";
        echo "【 投稿一覧 】<br>";
        $sql = 'SELECT * FROM mission52';
        $stmt = $pdo->query($sql);
        $results = $stmt -> fetchAll();
        foreach ($results as $row){
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']."<br>";
        }
    ?>
</body>
</html>