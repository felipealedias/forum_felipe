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

    if(isset($_GET['category_id']))
    {  
        $category_id = $_GET['category_id'];
    }else if (isset($_POST['category_id'])){
        $category_id = $_POST['category_id'];
    }else{
        header("Location: categories.php");
    }

    if(isset($category_id))
    {
    
        //$category_id = $_GET['category_id'];
        // Return topics from the categories
        $query_1 = "SELECT topics.* ,COUNT(DISTINCT replies.reply_id) as qty_replies FROM topics 
                    LEFT JOIN replies ON topics.topic_id = replies.topic_id
                    WHERE category_id = $category_id ";
            if (isset($_POST["topic"])){
                $topic_filter = $_POST["topic"];
                $query_1 .= " AND (topics.topic LIKE '%{$topic_filter}%' OR ";
                $query_1 .= " topics.description LIKE '%{$topic_filter}%' ) ";
            }
        $query_1 .= " GROUP BY topics.topic_id ";
        
        $query_result_1 = mysqli_query($connect,$query_1);
        $number_topics = mysqli_num_rows($query_result_1);
        
        
        // Return Data from Categories 
        $query_2 = "SELECT * FROM categories WHERE category_id = $category_id";
        $query_result_2 = mysqli_fetch_array(mysqli_query($connect,$query_2));
        
        
        if(!$query_result_1 or !$query_result_2) {
            die("Fail to return information from database");   
        }
    } else {
        header("Location: categories.php");
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
                <form action="topics.php" method="post">
                    <input type="text" name="topic" placeholder="Search">
                    <input type="image"  src="assets/botao_search.png">
                    <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
                </form>
            </div>
            
           <div class="box">
	           <div class="box_left">
                   <a href="categories.php?category_id=<?php echo $category_id; ?>"><?php echo htmlentities($query_result_2['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; These are the Topics
                </div>
                <div class="clean"></div>
            </div>
            
            <div class="content">

                    <table class="categories_table">
                       <tr>
                        
                           <th class="forum_cat">Topic</th>
                           <th class="forum_nrep">Replies</th>
                           <th class="forum_act">Edit</th>
                       </tr>

                        <?php
 
                        while($row_result_1 = mysqli_fetch_array($query_result_1))
                        {
                        ?>
                       <tr>
                       <td class="forum_cat">
                           <a href="replies.php?topic_id=<?php echo $row_result_1['topic_id']; ?>" class="title">
                               <?php echo htmlentities($row_result_1['topic'], ENT_QUOTES, 'UTF-8'); ?>
                           </a>
                           <div class="description"><?php echo $row_result_1['description']; ?>
                           </div>
                       </td>
                       
                       <td><?php echo $row_result_1['qty_replies']; ?></td>
                       <?php
                         if(isset($_SESSION['username']) and ($_SESSION["adminuser"] == True or $_SESSION['userid']==$row_result_1['user_id'])) 
                         {
                        ?>
                       <td>
                           <a href="delete_topic.php?topic_id=<?php echo $row_result_1['topic_id'] . "&category_id=" . $row_result_1['category_id']; ?>">
                               <img src="assets/delete.png" alt="Delete" />
                           </a>
                          <a href="update_topic.php?topic_id=<?php echo $row_result_1['topic_id'] . "&category_id=" . $row_result_1['category_id']; ?>">
                              <img src="assets/edit.png" alt="Edit" />
                          </a>
                        </td>
                        <?php
                        } else {
                        ?>
                        <td>
                        </td>
                        <?php 
                         }
                        ?>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                        <a href="insert_topic.php?category_id=<?php echo $category_id; ?>" class="button">New Topic</a>              
                </div>

        </main>

        <?php include_once("_add/footer.php"); ?>  
    </body>
</html>

<?php
    // Close connection
    mysqli_close($connect);
?>