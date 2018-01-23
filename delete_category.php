<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    if (isset($_POST["category_id"])){
        $category_id = $_POST["category_id"];
    }else{
        if ( isset($_GET["category_id"]) ) {
            $category_id = $_GET["category_id"];
        } else {
            header("Location: categories.php");
        }
    }
    

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:login.php");
    }

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["No"])){
      header("Location: categories.php");
    } else if (isset($_POST["Yes"])){
      // Remove data from database
        $category_id = $_POST["category_id"] ;
        $query = "DELETE FROM categories WHERE category_id = {$category_id} ";
        
        $delete = mysqli_query($connect,$query);
        if (!$delete){
            $messageDelete = "Fail to delete category";   
        } else {
            $messageDelete = "Category deleted successfully";
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
                <form action="delete_category.php" method="post" enctype="multipart/form-data">
                    <h2>Are you Sure?</h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="submit" id="Yes" name="Yes" value="Yes" style="width: 100px ; display:inline-block">
                    <input type="submit" id="No" name="No" value="No" style="width: 100px; display:inline-block">           <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                    <h4> All topics will be removed!</h4>
                    <div class="clean"></div>
                    <?php
                        if( isset($messageDelete) ) {
                            if($messageDelete == "Category deleted successfully"){
                                echo "<p style='background:green ; width: 200px; display:inline-block'>" . $messageDelete . "</p>";
                            }else{
                                echo "<p style='width: 200px; display:inline-block'>" . $messageDelete . "</p>";
                            }
                            
                            echo "<h5> <a class='button' href='categories.php'> Return </a> </H5>";
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