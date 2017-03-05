<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <script src='movement.js'></script>
        
        <link rel="stylesheet" href="main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    
    <body>
        <div class="jumbotron" id="header">            
                <h1>Orbit</h1>
                <h3>The Dynamic Library</h3>
        </div>
        <form action="index.php" method="post">
            Username:<br>
            <input type="text" name="username"><br>
            Password:<br>
            <input type="text" name="password"><br>
            <br>
            <input type="submit" value="login">
        </form>
        <?php
        
        require_once('config.php');
        
       $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

        if(!$link) {
            die('Could not connect: '. mysql_error());
        }

        $db_selected = mysql_select_db(DB_NAME, $link);

        if(!$db_selected) {
            die('Can\'t use ' . DB_NAME . ': ' .mysql_error());
        }

   
        $sql = "SELECT * FROM login";
        $checkval = false;
        
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $results = mysql_query($sql);
        
        if (!$results) {
            die('Invalid query: ' . mysql_error());
        }
        
        while($result = mysql_fetch_array($results)) {
            if(($result['username'] == $username || $result['email'] == $username) && $result['password'] == $password) {
                header("LOCATION: new_post.html");
                
            }
            
        }
        if($username != "") {
        echo 'No such email and password combination found';
        }
        mysql_close();
        ?>
        <br>
        <a href="create_new.html">New User</a>
        
    </body>
</html>
