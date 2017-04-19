<?php
        
        require_once('config.php');
        
       $db_selected = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if(!$db_selected) {
            die('Could not connect: '. mysqli_connect_error());
        }

      /*  $max_year = $_GET['max_year'];
        $max_month = $_GET['max_month'];
        $max_day = $_GET['max_date'];
        $max_date = '#'.$max_year.'/'. $max_month.'/'.max_day.'#';
        $min_date = '#'.$_GET['min_year'].'/'. $_GET['min_month'].'/'.$_GET['min_date'].'#';;
        */
        $type = 'quote';
        if(isset($_GET['type'])){
            $type = $_GET['type'];
        }
        $num_show = '15';
        if(isset($_GET['num_show'])){
            $num_show = $_GET['num_show'];
        }
        
        $sql = "SELECT title,date,content FROM uploads WHERE type = '$type'";
        //WHERE date BETWEEN $min_date AND $max_date AND type = $type"
        
        $results_ttl = mysqli_query($db_selected, $sql);
        $num_res = mysqli_num_rows($results_ttl);
        $num_page = ceil($num_res / $num_show);
        $page = '';
        if(isset($_GET['page'])){
            $page = $_GET['page'];   
        }
        $first_ele = 0;
        $cur_page = 1;
        if($page != '' && $page > '1'){
            $first_ele = $num_show * ($page - 1);
            $cur_page = $page;
        }
        
        $sqlr = "SELECT title,date,content FROM uploads WHERE type = '$type' LIMIT $first_ele,$num_show";

        $results = mysqli_query($db_selected, $sqlr);
        
        $results_arr = array();
        if (!$results) {
            die('Invalid query: lol' . mysqli_connect_error());
        }
        
        
        while($result = mysqli_fetch_array($results)) {
            $new_result = array($result['title'], $result['date'], $result['content']);
            array_push($results_arr, $new_result);
        }
        $json_result = json_encode($results_arr);
        mysqli_close($db_selected);
?>

<html lang="en">
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <link rel="stylesheet" href="result2.css">
        <link rel="stylesheet" href="result_pop3.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body onload="show_results()">
        <div id="button_field" style="text-align: center">
            <a id="show_btn" onclick="open_menu()">[Menu]</a>
        </div>
        <div id="result_field">
        </div>
        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div id="modal-window">
                <div id="menu">
                    <h2>Menu</h2>
                    <a href="search.html">Back to Search</a>
                    <br><br><br>
                    <p>Select Page</p>
                    <a href="result.php?page=1<?php echo '&type='.$type.'&num_show='.$num_show; ?>" id="pages">start</a>
                    <?php for($i = max(1, $cur_page - 2); $i <= min($cur_page + 2, $num_page); $i++){
                        ?><a href="result.php?<?php echo 'page='.$i.'&type='.$type.'&num_show='.$num_show; ?>" id="pages"><?php echo $i; ?></a><?php
                    }
                    ?>
                    <a href="result.php?<?php echo 'page='.$num_page.'&type='.$type.'&num_show='.$num_show; ?>" id="pages">end</a>
                </div>
                <div id ="results">
                    <h2 id="modal_title"></h2>
                    <p id="modal_date"></p>
                    <p id="modal_content"></p>
                </div>
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
                var space = document.createElement("div");
                space.className = 'animate_object';
                space.style.width = result_width;
                space.style.height = result_width;
                var title = document.createElement("h2");
                title.style.fontSize = 15;
                var para = document.createElement("p");
                var para2 = document.createElement("p");
                para.style.fontSize = 0;
                para2.style.fontSize = 0;
                var node1 = document.createTextNode(display_strs[i][0]);
                var node2 = document.createTextNode(display_strs[i][1]);
                var node3 = document.createTextNode(display_strs[i][2]);
                title.appendChild(node1);
                para.appendChild(node2);
                para2.appendChild(node3);
                space.appendChild(title);
                space.appendChild(para);
                space.appendChild(para2);
                space.style.left = window.innerWidth;
                space.style.borderRadius = 2;
                element.appendChild(space);
                
            }
            my_move();
        }
            
        
            function my_move() {
            var elem = document.getElementsByClassName("animate_object");
            reset_result_div_size();
            var elem_pos = [];
            var elem_spd = [];
            var elem_not_passed = [];
            var spd_min = 2;
            var spd_max = 6;
            var width_c = window.innerWidth;
            var height_c = Math.round(window.innerHeight * (17 / 20));
            var len = elem.length;
            var ratio = 1;
            if(len > 1){
                ratio = (len - 1) / len;
            }
            var check_len = Math.round((width_c - result_width * 2) * ratio);
            
            for(var i = 0; i < len; i++){
                elem_spd[i] = Math.floor(Math.random() * (spd_max - spd_min)) + spd_min;
                elem_pos[i] = width_c;
                elem_not_passed[i] = true;
                elem[i].style.top = Math.round(height_c * Math.random());
                if(elem_spd[i] == 2){
                    elem[i].style.backgroundColor = "#ffff80";
                } else{
                    elem[i].style.backgroundColor = "lightyellow";
                }
                elem[i].style.zIndex = elem_spd[i];
                elem[i].onmouseover = function(){
                    this.style.backgroundColor = "red";
                    var c = this.childNodes;
                    modal.style.display = "block";
                    modal.style.textAlign = "left";
                    modal_window.style.width = "50%";
                    result_things.style.display = "block";
                    document.getElementById("modal_title").innerHTML = c[0].innerHTML;
                    
                    document.getElementById("modal_date").innerHTML = c[1].innerHTML;
                    document.getElementById("modal_content").innerHTML = c[2].innerHTML;
                    
                
                };
                
            }   
                
            var id = setInterval(frame, 17);
            var moving = 1;
            function frame() {
                for(var i = 0; i < moving; i++){
                    if(elem_pos[i] <= -result_width){
                        elem[i].style.left = width_c;
                        elem_pos[i] = window.innerWidth;
                    }else{
                        elem_pos[i] -= elem_spd[i];
                        elem[i].style.left = elem_pos[i] + 'px';
                        if(elem_not_passed[i] && elem_pos[i] <= check_len && moving < len){
                            moving++;
                            elem_not_passed[i] = false;
                        }
                    }
                }
            }
        }
            
        function open_menu(){
            modal.style.display = "block";
            menu.style.display = "block";
            modal.style.textAlign = "center";
            modal_window.style.width = 350;
        }
            // Get the modal
            var modal = document.getElementById('myModal');
            var menu = document.getElementById('menu');
            var result_things = document.getElementById('results');
            var modal_window = document.getElementById('modal-window');
            
            

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    menu.style.display = "none";
                    result_things.style.display = "none";
                }
            }
        </script>
    </body>
</html>