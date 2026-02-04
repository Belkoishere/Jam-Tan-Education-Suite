// window.addEventListener("load", () => {
//   document.documentElement.classList.add("ready");
// //   updateScreen();
// });

// window.addEventListener("resize", updateScreen);

var popup_menu = document.getElementById("pop_up_menu");
var burger_menu_icon = document.getElementById("burger_menu_icon");
var main = document.getElementById("main");

function showmenu(){
    popup_menu.style.display = "block";
    burger_menu_icon.style.display = "none"
    main.style.display = "none";
}
function hidemenu(){
    popup_menu.style.display = "none";
    burger_menu_icon.style.display = "block";
    main.style.display = "block";
}