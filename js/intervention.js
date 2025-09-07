async function saveIntervention() {
    const intervention = {
        // Patient Identification
        nom: document.getElementById('nom').value,
        prenom: document.getElementById('prenom').value,
        naissance: document.getElementById('naissance').value,
        niss: document.getElementById('niss').value,
        numPatient: document.getElementById('numPatient').value,
        sexe: getRadioValue('sexe'),

        // Intervention
        dateOp: document.getElementById('dateOp').value,
        anesthesie: document.getElementById('anesthesie').value,
        typeAnesthesie: document.getElementById('typeAnesthesie').value,
        assistants: document.getElementById('assistants').value,
        infirmiers: document.getElementById('infirmiers').value,
        internes: document.getElementById('internes').value,
        typeInterv: document.getElementById('typeInterv').value,
        cote: getRadioValue('cote'),
        membreOpere: document.getElementById('membreOpere').value,
        codesInami: document.getElementById('codesInami').value,

        // Incapacité de travail
        nbSemaines: getRadioValue('nbSemaines'),
        arrondirDimanche: getRadioValue('arrondirDimanche'),
        dateDebutIncap: document.getElementById('dateDebutIncap').value,
        dateFinIncap: document.getElementById('dateFinIncap').value,

        // Prescription de kinésithérapie
        nbSeancesKine: document.getElementById('nbSeancesKine').value,
        frequenceSeancesKine: document.getElementById('frequenceSeancesKine').value,
        consignesKine: document.getElementById('consignesKine').value,

        // Soins de plaie
        matPansements: getCheckboxValue('matPansements'),
        matIsobetadine: getCheckboxValue('matIsobetadine'),
        matChlorexidine: getCheckboxValue('matChlorexidine'),
        matCompresses: getCheckboxValue('matCompresses'),
        soinsInfirmiers: getRadioValue('soinsInfirmiers'),
        frequenceSoins: document.getElementById('frequenceSoins').value,
        descriptionSoins: document.getElementById('descriptionSoins').value,
        dureeSoins: document.getElementById('dureeSoins').value,

        // Prescription de médicaments
        medParacetamol: getCheckboxValue('medParacetamol'),
        medIbuprofene: getCheckboxValue('medIbuprofene'),
        medTradonal: getCheckboxValue('medTradonal'),

        // Imagerie médicale de controle
        imagerie1Quand: document.getElementById('imagerie1Quand').value,
        imagerie1InfoClinique: document.getElementById('imagerie1InfoClinique').value,
        imagerie1DemandeDiag: document.getElementById('imagerie1DemandeDiag').value,
        imagerie1Type: document.getElementById('imagerie1Type').value,
        imagerie1MembreCote: document.getElementById('imagerie1MembreCote').value,
        imagerie2Quand: document.getElementById('imagerie2Quand').value,
        imagerie2InfoClinique: document.getElementById('imagerie2InfoClinique').value,
        imagerie2DemandeDiag: document.getElementById('imagerie2DemandeDiag').value,
        imagerie2Type: document.getElementById('imagerie2Type').value,
        imagerie2MembreCote: document.getElementById('imagerie2MembreCote').value,

        // Dispositions particulière
        priseAppui: getRadioValue('priseAppui')
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
        clearInterventionForm();
        alert("Intervention enregistrée (ID: " + result.id + ")");
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || "Erreur lors de la sauvegarde");
    }
}

