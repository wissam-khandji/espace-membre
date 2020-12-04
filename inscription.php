<?php
    session_start();

    if(isset($_SESSION['connect'])){

        header('location: ./index.php');
        exit();
    }

    if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])){

        require './connect.php';

        //variable
        $email        = htmlspecialchars($_POST['email']);
        $password     = htmlspecialchars($_POST['password']);
        $password_two = htmlspecialchars($_POST['password_two']);

        //password = password_two
        if($password != $password_two){

            header('location: inscription.php?error=1&message=Vos mots de passe ne sont pas identiques.');
            exit();
        };

        //ADRESS MAIL VALID
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

            header('location: inscription.php?error=1&message=Votre adresse mail est invalide.');
            exit();
        };

        //ADRESS MAIL DEJA UTILISE
        $req = $db->prepare('SELECT COUNT(*) AS numberEmail FROM user WHERE email = ?');
        $req->execute(array($email));

        while($email_verification = $req->fetch()){

            if($email_verification['numberEmail'] != 0){

                header('location: inscription.php?error=1&message=Votre adress email est deja utilisé par un autre utilisateur.');
                exit();
            };
        };

        //HASH
        $secret = sha1($email).time();
        $secret = sha1($secret).time();
        
        //CHIFFRER MOT DE PASSE
        $password = 'aq1'. sha1($password. '123'). '25';


        //ENVOI
        $req= $db->prepare('INSERT INTO user(email, password, secret) VALUES(?, ?, ?)');
        $req->execute(array($email, $password, $secret));

        header('location: inscription.php?sucess=1');
        exit();

        


    };


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Connectez vous</title>
        <link rel="icon" type="image/png" href="./design/pictures/favicon/faviconPetit.png"/>
        <link rel="stylesheet" type="text/css" href="./design/style.css">
    </head>
    <body>

    <?php include './header.php'; ?>

        <div class="conteneur">
            <div class="backAlign">
                <div class="blackFond">
                <h1> S'identifier </h1>

                <?php //ERROR MESSAGE 
                    if(isset($_GET['error'])){
                        
                        if(isset($_GET['message'])){

                            echo '<div style="display: inline-block;
                                            margin-left: 95px; 
                                            color: white; 
                                            border: 1px solid red;
                                            border-radius: 5px;
                                            background: red;">'. htmlspecialchars($_GET['message']). '</div>';
                        };
                    }else if(isset($_GET['sucess'])){

                        echo '<div style="display: inline-block;
                            margin-left: 95px; 
                            color: white; 
                            border: 1px solid green;
                            border-radius: 5px;
                            background: green;"> Vous etes desormais inscrit <a href="./index.php">Connectez-vous</a>.</div>';
                    };

                ?> 

                <form method="post" action="inscription.php">
                    <div class="form">
                        <input type="email" name="email" class="form-control" placeholder="Votre adresse email" required/>
                    </div>
                    <div class="form">
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe"  required/>
                    </div>
                    <div class="form">
                        <input type="password" name="password_two" class="form-control" placeholder="Retapez votre mot de passe"  required/>
                    </div>
                    <div class="form">
                        <button type="submit" class="input">S'inscrire</button>
                    </div>
                    <p>Déjà inscrit sur notre site ?  <a href="./index.php">Connectez-vous.</a></p>
                    </form>
                </div>

            </div>
        </div>


    </body>
</html>