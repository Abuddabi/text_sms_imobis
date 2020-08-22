<?php
header('Content-type: text/html; charset=UTF-8');
error_reporting(E_ALL);
date_default_timezone_set('Europe/Moscow');

function out($arg)
{
	echo '<pre>'; var_dump($arg); die();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Form</title>
	<style>
		body{padding: 50px;}
		.mt15{margin-top: 15px;}
		.ib{display: inline-block;}
		.result_wr{margin-left: 10px;}
	</style>
	<script
		src="https://code.jquery.com/jquery-3.5.1.min.js"
		integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
		crossorigin="anonymous">
	</script>
	<script>
		// считает количество SMS
		function sms(text, count_chars){
			if (/[а-яё\^\{\}\[\]\|\\~]/i.test(text)) {
				if (count_chars == 0) count_sms = 0;
				else if(count_chars<=70) count_sms = 1;
				else count_sms = Math.ceil(count_chars / 67);
			} else {
				if (count_chars == 0) count_sms = 0;
				else if(count_chars<=160) count_sms = 1;
				else count_sms = Math.ceil(count_chars / 153);
			}
			$('#sms').val(count_sms);

			return count_sms;
		}

		function countChars(text){
			let count_chars = text.length;
			$('#result_chars').text(count_chars);

			let count_sms = sms(text, count_chars);
			
			$('#result_sms').text(count_sms);
		}

		//Массив соответствия символов
		let translit = {
			"я":"ya", //не по порядку, чтобы правильно меняло
			"Я":"Ya",
			"ё":"yo",
			"Ё":"Yo",
			"ш":"sh",
			"Ш":"Sh",
			"щ":"sch",
			"Щ":"Sch",
			"ч":"ch", 
			"Ч":"Ch",
			"ж":"zh",
			"Ж":"Zh",
			"ц":"ts",
			"Ц":"Ts",
			"х":"h",
			"Х":"H",
			"а":"a",
			"А":"A",
			"б":"b",
			"Б":"B",
			"в":"v",
			"В":"V",
			"г":"g",
			"Г":"G",
			"д":"d",
			"Д":"D",
			"е":"e",
			"Е":"E",
			"з":"z",
			"З":"Z",
			"и":"i",
			"И":"I",
			"й":"y",
			"Й":"Y",
			"к":"k",
			"К":"K",
			"л":"l",
			"Л":"L",
			"м":"m",
			"М":"M",
			"н":"n",
			"Н":"N",
			"о":"o",
			"О":"O",
			"п":"p",
			"П":"P",
			"р":"r",
			"Р":"R",
			"с":"s",
			"С":"S",
			"т":"t",
			"Т":"T",
			"у":"u",
			"У":"U",
			"ф":"f",
			"Ф":"F",
			"ъ":"'",
			"Ъ":"'",
			"ы":"i",
			"Ы":"I",
			"ь":"'",
			"Ь":"'",
			"э":"e",
			"Э":"E",
			"ю":"u",
			"Ю":"U",
			"«":"\"",
			"»":"\"",
			"–":"-",
			"—":"-",
			"№":"#",
			"`":"'",
			"\\^":"\\*",
			"\\{":"<",
			"\\}":">",
			"\\[":"<",
			"\\]":">",
			// "\\":"\/", // не получается передать обратный слеш, хотя он тоже считается за Unicode символ
			"\\|":"\/",
		}

		$(document).ready(function(){
			let text = $('#text')[0].value;
			countChars(text);
			// считает количество символов и смс
			$('#text').keyup(function(){
				let text = $(this)[0].value;
				countChars(text);
			});

			//транслит
			$('#check_box').change(function(){
				let text = $('#text')[0].value;
				if ($('#check_box').is(':checked')){
					for (let kir in translit) {
						let pattern = new RegExp(kir, 'g');
						text = text.replace(pattern, translit[kir]);
					}
					$('#text').val(text);
					countChars(text);

				} else {
					for (let kir in translit) {
						let pattern = new RegExp(translit[kir], 'g');
						text = text.replace(pattern, kir);
					}
					$('#text').val(text);
					countChars(text);

				}
			});
		});

		$(function(){
			$('#text_sms').submit(function(e){
				e.preventDefault();
				let data = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "text_sms.php",
					data: data,
					success: function(result){
						$('#result').html(result);
					}
				});
			});
		});
	</script>
</head>
<body>
	<form id="text_sms" method="post">
		<textarea name="text" id="text" cols="50" rows="15"></textarea><br>
		<label class="mt15 ib"><input id="check_box" type="checkbox" > транслитерировать</label><br>
		<input id="sms" name="sms" type="hidden">
		<input class="mt15" type="submit" name="submit" value="Отправить">
		<span class="result_wr">символов: </span><span id="result_chars">0</span>
		<span class="result_wr">SMS: </span><span id="result_sms">0</span>
	</form>
	<div id="result" class="mt15" ></div>
</body>
</html>