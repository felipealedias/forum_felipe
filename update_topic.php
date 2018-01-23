<?php require_once("connection/connection.php"); ?>

<?php

    if(isset($_GET["topic_id"]) ) {
        $category_id =  $_GET["category_id"] ; 
        $topic_id    =  $_GET["topic_id"] ; 
    }elseif (isset($_POST["topic_id"]) ) {
         $category_id  = $_POST["category_id"];
        $topic_id      =  $_POST["topic_id"] ; 
    }else{
        header("Location: topics.php");
    }
    

    if( isset($_POST["topic_id"]) ) {
        $topic        = utf8_decode($_POST["topic"]);
        $description = utf8_decode($_POST["description"]);
        
        $error_update = False;
        if(empty($topic) or empty($description) ){
            $error_update = True;
            $messageUpdate = "All fields need values";   
        }
        
        if($error_update==False){
             // Objeto para alterar
            $query_1 = "UPDATE topics SET topic = '$topic' , description = '$description' , date = NOW() WHERE topic_id = $topic_id ";

            $update = mysqli_query($connect, $query_1);
            if($update) {
                 $messageUpdate = "Topic updated successfully";
            }else{
                $error_update = True; 
                $messageUpdate = "Error to update Topic";
            }
        
        }
        
    }

    // Getting data to show the user
    $query_2 = "SELECT * FROM topics WHERE topic_id = $topic_id ";
    
    $result_query_2 = mysqli_query($connect,$query_2);
    if(!$result_query_2) {
        die("Error to get data from topics");
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
                <form action="update_topic.php" method="post">
                    <h2>Edit Topic</h2>
                    
                    <label for="topic">Topic</label>
                    <input type="text" value="<?php echo utf8_encode($data_query_2["topic"])  ?>" name="topic" id="topic">

                    <label for="description">Description</label>
                    <input type="text" value="<?php echo utf8_encode($data_query_2["description"])  ?>" name="description" id="description">              

                    <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                    <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
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
                        echo "<h5 class='button'> <a href='topics.php?category_id=$category_id'> Return </a> </H5>";
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