function clearInterventionForm() {
    // Patient Identification
    document.getElementById('nom').value = '';
    document.getElementById('prenom').value = '';
    document.getElementById('naissance').value = '';
    document.getElementById('niss').value = '';
    document.getElementById('numPatient').value = '';
    setRadioValue('sexe', '');

    // Intervention
    document.getElementById('dateOp').value = '';
    document.getElementById('anesthesie').value = '';
    document.getElementById('typeAnesthesie').value = '';
    document.getElementById('assistants').value = '';
    document.getElementById('infirmiers').value = '';
    document.getElementById('internes').value = '';
    document.getElementById('typeInterv').value = '';
    setRadioValue('cote', '');
    document.getElementById('membreOpere').value = '';
    document.getElementById('codesInami').value = '';

    // Incapacité de travail
    setRadioValue('nbSemaines', '');
    setRadioValue('arrondirDimanche', '');
    document.getElementById('dateDebutIncap').value = '';
    document.getElementById('dateFinIncap').value = '';

    // Prescription de kinésithérapie
    document.getElementById('nbSeancesKine').value = '';
    document.getElementById('frequenceSeancesKine').value = '';
    document.getElementById('consignesKine').value = '';

    // Soins de plaie
    setCheckboxValue('matPansements', false);
    setCheckboxValue('matIsobetadine', false);
    setCheckboxValue('matChlorexidine', false);
    setCheckboxValue('matCompresses', false);
    setRadioValue('soinsInfirmiers', '');
    document.getElementById('frequenceSoins').value = '';
    document.getElementById('descriptionSoins').value = '';
    document.getElementById('dureeSoins').value = '';

    // Prescription de médicaments
    setCheckboxValue('medParacetamol', false);
    setCheckboxValue('medIbuprofene', false);
    setCheckboxValue('medTradonal', false);

    // Imagerie médicale de controle
    document.getElementById('imagerie1Quand').value = '';
    document.getElementById('imagerie1InfoClinique').value = '';
    document.getElementById('imagerie1DemandeDiag').value = '';
    document.getElementById('imagerie1Type').value = '';
    document.getElementById('imagerie1MembreCote').value = '';
    document.getElementById('imagerie2Quand').value = '';
    document.getElementById('imagerie2InfoClinique').value = '';
    document.getElementById('imagerie2DemandeDiag').value = '';
    document.getElementById('imagerie2Type').value = '';
    document.getElementById('imagerie2MembreCote').value = '';

    // Dispositions particulière
    setRadioValue('priseAppui', '');
}

