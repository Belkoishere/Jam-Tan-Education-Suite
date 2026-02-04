// //elements
// var logo = document.getElementById("logo");
// var down_icon = document.getElementById("down_icon");
// var burger_menu_icon = document.getElementById("burger_menu_icon");
// var popup_menu = document.getElementById("popup_menu");
// var fronttext = document.getElementById("fronttext");
// var topbar = document.getElementById("topbar");
// var links = document.getElementById("links");
// var app_title = document.getElementById("app_title");
// var title = document.getElementById("title");
// var sidebar_menu = document.getElementById("sidebar_menu");

// function updateScreen(){
//     var width = window.innerWidth;
//     var small = 70 + "px";
//     var big = 100 + "px";
    
//     if (width < 800){
//         burger_menu_icon.style.display = "block";
//         app_title.style.visibility = "hidden";
//         sidebar_menu.style.visibility = "hidden";
//         topbar.style.height = small;
//         logo.style.height = small;
//         title.style.fontSize = small;
//         //down_icon.style.width = ;
//     }
//     else{
//         burger_menu_icon.style.display = "none";
//         app_title.style.visibility = "visible";
//         sidebar_menu.style.visibility = "visible";
//         popup_menu.style.visibility = "hidden";
//         topbar.style.height = big;
//         logo.style.height = big;
//         title.style.fontSize = big;
//         //down_icon.style.width = big;
//     }
// }

// window.addEventListener('resize', updateScreen);
// window.addEventListener('load', updateScreen);

var popup_menu = document.getElementById("pop_up_menu");
var burger_menu_icon = document.getElementById("burger_menu_icon");
var main = document.getElementById("main");

function showmenu(){
    popup_menu.style.display = "block";
    burger_menu_icon.style.visibility = "hidden"
    main.style.display = "none";
}
function hidemenu(){
    popup_menu.style.display = "none";
    burger_menu_icon.style.visibility = "visible";
    main.style.display = "block";
}