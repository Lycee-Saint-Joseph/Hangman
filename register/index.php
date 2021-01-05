<?php
session_start();

require_once('../config.php');
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Pendu | Inscription</title>
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
                <h2>Inscription</h2>

                <?php
                if (isset($_POST['connect'])) {
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
                
                    $pseudo = $_POST['pseudo'];
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            
                    $sql = "INSERT INTO users (ID, pseudo, password, score) VALUES (NULL, '$pseudo', '$password', 0)";
            
                    $conn->query($sql);

                    echo "<div class='alert alert-success' role='alert'>
                        Inscription réussie. Vous serez redirigé dans un instant.
                    </div>
                    
                    <script>
                        setTimeout(function() { document.location.href='../login'; }, 2500);
                    </script>";
                }
                ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo">
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe">
                    </div>

                    <button type="submit" name="connect" class="btn btn-primary">S'incrire</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>