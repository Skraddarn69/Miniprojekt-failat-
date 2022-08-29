window.onload = function() {
    let back = document.getElementById("back");
    back.onclick = function() {
        window.location.href = "studMenu.html";
    }
    queryString = window.location.search;
    urlParams = new URLSearchParams(queryString);
    let ID = urlParams.get("ID");
    appendTables(ID);
}

// Skapar meny för tabellval
function appendTables(ID) {
    document.getElementById("topMenu").style.display = "unset";
    document.getElementById("tableMenu").style.display = "unset";
    document.getElementsByTagName("h1")[0].innerHTML = "Välj tabeller";
    document.getElementById("ovning").style.display = "none";

    let surprise = document.getElementById("surprise");
    let cells = document.getElementsByTagName("td");
    let begin = document.getElementById("begin");
    let selected = [];
    for(let i=0;i<12;i++) {
        let cell = cells.item(i);
        cell.onclick = function() {
            if(cell.style.backgroundColor=="green") {
                cell.style.backgroundColor = "initial";
                selected = selected.filter(checkCell);
                function checkCell(value) {
                    return value != i + 1;
                }
                console.log(selected);
            } else if(selected.length<10) {
                cell.style.backgroundColor = "green";
                selected.push(i + 1);
            }
        }
    }
    surprise.onclick = function() {
        for(let i=0;i<Math.floor(Math.random()*12)+1;i++) {
            let cell = cells.item(i);
            if(!selected.includes(i + 1)&&selected.length<10) {
                cell.style.backgroundColor = "green";
                selected.push(i + 1);
            }
        }
    }
    begin.onclick = function() {
        if(selected.length>0) {
            appendOvning(selected);
        }
    }
}

// Skapar övningsgränssnitt
function appendOvning(selected) {
    document.getElementById("topMenu").style.display = "none";
    document.getElementById("tableMenu").style.display = "none";
    document.getElementsByTagName("h1")[0].innerHTML = "Övning";

    let completed = [];
    let ovning = document.getElementById("ovning");
    ovning.style.display = "unset";

    appendProblem(selected, completed);

    
}

// Skapar tio slumpade uppgifter med de valda tabellerna
function appendProblem(selected, completed) {
    document.getElementById("answer").value = "";
    let form = document.getElementById("ovning");
    let formChild = form.getElementsByTagName("p");
    let term1 = selected[Math.floor(Math.random() * selected.length)];
    let term2 = Math.floor(Math.random()*10)+1;
    let problem = term1 + " X " + term2;

    if(!completed.includes(problem)) {
        formChild[0].innerHTML = term1;
        formChild[1].innerHTML = " X ";
        formChild[2].innerHTML = term2;
        completed.push(problem);
    } else {
        appendProblem(selected, completed);
    }
    form.onsubmit = function() {
        let answer = document.getElementById("answer").value;
        if(answer==="") {
            alert("Fyll i svarsrutan innan du klickar på 'Nästa'.")
        } else if(answer==formChild[0].innerHTML*formChild[2].innerHTML) {
            if(completed.length<10) {
                appendProblem(selected, completed);
            } else {
                alert("Du är klar nu, gå hem!");
            }
        } else {
            alert("FEL!");
        }
        console.log(completed);
        return false;
    }
}