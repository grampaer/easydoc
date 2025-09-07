
function backToLogin() {
    document.getElementById('page-createAccount').classList.add('hidden');
    document.getElementById('page-forgotPassword').classList.add('hidden');
    document.getElementById('loginScreen').classList.remove('hidden');
}

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

async function createAccount() {
    const nom = document.getElementById('newNom').value;
    const prenom = document.getElementById('newPrenom').value;
    const email = document.getElementById('newEmail').value;
    const password = document.getElementById('newPassword').value;
    const specialite = document.getElementById('newSpecialite').value;

    try {
        const response = await fetch('signup.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nom,
                prenom,
                email,
                password,
                specialite
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
            backToLogin();
        } else {
            throw new Error(result.message || 'Erreur lors de la création du compte');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || 'Échec de la création du compte');
    }
}

function loadCompte() {
    document.getElementById('compteNom').value = localStorage.getItem('compteNom') || '';
    document.getElementById('comptePrenom').value = localStorage.getItem('comptePrenom') || '';
    document.getElementById('compteHopital').value = localStorage.getItem('compteHopital') || '';
    document.getElementById('compteSpecialite').value = localStorage.getItem('compteSpecialite') || 'Médecine générale';
    document.getElementById('compteAdresse').value = localStorage.getItem('compteAdresse') || '';
    // Note: Signature file cannot be reloaded directly due to browser security restrictions.
}

async function applyPreset(presetType) {
    if (!presetType) return;
    
    try {
        const response = await fetch('get_presets.php');
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        const result = await response.json();
        
        if (result.success && result.data[presetType]) {
            const preset = result.data[presetType];
            
            // Remplir les champs avec les données du preset
            document.getElementById('niss').value = preset.niss || '';
            document.getElementById('nom').value = preset.nom || '';
            document.getElementById('prenom').value = preset.prenom || '';
            document.getElementById('naissance').value = preset.naissance || '';
            document.getElementById('numPatient').value = preset.numPatient || '';
            
            // Sélectionner le sexe
            if (preset.sexe) {
                const sexeRadio = document.querySelector(`input[name="sexe"][value="${preset.sexe}"]`);
                if (sexeRadio) {
                    sexeRadio.checked = true;
                }
            }
            
            // Remplir le type d'intervention
            if (preset.typeInterv) {
                document.getElementById('typeInterv').value = preset.typeInterv || '';
            }
            
            // Vous pouvez ajouter d'autres champs ici selon vos besoins
        }
    } catch (error) {
        console.error('Erreur lors de l\'application du preset:', error);
    }
}

async function loadPresets() {
    try {
        const response = await fetch('get_presets.php');
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        const result = await response.json();
        
        if (result.success) {
            // Remplir le menu déroulant des presets
            const presetSelect = document.getElementById('presetSelect');
            if (presetSelect) {
                presetSelect.innerHTML = '<option value="">Sélectionner un preset</option>';
                for (const [type, preset] of Object.entries(result.data)) {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = preset.nom && preset.prenom ? `${preset.nom} ${preset.prenom}` : type;
                    presetSelect.appendChild(option);
                }
            }
        } else {
            throw new Error(result.error || 'Erreur lors du chargement des presets');
        }
    } catch (error) {
        console.error('Erreur lors du chargement des presets:', error);
    }
}

function saveCompte() {
    localStorage.setItem('compteNom', document.getElementById('compteNom').value);
    localStorage.setItem('comptePrenom', document.getElementById('comptePrenom').value);
    localStorage.setItem('compteHopital', document.getElementById('compteHopital').value);
    localStorage.setItem('compteSpecialite', document.getElementById('compteSpecialite').value);
    localStorage.setItem('compteAdresse', document.getElementById('compteAdresse').value);
    alert('Informations du compte enregistrées !');
}
