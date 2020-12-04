<?php

session_start();

require './log.php';

if(!empty($_POST['email']) && !empty($_POST['password'])){

    require './connect.php';

    //variable
    $email        = htmlspecialchars($_POST['email']);
    $password     = htmlspecialchars($_POST['password']);

    //ADRESS MAIL VALID
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        header('location: ./index.php?error=1&message=Votre adresse mail est invalide.');
        exit();
    };

    //CHIFFRER MOT DE PASSE
    $password = 'aq1'. sha1($password. '123'). '25';

    //ADRESS MAIL DEJA UTILISE
    $req = $db->prepare('SELECT COUNT(*) AS numberEmail FROM user WHERE email = ?');
    $req->execute(array($email));

    while($email_verification = $req->fetch()){

        if($email_verification['numberEmail'] != 1){

            header('location: ./index.php?error=1&message=Impossible de vous authentifier correctement.');
            exit();
        };
    };

    //CONNEXION
    $req = $db->prepare('SELECT * FROM user WHERE email=?');
    $req->execute(array($email));

    while($user = $req->fetch()){

        if($password == $user['password']){

            $_SESSION['connect']= 1;
            $_SESSION['email'] = $user['email'];

            //COOKIE
            if(isset($_POST['auto'])){

                setcookie('auth', $user['secret'], time()+364*24*3600, '/', null, false, true);
            };

            header('location: ./index.php?succes=1&message=Vous etes connecté.');
            exit();
        }else{
            header('location: ./index.php?error=1&message=Mot de passe incorect.');
            exit();
        };
    };


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
    <body class=>

        <?php include './header.php'; ?>

        <div class="conteneur">
            <div class="backAlign">
                <div class="blackFond">

            <?php
                if(isset($_SESSION['connect'])){ ?>
                    <div class="welcomeMessage">
                        <?php if(isset($_GET['sucess'])){

                        echo '<div style="display: inline-block;
                            margin-left: 95px; 
                            color: white; 
                            border: 1px solid green;
                            border-radius: 5px;
                            background: green;"> Vous etes desormais connecté.</div>';
                        };
                        ?>
                        <h1> Bienvenue </h1>
                        <p>Qu'allez vous faire aujourd'hui?</p>
                        <small><a href="./logout.php">Déconnexion</a></small>
                    </div>

               <?php } else{ ?>

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
                    }

                ?>
                
                <form method="post" action="./index.php">
                    <div class="form">
                        <input type="email" name="email" class="form-control"  placeholder="Votre adresse email"  required/>
                    </div>
                    <div class="form">
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe"  required/>
                    </div>
                    <div class="form">
                        <button type="submit" class="input">S'identifier</button>
                    </div>
                    <div>
                        <input type="checkbox" name="auto" class="check">
                        <label  class=checkLabel>Se souvenir de moi</label>
                    </div>
                    <p>Première visite sur notre site ?  <a href="./inscription.php">Inscrivez vous.</a></p>
                    </form>
                </div>

            </div>
        <?php }; ?>
        </div>


    </body>
</html>
