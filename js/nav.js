function loadTemplates() {
  fetch("list.php", {
    method: "POST",
    credentials: "include" // Important pour envoyer les cookies de session PHP
  })
  .then(response => {
    if (!response.ok) {
      throw new Error("Erreur lors du chargement des templates");
    }
    return response.text();
  })
  .then(html => {
    document.getElementById("main").innerHTML = html;
    
    document.querySelectorAll(".menu-item").forEach(element => {
      element.classList.remove("selected");
    });
    
    const menu = document.getElementById("menu-templates");
    if (menu) menu.classList.add("selected");
  })
  .catch(error => {
    console.error("Erreur AJAX :", error);
    document.getElementById("main").innerHTML = "<p>Erreur de chargement.</p>";
  });
}

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

function loadTemplate(template_id) {
  fetch(`template.php?template_id=${encodeURIComponent(template_id)}`, {
    method: 'GET',
    credentials: 'include' // Transmet les cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error("Erreur lors du chargement du template");
    }
    return response.text();
  })
  .then(html => {
    // Retirer la classe "selected" de tous les items
    document.querySelectorAll(".item-list-template").forEach(element => {
      element.classList.remove("selected");
    });

    // Ajouter la classe "selected" à l’élément cliqué
    const selected = document.getElementById(`item-template_${template_id}`);
    if (selected) selected.classList.add("selected");

    // Injecter le contenu HTML dans la zone des templates
    document.getElementById("templates").innerHTML = html;
  })
  .catch(error => {
    console.error("Erreur :", error);
    document.getElementById("templates").innerHTML = "<p>Erreur de chargement du template.</p>";
  });
}

function loadPart(part_id) {
  fetch(`part.php?part_id=${encodeURIComponent(part_id)}`, {
    method: 'GET',
    credentials: 'include' // important pour envoyer les cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur lors du chargement de la partie');
    }
    return response.text();
  })
  .then(html => {
    // Retire la classe "selected" de tous les éléments
    document.querySelectorAll(".item-list-part").forEach(element => {
      element.classList.remove("selected");
    });

    // Ajoute la classe "selected" à l'élément actif
    const selectedItem = document.getElementById(`item-part_${part_id}`);
    if (selectedItem) selectedItem.classList.add("selected");

    // Insère le contenu HTML dans la div cible
    document.getElementById("parts").innerHTML = html;
  })
  .catch(error => {
    console.error("Erreur:", error);
    document.getElementById("parts").innerHTML = "<p>Une erreur est survenue.</p>";
  });
}

function addPart(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	loadParameters(user_id);
	loadPart(user_id,this.repsoneText);
    }
    var params = "add-part=1&user_id="+user_id+"&part_name="+document.getElementById("add_part_name").value
    xhttp.open("GET","db.php?"+params,true);
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

function addSection(template_id) {
  const sectionName = document.getElementById("add_section_name").value;

  const params = new URLSearchParams({
    'add-section': 1,
    template_id,
    section_name: sectionName
  });

  fetch("db.php?" + params.toString(), {
    method: "GET",
    credentials: "same-origin"
  })
  .then(response => {
    if (!response.ok) throw new Error("Erreur serveur");
    return response.text(); // ou .json() selon ton retour PHP
  })
  .then(() => {
    loadTemplate(template_id); // rechargement de l'affichage
  })
  .catch(error => {
    console.error("Erreur lors de l'ajout de section :", error);
  });
}

function addParametersSection(part_id) {
  const sectionName = document.getElementById("add_section_name").value;

  const params = new URLSearchParams({
    'add-section-parameters': 1,
    part_id,
    section_name: sectionName
  });

  fetch("db.php?" + params.toString(), {
    method: "GET",
    credentials: "same-origin"
  })
  .then(response => {
    if (!response.ok) throw new Error("Erreur serveur");
    return response.text(); // ou .json() selon ton retour PHP
  })
  .then(() => {
    loadTemplate(template_id); // rechargement de l'affichage
  })
  .catch(error => {
    console.error("Erreur lors de l'ajout de section :", error);
  });
}

