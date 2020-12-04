<?php

    if(isset($_COOKIE['auth']) && !isset($_SESSION['connect'])){

        //variable
        $secret = htmlspecialchars($_COOKIE['auth']);

        //verification
        require './connect.php';

        $req= $db->prepare('SELECT COUNT(*) AS numberAccount FROM user WHERE secret=?');
        $req->execute(array($secret));

        while($user= $req->fetch()){

            if($user['numberAccount'] == 1){
                $reqUser = $db->prepare('SELECT * FROM user WHERE secret=?');
                $reqUser->execute(array($secret));

                while($userAccount = $reqUser->fetch()){

                    $_SESSION['connect']= 1;
                    $_SESSION['email'] = $userAccount['email'];
                };
            };
        };

    };


?>