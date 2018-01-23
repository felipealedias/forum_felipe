<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    if (isset($_POST["topic_id"])){
       $topic_id = $_POST["topic_id"];
    } elseif (isset($_GET["topic_id"])){
       $topic_id = $_GET["topic_id"];
    }else{
       header("location:replies.php");  
    }  
           
    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["reply"])){

        $topic_id = $_POST["topic_id"];
        $reply = utf8_decode($_POST['reply']); 
        $user_id = $_SESSION["userid"];
        
        $insert_error = False;
        if(empty($reply) ){
            $insert_error =1;
            $messageInsert = "Message field need value";   
        }
        
        if ($insert_error==False){
            // Insert data to database
            $query = "INSERT INTO replies (topic_id,reply,user_id,date) VALUES ";
            $query .= "($topic_id , '$reply' , $user_id , NOW() )";

            $insert = mysqli_query($connect,$query);
            $insert_error = False;
            if (!$insert){
                $insert_error = True;
                $messageInsert = "Fail to insert new reply";   
            } else {
                $messageInsert = "Reply inserted successfully";
            }
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
            
            <div id="janela_formulario">
                <form action="insert_reply.php" method="post" enctype="multipart/form-data">
                    <h2>Reply: </h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <textarea name="reply" id="reply" cols="70" rows="6"></textarea><br/>
                    <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                                        
                    <!-- botao submit -->
                    <input type="submit" value="Reply to the Topic">
                
                    <?php
                        if( isset($messageInsert) ) 
                        {
                            if($insert_error == False){
                                echo "<p style='background:green'>" . $messageInsert . "</p>";
                            }else{
                                echo "<p>" . $messageInsert . "</p>";
                            }
                            
                        }
                           
                           echo "<h5> <a class='button' href='replies.php?topic_id=$topic_id'> Return </a> </H5>";
                           
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