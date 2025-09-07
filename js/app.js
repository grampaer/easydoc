let historique = [];
let sortDirection = true;

let presets = {};

async function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({username, password})
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById('loginScreen').classList.add('hidden');
            document.getElementById('appScreen').classList.remove('hidden');
            // Charger les presets après la connexion
            await loadPresets();
            showPage('interventions');
            loadInterventions(); // Charger les interventions après connexion
        } else {
            throw new Error(result.message || 'Identifiants incorrects');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || 'Échec de la connexion');
    }
}

async function loadPresets() {
    try {
        const response = await fetch('get_presets.php');
        if (!response.ok) throw new Error('Erreur réseau');
        const result = await response.json();
        if (result.success) {
            presets = result.data;
        } else {
            throw new Error(result.error || 'Erreur lors du chargement des presets');
        }
    } catch (error) {
        console.error('Erreur:', error);
        // Utiliser des presets par défaut en cas d'erreur
        presets = {
            ptg: { nom: "Dupont", prenom: "Jean", naissance: "1970-01-01", niss: "12345678901", numPatient: "A001", sexe: "Masculin", typeInterv: "PTG" },
            pth: { nom: "Martin", prenom: "Paul", naissance: "1965-05-12", niss: "23456789012", numPatient: "A002", sexe: "Masculin", typeInterv: "PTH" },
            lca: { nom: "Durand", prenom: "Luc", naissance: "1985-09-20", niss: "34567890123", numPatient: "A003", sexe: "Masculin", typeInterv: "LCA" },
            menisque: { nom: "Petit", prenom: "Louis", naissance: "1990-03-15", niss: "45678901234", numPatient: "A004", sexe: "Masculin", typeInterv: "Ménisque" },
            vierge: { nom: "", prenom: "", naissance: "", niss: "", numPatient: "", sexe: "Sexe", typeInterv: "" }
        };
    }
}

async function showPage(page) {
    document.getElementById('page-interventions').classList.add('hidden');
    document.getElementById('page-historique').classList.add('hidden');
    document.getElementById('page-statistiques').classList.add('hidden');

    if (page === 'interventions') document.getElementById('page-interventions').classList.remove('hidden');
    if (page === 'historique') { 
        document.getElementById('page-historique').classList.remove('hidden'); 
        await loadChartData();
        renderCharts(); 
    }
    if (page === 'statistiques') {
        document.getElementById('page-statistiques').classList.remove('hidden');
        await loadInterventions();
    }
}

function prefill(type) {
    const data = presets[type];
    if (data) {
        document.getElementById('nom').value = data.nom;
        document.getElementById('prenom').value = data.prenom;
        document.getElementById('naissance').value = data.naissance;
        document.getElementById('niss').value = data.niss;
        document.getElementById('numPatient').value = data.numPatient;
        document.getElementById('sexe').value = data.sexe;
        document.getElementById('typeInterv').value = data.typeInterv;
    }
}

async function saveIntervention() {
    const intervention = {
        nom: document.getElementById('nom').value,
        prenom: document.getElementById('prenom').value,
        naissance: document.getElementById('naissance').value,
        niss: document.getElementById('niss').value,
        numPatient: document.getElementById('numPatient').value,
        sexe: document.getElementById('sexe').value,
        dateOp: document.getElementById('dateOp').value,
        anesthesie: document.getElementById('anesthesie').value,
        assistants: document.getElementById('assistants').value,
        typeInterv: document.getElementById('typeInterv').value,
        codesInami: document.getElementById('codesInami').value,
        cote: document.getElementById('cote').value,
        membre: document.getElementById('membre').value
    };

    try {
        const response = await fetch('save_intervention.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(intervention)
        });

        if (!response.ok) throw new Error('Erreur lors de la sauvegarde');

        const result = await response.json();
        historique.push(intervention);
        updateStatTable();
        alert("Intervention enregistrée (ID: " + result.id + ")");
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || "Erreur lors de la sauvegarde");
    }
}

