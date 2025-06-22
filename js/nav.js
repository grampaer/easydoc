function loadTemplates(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	    for (element of document.getElementsByClassName("menu-item")){
		element.classList.remove("selected");
	    }
	    document.getElementById("menu-templates").classList.add("selected");
	}
    };
    xhttp.open("GET","list.php?user_id="+user_id,true);
    xhttp.send();
}

function loadParameters(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	    for (element of document.getElementsByClassName("menu-item")){
		element.classList.remove("selected");
	    }
	    document.getElementById("menu-parameters").classList.add("selected");
	}
    };
    xhttp.open("GET","list_parameters.php?user_id="+user_id,true);
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
    xhttp.open("GET","template.php?user_id="+user_id+"&template_id="+template_id,true);
    xhttp.send();
}

function loadPart(user_id,part_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    for (element of document.getElementsByClassName("item-list-part")) {
		element.classList.remove("selected");
	    }
	    document.getElementById("item-part_"+template_id).classList.add("selected");
	    document.getElementById("parts").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","part.php?user_id="+user_id+"&part_id="+part_id,true);
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

function addSection(user_id,template_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadTemplates(user_id);
	loadTemplate(user_id,template_id);
    }
    var params = "add-section=1&user_id="+user_id+"&template_id="+template_id+"&section_name="+document.getElementById("add_section_name").value;
    xhttp.open("GET","db.php?"+params,true);
    xhttp.send();
}

function addField(user_id,template_id,section_id) {
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadTemplate(user_id,template_id);
    }
    var params = "add-field=1&user_id="+user_id+"&template_id="+template_id+"&section_id="+section_id+"&field_name="+document.getElementById("add_field"+section_id+"_name").value+"&field_value="+document.getElementById(getTypeFieldId(section_id)).value;
    xhttp.open("GET","db.php?"+params,true);
    xhttp.send();
}

function saveFolder(user_id) {
    
}

function getTypeFieldId(section_id) {

    const type = $("#add_field"+section_id+"_value_select").find(':selected').data('type');

    switch(type) {
    case 0:
	return "add_field"+section_id+"_value_text";
	break;
    case 1: 
	return "add_field"+section_id+"_value_date";
	break;
    case 2:
	return "add_field"+section_id+"_value_1line";
	break;
    default:
	return "add_field"+section_id+"_value_date";
	break;
    }
}

function changeTypeField(section_id) {

    if (!document.getElementById("add_field"+section_id+"_value_text").classList.contains("hidden"))
	document.getElementById("add_field"+section_id+"_value_text").classList.add("hidden");
    if (!document.getElementById("add_field"+section_id+"_value_date").classList.contains("hidden"))
	document.getElementById("add_field"+section_id+"_value_date").classList.add("hidden");
    if (!document.getElementById("add_field"+section_id+"_value_1line").classList.contains("hidden"))
	document.getElementById("add_field"+section_id+"_value_1line").classList.add("hidden");
    if (!document.getElementById("add_field"+section_id+"_value_options").classList.contains("hidden"))
	document.getElementById("add_field"+section_id+"_value_options").classList.add("hidden");

    const type = $("#add_field"+section_id+"_value_select").find(':selected').data('type');
    const type_id = $("#add_field"+section_id+"_value_select").find(':selected').value;
    
    switch(type) {
    case 0:
	document.getElementById("add_field"+section_id+"_value_text").classList.remove("hidden");
	break;
    case 1: 
	document.getElementById("add_field"+section_id+"_value_date").classList.remove("hidden");
	break;
    case 2: 
	document.getElementById("add_field"+section_id+"_value_1line").classList.remove("hidden");
	break;
    default:
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	    $.ajax({
		dataType: "json",
		success: function(data) {
		    for (i in data) {
			a.push(data[i].name);
		    }
		}
	    });
	}
	var params = "get-type-options=1&type_id="+type_id;
	xhttp.open("GET","db.php?"+params,true);
	xhttp.send();
	break;

    }
    
}

function loadHistorics(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","historics.php?user_id="+user_id,true);
    xhttp.send();
}

function loadStatistics(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	}
    };
    xhttp.open("GET","statistics.php",true);
    xhttp.send();
}
