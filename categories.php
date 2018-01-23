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

    // Return Categories
    $query_1 = "SELECT categories.* , COUNT(DISTINCT topics.topic_ID) qty_topics , COUNT(DISTINCT replies.reply_id) as qty_replies FROM categories
                LEFT JOIN topics ON categories.category_id = topics.category_id 
                LEFT JOIN replies ON  topics.topic_id = replies.topic_id";
        if (isset($_POST["categorie"])){
            $categorie_Post = $_POST["categorie"];
            $query_1 .= " WHERE categories.name LIKE '%{$categorie_Post}%' OR ";
            $query_1 .= " categories.description LIKE '%{$categorie_Post}%' ";
        }
    $query_1 .= " GROUP BY categories.category_id";

    $query_result_1 = mysqli_query($connect,$query_1);
    $number_categories = mysqli_num_rows($query_result_1);

    if(!$query_result_1) {
        die("Fail to return information from database");   
    }
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
                <form action="categories.php" method="post">
                    <input type="text" name="categorie" placeholder="Search">
                    <input type="image"  src="assets/botao_search.png">
                </form>
            </div>
            
           
            
            <div class="content">
            
                    <table class="categories_table">
                       <tr>
                           <th class="forum_cat">Category</th>
                           <th class="forum_ntop">Topics</th>
                           <th class="forum_nrep">Replies</th>
                        <?php
                            if(isset($_SESSION['username']) and $_SESSION["adminuser"] == True)
                            {
                        ?>
                           <th class="forum_act">Edit</th>
                        <?php
                            }
                        ?>
                       </tr>

                        <?php
 
                        while($row_result_1 = mysqli_fetch_array($query_result_1))
                        {
                        ?>
                       <tr>
                       <td class="forum_cat">
                           <a href="topics.php?category_id=<?php echo $row_result_1['category_id']; ?>" class="title">
                               <?php echo htmlentities($row_result_1['name'], ENT_QUOTES, 'UTF-8'); ?>
                           </a>
                           <div class="description"><?php echo $row_result_1['description']; ?>
                           </div>
                       </td>
                       <td><?php echo $row_result_1['qty_topics']; ?></td>
                       <td><?php echo $row_result_1['qty_replies']; ?></td>
                       <?php
                         if(isset($_SESSION['username']) and $_SESSION["adminuser"] == True) 
                         {
                        ?>
                       <td>
                           <a href="delete_category.php?category_id=<?php echo $row_result_1['category_id']; ?>">
                               <img src="assets/delete.png" alt="Delete" />
                           </a>
                          <a href="update_category.php?category_id=<?php echo $row_result_1['category_id']; ?>">
                              <img src="assets/edit.png" alt="Edit" />
                          </a>
                        </td>
                        <?php
                        }
                        ?>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <?php
                        if(isset($_SESSION['username']) and  $_SESSION["adminuser"] == True)
                        {
                    ?>
                           <a href="insert_category.php" class="button">New Category</a>
                           <a href="manage_users.php" class="button">Manage Users</a>
                    <?php
                        }
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