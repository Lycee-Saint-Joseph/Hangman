<?php
session_start();

require('../config.php');

$servername = "localhost";
$username = DB_USER;
$password = DB_MDP;
$dbname = "pendu";

$id = $_SESSION['user']['ID'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	echo "<div class='alert alert-danger' role='alert'>
		Impossible d'établir la connexion avec la base de donnée.
	</div>";
}

$word = $_SESSION['data']['word'];
$tries = $_SESSION['data']['tries'];
$wrong_tries = $_SESSION['data']['wrong_tries'];

$new_score = 13 - $wrong_tries;

$message = "Vous avez gagné $new_score points.";

if (isset($_SESSION['user'])) {
	$sql = "SELECT * FROM users WHERE ID = '$id'";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$pre_score = $row['score'];
		}
	}

	$score = intval($new_score) + intval($pre_score);

	$sql_score = "UPDATE users SET score='$score' WHERE ID='$id'";

	$conn->query($sql_score);
} else {
	$message = "Si vous étiez <a href='../login'>connecté</a>, vous auriez pu gagner $new_score points.";
}

$letters = mb_str_split($_SESSION['data']['word']);
$found = 0;

foreach ($letters as $key => $letter) {
	if (in_array($letter, $_SESSION['data']['found_letters'])) {
		$found = $found + 1;
	}
}

if ($found != count($letters)) {
	$state = "perdu";
} else {
	$state = "gagné";
}

unset($_SESSION['data']);
?>

<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<title>Pendu | Résultat</title>
	<style type="text/css">
		h3,
		h2,
		h1 {
			color: white;
		}

		body {
			background: black;
		}
	</style>
</head>

<body style="margin-top: 1em;">
	<div class="container">
		<div class="row">
			<div class="col bg-dark" style="text-align: center;">
				<h2>Vous avez <?= $state ?> en <?= $tries ?> essais dont <?= $wrong_tries ?> faux.</h2>

				<h2>Le mot était "<?= $word ?>"</h2>
				<h3><?= $message ?></h3>

				<a href="../">
					<img src="../assets/images/score/restart.gif" class="img-thumbnail" alt="Image d'un bouton 'Recommencer'">
				</a>
			</div>
		</div>
	</div>
</body>

</html>