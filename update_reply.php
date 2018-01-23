<?php require_once("connection/connection.php"); ?>

<?php

   if (isset($_POST["reply_id"])){
        $topic_id = $_POST["topic_id"];
        $reply_id = $_POST["reply_id"];
    }else if ( isset($_GET["reply_id"]) ) {
        $topic_id = $_GET["topic_id"]; 
        $reply_id = $_GET["reply_id"];
    }else {
        header("Location: replies.php");
    }
    

    if( isset($_POST["reply_id"]) ) {
        $reply = utf8_decode($_POST["reply"]);
        
        $error_update = False;
        if(empty($reply) ){
            $error_update = True;
            $messageUpdate = "Message field need value";   
        }
        
        if ($error_update==False){
            // Objeto para alterar
            $query_1 = "UPDATE replies SET reply = '$reply' , date = NOW() WHERE reply_id = $reply_id ";

            $update = mysqli_query($connect, $query_1);
            $error_update = False;
            if($update) {
                 $messageUpdate = "Message updated successfully";
            }else{
                $error_update = True; 
                $messageUpdate = "Error to update Message";
            }
        }
        
    }

    // Getting data to show the user
    $query_2 = "SELECT * FROM replies WHERE reply_id = $reply_id ";
    
    $result_query_2 = mysqli_query($connect,$query_2);
    if(!$result_query_2) {
        die("Error to get data from replies");
    }

    $data_query_2 = mysqli_fetch_assoc($result_query_2);

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
            <div id="janela_formulario">
                <form action="update_reply.php" method="post">
                    <h2>Edit Topic</h2>
                    
                    <label for="reply">Message</label>
                    <textarea name="reply" id="reply" cols="70" rows="6"><?php echo utf8_encode($data_query_2["reply"])  ?></textarea><br/>        

                    <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                    <input type="hidden" name="reply_id" value="<?php echo $reply_id ?>">
                    <input type="submit" value="Save">    
                    
                    <div class="clean"></div>
                    <?php
                        if( isset($error_update) ) {
                            if($error_update == False){
                                echo "<p style='background:green ; width: 200px; display:inline-block'>" . $messageUpdate . "</p>";
                            }else{
                                echo "<p style='width: 200px; display:inline-block'>" . $messageUpdate . "</p>";
                            }
                            echo "<div class='clean'></div>";
                        }

                           echo "<h5 class='button'> <a href='replies.php?topic_id=$topic_id'> Return </a> </H5>";
                    
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