
<?php

require_once('config.php');
require_once('binary_search.php');

$invalid_email = false;
$invalid_username = false;
$null_email = false;
$null_username = false;
$null_password = false;
$short_password = false;

$db_selected = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if(!$db_selected) {
    die('Could not connect: '. mysqli_connect_error());
}


if(!empty($_POST)){

    $v1 = $_POST['email'];
    $v2 = $_POST['username'];
    $v3 = $_POST['password'];

    $sql1 = "SELECT username FROM login ORDER BY username ASC";

    $all_usernames = mysqli_query($db_selected, $sql1);
    $array = array();
    
    while($user = mysqli_fetch_array($all_usernames)){
        $array[] = $user[0];
    }
    
    if($v1 == null){
        $null_email = true;
    } else {
        $null_email = false;
    }
    
    if($v2 == null){
        $null_username = true;
    } else {
        $null_username = false;
    }
    
    if($v3 == null){
        $null_password = true;
    } else {
        $null_password = false;
    }
    
    if(strlen($v3) < 8){
        $short_password = true;
    } else {
        $short_password = false;
    }
    
    if(binary_search_str($array, $v2)){
        $invalid_username = true;
        
    } else {
        $invalid_username = false;
    }
       
    if(!$invalid_email && !$invalid_username && !$short_password && !$null_username && !$null_email && !$null_password){
        $sql2 = "INSERT INTO login (email, username, password) VALUES ('$v1', '$v2', '$v3')";

        if(!mysqli_query($db_selected, $sql2)) {
            die('Error:' . mysqli_connect_error());
        }

        mysqli_close($db_selected);

        header("LOCATION: index.php");
    }
}
?>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <title>Orbit: Dynamic Library</title>
        <script src='movement.js'></script>

        <link rel="stylesheet" href="main2.css">
        <link rel="stylesheet" href="result2.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            var result_width = 20;
            var start_show = 0;
            var num_show = 40;

            function reset_result_div_size(){
                var result_div = document.getElementById("result_field");
                result_div.style.height = window.innerHeight;
                result_div.style.width = window.innerWidth;

            }


            function show_results(){
                for(var i = 0; i < 40; i++){
                    var element = document.getElementById("result_field");
                    reset_result_div_size();
                    var space = document.createElement("div");
                    space.className = 'animate_object';
                    space.style.width = result_width;
                    space.style.height = result_width;
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
        </script>
        <script src="redirect.js"></script>
    </head>

    <body onload="show_results()">
        <div class="login_section">
            <div id="header" class="hover_btn">
                <h1 class="hover_btn" onclick="redirect('index.php')">Orbit</h1>
            </div>
            <div class="container" id="login_div">
                <form action="create_new.php" method="post">
                    <p>Email:</p>
                    <input type="email" name="email" id="login_ele">
                    <?php 
                        if($null_email){
                            echo"<br>Cannot have empty email";
                        }  
                    ?><br><br>
                    <fieldset>
                        <h3>User Information</h3>
                        <p>Username:</p>
                        <input type="text" name="username" id="login_ele">
                        <?php 
                        if($invalid_username){
                                echo "<br>This username has been taken";
                        }
                        if($null_username){
                            echo"<br>Cannot have empty username";
                        }
                            
                        ?>
                        <br>
                        <p>Password:</p>
                        <input type="password" name="password" id="login_ele">
                        <?php 
                        if($null_password){
                            echo"<br>Password is required";
                        }else if($short_password){
                                echo "<br>Password must be over 7 characters";
                        }
                        
                            
                        ?><br><br>
                    </fieldset>
                    <input type="submit" value="Create New User" id="login_ele">
                </form>
            </div>
        </div>
        <div id="result_field"></div>
    </body>
</html>

