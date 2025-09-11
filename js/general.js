let historique = [];
let sortDirection = true;
let isSidebarOpen = false;
let presets = {};

// Helper functions for radio buttons and checkboxes
function getRadioValue(name) {
    const radios = document.getElementsByName(name);
    for (let i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            return radios[i].value;
        }
    }
    return '';
}

function setRadioValue(name, value) {
    const radios = document.getElementsByName(name);
    for (let i = 0; i < radios.length; i++) {
        radios[i].checked = (radios[i].value === value);
    }
}

function getCheckboxValue(id) {
    const checkbox = document.getElementById(id);
    return checkbox ? checkbox.checked : false;
}

function setCheckboxValue(id, checked) {
    const checkbox = document.getElementById(id);
    if (checkbox) {
        checkbox.checked = checked;
    }
}

function showAuthPage(page) {
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('page-createAccount').classList.add('hidden');
    document.getElementById('page-forgotPassword').classList.add('hidden');

    if (page === 'createAccount') document.getElementById('page-createAccount').classList.remove('hidden');
    if (page === 'forgotPassword') document.getElementById('page-forgotPassword').classList.remove('hidden');
}

async function showPage(page) {
    document.getElementById('page-interventions').classList.add('hidden');
    document.getElementById('page-historique').classList.add('hidden');
    document.getElementById('page-statistiques').classList.add('hidden');
    document.getElementById('page-compte').classList.add('hidden');

    // Cacher le menu latéral si ce n'est pas la page interventions
    const sidebar = document.getElementById('sidebar');
    if (page !== 'interventions') {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        // Cacher aussi l'overlay si nécessaire
        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
            overlay.classList.add('opacity-0', 'hidden');
            overlay.classList.remove('opacity-50', 'block');
        }
        isSidebarOpen = false;
    } else {
        // Pour la page interventions, on affiche la sidebar en mode desktop
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        }
    }

    if (page === 'interventions') document.getElementById('page-interventions').classList.remove('hidden');
    if (page === 'historique') { 
        document.getElementById('page-historique').classList.remove('hidden'); 
        await loadInterventions();
        updateStatTable(); 
    }
    if (page === 'statistiques') {
        document.getElementById('page-statistiques').classList.remove('hidden');
        await loadChartData();
        renderCharts();
    }
    if (page === 'compte') { 
        document.getElementById('page-compte').classList.remove('hidden'); 
        loadCompte(); 
    }

    // Mettre à jour la navigation active
    setActiveNav(page);

    // Close sidebar if open and on a small screen
    if (isSidebarOpen && window.innerWidth < 768) { // 768px is Tailwind's 'md' breakpoint
        toggleSidebar();
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

function updateStatTable() {
    const body = document.getElementById('statTableBody');
    body.innerHTML = '';
    historique.forEach((h, i) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="p-2 border-b">${h.dateOp}</td>
            <td class="p-2 border-b">${h.niss}</td>
            <td class="p-2 border-b">${h.nom}</td>
            <td class="p-2 border-b">${h.prenom}</td>
            <td class="p-2 border-b">${h.anesthesie}</td>
            <td class="p-2 border-b">${h.assistants}</td>
            <td class="p-2 border-b">${h.typeInterv}</td>
            <td class="p-2 border-b">${h.codesInami}</td>
        `;
        row.onclick = () => editIntervention(i);
        row.classList.add('cursor-pointer','hover:bg-teal-50');
        body.appendChild(row);
    });
}

function filterTable() {
    const q = document.getElementById('searchBox').value.toLowerCase();
    document.querySelectorAll('#statTable tbody tr').forEach(r => {
        r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
}

function sortTable(col) {
    const rows = Array.from(document.querySelectorAll('#statTable tbody tr'));
    rows.sort((a,b) => {
        const A = a.cells[col].innerText;
        const B = b.cells[col].innerText;
        return sortDirection ? A.localeCompare(B) : B.localeCompare(A);
    });
    sortDirection = !sortDirection;
    const body = document.getElementById('statTableBody');
    body.innerHTML = '';
    rows.forEach(r=>body.appendChild(r));
}

// Chart instances to allow destruction and re-rendering
let chart1MInstance = null;
let chart1YInstance = null;
let chart5YInstance = null;

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

function renderCharts() {
    // Destroy existing charts if they exist to prevent issues on re-render
    if (chart1MInstance) chart1MInstance.destroy();
    if (chart1YInstance) chart1YInstance.destroy();
    if (chart5YInstance) chart5YInstance.destroy();

    chart1MInstance = new Chart(document.getElementById('chart1M'), {
        type: 'bar',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4'],
            datasets: [{
                label: 'Interventions (1 mois)',
                data: [3, 5, 2, 4],
                backgroundColor: '#0d9488'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Interventions par semaine (dernier mois)'
                }
            }
        }
    });

    chart1YInstance = new Chart(document.getElementById('chart1Y'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Interventions (1 an)',
                data: [10, 12, 15, 11, 14, 18, 20, 16, 19, 22, 25, 23],
                borderColor: '#0d9488',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Interventions par mois (dernière année)'
                }
            }
        }
    });

    chart5YInstance = new Chart(document.getElementById('chart5Y'), {
        type: 'bar',
        data: {
            labels: ['2020', '2021', '2022', '2023', '2024'],
            datasets: [{
                label: 'Interventions (5 ans)',
                data: [150, 180, 200, 220, 250],
                backgroundColor: '#67e8f9'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Interventions par année (5 dernières années)'
                }
            }
        }
    });
}

function showAuthPage(page) {
      document.getElementById('loginScreen').classList.add('hidden');
      document.getElementById('page-createAccount').classList.add('hidden');
      document.getElementById('page-forgotPassword').classList.add('hidden');

      if (page === 'createAccount') document.getElementById('page-createAccount').classList.remove('hidden');
      if (page === 'forgotPassword') document.getElementById('page-forgotPassword').classList.remove('hidden');
    }


function setActiveNav(pageId) {
    // Enlever la classe active de tous les liens de navigation
    const navLinks = document.querySelectorAll('header nav a, #sidebar nav a');
    navLinks.forEach(link => {
        link.classList.remove('text-teal-600', 'font-bold');
        link.classList.add('text-gray-600', 'hover:text-teal-600');
    });
    
    // Ajouter la classe active au lien correspondant
    const activeLink = document.querySelector(`header nav a[onclick="showPage('${pageId}')"]`);
    if (activeLink) {
        activeLink.classList.remove('text-gray-600', 'hover:text-teal-600');
        activeLink.classList.add('text-teal-600', 'font-bold');
    }
    
    // Pour la navigation mobile dans la sidebar
    const mobileActiveLink = document.querySelector(`#sidebar nav a[onclick="showPage('${pageId}');"]`);
    if (mobileActiveLink) {
        mobileActiveLink.classList.remove('text-gray-700', 'hover:bg-gray-100');
        mobileActiveLink.classList.add('bg-teal-100', 'text-teal-700');
    }
}

function filterTable() {
      const q = document.getElementById('searchBox').value.toLowerCase();
      document.querySelectorAll('#statTable tbody tr').forEach(r => {
        r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none';
      });
    }

function sortTable(col) {
      const rows = Array.from(document.querySelectorAll('#statTable tbody tr'));
      rows.sort((a,b) => {
        const A = a.cells[col].innerText;
        const B = b.cells[col].innerText;
        return sortDirection ? A.localeCompare(B) : B.localeCompare(A);
      });
      sortDirection = !sortDirection;
      const body = document.getElementById('statTableBody');
      body.innerHTML = '';
      rows.forEach(r=>body.appendChild(r));
    }

function renderCharts() {
      // Destroy existing charts if they exist to prevent issues on re-render
      if (chart1MInstance) chart1MInstance.destroy();
      if (chart1YInstance) chart1YInstance.destroy();
      if (chart5YInstance) chart5YInstance.destroy();

      chart1MInstance = new Chart(document.getElementById('chart1M'), {
        type: 'bar',
        data: {
          labels: ['S1', 'S2', 'S3', 'S4'],
          datasets: [{
            label: 'Interventions (1 mois)',
            data: [3, 5, 2, 4],
            backgroundColor: '#0d9488' /* teal-600 */
          }]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Interventions par semaine (dernier mois)'
            }
          }
        }
      });

      chart1YInstance = new Chart(document.getElementById('chart1Y'), {
        type: 'line',
        data: {
          labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
          datasets: [{
            label: 'Interventions (1 an)',
            data: [10, 12, 15, 11, 14, 18, 20, 16, 19, 22, 25, 23],
            borderColor: '#0d9488', /* teal-600 */
            tension: 0.1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Interventions par mois (dernière année)'
            }
          }
        }
      });

      chart5YInstance = new Chart(document.getElementById('chart5Y'), {
        type: 'bar',
        data: {
          labels: ['2020', '2021', '2022', '2023', '2024'],
          datasets: [{
            label: 'Interventions (5 ans)',
            data: [150, 180, 200, 220, 250],
            backgroundColor: '#67e8f9' /* cyan-300 */
          }]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Interventions par année (5 dernières années)'
            }
          }
        }
      });
    }

// Function to toggle sidebar visibility
function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if (isSidebarOpen) {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('opacity-0', 'hidden');
            overlay.classList.remove('opacity-50', 'block');
        } else {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('opacity-0', 'hidden');
            overlay.classList.add('opacity-50', 'block');
        }
        isSidebarOpen = !isSidebarOpen;
    }

// Add event listener to the hamburger toggle button
document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);

// Handle resize to ensure correct sidebar behavior on desktop/mobile switch
window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth >= 768) { // Tailwind's 'md' breakpoint
            // On desktop, ensure sidebar is visible and overlay is hidden
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.add('hidden', 'opacity-0');
            overlay.classList.remove('block', 'opacity-50');
            isSidebarOpen = false; // Reset state
        } else {
            // On mobile, if sidebar was open, keep it open, otherwise keep it hidden
            if (!isSidebarOpen) { // If it was already closed, ensure it stays hidden
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden', 'opacity-0');
                overlay.classList.remove('block', 'opacity-50');
            }
        }
    });
