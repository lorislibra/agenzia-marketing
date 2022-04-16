function modify_order_quantity(number_change, max_number){
    var order_number_input = document.getElementById("order_number_input");
    var current_quantity = parseInt(order_number_input.value);

    var modified_quantity = current_quantity + number_change;
    if(modified_quantity == 1){
        var btn_sub = document.getElementById("btn_sub");
        btn_sub.disabled = true;
    }
    else{
        var btn_sub = document.getElementById("btn_sub");
        btn_sub.disabled = false;
    }
    
    if(modified_quantity == max_number){
        var btn_add = document.getElementById("btn_add");
        btn_add.disabled = true;
    }
    else{
        var btn_add = document.getElementById("btn_add");
        btn_add.disabled = false;
    }

    if(modified_quantity >= 1 && modified_quantity <= max_number){
        order_number_input.value = modified_quantity;
    }
}

function check_value(max_number){
    var order_number_input = document.getElementById("order_number_input");
    var current_quantity = parseInt(order_number_input.value);

    if(current_quantity <= 1){
        var btn_sub = document.getElementById("btn_sub");
        btn_sub.disabled = true;
    }
    else{
        var btn_sub = document.getElementById("btn_sub");
        btn_sub.disabled = false;
    }
    if(current_quantity < 1){
        order_number_input.value = 1;
    }
    
    if(current_quantity >= max_number){
        var btn_add = document.getElementById("btn_add");
        btn_add.disabled = true;
    }
    else{
        var btn_add = document.getElementById("btn_add");
        btn_add.disabled = false;
    }
    if(current_quantity > max_number){
        order_number_input.value = max_number;
    }
}