<?php
        
        require_once('config.php');
        
       $db_selected = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if(!$db_selected) {
            die('Could not connect: '. mysqli_connect_error());
        }


        $max_date = '#'.$_POST['max_year'].'/'. $_POST['max_month'].'/'.$_POST['max_date'].'#';
        $min_date = '#'.$_POST['min_year'].'/'. $_POST['min_month'].'/'.$_POST['min_date'].'#';;
        
        $type = $_POST['type'];
        
        $num_show = $_POST['num_show'];
    
        $sql = "SELECT title,content FROM uploads WHERE type = '$type'";
        //WHERE date BETWEEN $min_date AND $max_date AND type = $type"
        
        
        
        
        $results = mysqli_query($db_selected, $sql);
        $results_arr = array();
        if (!$results) {
            die('Invalid query: ' . mysqli_connect_error());
        }
        
        while($result = mysqli_fetch_array($results)) {
            $new_result = array($result['title'], $result['content']);
            array_push($results_arr, $new_result);
        }
        $json_result = json_encode($results_arr);
        mysqli_close($db_selected);
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
        <div id="button_field">
            <a id="show_btn" onclick="increment_show('prev')">[prev]</a>
            <a id="show_btn" onclick="increment_show('next')">[next]</a>
        </div>
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
            var max_length = display_strs.length;
            var result_width = 20;
            var start_show = 0;
            var num_show = <?php echo $num_show ?>;
            var last_show = 0;
            if(num_show < max_length){
                last_show = num_show;
            }
            var stop_myMove = false;
            
        function reset_result_div_size(){
            var result_div = document.getElementById("result_field");
            result_div.style.height = window.innerHeight;
            result_div.style.width = window.innerWidth;
            
        }
        
        
        function show_results(){
            
            
            for(var i = start_show; i < last_show; i++){
                var element = document.getElementById("result_field");
                reset_result_div_size();
                var space = document.createElement("h3");
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
            my_move();
        }
            
        
            function my_move() {
            var elem = document.getElementsByTagName("h3");
            stop_myMove = false;
            reset_result_div_size();
            var elem_pos = [];
            var elem_spd = [];
            var elem_not_passed = [];
            var spd_min = 1;
            var spd_max = 4;
            var width_c = window.innerWidth;
            var height_c = Math.round(window.innerHeight * (9 / 10));
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
                
            var id = setInterval(frame, 17);
            var moving = 1;
            function frame() {
                for(var i = 0; i < moving; i++){
                    if(elem_pos[i] == -result_width){
                        elem[i].style.left = width_c;
                        elem_pos[i] = window.innerWidth;
                    }else{
                        if(stop_myMove){
                            clearInterval(id);
                        }
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
            function increment_show(change){
                if(change == "next"){
                    if(last_show + num_show < max_length){
                        last_show += num_show;
                    }else{
                        last_show = max_length;
                    }
                } else {
                    if(start_show != 0){
                        start_show -= num_show;
                        last_show -= num_show;
                    }
                    
                }
                stop_myMove = true;
                var field = document.getElementById("result_field");
               /*while(field.hasChildNodes){
                    field.removeChild(field.lastChild);
                }*/
                show_results();
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