function editIntervention(i) {
    const h = historique[i];
    showPage('interventions');

    // Patient Identification
    document.getElementById('nom').value = h.nom || '';
    document.getElementById('prenom').value = h.prenom || '';
    document.getElementById('naissance').value = h.naissance || '';
    document.getElementById('niss').value = h.niss || '';
    document.getElementById('numPatient').value = h.numPatient || '';
    setRadioValue('sexe', h.sexe || '');

    // Intervention
    document.getElementById('dateOp').value = h.dateOp || '';
    document.getElementById('anesthesie').value = h.anesthesie || '';
    document.getElementById('typeAnesthesie').value = h.typeAnesthesie || '';
    document.getElementById('assistants').value = h.assistants || '';
    document.getElementById('infirmiers').value = h.infirmiers || '';
    document.getElementById('internes').value = h.internes || '';
    document.getElementById('typeInterv').value = h.typeInterv || '';
    setRadioValue('cote', h.cote || '');
    document.getElementById('membreOpere').value = h.membreOpere || '';
    document.getElementById('codesInami').value = h.codesInami || '';

    // Incapacité de travail
    setRadioValue('nbSemaines', h.nbSemaines || '');
    setRadioValue('arrondirDimanche', h.arrondirDimanche || '');
    document.getElementById('dateDebutIncap').value = h.dateDebutIncap || '';
    document.getElementById('dateFinIncap').value = h.dateFinIncap || '';

    // Prescription de kinésithérapie
    document.getElementById('nbSeancesKine').value = h.nbSeancesKine || '';
    document.getElementById('frequenceSeancesKine').value = h.frequenceSeancesKine || '';
    document.getElementById('consignesKine').value = h.consignesKine || '';

    // Soins de plaie
    setCheckboxValue('matPansements', h.matPansements || false);
    setCheckboxValue('matIsobetadine', h.matIsobetadine || false);
    setCheckboxValue('matChlorexidine', h.matChlorexidine || false);
    setCheckboxValue('matCompresses', h.matCompresses || false);
    setRadioValue('soinsInfirmiers', h.soinsInfirmiers || '');
    document.getElementById('frequenceSoins').value = h.frequenceSoins || '';
    document.getElementById('descriptionSoins').value = h.descriptionSoins || '';
    document.getElementById('dureeSoins').value = h.dureeSoins || '';

    // Prescription de médicaments
    setCheckboxValue('medParacetamol', h.medParacetamol || false);
    setCheckboxValue('medIbuprofene', h.medIbuprofene || false);
    setCheckboxValue('medTradonal', h.medTradonal || false);

    // Imagerie médicale de controle
    document.getElementById('imagerie1Quand').value = h.imagerie1Quand || '';
    document.getElementById('imagerie1InfoClinique').value = h.imagerie1InfoClinique || '';
    document.getElementById('imagerie1DemandeDiag').value = h.imagerie1DemandeDiag || '';
    document.getElementById('imagerie1Type').value = h.imagerie1Type || '';
    document.getElementById('imagerie1MembreCote').value = h.imagerie1MembreCote || '';
    document.getElementById('imagerie2Quand').value = h.imagerie2Quand || '';
    document.getElementById('imagerie2InfoClinique').value = h.imagerie2InfoClinique || '';
    document.getElementById('imagerie2DemandeDiag').value = h.imagerie2DemandeDiag || '';
    document.getElementById('imagerie2Type').value = h.imagerie2Type || '';
    document.getElementById('imagerie2MembreCote').value = h.imagerie2MembreCote || '';

    // Dispositions particulière
    setRadioValue('priseAppui', h.priseAppui || '');
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

function loadInterventionTemplate(type) {
    clearInterventionForm();

    // Show the interventions page
    showPage('interventions');

    switch (type) {
        case 'PTG':
            document.getElementById('typeInterv').value = 'Arthroplastie totale de genou';
            document.getElementById('codesInami').value = '456789';
            document.getElementById('membreOpere').value = 'Genou';
            document.getElementById('typeAnesthesie').value = 'Générale + Locorégionale';
            setRadioValue('nbSemaines', '12 semaines');
            setRadioValue('priseAppui', 'Appui autorisé avec des béquilles');
            setCheckboxValue('matPansements', true);
            setCheckboxValue('matIsobetadine', true);
            setCheckboxValue('medParacetamol', true);
            setCheckboxValue('medIbuprofene', true);
            document.getElementById('nbSeancesKine').value = 20;
            document.getElementById('frequenceSeancesKine').value = '3x/semaine';
            document.getElementById('consignesKine').value = 'Protocole de rééducation PTG';
            document.getElementById('imagerie1Type').value = 'Radiographie Genou';
            document.getElementById('imagerie1Quand').value = '6 semaines post-op';
            document.getElementById('imagerie1InfoClinique').value = 'Contrôle post-PTG';
            document.getElementById('imagerie1DemandeDiag').value = 'Exclure complications, évaluer position prothèse';
            document.getElementById('imagerie1MembreCote').value = 'Genou';
            break;
        case 'PTH':
            document.getElementById('typeInterv').value = 'Arthroplastie totale de hanche';
            document.getElementById('codesInami').value = '456780';
            document.getElementById('membreOpere').value = 'Hanche';
            document.getElementById('typeAnesthesie').value = 'Générale + Locorégionale';
            setRadioValue('nbSemaines', '12 semaines');
            setRadioValue('priseAppui', 'Appui autorisé avec des béquilles');
            setCheckboxValue('matPansements', true);
            setCheckboxValue('matIsobetadine', true);
            setCheckboxValue('medParacetamol', true);
            setCheckboxValue('medIbuprofene', true);
            document.getElementById('nbSeancesKine').value = 20;
            document.getElementById('frequenceSeancesKine').value = '3x/semaine';
            document.getElementById('consignesKine').value = 'Protocole de rééducation PTH';
            document.getElementById('imagerie1Type').value = 'Radiographie Hanche';
            document.getElementById('imagerie1Quand').value = '6 semaines post-op';
            document.getElementById('imagerie1InfoClinique').value = 'Contrôle post-PTH';
            document.getElementById('imagerie1DemandeDiag').value = 'Exclure complications, évaluer position prothèse';
            document.getElementById('imagerie1MembreCote').value = 'Hanche';
            break;
        case 'LCA':
            document.getElementById('typeInterv').value = 'Reconstruction LCA';
            document.getElementById('codesInami').value = '456781';
            document.getElementById('membreOpere').value = 'Genou';
            document.getElementById('typeAnesthesie').value = 'Générale';
            setRadioValue('nbSemaines', '4 semaines');
            setRadioValue('priseAppui', 'Appui autorisé immédiatement');
            setCheckboxValue('matPansements', true);
            setCheckboxValue('medParacetamol', true);
            document.getElementById('nbSeancesKine').value = 30;
            document.getElementById('frequenceSeancesKine').value = '3-4x/semaine';
            document.getElementById('consignesKine').value = 'Protocole de rééducation LCA';
            document.getElementById('imagerie1Type').value = 'IRM Genou';
            document.getElementById('imagerie1Quand').value = 'Si doute clinique';
            document.getElementById('imagerie1InfoClinique').value = 'Douleur/instabilité post-LCA';
            document.getElementById('imagerie1DemandeDiag').value = 'Évaluation greffon, épanchement';
            document.getElementById('imagerie1MembreCote').value = 'Genou';
            break;
        case 'Menisque':
            document.getElementById('typeInterv').value = 'Méniscectomie / Suture méniscale';
            document.getElementById('codesInami').value = '456782';
            document.getElementById('membreOpere').value = 'Genou';
            document.getElementById('typeAnesthesie').value = 'Locorégionale';
            setRadioValue('nbSemaines', '1 semaine');
            setRadioValue('priseAppui', 'Appui autorisé immédiatement');
            setCheckboxValue('matPansements', true);
            setCheckboxValue('medParacetamol', true);
            document.getElementById('nbSeancesKine').value = 10;
            document.getElementById('frequenceSeancesKine').value = '2x/semaine';
            document.getElementById('consignesKine').value = 'Protocole de rééducation ménisque';
            document.getElementById('imagerie1Type').value = 'Radiographie Genou';
            document.getElementById('imagerie1Quand').value = 'Si douleur persistante';
            document.getElementById('imagerie1InfoClinique').value = 'Douleur persistante post-ménisque';
            document.getElementById('imagerie1DemandeDiag').value = 'Recherche corps étranger, arthrose';
            document.getElementById('imagerie1MembreCote').value = 'Genou';
            break;
        case 'Vierge':
            break;
    }
}

function saveIntervention() {
      const intervention = {
        // Patient Identification
        nom: document.getElementById('nom').value,
        prenom: document.getElementById('prenom').value,
        naissance: document.getElementById('naissance').value,
        niss: document.getElementById('niss').value,
        numPatient: document.getElementById('numPatient').value,
        sexe: getRadioValue('sexe'),

        // Intervention
        dateOp: document.getElementById('dateOp').value,
        anesthesie: document.getElementById('anesthesie').value,
        typeAnesthesie: document.getElementById('typeAnesthesie').value,
        assistants: document.getElementById('assistants').value,
        infirmiers: document.getElementById('infirmiers').value,
        internes: document.getElementById('internes').value,
        typeInterv: document.getElementById('typeInterv').value,
        cote: getRadioValue('cote'),
        membreOpere: document.getElementById('membreOpere').value,
        codesInami: document.getElementById('codesInami').value,

        // Incapacité de travail
        nbSemaines: getRadioValue('nbSemaines'),
        arrondirDimanche: getRadioValue('arrondirDimanche'),
        dateDebutIncap: document.getElementById('dateDebutIncap').value,
        dateFinIncap: document.getElementById('dateFinIncap').value,

        // Prescription de kinésithérapie
        nbSeancesKine: document.getElementById('nbSeancesKine').value,
        frequenceSeancesKine: document.getElementById('frequenceSeancesKine').value,
        consignesKine: document.getElementById('consignesKine').value,

        // Soins de plaie
        matPansements: getCheckboxValue('matPansements'),
        matIsobetadine: getCheckboxValue('matIsobetadine'),
        matChlorexidine: getCheckboxValue('matChlorexidine'),
        matCompresses: getCheckboxValue('matCompresses'),
        soinsInfirmiers: getRadioValue('soinsInfirmiers'),
        frequenceSoins: document.getElementById('frequenceSoins').value,
        descriptionSoins: document.getElementById('descriptionSoins').value,
        dureeSoins: document.getElementById('dureeSoins').value,

        // Prescription de médicaments
        medParacetamol: getCheckboxValue('medParacetamol'),
        medIbuprofene: getCheckboxValue('medIbuprofene'),
        medTradonal: getCheckboxValue('medTradonal'),

        // Imagerie médicale de controle
        imagerie1Quand: document.getElementById('imagerie1Quand').value,
        imagerie1InfoClinique: document.getElementById('imagerie1InfoClinique').value,
        imagerie1DemandeDiag: document.getElementById('imagerie1DemandeDiag').value,
        imagerie1Type: document.getElementById('imagerie1Type').value,
        imagerie1MembreCote: document.getElementById('imagerie1MembreCote').value,
        imagerie2Quand: document.getElementById('imagerie2Quand').value,
        imagerie2InfoClinique: document.getElementById('imagerie2InfoClinique').value,
        imagerie2DemandeDiag: document.getElementById('imagerie2DemandeDiag').value,
        imagerie2Type: document.getElementById('imagerie2Type').value,
        imagerie2MembreCote: document.getElementById('imagerie2MembreCote').value,

        // Dispositions particulière
        priseAppui: getRadioValue('priseAppui')
      };
      
      // Ajouter un ID unique pour chaque intervention
      intervention.id = Date.now();
      
      // Charger les interventions existantes depuis localStorage
      let savedInterventions = JSON.parse(localStorage.getItem('interventions')) || [];
      
      // Ajouter la nouvelle intervention
      savedInterventions.push(intervention);
      
      // Sauvegarder dans localStorage
      localStorage.setItem('interventions', JSON.stringify(savedInterventions));
      
      // Mettre à jour l'historique local et l'interface
      historique = savedInterventions;
      updateStatTable();
      clearInterventionForm();
      alert("Intervention enregistrée !");
    }

function clearInterventionForm() {
      // Patient Identification
      document.getElementById('nom').value = '';
      document.getElementById('prenom').value = '';
      document.getElementById('naissance').value = '';
      document.getElementById('niss').value = '';
      document.getElementById('numPatient').value = '';
      setRadioValue('sexe', ''); // Clear radio selection

      // Intervention
      document.getElementById('dateOp').value = '';
      document.getElementById('anesthesie').value = '';
      document.getElementById('typeAnesthesie').value = '';
      document.getElementById('assistants').value = '';
      document.getElementById('infirmiers').value = '';
      document.getElementById('internes').value = '';
      document.getElementById('typeInterv').value = '';
      setRadioValue('cote', ''); // Clear radio selection
      document.getElementById('membreOpere').value = '';
      document.getElementById('codesInami').value = '';

      // Incapacité de travail
      setRadioValue('nbSemaines', '');
      setRadioValue('arrondirDimanche', '');
      document.getElementById('dateDebutIncap').value = '';
      document.getElementById('dateFinIncap').value = '';

      // Prescription de kinésithérapie
      document.getElementById('nbSeancesKine').value = '';
      document.getElementById('frequenceSeancesKine').value = '';
      document.getElementById('consignesKine').value = '';

      // Soins de plaie
      setCheckboxValue('matPansements', false);
      setCheckboxValue('matIsobetadine', false);
      setCheckboxValue('matChlorexidine', false);
      setCheckboxValue('matCompresses', false);
      setRadioValue('soinsInfirmiers', '');
      document.getElementById('frequenceSoins').value = '';
      document.getElementById('descriptionSoins').value = '';
      document.getElementById('dureeSoins').value = '';

      // Prescription de médicaments
      setCheckboxValue('medParacetamol', false);
      setCheckboxValue('medIbuprofene', false);
      setCheckboxValue('medTradonal', false);

      // Imagerie médicale de controle
      document.getElementById('imagerie1Quand').value = '';
      document.getElementById('imagerie1InfoClinique').value = '';
      document.getElementById('imagerie1DemandeDiag').value = '';
      document.getElementById('imagerie1Type').value = '';
      document.getElementById('imagerie1MembreCote').value = '';
      document.getElementById('imagerie2Quand').value = '';
      document.getElementById('imagerie2InfoClinique').value = '';
      document.getElementById('imagerie2DemandeDiag').value = '';
      document.getElementById('imagerie2Type').value = '';
      document.getElementById('imagerie2MembreCote').value = '';

      // Dispositions particulière
      setRadioValue('priseAppui', '');
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
        // Ajouter un bouton de suppression
        const deleteCell = document.createElement('td');
        deleteCell.className = 'p-2 border-b';
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Supprimer';
        deleteButton.className = 'bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600';
        deleteButton.onclick = (e) => {
            e.stopPropagation(); // Empêcher le déclenchement de l'édition
            deleteIntervention(h.id);
        };
        deleteCell.appendChild(deleteButton);
        row.appendChild(deleteCell);
        
        row.onclick = () => editIntervention(i);
        row.classList.add('cursor-pointer','hover:bg-teal-50');
        body.appendChild(row);
    });
}

