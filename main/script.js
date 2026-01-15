// script file
// toggle side bar function
function openSide() {
    // variables
    let openSideBar  = document.querySelector(".open");
    let closeSideBar  = document.querySelector(".close");
    const sideBar = document.querySelector("#sidebar");
    // function
    openSideBar.style.display = "none";
    closeSideBar.style.display = "block";
    sideBar.style.left ="0px";
}
function closeSide() {
    // variables
    let openSideBar  = document.querySelector(".open");
    let closeSideBar  = document.querySelector(".close");
    const sideBar = document.querySelector("#sidebar");
    // function
    openSideBar.style.display = "block";
    closeSideBar.style.display = "none";
    sideBar.style.left ="-400px";
}
