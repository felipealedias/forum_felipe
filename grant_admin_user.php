<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    if (isset($_POST["user_id"])){
        $user_id    = $_POST["user_id"];
        $adminuser  = $_POST["adminuser"];
    }elseif ( isset($_GET["user_id"]) ) {
        $user_id    = $_GET["user_id"];
        $adminuser  = $_GET["adminuser"];
    }else{
         header("Location: manage_users.php");
    }
    

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["No"])){
      header("Location: manage_users.php");
    } else if (isset($_POST["Yes"])){
      // Grant or Remove admin rights
       
        $query = "UPDATE users set adminuser = ";
        if ($adminuser==True){
            $query .= " 0 ";
        }else{
            $query .= " 1 ";
        }
        $query .= " WHERE user_id = $user_id";
        
        $update = mysqli_query($connect,$query);
     
        $update_error = False;
        if (!$update){
            $update_error = True;
            $messageupdate = "Fail to update admin rights";   
        } else {
            $messageupdate = "Admin rights updated successfuly";
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
        <link href="_css/insert.css" rel="stylesheet">
    </head>

    <body>
        <?php include_once("_add/header.php"); ?>
        
        <main>
            
            <div id="janela_formulario" style="text-align:center">
                <form action="grant_admin_user.php" method="post" enctype="multipart/form-data">
                    <h2>Are you Sure?</h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="submit" id="Yes" name="Yes" value="Yes" style="width: 100px ; display:inline-block">
                    <input type="submit" id="No" name="No" value="No" style="width: 100px; display:inline-block">           
                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <input type="hidden" name="adminuser" value="<?php echo $adminuser ?>">
                    
                    <?php
                        if($adminuser==True){
                            echo "<h4> Admin Rights will be removed!</h4>";
                        }else{
                            echo "<h4> Admin Rights will be granted!</h4>";
                        }
                    ?>
                    
                    <div class="clean"></div>
                    <?php
                        if( isset($update_error) ) {
                            if($update_error == False){
                                echo "<p style='background:green ; width: 200px; display:inline-block'>" . $messageupdate . "</p>";
                            }else{
                                echo "<p style='width: 200px; display:inline-block'>" . $messageupdate . "</p>";
                            }
                            
                            echo "<h5> <a class='button' href='manage_users.php'> Return </a> </H5>";
                        }
                    
                    ?>
                    
                </form>
                
            </div>

        </main>

        <?php include_once("_add/footer.php"); ?>  
    </body>
</html>

<?php
    // Close connection
    mysqli_close($connect);
?>