// Hämtar klasser
function getClasses(page, teacherID) {   
    if((teacherID===null)) {
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getClasses.php?teacherID=' +teacherID +'page=' +page)
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

    for(let i=0;i<cells.length;i++) {
        cells[i].innerHTML = "";
    }

    appendNavClasses(page, pages);
    
    for(let i=0;i<classCount;i++) {
        cells.item(i).innerHTML = classes[i].klass;
        cells.item(i).onclick = function() {console.log(cells.item(i).innerHTML)};
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

function getStudents(page, classId) {
        fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getStudents.php?page=' +page)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data, page);
        })
}