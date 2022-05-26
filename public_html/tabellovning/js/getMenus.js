function getClasses(userType, teacherID) {
    if(userType===1) {
        fetch('../php/getClasses.php?anvandarTyp=' + userType)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            appendClasses(data);
        })
    } else {
        fetch('../php/getClasses.php?anvandarTyp=' + userType + '&lararID=' + teacherID)
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
    for(let i=0;i<data.classes.length;i++) {
        let cells = document.getElementsByTagName("td");
        cells.item(i).id = data.classes(i).ID;
        cells.item(i).innerHTML = data.classes(i).klass;
    }
}