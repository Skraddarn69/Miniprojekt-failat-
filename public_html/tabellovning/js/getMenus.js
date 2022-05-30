function getClasses(userType, teacherID) {
    if(userType===1) {
        fetch('../php/getClasses.php?anvandarTyp=' + userType + '&lararID=' + teacherID)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data);
        })
    } else {
        fetch('../php/getClasses.php?anvandarTyp=' + userType)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data);
        })
    }
}

function appendClasses(data) {
    let table = document.getElementById("menu");
    let cells = table.getElementsByTagName("td");
    for(let i=0;i<cells.length;i++) {
        cells[i].id = data.classes[i].ID;
        cells[i].innerHTML = data.classes[i].klass;
    }
}