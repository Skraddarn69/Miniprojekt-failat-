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
            appendClasses(data);
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
function appendClasses(data, page = 0) {
    
}

function appendNav(page, pages) {
    
}