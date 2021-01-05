<?php
session_start();

mb_internal_encoding('UTF-8');

require_once('config.php');

$servername = "localhost";
$username = DB_USER;
$password = DB_MDP;
$dbname = "pendu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['data']['word'])) {
	$sql = "SELECT * FROM themes";
	$result = $conn->query($sql);
	$theme_name = mysqli_fetch_all($result);

	$theme = ucfirst($theme_name[rand(0, count($theme_name) - 1)][1]);

	$sql_word = "SELECT word FROM words WHERE theme='$theme'";
	$result_word = $conn->query($sql_word);
	$words = mysqli_fetch_all($result_word);
	$word = $words[rand(0, count($words) - 1)][0];

	$_SESSION['data']['theme'] = $theme;
	$_SESSION['data']['word'] = $word;
	$_SESSION['data']['tries'] = 0;
	$_SESSION['data']['wrong_tries'] = 0;
	$_SESSION['data']['found_letters'] = [];
}

if (isset($_POST['choosed'])) {
	$_SESSION['data']['tries'] = $_SESSION['data']['tries'] + 1;
	$letters = mb_str_split($_SESSION['data']['word']);

	if (in_array($_POST['choosed'], $letters)) {
		array_push($_SESSION['data']['found_letters'], $_POST['choosed']);
	} else {
		$_SESSION['data']['wrong_tries'] = $_SESSION['data']['wrong_tries'] + 1;
	}
}
?>
<!doctype html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<title>Pendu | Jeu</title>
	<style type="text/css">
		h2, h1 {
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
			<div class="col-4">
				<h2>Proposez une lettre</h2>

				<form action="" method="POST">
					<div class="form-group">

						<select name="choosed" class="custom-select mr-sm-2">
							<option selected>Choisir une lettre</option>
							<?php
							$alphabet = range("a", "z");

							foreach ($alphabet as $key => $value) {
								echo '<option value="' . $value . '">' . $value . '</option>';
							}
							?>
							<option value="-">-</option>
							<option value="à">à</option>
							<option value="é">é</option>
							<option value="è">è</option>
							<option value=" ">[ESPACE]</option>
						</select>
					</div>

					<button type="submit" class="btn btn-primary" name="envoyer">Testez</button>
				</form>
			</div>
			
			<div class="col-4">
				<img src="assets/images/tries/<?php echo $_SESSION['data']['wrong_tries']; ?>.png" class="img-fluid" alt="Responsive image">
			</div>

			<div class="col-4">
				<a class="btn btn-primary" href="login">Se connecter</a>
				<a class="btn btn-primary" href="register">S'inscrire</a>
			</div>
		</div>

		<div class="row">
			<div class="col-8">
				<?php
				$theme = $_SESSION['data']['theme'];

				echo "<h2>Thème : $theme</h2>";

				if ($_SESSION['data']['wrong_tries'] === 13) {
					header('Location: score');
				} else {
					$letters = mb_str_split($_SESSION['data']['word']);
					$found = 0;

					foreach ($letters as $key => $letter) {
						if (in_array($letter, $_SESSION['data']['found_letters'])) {
							$found = $found + 1;
						}
					}

					if ($found != count($letters)) {
						foreach (mb_str_split($_SESSION['data']['word']) as $key => $letter) {
							if (in_array($letter, $_SESSION['data']['found_letters'])) {
								echo "<button type='button' class='btn btn-success'>$letter</button> ";
							} else {
								echo '<button type="button" class="btn btn-outline-primary">?</button> ';
							}
						}
					} else {
						header('Location: score');
					}
				}
				?>
			</div>
		</div>
	</div>
</body>

</html>
