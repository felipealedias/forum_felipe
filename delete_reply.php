<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    if (isset($_POST["reply_id"])){
        $topic_id = $_POST["topic_id"];
        $reply_id = $_POST["reply_id"];
    }else if ( isset($_GET["reply_id"]) ) {
        $topic_id = $_GET["topic_id"]; 
        $reply_id = $_GET["reply_id"];
    }else {
        header("Location: replies.php");
    }
    

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["No"])){
      $topic_id = $_POST["topic_id"];
      header("Location: replies.php?topic_id=$topic_id");
    } else if (isset($_POST["Yes"])){
      // Remove data from database
        $query = "DELETE FROM replies WHERE reply_id = {$reply_id} ";
        
        $delete = mysqli_query($connect,$query);
        if (!$delete){
            $delete_error = True;
            $messageDelete = "Fail to delete message";   
        } else {
            $delete_error = False;
            $messageDelete = "Message deleted successfully";
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
                <form action="delete_reply.php" method="post" enctype="multipart/form-data">
                    <h2>Are you Sure?</h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="submit" id="Yes" name="Yes" value="Yes" style="width: 100px ; display:inline-block">
                    <input type="submit" id="No" name="No" value="No" style="width: 100px; display:inline-block">           <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                    <input type="hidden" name="reply_id" value="<?php echo $reply_id ?>">
                    <div class="clean"></div>
                    <?php
                        if( isset($delete_error) ) {
                            if($delete_error == False){
                                echo "<p style='background:green ; width: 200px; display:inline-block'>" . $messageDelete . "</p>";
                            }else{
                                echo "<p style='width: 200px; display:inline-block'>" . $messageDelete . "</p>";
                            }
                            
                            echo "<h5> <a class='button' href='replies.php?topic_id=$topic_id'> Return </a> </H5>";
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