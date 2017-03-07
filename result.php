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
        $max_date = $_POST['max_year'].'-'. $_POST['max_month'].'-'.$_POST['max_date'];
        $min_date = $_POST['min_year'].'-'. $_POST['min_month'].'-'.$_POST['min_date'];;
        $type = $_POST['type'];
        echo $max_date. $min_date;
    
        $sql = "SELECT * FROM uploads WHERE type = '$type'";
        //WHERE date BETWEEN $min_date AND $max_date AND type = $type"
        
        
        
        
        $results = mysql_query($sql);
        $results_arr = array();
        if (!$results) {
            die('Invalid query: ' . mysql_error());
        }
        
        while($result = mysql_fetch_array($results)) {
            $new_result = array($result['title'], $result['content']);
            array_push($results_arr, $new_result);
        }
        $json_result = json_encode($results_arr);
        mysql_close();
?>
<html lang="en">
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <link rel="stylesheet" href="result.css">
        <link rel="stylesheet" href="result_pop.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body onload="show_results()">
        <div id="result_field">
        </div>
        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <h2 id="modal_title"></h2>
                <p id="modal_content"></p>
            </div>
        </div>
        <script>
            
            var display_strs = <?php echo $json_result ?>;
        var result_width = 20;
            
        function reset_result_div_size(){
            var result_div = document.getElementById("result_field");
            result_div.style.height = window.innerHeight;
            result_div.style.width = window.innerWidth;
            
        }
        
        
        function show_results(){
            
            
            for(var i = 0; i < display_strs.length; i++){
                var element = document.getElementById("result_field");
                reset_result_div_size();
                var i_str = i.toString();
                
                var space = document.createElement("a");
                space.id = 'animate';
                space.style.width = result_width;
                space.style.height = result_width;
                var title = document.createElement("h2");
                title.style.fontSize = 20;
                var para = document.createElement("p");
                para.style.fontSize = 0;
                var node1 = document.createTextNode(display_strs[i][0]);
                var node2 = document.createTextNode(display_strs[i][1]);
                title.appendChild(node1);
                para.appendChild(node2);
                space.appendChild(title);
                space.appendChild(para);
                space.style.left = window.innerWidth;
                element.appendChild(space);
                
            }
            myMove();
            location.href = '#result_field';
        }
        
            function myMove() {
            var elem = document.getElementsByTagName("a");
            var elem_pos = [];
            var elem_spd = [];
            var spd_min = 1;
            var spd_max = 3;
            reset_result_div_size();
            var width_c = window.innerWidth;
            var height_c = Math.round(window.innerHeight * (9 / 10));
            var len = elem.length;
            var ratio = 1.0;
            if(len > 1){
                ratio = (len - 1) / len;
            }
            var check_len = Math.round((width_c - (result_width * 2)) * ratio);
            
            for(i = 0; i < len; i++){
                elem_spd[i] = Math.floor(Math.random() * (spd_max - spd_min)) + spd_min;
                elem_pos[i] = width_c;
                elem[i].style.top = Math.round(height_c * Math.random());
                if(elem_spd[i] == 1){
                    elem[i].style.backgroundColor = "#ffff80";
                }
                elem[i].style.zIndex = elem_spd[i];
                elem[i].onmouseover = function(){
                    this.style.backgroundColor = "red";
                    var c = this.childNodes;
                    modal.style.display = "block";
                document.getElementById("modal_title").innerHTML = c[0].innerHTML;
                document.getElementById("modal_content").innerHTML = c[1].innerHTML;
                };
                
            }
            
            var id = setInterval(frame, 5);
            var moving = 1;
            function frame() {
                for(var i = 0; i < moving; i++){
                    if(elem_pos[i] == -result_width){
                        elem[i].style.left = width_c;
                        elem_pos[i] = window.innerWidth;
                    }else{
                        elem_pos[i] -= elem_spd[i];
                        elem[i].style.left = elem_pos[i] + 'px';
                        if(elem_pos[i] == check_len && moving < len){
                            moving++;
                        }
                    }
                }
            }
        }
            
            // Get the modal
            var modal = document.getElementById('myModal');
            
            
            // When the user clicks the button, open the modal 
            function display_modal(j) {
                modal.style.display = "block";
                document.getElementById("modal_title").innerHTML = j;
                document.getElementById("modal_content").innerHTML = j;
                
            }
            

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </body>
</html>