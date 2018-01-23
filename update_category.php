<?php require_once("connection/connection.php"); ?>

<?php

    if(isset($_GET["category_id"]) ) {
        $category_id =  $_GET["category_id"] ; 
    }elseif (isset($_POST["category_id"]) ) {
         $category_id  = $_POST["category_id"];
    }else{
        header("Location: categories.php");
    }
    

    if( isset($_POST["category_id"]) ) {
        $name        = utf8_decode($_POST["name"]);
        $description = utf8_decode($_POST["description"]);
        
        
        $error_update = False;
        if(empty($name) or empty($description) ){
            $error_update = True;
            $messageUpdate = "All fields need value!";   
        }
        
        if ($error_update==False){
            // Objeto para alterar
            $query_1 = "UPDATE categories SET name = '$name' , description = '$description' WHERE category_id = $category_id ";

            $update = mysqli_query($connect, $query_1);
            if($update) {
                 $messageUpdate = "Category updated successfully";
            }else{
                $error_update = True; 
                $messageUpdate = "Error to update data";
            }
        }
    }

    // Getting data to show the user
    $query_2 = "SELECT * FROM categories WHERE category_id = $category_id ";
    
    $result_query_2 = mysqli_query($connect,$query_2);
    if(!$result_query_2) {
        die("Error to get data from cateories");
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
                <form action="update_category.php" method="post">
                    <h2>Edit Category</h2>
                    
                    <label for="name">Name</label>
                    <input type="text" value="<?php echo utf8_encode($data_query_2["name"])  ?>" name="name" id="name">

                    <label for="description">Description</label>
                    <input type="text" value="<?php echo utf8_encode($data_query_2["description"])  ?>" name="description" id="description">              

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
                           
                           echo "<h5 class='button'> <a href='categories.php'> Return </a> </H5>";
                    
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