function addField(template_id, section_id) {
    const fieldNameInput = document.getElementById("add_field" + section_id + "_name");
    const fieldValueInput = document.getElementById(getTypeFieldId(section_id));
    const fieldTypeInput = document.getElementById("add_field"+section_id+"_value_select");

    const params = new URLSearchParams({
	'add-field': 1,
	template_id,
	section_id,
	field_name: fieldNameInput.value,
	field_value: fieldValueInput.value,
	field_type: fieldTypeInput.value
    });
    
    fetch("db.php?" + params.toString(), {
	method: "GET",
	credentials: "same-origin"
    })
	.then(response => {
	    if (!response.ok) throw new Error("Erreur serveur");
	    return response.text(); // ou .json() si ton PHP retourne du JSON
	})
	.then(() => {
	    loadTemplate(template_id); // Recharge le template
	})
	.catch(error => {
	    console.error("Erreur lors de l'ajout du champ :", error);
	});
}

function removeField(template_id, field_id) {
    fetch(`remove.php?field_id=${encodeURIComponent(field_id)}`, {
    method: 'GET',
    credentials: 'include' // important pour envoyer les cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur lors du chargement de la partie');
    }
      loadTemplate(template_id);
  })
}

function removeSection(template_id, section_id) {
    fetch(`remove.php?section_id=${encodeURIComponent(section_id)}`, {
    method: 'GET',
    credentials: 'include' // important pour envoyer les cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur lors du chargement de la partie');
    }
      loadTemplate(template_id);
  })
}

function removeTemplate(template_id) {
    fetch(`remove.php?template_id=${encodeURIComponent(template_id)}`, {
    method: 'GET',
    credentials: 'include' // important pour envoyer les cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur lors du chargement de la partie');
    }
      loadTemplates();
  })
}

function saveFolder(template_id) {
    const params = new URLSearchParams({
	template_id
    });
    
    fetch("save.php?" + params.toString(), {
	method: "GET",
	credentials: "same-origin"
    })
	.then(response => {
	    if (!response.ok) throw new Error("Erreur serveur");
	    return response.text();
	})
	.then(data => {
	    data.forEach(field => {
		saveField(field.id)
	    });
	})
	.catch(error => {
	    console.error("Erreur lors de l'ajout du champ :", error);
	});
}

function saveField(field_id) {
    const fieldValueInput = document.getElementById("item-field_"+field_id+"-value");

    const params = new URLSearchParams({
	field_id,
	field_value: fieldValueInput.value
    });
    
    fetch("save.php?" + params.toString(), {
	method: "GET",
	credentials: "same-origin"
    })
	.then(response => {
	    if (!response.ok) throw new Error("Erreur serveur");
	    return response.text();
	})
	.then(() => {
	})
	.catch(error => {
	    console.error("Erreur lors de l'ajout du champ :", error);
	});
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
	    for (element of document.getElementsByClassName("menu-item")){
		element.classList.remove("selected");
	    }
	    document.getElementById("menu-historics").classList.add("selected");
	}
    };
    xhttp.open("GET","historics.php?user_id="+user_id,true);
    xhttp.send();
}

function loadHistorics() {
  fetch("historics.php", {
    method: "GET",
    credentials: "include"
  })
  .then(response => {
    if (!response.ok) {
      throw new Error("Erreur lors du chargement des historiques");
    }
    return response.text();
  })
  .then(html => {
    document.getElementById("main").innerHTML = html;

    document.querySelectorAll(".menu-item").forEach(element => {
      element.classList.remove("selected");
    });

    const menu = document.getElementById("menu-historics");
    if (menu) menu.classList.add("selected");
  })
  .catch(error => {
    console.error("Erreur AJAX :", error);
    document.getElementById("main").innerHTML = "<p>Erreur de chargement.</p>";
  });
}

function loadStatistics(user_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("main").innerHTML = this.responseText;
	    for (element of document.getElementsByClassName("menu-item")){
		element.classList.remove("selected");
	    }
	    document.getElementById("menu-statistics").classList.add("selected");
	}
    };
    xhttp.open("GET","statistics.php",true);
    xhttp.send();
}
