// Hämtar klasser
function getClasses(page, teacherID) {   
    if((teacherID!=null)) {
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getClasses.php?teacherID=' +teacherID +'&page=' +page)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data, page);
        })
    } else {
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getClasses.php?page=' +page)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data, page);
        })
    }
}

// Fyller tabell med klasser
function appendClasses(data, page) {
    let classes = data.classes;
    let classCount = Object.keys(classes).length;
    let pages = data.pages;
    let table = document.getElementById("menu");
    let cells = table.getElementsByTagName("td");
    let back = document.getElementById("back");

    back.onclick = function() {window.location.href = "index.html"};

    for(let i=0;i<cells.length;i++) {
        cells[i].innerHTML = "";
    }

    appendNavClasses(page, pages);
    
    for(let i=0;i<classCount;i++) {
        cells.item(i).innerHTML = classes[i].klass;
        if(classes[i].studCount>0) {
            cells.item(i).onclick = function() {getStudents(1, classes[i].ID)};
        } else {
            cells.item(i).onclick = "";
        }
    }
}

function appendNavClasses(page, pages) {
    let prev = document.getElementById("previous");
    let next = document.getElementById("next");
    
    if(page!=1) {
        prev.style.color = "initial";
        prev.onclick = function() {getClasses(page-1)};
    } else {
        prev.style.color = "grey";
        prev.onclick = "";
    }

    if(page!=pages) {
        next.style.color = "initial";
        next.onclick = function() {getClasses(page+1)};
    } else {
        next.style.color = "grey"
        next.onclick = "";
    }
}

function getStudents(page, classID) {
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getStudents.php?page=' +page +'&classID=' +classID)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendStudents(data, page, classID);
        })
}

function appendStudents(data, page, classID) {
    let table = document.getElementById("menu");
    let cells = table.getElementsByTagName("td");
    let back = document.getElementById("back");

    back.onclick = function() {getClasses(1)};

    let students = data.students;
    let studentCount = Object.keys(students).length;
    let pages = data.pages;

    for(let i=0;i<cells.length;i++) {
        cells[i].innerHTML = "";
    }

    appendNavStudents(page, pages, classID);
    
    for(let i=0;i<studentCount;i++) {
        cells.item(i).innerHTML = students[i].namn;
        cells.item(i).onclick = function() {
            window.location.href = "passwordCheck.html?ID=" +students[i].ID +"&userType=0";
        }
    }
}

function appendNavStudents(page, pages) {
    let prev = document.getElementById("previous");
    let next = document.getElementById("next");
    
    if(page!=1) {
        prev.style.color = "initial";
        prev.onclick = function() {getStudents(page-1, classID)};
    } else {
        prev.style.color = "grey";
        prev.onclick = "";
    }

    if(page!=pages) {
        next.style.color = "initial";
        next.onclick = function() {getStudents(page+1, classID)};
    } else {
        next.style.color = "grey"
        next.onclick = "";
    }
}