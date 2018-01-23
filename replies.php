<?php require_once("connection/connection.php"); ?>

<?php
    
    // to start session
    session_start();

    // Determinar localidade BR
    setlocale(LC_ALL, 'pt_BR');

    // return to login page case user is not detected
    if (!isset($_SESSION["userid"])) {
        header("location:topics.php");
    }

    if(isset($_GET['topic_id']))
    {  
        $topic_id = $_GET['topic_id'];
    }else if (isset($_POST['topic_id'])){
        $topic_id = $_POST['topic_id'];
    }else{
        header("Location: topics.php");
    }

    if(isset($topic_id))
    {
    
        //$category_id = $_GET['category_id'];
        // Return topics from the categories
        $query_1 = "SELECT replies.* , users.image , users.username FROM replies INNER JOIN users ON replies.user_id = users.user_id WHERE topic_id = $topic_id";
            if (isset($_POST["message"])){
                $filter = $_POST["message"];
                $query_1 .= " AND (reply LIKE '%{$filter}%')";
            }
        $query_result_1 = mysqli_query($connect,$query_1);
        $number_topics = mysqli_num_rows($query_result_1);
        
        // Return Data from Categories         
        $query_2 = "SELECT categories.category_id , categories.name , topics.topic FROM categories INNER JOIN topics ON categories.category_id = topics.category_id WHERE topic_id = $topic_id ";
        $query_result_2 = mysqli_fetch_array(mysqli_query($connect,$query_2));
        $category_id = $query_result_2["category_id"];
        
        if(!$query_result_1 or !$query_result_2) {
            die("Fail to return information from database");   
        }
    } else {
        header("Location: topics.php");
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
                <form action="replies.php" method="post">
                    <input type="text" name="message" placeholder="Search">
                    <input type="image"  src="assets/botao_search.png">
                    <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>">
                </form>
            </div>
            
           <div class="box">
	           <div class="box_left">
                   <a href="categories.php?category_id=<?php echo $category_id; ?>"><?php echo htmlentities($query_result_2['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt;
                   <a href="topics.php?category_id=<?php echo $category_id; ?>"><?php echo htmlentities($query_result_2['topic'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; These are the Replies
                </div>
                <div class="clean"></div>
            </div>
            
            <div class="content">
                
                <a href="insert_reply.php?topic_id=<?php echo $topic_id; ?>" class="button">Reply</a>  

                    <table class="messages_table">
                       <tr>
                        
                           <th class="author">Author</th>
                           <th>Message</th>
                           <th class="forum_act">Edit</th>
                       </tr>

                        <?php
                        while($row_result_1 = mysqli_fetch_array($query_result_1))
                        {
                        ?>
                       <tr>
                      <td class="author center">
                          <?php echo htmlentities($row_result_1['username'], ENT_QUOTES, 'UTF-8'); ?>
                          <div class="clear"></div>
                          <?php
                            if($row_result_1['image']!='')
                            {
                                echo '<img src="'.htmlentities($row_result_1['image']).'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
                            }
                           ?>
                           
                       </td>
                       
                       <td class="left">
                        
                            <div class="date">Date sent: <?php echo $row_result_1['date']; ?></div>
                            <div class="clean"></div>
                            <?php echo $row_result_1['reply']; ?>
        
                        </td>
                           
                       <?php
                         if(isset($_SESSION['username']) and ($_SESSION["adminuser"] == True or $_SESSION['userid']==$row_result_1['user_id'])) 
                         {
                        ?>
                       <td>
                           <a href="delete_reply.php?reply_id=<?php echo $row_result_1['reply_id'] . "&topic_id=" . $topic_id ;  ?>">
                               <img src="assets/delete.png" alt="Delete" />
                           </a>
                           <a href="update_reply.php?reply_id=<?php echo $row_result_1['reply_id'] . "&topic_id=" . $topic_id ;  ?>">
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
                        <a href="insert_reply.php?topic_id=<?php echo $topic_id; ?>" class="button">Reply</a>              
                </div>

        </main>

        <?php include_once("_add/footer.php"); ?>  
    </body>
</html>

<?php
    // Close connection
    mysqli_close($connect);
?>