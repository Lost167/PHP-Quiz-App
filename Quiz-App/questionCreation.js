let addOrUpdate; // need this because the same panel is used for adds and updates
addOrUpdate = "add";

window.onload = function () {

    // add event handlers for buttons
    document.querySelector("#GetButton").addEventListener("click", getAllItems);
    document.querySelector("#DeleteButton").addEventListener("click", deleteItem);
    //document.querySelector("#AddButton").addEventListener("click", addItem);
    document.querySelector("#UpdateButton").addEventListener("click", updateItem);
    document.querySelector("#DoneButton").addEventListener("click", processForm);
    document.querySelector("#frm").addEventListener("submit", checkInputs);
    document.querySelector("#CancelButton").addEventListener("click", cancelAddUpdate);
    document.getElementById("choices").addEventListener("change", handleChoices);
    
    // add event handler for selections on the table
    document.querySelector("table").addEventListener("click", handleRowClick);
    
    loadMenuItemCategories();
    //hideUpdatePanel();
};

// "Get Data" button
function getAllItems() {
    // let url = "api/getAllItems.php";
    let url = "quizapp/questions"; // REST-style: URL refers to an entity or collection, not an action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no... see console for error");
                //console.log(resp);
            } else {
                buildTable(xmlhttp.responseText);
                clearSelections();
                resetUpdatePanel();
                //hideUpdatePanel();
            }
        }
    };
    xmlhttp.open("GET", url, true); // HTTP verb says what action to take; URL says which item(s) to act upon
    xmlhttp.send();

    // disable Delete and Update buttons
    document.querySelector("#DeleteButton").setAttribute("disabled", "disabled");
    document.querySelector("#UpdateButton").setAttribute("disabled", "disabled");
}

// "Delete" button
function deleteItem() {
    let id = document.querySelector(".highlighted").querySelector("td").innerHTML;
    //let url = "api/deleteItem.php";
    let url = "quizapp/questions/" + id; // entity, not action
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            if (resp.search("ERROR") >= 0 || resp != 1) {
                alert("could not complete request");
                console.log(resp);
            } else {
                getAllItems();
            }
        }
    };
    xmlhttp.open("DELETE", url, true); // "DELETE" is the action, "url" is the entity
    xmlhttp.send();
}

// "Add" button
function addItem() {
    // Show panel, panel handler takes care of the rest
    addOrUpdate = "add";
    //resetUpdatePanel();
    //showUpdatePanel();
}

// "Update" button
function updateItem() {
    addOrUpdate = "update";
    resetUpdatePanel();
    populateUpdatePanelWithSelectedItem();
    //showUpdatePanel();
}

// "Done" button (on the input panel)
function processForm() {
    // We need to send the data to the server. 
    // We will create a JSON string and pass it to the "send" method
    // of the HttpRequest object. Then if we send the request with POST or PUT,
    // the JSON string will be included as part of the message body 
    // (not a form parameter).
    //    questionID, questionText, choices, answer, categorySelect
    console.log("processForm()");
    //addItem();
    
    let questionID = document.querySelector("#questionID").value;
    let questionText = document.querySelector("#questionText").value;
    
    let choices = document.querySelector("#choices").value;
    let options = [choices];
    //console.log(options);
    for (let i = 0; i < choices; i++) {
        console.log(i);
        options[i] = document.getElementById(i).value;
        console.log(options[i]);
    }
    
    //let answer = Number(document.querySelector("#answer").value);
    let answer = document.querySelector("#answer").value;
    let tags = document.querySelector("#categorySelect").value;
    

    let obj = {
        "questionID": questionID,
        "questionText": questionText,
        //"choices": [options],
        "choices": options,
        "answer": answer,
        "tags": tags
        
    };

    let url = "quizapp/questions/" + questionID;
    let method = (addOrUpdate === "add") ? "POST" : "PUT";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log("Hellooooo");
            console.log(xmlhttp.responseText);
            if (resp.search("ERROR") >= 0 || resp !== 1) {
                console.log("Problem");
                //alert("could not complete request");
                //console.log(resp);
            } else {
                alert("Question " + (addOrUpdate === "add" ? "added." : "updated."));
                getAllItems();
            }
        }
    };
    xmlhttp.open(method, url, true); // method is either POST or PUT
    xmlhttp.send(JSON.stringify(obj));
}

// "Cancel" button (on the input panel)
function cancelAddUpdate() {
    hideUpdatePanel();
}

function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("highlighted");
    }
}

function handleRowClick(e) {
    //add style to parent of clicked cell
    clearSelections();
    e.target.parentElement.classList.add("highlighted");

    // enable Delete and Update buttons
    document.querySelector("#DeleteButton").removeAttribute("disabled");
    document.querySelector("#UpdateButton").removeAttribute("disabled");
}

function showUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.remove("hidden");
}

function hideUpdatePanel() {
    document.getElementById("AddUpdatePanel").classList.add("hidden");
}

function loadMenuItemCategories() {
    //let url = "menuService/categories";
    let url = "quizapp/tags";
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            let resp = xmlhttp.responseText;
            //console.log(resp);
            if (resp.search("ERROR") >= 0) {
                alert("oh no...");
                //console.log(resp);
            } else {
                //console.log(resp);
                console.log("Patel");
                initUpdatePanel(resp);
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function initUpdatePanel(text) {
    console.log("initUpdatePanel");
    //console.log(text);
    let cats = JSON.parse(text);
    let html = "";
    for (let i = 0; i < cats.length; i++) {
        let tagID = cats[i].tagID;
        let tagName = cats[i].tagName;
        //let tagCategoryName = cats[i].tagcategory.tagCategoryName;
        html += "<option value='" + tagID + "'>" + tagName + "</option>";
    }
    document.querySelector("#categorySelect").innerHTML = html;
    resetUpdatePanel();
}

function resetUpdatePanel() {
//    questionID, questionText, choices, answer, categorySelect
    document.querySelector("#questionID").value = "";
    document.querySelector("#questionText").value = "";
    document.querySelector("#choices").value = "";
    document.querySelector("#answer").value = "";
    document.querySelectorAll("option")[0].selected = true; // select first one
    
}

function populateUpdatePanelWithSelectedItem() {
    let tds = document.querySelector(".highlighted").querySelectorAll("td");
    document.querySelector("#questionID").value = tds[0].innerHTML;
    document.querySelector("#questionText").value = tds[1].innerHTML;
 
    console.log(tds[2].innerHTML);
    let ch = tds[2].innerHTML;
    const cho = ch.split(",");
    console.log(cho.length);     
    for (let i = 0; i < cho.length; i++) {
        console.log(cho[i]);
    }
    
    //document.getElementById("choices").addEventListener("change", handleChoices).value = cho.len;
    document.querySelector("#choices").value = cho.length;
    
    let numOfChoices = document.getElementById("choices").value;
    let res = "";
    
    for (let i = 0; i < numOfChoices; i++) {
        let temp = i + 1;
        res += "[" + temp + "]" + " <input type='text' value='" + cho[i] +"' id = '" + i + "' name = '" + i + "' required><br><br>";
    }
    document.getElementById("inputChoices").innerHTML = res;
    
    document.querySelector("#answer").value = +tds[3].innerHTML;
    
    console.log(tds[4].innerHTML);
    
    let options = document.querySelectorAll("option");
    
    for (let i = 0; i < options.length; i++) {
        options[i].selected = options[i].value === tds[4].innerHTML;
       // console.log(options.includes(tds[4].innerHTML));
    }
    
}

function buildTable(text) {
    let data = JSON.parse(text);
    let theTable = document.querySelector("table");
    let html = theTable.querySelector("tr").innerHTML;
    for (let i = 0; i < data.length; i++) {
        let temp = data[i];
        html += "<tr>";
        html += "<td>" + temp.questionID + "</td>";
        html += "<td>" + temp.questionText + "</td>";
        html += "<td>" + temp.choices + "</td>";
        html += "<td>" + temp.answer + "</td>";
         
        console.log(data[i].tags.length);
        let a = ""; 
        for (var j = 0; j < data[i].tags.length; j++) {
            a += data[i].tags[j]["tagName"] + ", ";
            console.log(data[i].tags[j]["tagName"]);
        }
        html += "<td>" + a +"</td>";
        //html += "<td>" + data[i].tags[j]["tagName"] + "</td>";
        html += "</tr>";
    }
    //html += "</table>";
    theTable.innerHTML = html;
}

function handleChoices() {
    let numOfChoices = document.getElementById("choices").value;
    console.log(numOfChoices);
    let res = "";
    
    for (let i = 0; i < numOfChoices; i++) {
        let temp = i + 1;
        res += "[" + temp + "]" + " <input type='text' id = '" + i + "' name = '" + i + "' required><br><br>";
    }
    document.getElementById("inputChoices").innerHTML = res;
}

function checkInputs(e) {
    if (matchChoices() === false) {
        e.preventDefault();
    }
}

function matchChoices() {
    let numOfChoices = document.getElementById("choices").value;
    let temp = [numOfChoices];
    
    for (let i = 0; i < numOfChoices; i++) {
        temp[i] = document.getElementById(i).value;
    }
    let res = validateAnswer(temp);
    return res;
}

function validateAnswer(temp) {
    let answer = document.querySelector("#answer").value;
    if (temp.indexOf(answer) > -1) {
        return true;
    } else {
        document.querySelector("#error").innerHTML = "Answer must match a choice!";
        return false;
    }
}
