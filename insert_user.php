<?php require_once("connection/connection.php"); ?>
<?php include_once("_add/functions.php"); ?>

<?php
    
    // to start session
    //session_start();

    // return to login page case user is not detected
    //if (!isset($_SESSION["userid"])) {
       // header("location:login.php");
    //}

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    if (isset($_POST["name"])){
        
        $username = utf8_decode($_POST['username']); 
        $password = utf8_decode($_POST['password']); 
        $name = utf8_decode($_POST['name']);
        $email = utf8_decode($_POST['email']); 
        $useradmin = 0;
        
        $insert_error = False;
        if(empty($username) or empty($password) or empty($name) or empty($email)){
            $insert_error = True;
            $messageInsert = "All fields need values";   
        }
        
        //check if user name already exists
        $query_check = "SELECT COUNT(*) qty FROM users WHERE username = '$username' ";
        $check_user = mysqli_fetch_array(mysqli_query($connect,$query_check));
        
        if($check_user["qty"]>0){
            $insert_error = True;
            $messageInsert = "username already in use!";   
        }
        
        if ($insert_error==False){
            
            if ($_FILES['photo']!=''){
                 $publishImage1 = publishImage($_FILES['photo']);
                $returnPublishImage1 = $publishImage1[0];
                $imageProfile = $publishImage1[1];
            }else{
                $imageProfile = '';
            }

            // Insert data to database
            $query = "INSERT INTO users (username,password,name,email,adminuser,image) VALUES ";
            $query .= "('$username' , '$password' , '$name' , '$email' , $useradmin , '$imageProfile'  )";
            
            $insert = mysqli_query($connect,$query);
            if (!$insert){
                $insert_error = True;       
                $messageInsert = "Fail to insert new User";   
            } else {
                $messageInsert = "User created successfully";
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
                <form action="insert_user.php" method="post" enctype="multipart/form-data">
                    <h2>New User</h2>
                    
                    <!-- campo de nome do produto e codigo de barra -->
                    <input type="text" name="username" placeholder="Username">
                    <input type="text" name="password" placeholder="Password">
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="email" placeholder="Email">                                      
                    <!-- campo de estoque -->
                    <input type="hidden" name="MAX_FILE_SIZE" value="614400">
                    
                    <!-- campo de foto grande -->
                    <label>Photo</label>
                    <input type="file" name="photo">
                    <span class="resposta">
                        <?php
                            if( isset($returnPublishImage1) ) {
                                echo $returnPublishImage1;
                            }    
                        ?>
                    </span>
                                        
                    <!-- botao submit -->
                    <input type="submit" value="Create new User">
                
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