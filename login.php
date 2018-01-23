<?php require_once("connection/connection.php"); ?>
<?php

    // to start session
    session_start();

    if( isset($_POST["username"]) ){
        $username       = $_POST["username"];
        $password       = $_POST["password"];
                
        $login  = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'";
        $access = mysqli_query($connect,$login);
        
        if (!$access){
            die("Fail to obtain data from database");
        }
        
        $dataUser = mysqli_fetch_assoc($access);
        
        if (empty($dataUser)) {
            $mensagem = "Failed to Login.";
        } else {
            $_SESSION["userid"] = $dataUser["user_id"];
            $_SESSION["username"] = $dataUser["username"];
            $_SESSION["adminuser"] = $dataUser["adminuser"];
            header("location:categories.php");
        }
        
    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>FORUM</title>
        
        <!-- estilo -->
        <link href="_css/estilo.css" rel="stylesheet">
        <link href="_css/login.css" rel="stylesheet">
    </head>

    <body>
        <header>
            <div id="header_central">
                <img src="assets/forum.jpg">
            </div>
        </header>
        
        <main>  
            <div id="janela_login">
                <form action="login.php" method="post">
                    <h2>Login to forum</h2>
                    <input type="text" name="username" placeholder="Username">
                    <input type="password" name="password" placeholder="Password">
                    <input type="submit" value="login">                         
                    
                    <?php           
                        if(isset($mensagem)){
                    ?>
                    
                        <p><?php echo $mensagem; ?></p>
                    
                    <?php 
                        }
                        echo "<h5> <a class='button' href='insert_user.php'>  New User </a> </H5>";
                    ?>
                    
                </form>
            
            </div>
            
        </main>

        <footer>
            <div id="footer_central">
                <p>Forum test for VanHack.</p>
            </div>
        </footer>
    </body>
</html>

<?php
    // Close Connection
    mysqli_close($connect);
?>