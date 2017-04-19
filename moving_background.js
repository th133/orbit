var result_width = 20;
var start_show = 0;
var num_show = 40;

function reset_result_div_size() {
    var result_div = document.getElementById("result_field");
    result_div.style.height = window.innerHeight;
    result_div.style.width = window.innerWidth;
    
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
    if (len > 1) {
        ratio = (len - 1) / len;
    }
    var check_len = Math.round((width_c - result_width * 2) * ratio);
    
    var i = 0;
    for (i = 0; i < len; i += 1) {
        elem_spd[i] = Math.floor(Math.random() * (spd_max - spd_min)) + spd_min;
        elem_pos[i] = width_c;
        elem_not_passed[i] = true;
        elem[i].style.top = Math.round(height_c * Math.random());
        if (elem_spd[i] == 2) {
            elem[i].style.backgroundColor = "#ffff80";
        } else {
            elem[i].style.backgroundColor = "lightyellow";
        }
        elem[i].style.zIndex = elem_spd[i];
    }
        
    var moving = 1;
    function frame() {
        var i = 0;
        for (i = 0; i < moving; i += 1) {
            if (elem_pos[i] <= -result_width) {
                elem[i].style.left = width_c;
                elem_pos[i] = window.innerWidth;
            } else {
                elem_pos[i] -= elem_spd[i];
                elem[i].style.left = elem_pos[i] + 'px';
                if (elem_not_passed[i] && elem_pos[i] <= check_len && moving < len) {
                    moving += 1;
                    elem_not_passed[i] = false;
                }
            }
        }
    }
    var id = setInterval(frame, 17);
}

function show_results() {
    var i = 0;
    for (i = 0; i < 40; i += 1) {
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


