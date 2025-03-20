let auth0Client = null;
let currentUser = null;

// Initialize Auth0 client
const initAuth0 = async () => {
    try {
        auth0Client = await createAuth0Client({
            domain: 'nexuscloud.eu.auth0.com',
            clientId: 'i6WFJVG5uhe2NgyzYQZlO9FQNwPL5vIa',
            authorizationParams: {
                redirect_uri: window.location.origin
            }
        });
    } catch (err) {
        console.warn('Auth0 initialization failed:', err);
        utils.showError("Erreur lors de l'initialisation d'Auth0. Vérifiez vos paramètres.");
    }
};

// Initialize authentication state
const initAuth = async () => {
    try {
        await initAuth0();
        
        // Check if there's a stored session
        const storedUser = localStorage.getItem('currentUser');
        if (storedUser) {
            currentUser = JSON.parse(storedUser);
            await updateUI();
            utils.showSuccess('Session restaurée');
        }

        // Check for Auth0 callback
        if (window.location.search.includes("code=")) {
            await handleAuth0Callback();
        }

        // Set up event listeners
        setupEventListeners();
    } catch (err) {
        console.error("Erreur d'initialisation:", err);
        utils.showError("Erreur lors de l'initialisation de l'authentification");
    }
};

const setupEventListeners = () => {
    const localLoginBtn = document.getElementById('main-login-btn');
    const auth0LoginBtn = document.getElementById('auth0-login-btn');
    const logoutBtn = document.getElementById('logout-btn');

    if (localLoginBtn) {
        localLoginBtn.addEventListener('click', loginLocal);
        console.log('Local login button listener added');
    }
    
    if (auth0LoginBtn) {
        auth0LoginBtn.addEventListener('click', loginWithAuth0);
        console.log('Auth0 login button listener added');
    }
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
        console.log('Logout button listener added');
    }
};

const updateUI = async () => {
    try {
        const isAuthenticated = !!currentUser;
        console.log('Updating UI, authenticated:', isAuthenticated);
        
        // Update navigation visibility
        document.getElementById('login-container').style.display = isAuthenticated ? 'none' : 'block';
        document.getElementById('dashboard').style.display = isAuthenticated ? 'block' : 'none';
        document.getElementById('nav-links').style.display = isAuthenticated ? 'flex' : 'none';
        document.getElementById('login-btn').style.display = isAuthenticated ? 'none' : 'block';
        document.getElementById('user-info').style.display = isAuthenticated ? 'flex' : 'none';

        if (isAuthenticated) {
            // Update user info
            document.getElementById('user-name').textContent = currentUser.name;
            
            // Load dashboard data
            await loadDashboardData();
        }
    } catch (err) {
        console.error('Error updating UI:', err);
        utils.showError("Erreur lors de la mise à jour de l'interface");
    }
};

const loginLocal = async () => {
    try {
        console.log('Starting local login process');
        utils.showLoading('Connexion en cours...');
        
        // Simulate a delay for demo purposes
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // For demo, always login as director
        currentUser = {
            id: 1,
            email: 'director@site.com',
            name: 'Jean Dupont',
            role: 'director',
            department: 'Administration',
            auth_type: 'local'
        };
        
        console.log('Setting current user:', currentUser);
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        
        await updateUI();
        utils.showSuccess('Connexion réussie en tant que ' + currentUser.name);
    } catch (err) {
        console.error('Login error:', err);
        utils.showError("Erreur lors de la connexion");
    } finally {
        utils.hideLoading();
    }
};

const loginWithAuth0 = async () => {
    try {
        await auth0Client.loginWithRedirect();
    } catch (err) {
        console.error('Auth0 login error:', err);
        utils.showError("Erreur lors de la connexion avec Auth0");
    }
};

const handleAuth0Callback = async () => {
    try {
        const result = await auth0Client.handleRedirectCallback();
        const user = await auth0Client.getUser();
        
        currentUser = {
            id: user.sub,
            email: user.email,
            name: user.name || user.email,
            role: 'user',
            department: 'Non assigné',
            auth_type: 'auth0'
        };
        
        localStorage.setItem('currentUser', JSON.stringify(currentUser));
        await updateUI();
        utils.showSuccess('Connexion réussie avec Auth0');
    } catch (err) {
        console.error('Auth0 callback error:', err);
        utils.showError("Erreur lors de l'authentification avec Auth0");
    }
};

const logout = async () => {
    try {
        utils.showLoading('Déconnexion en cours...');
        
        if (currentUser?.auth_type === 'auth0' && auth0Client) {
            await auth0Client.logout({
                returnTo: window.location.origin
            });
        }
        
        localStorage.removeItem('currentUser');
        currentUser = null;
        await updateUI();
        utils.showSuccess('Déconnexion réussie');
    } catch (err) {
        console.error('Logout error:', err);
        utils.showError("Erreur lors de la déconnexion");
    } finally {
        utils.hideLoading();
    }
};

const loadDashboardData = async () => {
    try {
        // Load departments
        const departments = await utils.fetchAPI('departments');
        // Update departments list
        const departmentsList = document.getElementById('departments-list');
        if (departmentsList) {
            departmentsList.innerHTML = departments.map(dept => `
                <div class="p-4 bg-white rounded-lg shadow">
                    <h4 class="text-lg font-semibold">${dept.name}</h4>
                    <p>Budget: ${utils.formatCurrency(dept.budget)}</p>
                    <p>Status: ${dept.status}</p>
                </div>
            `).join('');
        }
        
        // Load other dashboard data...
    } catch (err) {
        console.error('Error loading dashboard data:', err);
        utils.showError("Erreur lors du chargement des données");
    }
};

// Initialize when the page loads
document.addEventListener('DOMContentLoaded', initAuth);

// Export functions for use in other modules
window.auth = {
    loginLocal,
    loginWithAuth0,
    logout,
    getCurrentUser: () => currentUser,
    hasPermission: (permission) => {
        return currentUser && currentUser.role === 'director';
    }
};