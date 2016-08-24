function random_item_color() {
    var color_arr = [
        "rgb(240, 227, 249)", "rgb(240, 227, 249)", "rgb(227, 255, 164)", "rgb(202, 244, 217)",
        "rgb(226, 237, 209)", "rgb(255, 234, 223)", "rgb(199, 220, 167)", "rgb(254, 177, 134)",
        "rgb(254, 214, 191)", "rgb(250, 198, 203)", "rgb(254, 197, 165)", "rgb(229, 187, 204)"];
    var last_color_id_0 = -1;
    var last_color_id_1 = -1;
    for (var i = 0; i < 10000; i++) {
        var item_id = "review_item" + String(i);
        var item_obj = document.getElementById(item_id);
        if (item_obj == null) break;
        var color_id = Math.floor(Math.random() * color_arr.length);
        while (color_id == last_color_id_0 || color_id == last_color_id_1) {
            var color_id = Math.floor(Math.random() * color_arr.length);
        }
        last_color_id_0 = last_color_id_1;
        last_color_id_1 = color_id;
        item_obj.style.background = color_arr[color_id];
    }
}