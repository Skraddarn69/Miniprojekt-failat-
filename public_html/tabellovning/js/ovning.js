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
    document.getElementById("results").style.display = "none";
    document.getElementById("exercise").style.display = "none";
    document.getElementsByTagName("h1")[0].innerHTML = "Välj tabeller";

    let surprise = document.getElementById("surprise");
    let cells = document.getElementsByTagName("td");
    let begin = document.getElementById("begin");
    let selected = [];
    for(let i=0;i<12;i++) {
        let cell = cells.item(i);
        cell.style.backgroundColor = "initial";
        cell.onclick = function() {
            if(cell.style.backgroundColor=="green") {
                cell.style.backgroundColor = "initial";
                selected = selected.filter(checkCell);
                function checkCell(value) {
                    return value != i + 1;
                }
            } else if(selected.length<10) {
                cell.style.backgroundColor = "green";
                selected.push(i + 1);
            }
        }
    }
    surprise.onclick = function() {
        let loops = Math.floor(Math.random()*10)+1;
        let table
        for(let i=0;i<loops;) {
            table = Math.floor(Math.random()*12)+1;
            if(selected.length<10) {
                if(!selected.includes(table)) {
                    selected.push(table);
                    i++;
                }
            }
            if(i==loops) {
                appendExercise(selected, ID);
            }
        }
    }
    begin.onclick = function() {
        if(selected.length>0) {
            appendExercise(selected, ID);
        } else {
            alert("Välj minst en tabell innan du klickar på 'börja'.");
        }
    }
}

// Skapar övningsgränssnitt
function appendExercise(selected, ID) {
    document.getElementById("topMenu").style.display = "none";
    document.getElementById("tableMenu").style.display = "none";
    document.getElementsByTagName("h1")[0].innerHTML = "Övning";

    let completed = [];
    let score = 0;
    let exercise = document.getElementById("exercise");
    exercise.style.display = "unset";

    let checklist = [];
    let term;
    for(let i=0;i<selected.length;i++) {
        term = selected[Math.floor(Math.random() * selected.length)];
        if(!checklist.includes(term)) {
            checklist.push(term);
        } else {
            i--;
        }
    }

    appendProblem(selected, completed, score ,ID, checklist);
}

// Skapar tio slumpade uppgifter med de valda tabellerna
function appendProblem(selected, completed, score, ID, checklist) {
    document.getElementById("answer").value = "";
    let form = document.getElementById("exercise");
    let formChild = form.getElementsByTagName("p");

    let term1;
    if(checklist.length>0) {
        term1 = checklist[0];
    } else {
        term1 = selected[Math.floor(Math.random() * selected.length)];
    }
    let term2 = Math.floor(Math.random()*10)+1;
    let problem = term1 + " X " + term2;

    if(!completed.includes(problem)) {
        formChild[0].innerHTML = term1;
        formChild[1].innerHTML = " X ";
        formChild[2].innerHTML = term2;
        formChild[3].innerHTML = ""
        completed.push(problem);
        checklist.shift();
    } else {
        appendProblem(selected, completed, score, ID, checklist);
    }
    form.onsubmit = function() {
        let answer = document.getElementById("answer").value;
        if(answer==="") {
            alert("Fyll i svarsrutan innan du klickar på 'Nästa'.");
        } else if(answer==formChild[0].innerHTML*formChild[2].innerHTML) {
            if(completed.length<10) {
                score += 1;
                appendProblem(selected, completed, score, ID, checklist);
            } else {
                score += 1;
                appendResult(score, ID, selected);
                //saveResult(score);
            }
        } else {
            appendProblem(selected, completed, score, ID, checklist);
        }
        return false;
    }
}

// Bygger upp resultatvy
function appendResult(score, ID, selected) {
    document.getElementById("exercise").style.display = "none";
    let resultMenu = document.getElementById("results");
    let table;
    resultMenu.style.display = "unset";
    resultMenu.getElementsByTagName("button")[0].onclick = function() {
        appendTables(ID);
    }
    resultMenu.getElementsByTagName("button")[1].onclick = function() {
        window.location.href = "index.html";
    }
    let result = document.getElementsByTagName("p")[4];
    let message;
    if(score<5) {
        message = "LITE MER ÖVNING";
    } else if(score<8) {
        message = "SNART DÄR";
    } else if(score<10) {
        message = "SÅ NÄRA";
    } else {
        message = "GRATTIS!";
    }
    result.innerHTML = score + " / 10 POÄNG, " + message;

    if(selected.length>1) {
        table = 13;
    } else {
        table = selected[0];
    }

    saveResult(ID, score, table);
}

function saveResult(ID, score, table) {
    fetch('http://localhost/Miniprojekt/public_html/tabellovning/php/saveResult.php?elevID=' + ID + '&poang=' + score + '&tabell=' + table)
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
}