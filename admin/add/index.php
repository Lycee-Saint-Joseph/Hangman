<?php
session_start();

require_once('../../config.php');

if (!isset($_SESSION['data']['connected'])) {
    header('Location: ../admin/login');
}

$servername = "localhost";
$username = DB_USER;
$password = DB_MDP;
$dbname = "pendu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<div class='alert alert-danger' role='alert'>
        Impossible d'établir la connexion avec la base de donnée.
    </div>";
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Pendu | Admninistration</title>
    <style type="text/css">
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
            <div class="col bg-dark" style="padding: 2em;">
                <h2>Ajout de données</h2>

                <?php
                if (isset($_POST['add_theme'])) {
                    $theme = $_POST['theme'];

                    $sql = "INSERT INTO themes (ID, theme) VALUES (NULL, '$theme')";

                    $conn->query($sql);

                    echo "<div class='alert alert-success' role='alert'>
                        Thème ajouté avec succès.
                    </div>";
                }

                if (isset($_POST['add_word'])) {
                    $theme = $_POST['theme'];
                    $word = $_POST['word'];

                    $sql = "INSERT INTO words (ID, word, theme) VALUES (NULL, '$word', '$theme')";

                    $conn->query($sql);

                    echo "<div class='alert alert-success' role='alert'>
                        Mot ajouté avec succès.
                    </div>";
                }
                ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="theme" name="theme" placeholder="Entrez le thème souhaité">

                        <br>

                        <button type="submit" name="add_theme" class="btn btn-primary">Créer le thème</button>
                    </div>
                </form>

                <br>

                <form action="" method="POST">
                    <div class="form-group">
                        <select name="theme" class="custom-select mr-sm-2">
                            <option selected>Choisir le thème souhaité</option>
                            <?php
                            $sql_themes = "SELECT theme FROM themes";

                            $themes_array = $conn->query($sql_themes);

                            $themes = mysqli_fetch_all($themes_array);

                            foreach ($themes as $key => $theme) {
                                echo '<option value="' . $theme[0] . '">' . $theme[0] . '</option>';
                            }
                            ?>
                        </select>

                        <br>
                        <br>

                        <input type="text" class="form-control" id="word" name="word" placeholder="Entrez le mot souhaité">

                        <br>

                        <button type="submit" name="add_word" class="btn btn-primary">Ajouter le mot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>