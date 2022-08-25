window.onload = function() {
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);

    let ID = urlParams.get('ID');
    let userType = urlParams.get('userType');

    let back = document.getElementById("back");
    back.onclick = function() {
        window.location.href = "studMenu.html";
    }

    let next = document.getElementById("next1");
    next.onclick = function() {
        getPassword(ID, userType);
    }
}

function getPassword(ID, userType) {
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/getPassword.php?ID=' +ID +'&userType=' +userType)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        .then(function(data) {
            checkPassword(data, ID, userType);
        })
}

function checkPassword(data, ID, userType) {
    let password = data.losenord;
    let input = document.getElementById("losenord").value;

    if(input===password) {
        if(userType==0) {
            window.location.href = "ovning.html?ID=" +ID;
        } else if(userType==1) {
            console.log('Nej!');
        }
    } else {
        alert("Felaktigt lösenord har angivits");
    }
}