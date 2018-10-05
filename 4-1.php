<?php
//接続開始
ini_set( 'display_errors', 1 );//エラーが出てきたら表示する
$dsn = 'データベース名';   //dbname=>データベース名, host=>MySQLサーバの在り処
$username = 'ユーザ名';    //DBのユーザ名
$password = 'パスワード';       //DBのパスワード
$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

//テーブル作成
//$sql = "CREATE TABLE mission4th"
//."("
//."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
//."name char(32),"
//."comment TEXT,"
//."date DATETIME,"
//."password INT"
//.");";
//$stmt = $pdo->query($sql);
?>


<?php

$name = $_POST["name"];
$comment = $_POST["comment"];
$date = date("Y/m/d H:i:s");
$pass = $_POST["password1"];

//フォームからのデータが送られてきた場合
if(!empty($name)&&!empty($comment)&&!empty($pass)){


	//SQLを準備、各テーブルにパラメータを与える
	$sql = $pdo -> prepare("INSERT INTO mission4th(name, comment, date, password) VALUES(:name, :comment, :date, :password)");
	//一つ目でパラメータ指定、二つ目でそこに入れる変数指定、三つ目で型指定
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        //DBに値を挿入(INSERTを実行)
	$sql -> execute();

}

//削除番号とパスワードが送られてきた場合
$delete=$_POST['delete'];
$password2=$_POST['password2'];
if(!(empty($delete))&&!(empty($password2)) ){
	$sql="delete from mission4th where id = $delete and password = $password2";
	$result=$pdo->query($sql);
}

//編集（ｒｙ
$edit=$_POST['edit'];
$password3=$_POST['password3'];
if(!(empty($edit))&&!(empty($password3)) ){
	$sql="SELECT*FROM mission4th where id = $edit and password = $password3";
	$results=$pdo->query($sql);
	foreach ($results as $row){
		if($edit==$row['id']){
			$editedNum = $row['id'];
			$editedName = $row['name'];
			$editedComment = $row['comment'];
		}
	}
}

if(!empty($_POST['edit2'])){
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$editNum=$_POST['edit2'];
	
	//mission4thの中で、idがHIDDENの値と同じ投稿の各カラムを更新する
	$sql = $pdo -> prepare("UPDATE mission4th SET name=:name, comment=:comment, date=:date where id=:editNum");
	$sql -> bindParam(':name' , $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment' , $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':editNum', $editNum, PDO::PARAM_STR);
	$sql -> execute();

}


?>

<!DOCTYPE html>  
<html lang="ja">  
<head>  
      <meta charset="utf-8">
</head>  
<body>  

<form action="4-1.php" method="post" >
<input type='text' name='name' placeholder='名前' value="<?php echo $editedName;?>"><br>
<input type='text' name='comment' placeholder='コメント' value="<?php echo $editedComment;?>"><br>
<input type='text' name='edit2' value="<?php echo $editedNum;?>">
<input type='text' name='password1' placeholder='パスワード' >
<input type='submit' value='送信'><br>
<input type='text' name='delete' placeholder='削除対象番号'><br>
<input type='text' name='password2' placeholder='パスワード'>
<input type='submit' value='削除'><br>
<input type='text' name='edit' placeholder='編集対象番号'><br>
<input type='text' name='password3' placeholder='パスワード'>
<input type='submit' value='編集'><br>
</form>


<?php

$sql = "SELECT * FROM mission4th order by id";
$results = $pdo -> query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].',';
	echo $row['password'].'<br>';
}
?>


</body>  
</html>