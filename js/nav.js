function loadTemplates(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	    for (element of document.getElementsByClassName("menu-item")){
		element.classList.remove("selected");
	    }
	    document.getElementById("menu-templates").classList.add("selected");
	h}
    };
    xhttp.open("GET","list.php?user_id="+user_id,true);
    xhttp.send();
}

function loadTemplate(user_id,template_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    for (element of document.getElementsByClassName("item-list-template")) {
		element.classList.remove("selected");
	    }
	    document.getElementById("item-template_"+template_id).classList.add("selected");
	    document.getElementById("templates").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","templates.php?user_id="+user_id+"&template_id="+template_id,true);
    xhttp.send();
}

function addTemplate(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadTemplates(user_id);
	loadTemplate(user_id,this.repsoneText);
    }
    var params = "add-template=1&user_id="+user_id+"&template_name="+document.getElementById("add_template_name").value
    xhttp.open("GET","db.php?"+params,true);
    xhttp.send();
}

function addSetion(user_id,template_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadTemplates(user_id);
	loadTemplate(user_id,this.repsoneText);
    }
    var params = "add-section=1&user_id="+user_id+"&template_id="+template_id+"&section_name="+document.getElementById("add_template_name").value
    xhttp.open("GET","db.php?"+params,true);
    xhttp.send();
}

function addField(user_id,template_id,section_id) {
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadTemplates(user_id);
	loadTemplate(user_id,this.repsoneText);
    }
    var params = "add-field=1&user_id="+user_id+"&template_id="+template_id+"&section_id="+section_id+"field_name="+document.getElementById("add_template_name").value;
    xhttp.open("GET","db.php?"+params,true);
    xhttp.send();
}

function loadHistorics() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","historics.php",true);
    xhttp.send();
}

function loadStatistics() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","statistics.php",true);
    xhttp.send();
}

function loadParameters() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","parameters.php",true);
    xhttp.send();
}