async function deleteIntervention(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')) {
        try {
            const response = await fetch(`remove.php?intervention_id=${encodeURIComponent(id)}`, {
                method: 'GET',
                credentials: 'include'
            });
            
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Erreur lors de la suppression');
            }
            
            // Recharger les interventions depuis le serveur
            await loadInterventions();
        } catch (error) {
            console.error('Erreur:', error);
            alert(error.message || "Erreur lors de la suppression");
        }
    }
}

function editIntervention(i) {
      const h = historique[i];
      showPage('interventions'); // Navigate back to interventions page

      // Patient Identification
      document.getElementById('nom').value = h.nom || '';
      document.getElementById('prenom').value = h.prenom || '';
      document.getElementById('naissance').value = h.naissance || '';
      document.getElementById('niss').value = h.niss || '';
      document.getElementById('numPatient').value = h.numPatient || '';
      setRadioValue('sexe', h.sexe || '');

      // Intervention
      document.getElementById('dateOp').value = h.dateOp || '';
      document.getElementById('anesthesie').value = h.anesthesie || '';
      document.getElementById('typeAnesthesie').value = h.typeAnesthesie || '';
      document.getElementById('assistants').value = h.assistants || '';
      document.getElementById('infirmiers').value = h.infirmiers || '';
      document.getElementById('internes').value = h.internes || '';
      document.getElementById('typeInterv').value = h.typeInterv || '';
      setRadioValue('cote', h.cote || '');
      document.getElementById('membreOpere').value = h.membreOpere || '';
      document.getElementById('codesInami').value = h.codesInami || '';

      // Incapacité de travail
      setRadioValue('nbSemaines', h.nbSemaines || '');
      setRadioValue('arrondirDimanche', h.arrondirDimanche || '');
      document.getElementById('dateDebutIncap').value = h.dateDebutIncap || '';
      document.getElementById('dateFinIncap').value = h.dateFinIncap || '';

      // Prescription de kinésithérapie
      document.getElementById('nbSeancesKine').value = h.nbSeancesKine || '';
      document.getElementById('frequenceSeancesKine').value = h.frequenceSeancesKine || '';
      document.getElementById('consignesKine').value = h.consignesKine || '';

      // Soins de plaie
      setCheckboxValue('matPansements', h.matPansements || false);
      setCheckboxValue('matIsobetadine', h.matIsobetadine || false);
      setCheckboxValue('matChlorexidine', h.matChlorexidine || false);
      setCheckboxValue('matCompresses', h.matCompresses || false);
      setRadioValue('soinsInfirmiers', h.soinsInfirmiers || '');
      document.getElementById('frequenceSoins').value = h.frequenceSoins || '';
      document.getElementById('descriptionSoins').value = h.descriptionSoins || '';
      document.getElementById('dureeSoins').value = h.dureeSoins || '';

      // Prescription de médicaments
      setCheckboxValue('medParacetamol', h.medParacetamol || false);
      setCheckboxValue('medIbuprofene', h.medIbuprofene || false);
      setCheckboxValue('medTradonal', h.medTradonal || false);

      // Imagerie médicale de controle
      document.getElementById('imagerie1Quand').value = h.imagerie1Quand || '';
      document.getElementById('imagerie1InfoClinique').value = h.imagerie1InfoClinique || '';
      document.getElementById('imagerie1DemandeDiag').value = h.imagerie1DemandeDiag || '';
      document.getElementById('imagerie1Type').value = h.imagerie1Type || '';
      document.getElementById('imagerie1MembreCote').value = h.imagerie1MembreCote || '';
      document.getElementById('imagerie2Quand').value = h.imagerie2Quand || '';
      document.getElementById('imagerie2InfoClinique').value = h.imagerie2InfoClinique || '';
      document.getElementById('imagerie2DemandeDiag').value = h.imagerie2DemandeDiag || '';
      document.getElementById('imagerie2Type').value = h.imagerie2Type || '';
      document.getElementById('imagerie2MembreCote').value = h.imagerie2MembreCote || '';

      // Dispositions particulière
      setRadioValue('priseAppui', h.priseAppui || '');
    }

