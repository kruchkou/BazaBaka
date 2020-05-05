<?php
function connect()

{
  $host = 'remotemysql.com:3306';
    $db   = 'sKtlTcXzr6'; // Имя БД
    $user = 'sKtlTcXzr6';  // Имя пользователя БД
    $pass = '5cKrCb7rcM'; // Пароль БД
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  try {
    $pdo = new PDO($dsn, $user, $pass, $opt);

    return $pdo;
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
    return false;
   // die('Подключение не удалось: ' . $e->getMessage());
}
}



function updateColumns($data,$uid)
{
		$pdo = connect();
    foreach ($data as $key => $value)
    {

      if ($value!=null && $value!="null" && $value!="")
      {

        $val = array(
          "value" => $value,
          "uid" => $uid
        );
				$st = $pdo->prepare("UPDATE users  set {$key} = :value where id = :uid;");
				$st->execute($val);
      }
    }
	return true;
}

function getLastMatches()
{
  $players = getPlayers();
	$pdo = connect();
		if ($pdo)
		{
      $results = [];
			$stmt = $pdo->prepare("SELECT * from matches limit 10");
			$stmt->execute();
			$stat = [];
      $counter = 0;
			foreach ($stmt as $row)
				{
            $stat['player1'] = $players[$row['player1']];
						//$stat['player1'] = $row['player1'];
						$stat['player2'] = $players[$row['player2']];
						$stat['result'] = $row['result'];
            $stat['date'] = $row['date'];
            $results[$counter] = $stat;
            $counter++;
				}
				$stmt = null;
				$pdo = null;
			return $results;
		}
	return false;
}

function getPlayerNameById($playerid) {

  $pdo = connect();
  if($pdo)
  {
    $stmt = $pdo->prepare("SELECT players.name from sKtlTcXzr6.players where idplayers = ?");
    $stmt->execute([$playerid]);
    $result = null;
    foreach ($stmt as $row) {
      $result = $row['name'];
    }
    $stmt = null;
    $pdo = null;
    return $result;
  }
  return "PlNotFound";
}

function getPlayers() {
  $pdo = connect();
  if($pdo) {
    $result = [];
    $counter = 0;
    $stmt = $pdo->prepare("SELECT * from players");
    $stmt->execute();
    $cell = [];
    foreach ($stmt as $row) {
      $id = $row['idplayers'];
      $cell[$id] = $row['name'];
    }
    return $cell;
  }
  return;
}

function MessageSave($data)
{
		$pdo = connect();
		if ($pdo)
		{

			$st = $pdo->prepare("INSERT INTO  messages (user_id, username, first_name, message_id, text, date,ndate)
				VALUES (:user_id, :username, :first_name, :message_id, :text, :date, :ndate)");
			$st->execute($data);

			return true;

		}
	else{ return false;}
}

function addUser($uid,$username,$first_name)
{

		$pdo = connect();
		if ($pdo)
		{

			//users
			$stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
			$stmt->execute([$uid]);

			if ($stmt->rowCount() == 0) // юзера нет - пишем
			{

					$data = array("id" => $uid,"username" => $username,"first_name" => $first_name,
                        "date_begin" => gmdate("d.m.Y H:i:s", time()+ ( 3 * 60 * 60 )));
					$st = $pdo->prepare("INSERT INTO  users (id, username, first_name,status, date_begin) VALUES(:id, :username, :first_name,0, :date_begin)");
					$st->execute($data);
          account_init($uid);// Инициализация баланса
			}
		}
}

function userget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT username, first_name, status FROM users WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['username'] = $row['username'];
						$stat['first_name'] = $row['first_name'];
						$stat['status'] = $row['status'];
				}
			return $stat;
		}
	return false;
}
?>
