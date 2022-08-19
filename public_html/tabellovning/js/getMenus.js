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
    let pages;
    let table = document.getElementById("menu");
    let cells = table.getElementsByTagName("td");
    
    if (pages > 1) {
        pages = data.pages; 
        appendNav(page, pages);
    }

    for(let i=0;i<cells.length;i++) {
        cells.item(i).innerHTML = classes[i].klass;
    }
}

function appendNav(page, pages) {
    
}