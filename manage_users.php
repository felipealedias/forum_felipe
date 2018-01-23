<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:categories.php");
    }


    $query_1 = "SELECT user_id , username , name , email , adminuser FROM users";
    $query_result_1 = mysqli_query($connect,$query_1);

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>FORUM</title>
        
        <!-- estilo -->
        <link href="_css/estilo.css" rel="stylesheet">
        <link href="_css/style2.css" rel="stylesheet">
        <link href="_css/produtos.css" rel="stylesheet">
        <link href="_css/produto_pesquisa.css" rel="stylesheet">
    </head>

    <body>
        <?php include_once("_add/header.php"); ?>
        
        <main>
            <div id="janela_pesquisa">
                <form action="manage_users.php" method="post">
                    <input type="text" name="topic" placeholder="Search">
                    <input type="image"  src="assets/botao_search.png">
                    <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                </form>
            </div>
            
            <div class="content">

                    <table class="categories_table">
                       <tr>
                        
                           <th class="forum_cat">Username</th>
                           <th class="forum_nrep">Name</th>
                           <th class="forum_nrep">Email</th>
                           <th class="forum_act">Admin Access</th>
                       </tr>

                        <?php
 
                        while($row_result_1 = mysqli_fetch_array($query_result_1))
                        {
                        ?>
                       <tr>
                       <td class="forum_nrep">
                            <?php echo htmlentities($row_result_1['username'], ENT_QUOTES, 'UTF-8'); ?>
                       </td>
                    
                        <td class="forum_nrep">
                            <?php echo htmlentities($row_result_1['name'], ENT_QUOTES, 'UTF-8'); ?>
                       </td>
                      
                           <td class="forum_nrep">
                            <?php echo htmlentities($row_result_1['email'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                      
                       <td>
                           <?php 
                            if ($row_result_1['adminuser']==True)
                            {
                            ?>
                            <a href="grant_admin_user.php?user_id=<?php echo $row_result_1['user_id'] . "&adminuser=" . $row_result_1['adminuser']; ?>">
                               <img src="assets/apply.png" alt="Delete" />
                           </a>
                           <?php
                            } else {
                            ?>
                            <a href="grant_admin_user.php?user_id=<?php echo $row_result_1['user_id'] . "&adminuser=" . $row_result_1['adminuser']; ?>">
                              <img src="assets/delete.png" alt="Edit" />
                            </a>
                           <?php
                            }
                            ?>
            
                        </td>
                        
                           <?php
                            }
                            ?>
                           
                    </table>
                        
                        <?php
                            echo "<h5> <a class='button' href='categories.php'> Return </a> </h5>";  
                        ?>
                               
                </div>

        </main>

        <?php include_once("_add/footer.php"); ?>  
    </body>
</html>

<?php
    // Close connection
    mysqli_close($connect);
?>