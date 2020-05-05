<?php
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');

include('function.php'); // подключаем настройки бота
include('webhook.php'); // подключаем  функциями
include('mysql.php');

echo get($url.'setWebhook?url='.$webhook);

if (($json = valid()) == false) { echo "Hi, bax_100 =)"; exit(); }

    $uid = $json['message']['from']['id'];
    $first_name = $json['message']['from']['first_name'];

    $ANSWER = "Салют, ".$first_name;



    $text = $json['message']['text'];

  switch($text){

    case '/help':
      $ANSWER = "Добро пожаловать в раздел Помощи!";
      $keyboard = keyboard();

    break;

    case '/reset':
      $ANSWER = "Клавиатура сброшена!";
      $keyboard = delete_keyboard();
    break;

    case '/byby':
      $ANSWER = "До встречи!";
    break;

		case 'Матчи двух игроков':
	  $players = getPlayers();
	  $ANSWER = "";

	  foreach ($players as $row) {
	     $id = $row['idplayers'];
	     $name =$row['name'];
	     $ANSWER.=strval($id)." | ".strval($name)."\n";
	  }
  //   $result = getTwoPlayersMatches("Андрей Факеев","Михаил Будников")
  // $ANSWER = "";
  // $counter = 0;
  // foreach($result as $row) {
  // $counter++;
  // $player1 = $row['player1'];
  // $player2 = $row['player2'];
  // $sets = $row['result'];
  // $date = $row['date'];
  // $ANSWER .= strval($player1)." ".strval($player2)." ".strval($sets)." ".$date."\n";
  //}
		//$ANSWER = "Захотееел, захотееел";
		break;
    // case 'Матчи двух игроков':

    // }
    // break;



		case 'Матчи игрока':
		//тут вызов функции
		$ANSWER = "Захотееел, захотееел";
		break;

		case 'Последние матчи':
		//тут вызов функции
		//$ANSWER = "Захотееел, захотееел";
    $result = getLastMatches1();
    $ANSWER = "";
    $counter = 0;
    foreach($result as $row) {
    $counter++;
    $player1 = $row['player1'];
    $player2 = $row['player2'];
    $sets = $row['result'];
    $date = $row['date'];
    $ANSWER .= strval($player1)." ".strval($player2)." ".strval($sets)." ".$date."\n";
    }
    break;


case '/start':
$ANSWER = "Чего хочешь?";
$keyboard = keyboard();
break;
}

sendMessage($uid,$ANSWER, $keyboard);


?>
