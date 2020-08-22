<?php
	require_once('pdo_conf.php');

	$text = $_POST['text'];
	$sms = $_POST['sms'];

	$stmt = $pdo->prepare(
		"INSERT INTO text_sms_table (text, sms) 
		VALUES (:text, :sms)"
	);
	$result = $stmt->execute([":text" => $text, ":sms" => $sms]);
	if ($result) {
	  echo "<span style='color:green;'>Данные успешно записаны</span>";
	} else {
	  echo "<span style='color:red;'>Невозможно создать запись</span>";
	}

?>