﻿
<!__MySQL__>

<?php

//データベース接続

$dsn = 'aaa';
$user = 'sss';
$password = 'ddd';
$pdo = new PDO($dsn,$user,$password);

?>

<?php

header("Content-Type: text/html; charset=UTF-8");

	$name = $_POST['name'];
	$message = $_POST['message'];
	$edit_number = (int)$_POST['edit_number'];
	$password1 = $_POST['send_password'];

	$id = 1;
        $sql = "SELECT*FROM mission4_4";
        $results = $pdo->query($sql);
        foreach($results as $row){
                $id += 1;
       }

//MySQLにデータの書き込み

	if(empty($edit_number)){

		if( !empty($name) && !empty($message) && !empty($password)){

			$sql =$pdo->prepare("INSERT INTO mission4_4(id,dele,name,comment,date,password) VALUES (:id,'0',:name,:comment,:date,:password)");
			$sql->bindParam(":id",$id,PDO::PARAM_STR);
			$sql->bindValue(":name",$name,PDO::PARAM_STR);
			$sql->bindValue(":comment",$message,PDO::PARAM_STR);
			$sql->bindValue(":date",date("Y/m/d H:i:s"),PDO::PARAM_STR);
			$sql->bindValue(":password",$password1,PDO::PARAM_STR);
			$sql->execute();
		}
	}else{
		$password2 = $_POST['send_password'];
		$date = date("Y/m/d H:i:s");
		$sql = "update mission4_4 set name = '$name', comment = '$message', date = '$date', password = '$password2' where id = $edit_number";
		$results = $pdo->query($sql);
	}

	$output_name = "名前";
	$output_comment = "コメント";
	$edit_number = null;


?>
	

<?php

/*削除ボタンの操作*/

	$delete = (int)$_POST['delete'];
	$delete_password = $_POST['delete_password'];

	$sql = "SELECT * FROM mission4_4 where id = $delete";
        $result = $pdo -> query($sql);

        foreach($result as $row){
		if($delete_password == $row['password']){
			$sql = "update mission4_4 set dele = 1 where id = $delete";
			$results = $pdo->query($sql);
		}
	}

?>


<?php
/*編集ボタンの操作*/

	$edit = (int)$_POST['edit'];
	$edit_password = $_POST['edit_password'];

	$sql = "SELECT * FROM mission4_4 where id = $edit";
	$result = $pdo -> query($sql);

	foreach($result as $row){

		if(!empty($edit) and $edit_password == $row['password']){
			$output_name = $row['name'];
			$output_comment = $row['comment'];
			$edit_number = $edit;
	}else{
		$output_number = null;
		$output_name = "名前";
		$output_comment = "コメント";
	}
   }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset = "UTF-8">
	<title>
	</title>
</head>
<body>
	<form action="mission_4.php" method="post">
		<input type="text" name="name" value="<?php echo $output_name ?>"><br>
		<input type="text" name="message" value="<?php echo $output_comment; ?>"><br>
		<input type="text" name="edit_number" value="<?php echo $edit_number; ?>">
		<input type="text" name="send_password" value="パスワード">
		<input type="submit" value="送信"><br><br>
	</form>

	<form action="mission_4.php" method="post">
		<input type="text" name="delete" value="削除対象番号"><br>
		<input type="text" name="delete_password" value="パスワード">
		<input type="submit" value="削除"><br><br>
	</form>

	<form action="mission_4.php" method="post">
		<input type="text" name="edit" value="編集対象番号"><br>
		<input type="text" name="edit_password" value="パスワード">
		<input type="submit" value="編集"><br><br>
	</form>

</body>
</html>

<?php

header("Content-Type: text/html; charset=UTF-8");

//出力

$sql = "SELECT*FROM mission4_4 ORDER BY id ASC";
	$results = $pdo->query($sql);
	foreach($results as $row){
		if($row['dele'] == 0){
			echo $row['id'].",";
			echo $row['name'].",";
			echo $row['comment'].",";
			echo $row['date']."<br>";
		}
	}

	$sql = "SELECT * FROM mission4_4 ORDER BY id ASC";
	$results = $pdo->query($sql);
	foreach($results as $row){
		echo $row['id'].",";
		echo $row['dele'].",";
		echo $row['name'].",";
		echo $row['comment'].",";
		echo $row['date'].",";
		echo $row['password']."<br>";
	}

?>