function loadInterventionTemplate(type) {
        clearInterventionForm(); // Always start with a clean form

        // Show the interventions page
        showPage('interventions');

        switch (type) {
            case 'PTG': // Prothèse Totale de Genou
                document.getElementById('typeInterv').value = 'Arthroplastie totale de genou';
                document.getElementById('codesInami').value = '456789'; // Exemple de code INAMI
                document.getElementById('membreOpere').value = 'Genou';
                document.getElementById('typeAnesthesie').value = 'Générale + Locorégionale';
                setRadioValue('nbSemaines', '12 semaines');
                setRadioValue('priseAppui', 'Appui autorisé avec des béquilles');
                setCheckboxValue('matPansements', true);
                setCheckboxValue('matIsobetadine', true);
                setCheckboxValue('medParacetamol', true);
                setCheckboxValue('medIbuprofene', true);
                document.getElementById('nbSeancesKine').value = 20;
                document.getElementById('frequenceSeancesKine').value = '3x/semaine';
                document.getElementById('consignesKine').value = 'Protocole de rééducation PTG';
                document.getElementById('imagerie1Type').value = 'Radiographie Genou';
                document.getElementById('imagerie1Quand').value = '6 semaines post-op';
                document.getElementById('imagerie1InfoClinique').value = 'Contrôle post-PTG';
                document.getElementById('imagerie1DemandeDiag').value = 'Exclure complications, évaluer position prothèse';
                document.getElementById('imagerie1MembreCote').value = 'Genou';
                break;
            case 'PTH': // Prothèse Totale de Hanche
                document.getElementById('typeInterv').value = 'Arthroplastie totale de hanche';
                document.getElementById('codesInami').value = '456780'; // Exemple de code INAMI
                document.getElementById('membreOpere').value = 'Hanche';
                document.getElementById('typeAnesthesie').value = 'Générale + Locorégionale';
                setRadioValue('nbSemaines', '12 semaines');
                setRadioValue('priseAppui', 'Appui autorisé avec des béquilles');
                setCheckboxValue('matPansements', true);
                setCheckboxValue('matIsobetadine', true);
                setCheckboxValue('medParacetamol', true);
                setCheckboxValue('medIbuprofene', true);
                document.getElementById('nbSeancesKine').value = 20;
                document.getElementById('frequenceSeancesKine').value = '3x/semaine';
                document.getElementById('consignesKine').value = 'Protocole de rééducation PTH';
                document.getElementById('imagerie1Type').value = 'Radiographie Hanche';
                document.getElementById('imagerie1Quand').value = '6 semaines post-op';
                document.getElementById('imagerie1InfoClinique').value = 'Contrôle post-PTH';
                document.getElementById('imagerie1DemandeDiag').value = 'Exclure complications, évaluer position prothèse';
                document.getElementById('imagerie1MembreCote').value = 'Hanche';
                break;
            case 'LCA': // Ligament Croisé Antérieur
                document.getElementById('typeInterv').value = 'Reconstruction LCA';
                document.getElementById('codesInami').value = '456781'; // Exemple de code INAMI
                document.getElementById('membreOpere').value = 'Genou';
                document.getElementById('typeAnesthesie').value = 'Générale';
                setRadioValue('nbSemaines', '4 semaines'); // Durée initiale, peut être plus longue
                setRadioValue('priseAppui', 'Appui autorisé immédiatement'); // Souvent avec attelle
                setCheckboxValue('matPansements', true);
                setCheckboxValue('medParacetamol', true);
                document.getElementById('nbSeancesKine').value = 30;
                document.getElementById('frequenceSeancesKine').value = '3-4x/semaine';
                document.getElementById('consignesKine').value = 'Protocole de rééducation LCA';
                document.getElementById('imagerie1Type').value = 'IRM Genou';
                document.getElementById('imagerie1Quand').value = 'Si doute clinique';
                document.getElementById('imagerie1InfoClinique').value = 'Douleur/instabilité post-LCA';
                document.getElementById('imagerie1DemandeDiag').value = 'Évaluation greffon, épanchement';
                document.getElementById('imagerie1MembreCote').value = 'Genou';
                break;
            case 'Menisque': // Méniscectomie / Suture méniscale
                document.getElementById('typeInterv').value = 'Méniscectomie / Suture méniscale';
                document.getElementById('codesInami').value = '456782'; // Exemple de code INAMI
                document.getElementById('membreOpere').value = 'Genou';
                document.getElementById('typeAnesthesie').value = 'Locorégionale';
                setRadioValue('nbSemaines', '1 semaine'); // Pour méniscectomie, suture serait plus longue
                setRadioValue('priseAppui', 'Appui autorisé immédiatement'); // Pour méniscectomie
                setCheckboxValue('matPansements', true);
                setCheckboxValue('medParacetamol', true);
                document.getElementById('nbSeancesKine').value = 10;
                document.getElementById('frequenceSeancesKine').value = '2x/semaine';
                document.getElementById('consignesKine').value = 'Protocole de rééducation ménisque';
                document.getElementById('imagerie1Type').value = 'Radiographie Genou';
                document.getElementById('imagerie1Quand').value = 'Si douleur persistante';
                document.getElementById('imagerie1InfoClinique').value = 'Douleur persistante post-ménisque';
                document.getElementById('imagerie1DemandeDiag').value = 'Recherche corps étranger, arthrose';
                document.getElementById('imagerie1MembreCote').value = 'Genou';
                break;
            case 'Vierge':
                // clearInterventionForm() est déjà appelée en début de fonction, donc pas d'action supplémentaire nécessaire ici.
                break;
        }
    }
