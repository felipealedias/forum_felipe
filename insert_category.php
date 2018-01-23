<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["name"])){
        
        $name = utf8_decode($_POST['name']); 
        $description = utf8_decode($_POST['description']);
        $user_id = $_SESSION["userid"];
        
        $insert_error = False;
        if(empty($name) or empty($description) ){
            $insert_error = 1;
            $messageInsert = "All fields need value!";   
        }
        
        if ($insert_error==False){

            // Insert data to database
            $query = "INSERT INTO categories (name,description,user_id,date) VALUES ";
            $query .= "('$name' , '$description' , $user_id , NOW() )";

            $insert = mysqli_query($connect,$query);
            if (!$insert){
                $messageInsert = "Fail to insert new category";   
            } else {
                $messageInsert = "Category inserted successfully";
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
                <form action="insert_category.php" method="post" enctype="multipart/form-data">
                    <h2>Insert New Category</h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="description" placeholder="Description">                                        
                    <!-- campo de estoque -->
                                        
                    <!-- botao submit -->
                    <input type="submit" value="Insert new Category">
                
                    <?php
                        if( isset($insert_error) ) {
                            if($insert_error == False){
                                echo "<p style='background:green'>" . $messageInsert . "</p>";
                            }else{
                                echo "<p>" . $messageInsert . "</p>";
                            }
                        }
                        echo "<h5> <a class='button' href='categories.php'> Return </a> </H5>";
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