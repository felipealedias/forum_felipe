<header>
    <div id="header_central">
        
        <?php 
            if ( isset($_SESSION["userid"])  ) {
                
            $user = $_SESSION["userid"];
            
            $saudacao = "SELECT name FROM users WHERE user_id = {$user}";
            
            $saudacao_login = mysqli_query($connect,$saudacao);
                
            if(!$saudacao_login){
                die("Fail to get data from database (header.php)");
            }
                
            $saudacao_login = mysqli_fetch_assoc($saudacao_login);
            $nome = $saudacao_login["name"];
                
        ?>
        
            <div id="header_saudacao">
                <H5>Welcome, <?php echo $nome ?> - <a href="logout.php"> Logout </a></H5>
            </div>
        
        <?php
            }
        ?>
        
        
        <img src="assets/forum.jpg">
        
    </div>
</header>