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
        $max_date = $_POST['max_date'];
        $min_date = $_POST['min_date'];
        
        $sql = "SELECT * FROM uploads WHERE date BETWEEN $min_date AND $max_date AND type = $type";
        
        $type = $_POST['type'];
        
        
        $results = mysql_query($sql);
        $results_arr = array();
        if (!$results) {
            die('Invalid query: ' . mysql_error());
        }
        
        while($result = mysql_fetch_array($results)) {    
            array_push($results_arr, $result); 
        }
        mysql_close();
        ?>
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
        
         var display_strs = <?php echo json_encode($results_arr) ?>;
         
         var display_arr = JSON.parse(display_strs);
        var element = document.getElementById("result_field");
        for(i = 0; i < display_arr.length; i++){
            var title = document.createElement("h2");
            var para = document.createElement("p");
            var node1 = document.createTextNode(display_arr[i]['title']);
            var node2 = document.createTextNode(display_arr[i]['content']);
            title.appendChild(node1);
            para.appendChild(node2);
            element.appendChild(title);
            element.appendChild(para);
                
        }
        </script>
    </head>
    <body>
        <div>
            <form action="search.php" method="post">
                <input type="datetime" name="min_time"><br>
                <input type="datetime" name="max_time"><br>
                <select name="type">
                    <option value="quote">Quote of the day</option>
                    <option value="meme">Meme of the day</option>
                    <option value="word">Word of the day</option>
                </select>
                <input type="submit" value="Search">
            </form>
        </div>
        <div id="result_field">
        </div>
    </body>
</html>