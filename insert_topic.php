<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    if (isset($_POST["category_id"])){
       $category_id = $_POST["category_id"];
    } else {
        
        if (isset($_GET["category_id"])){
            $category_id = $_GET["category_id"];
        }else{
          header("location:topics.php");  
        }  
    }
           
    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["topic"])){

        $category_id = $_POST["category_id"];
        $topic = utf8_decode($_POST['topic']); 
        $description = utf8_decode($_POST['description']);
        $user_id = $_SESSION["userid"];
        
        $insert_error = False;
        if(empty($topic) or empty($description) ){
            $insert_error = True;
            $messageInsert = "All fields need values";   
        }
        
        if($insert_error==False){
            // Insert data to database
            $query = "INSERT INTO topics (category_id,user_id,topic,description,date) VALUES ";
            $query .= "($category_id , $user_id , '$topic' , '$description' , NOW() )";

            $insert = mysqli_query($connect,$query);
            if (!$insert){
                $insert_error = True;
                $messageInsert = "Fail to insert new topic";   
            } else {
                $messageInsert = "Topic inserted successfully";
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
                <form action="insert_topic.php" method="post" enctype="multipart/form-data">
                    <h2>Insert New Topic: </h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="text" name="topic" placeholder="Topic">
                    <input type="text" name="description" placeholder="Description">                                       
                    <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                                        
                    <!-- botao submit -->
                    <input type="submit" value="Insert new Topic">
                
                    <?php
                        if( isset($messageInsert) ) {
                            if($insert_error == False){
                                echo "<p style='background:green'>" . $messageInsert . "</p>";
                            }else{
                                echo "<p>" . $messageInsert . "</p>";
                            }
                            
                        }
                                                                                                                                  
                        echo "<h5> <a class='button' href='topics.php?category_id=$category_id'> Return </a> </H5>";
                    
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