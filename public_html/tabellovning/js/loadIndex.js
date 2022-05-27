window.onload = function() {
    let userType;

    let node = document.getElementById("teacher");
    node.onclick = function() {
        userType = 1;
    }

    node = document.getElementById("student");
    node.onclick = function() {
        userType = 2;
        getClasses(userType);
        console.log(userType);
    }

    node = document.getElementById("admin");
    node.onclick = function() {
        userType = 3;
    }


}