<html lang="en">
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <script src='movement.js'></script>
        
        <link rel="stylesheet" href="main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
         var display_str = [];
        
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

   
        $sql = "SELECT * FROM uploads";
        $checkval = false;
        
        
        $max_date = $_POST['max_date'];
        $min_date = $_POST['min_date'];
        $type = $_POST['type'];
        
        
        $results = mysql_query($sql);
        
        if (!$results) {
            die('Invalid query: ' . mysql_error());
        }
        
        while($result = mysql_fetch_array($results)) {
            if($result['type'] == $type){
                echo $result['title'] + "\n" + $result['content'];
                
            }
                
            
        }
        if($username != "") {
        echo 'No such email and password combination found';
        }
        mysql_close();
        ?>
        </script>
    </head>
    <body>
        
        
       
    </body>
</html>