
function backToLogin() {
    document.getElementById('page-createAccount').classList.add('hidden');
    document.getElementById('page-forgotPassword').classList.add('hidden');
    document.getElementById('page-resetPassword').classList.add('hidden');
    document.getElementById('loginScreen').classList.remove('hidden');
}

function showAuthPage(page) {
    document.getElementById('loginScreen').classList.add('hidden');
    document.getElementById('page-createAccount').classList.add('hidden');
    document.getElementById('page-forgotPassword').classList.add('hidden');

    if (page === 'createAccount') {
        document.getElementById('page-createAccount').classList.remove('hidden');
        // Préremplir l'email avec une valeur par défaut
        const emailInput = document.getElementById('newEmail');
        if (emailInput && !emailInput.value) {
            emailInput.value = 'exemple@email.com';
        }
    }
    if (page === 'forgotPassword') document.getElementById('page-forgotPassword').classList.remove('hidden');
}

async function requestPasswordReset() {
    const email = document.getElementById('resetEmail').value;
    const messageDiv = document.getElementById('resetMessage');
    
    try {
        const response = await fetch('request_password_reset.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email })
        });
        
        const result = await response.json();
        
        if (result.success) {
            messageDiv.style.color = 'green';
            messageDiv.textContent = result.message + ' ' + (result.reset_link || '');
        } else {
            throw new Error(result.message || 'Erreur lors de la demande de réinitialisation');
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.style.color = 'red';
        messageDiv.textContent = error.message;
    }
}

async function resetPassword() {
    const token = document.getElementById('resetToken').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const messageDiv = document.getElementById('resetPasswordMessage');
    
    if (newPassword !== confirmPassword) {
        messageDiv.style.color = 'red';
        messageDiv.textContent = 'Les mots de passe ne correspondent pas';
        return;
    }
    
    try {
        const response = await fetch('reset_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                token, 
                password: newPassword 
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            messageDiv.style.color = 'green';
            messageDiv.textContent = result.message;
            setTimeout(() => {
                backToLogin();
            }, 2000);
        } else {
            throw new Error(result.message || 'Erreur lors de la réinitialisation');
        }
    } catch (error) {
        console.error('Erreur:', error);
        messageDiv.style.color = 'red';
        messageDiv.textContent = error.message;
    }
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
    const username = document.getElementById('newUsername').value;
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
                username,
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

async function loadCompte() {
    try {
        const response = await fetch('get_compte.php');
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            document.getElementById('compteNom').value = data.nom || '';
            document.getElementById('comptePrenom').value = data.prenom || '';
            document.getElementById('compteHopital').value = data.hopital || '';
            document.getElementById('compteSpecialite').value = data.specialite || 'Médecine générale';
            document.getElementById('compteAdresse').value = data.adresse || '';
        } else {
            throw new Error(result.message || 'Erreur lors du chargement des informations du compte');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || 'Échec du chargement des informations du compte');
    }
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

async function saveCompte() {
    const nom = document.getElementById('compteNom').value;
    const prenom = document.getElementById('comptePrenom').value;
    const hopital = document.getElementById('compteHopital').value;
    const specialite = document.getElementById('compteSpecialite').value;
    const adresse = document.getElementById('compteAdresse').value;
    
    try {
        const response = await fetch('save_compte.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nom,
                prenom,
                hopital,
                specialite,
                adresse
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Informations du compte enregistrées !');
        } else {
            throw new Error(result.message || 'Erreur lors de la sauvegarde');
        }
    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message || 'Échec de la sauvegarde des informations');
    }
}

function logout() {
    window.location.href = 'logout.php';
}

function loginWithItsme() {
    // Rediriger vers le endpoint d'authentification itsme
    // Note: Vous devez remplacer CLIENT_ID et REDIRECT_URI par vos valeurs réelles
    const clientId = 'VOTRE_CLIENT_ID';
    const redirectUri = encodeURIComponent('https://votre-domaine.com/itsme_callback.php');
    const scope = encodeURIComponent('openid service:name');
    const state = generateRandomString(); // Pour la protection CSRF
    
    // Stocker le state dans le sessionStorage pour la vérification plus tard
    sessionStorage.setItem('itsme_oauth_state', state);
    
    const itsmeAuthUrl = `https://connect.itsme.be/oidc/authorize?` +
        `client_id=${clientId}` +
        `&redirect_uri=${redirectUri}` +
        `&response_type=code` +
        `&scope=${scope}` +
        `&state=${state}`;
    
    window.location.href = itsmeAuthUrl;
}

function generateRandomString() {
    const array = new Uint8Array(16);
    window.crypto.getRandomValues(array);
    return Array.from(array, byte => byte.toString(16).padStart(2, '0')).join('');
}