function updateStatTable() {
    const body = document.getElementById('statTableBody');
    body.innerHTML = '';
    historique.forEach((h, i) => {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${h.dateOp}</td>
        <td>${h.niss}</td>
        <td>${h.nom}</td>
        <td>${h.prenom}</td>
        <td>${h.anesthesie}</td>
        <td>${h.assistants}</td>
        <td>${h.typeInterv}</td>
        <td>${h.codesInami}</td>
        `;
        row.classList.add('hover:bg-teal-50','cursor-pointer');
        row.onclick = () => editIntervention(i);
        body.appendChild(row);
    });
}

function editIntervention(index) {
    const h = historique[index];
    showPage('interventions');
    document.getElementById('nom').value = h.nom;
    document.getElementById('prenom').value = h.prenom;
    document.getElementById('naissance').value = h.naissance;
    document.getElementById('niss').value = h.niss;
    document.getElementById('numPatient').value = h.numPatient;
    document.getElementById('sexe').value = h.sexe;
    document.getElementById('dateOp').value = h.dateOp;
    document.getElementById('anesthesie').value = h.anesthesie;
    document.getElementById('assistants').value = h.assistants;
    document.getElementById('typeInterv').value = h.typeInterv;
    document.getElementById('codesInami').value = h.codesInami;
}

function filterTable() {
    const query = document.getElementById('searchBox').value.toLowerCase();
    const rows = document.querySelectorAll('#statTable tbody tr');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(query) ? '' : 'none';
    });
}

function sortTable(colIndex) {
    const table = document.getElementById('statTable');
    const rows = Array.from(table.rows).slice(1);
    rows.sort((a, b) => {
        const aText = a.cells[colIndex].innerText;
        const bText = b.cells[colIndex].innerText;
        return sortDirection ? aText.localeCompare(bText) : bText.localeCompare(aText);
    });
    sortDirection = !sortDirection;
    const body = document.getElementById('statTableBody');
    body.innerHTML = '';
    rows.forEach(r => body.appendChild(r));
}

async function loadChartData() {
    try {
        const response = await fetch('data.php');
        if (!response.ok) throw new Error('Erreur réseau');
        const data = await response.json();
        // Traiter les données pour les graphiques ici
        // Vous devrez peut-être adapter renderCharts() pour utiliser ces données
        console.log('Données des graphiques:', data);
    } catch (error) {
        console.error('Erreur:', error);
        alert("Erreur lors du chargement des données des graphiques");
    }
}

async function loadInterventions() {
    try {
        const response = await fetch('get_interventions.php');
        if (!response.ok) throw new Error('Erreur réseau');
        const result = await response.json();
        if (result.success) {
            historique = result.data;
            updateStatTable();
        } else {
            throw new Error(result.error || 'Erreur lors du chargement des interventions');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || "Erreur lors du chargement des interventions");
    }
}

function renderCharts() {
    const ctx1 = document.getElementById('chart1M').getContext('2d');
    const ctx2 = document.getElementById('chart1Y').getContext('2d');
    const ctx3 = document.getElementById('chart5Y').getContext('2d');

    new Chart(ctx1, { type: 'bar', data: { labels: ['S1','S2','S3','S4'], datasets:[{label:'Interventions (1 mois)', data:[3,5,2,4], backgroundColor:'teal'}] }});

    new Chart(ctx2, { type: 'line', data: { labels:['Jan','Fév','Mar','Avr','Mai'], datasets:[{label:'1 an', data:[10,15,12,20,18], borderColor:'teal', fill:false},{label:'Moyenne', data:[15,15,15,15,15], borderColor:'orange', borderDash:[5,5], fill:false}] }});

    new Chart(ctx3, { type: 'line', data: { labels:['2020','2021','2022','2023','2024'], datasets:[{label:'5 ans', data:[100,120,90,150,170], borderColor:'teal', fill:false},{label:'Tendance', data:[126,126,126,126,126], borderColor:'orange', borderDash:[5,5], fill:false}] }});
}
