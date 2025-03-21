// Form handlers
async function handleDepartmentSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const data = utils.getFormData(form);

    const errors = utils.validateForm(data, {
        name: ['required'],
        budget: ['required', 'number']
    });

    if (errors) {
        utils.showFormErrors(form, errors);
        return;
    }

    try {
        utils.showLoading('Création du département...');
        const response = await utils.fetchAPI('departments', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.error) {
            throw new Error(response.error);
        }

        utils.showSuccess('Département créé avec succès');
        utils.hideModal('department-modal');
        form.reset();
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de la création du département: ' + error.message);
    } finally {
        utils.hideLoading();
    }
}

async function handleDeleteDepartment(id) {
    try {
        utils.showLoading('Suppression du département...');
        const response = await utils.fetchAPI(`departments/${id}`, {
            method: 'DELETE'
        });
        
        if (response.error) {
            throw new Error(response.error);
        }

        utils.showSuccess('Département supprimé avec succès');
        await loadDepartments(); // Refresh the list of departments
    } catch (error) {
        utils.showError('Erreur lors de la suppression du département: ' + error.message);
    } finally {
        utils.hideLoading();
    }
}


async function handlePersonnelSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const data = utils.getFormData(form);

    const errors = utils.validateForm(data, {
        name: ['required'],
        email: ['required', 'email'],
        department: ['required'],
        role: ['required']
    });

    if (errors) {
        utils.showFormErrors(form, errors);
        return;
    }

    try {
        utils.showLoading('Ajout du personnel...');
        const response = await utils.fetchAPI('users', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        utils.showSuccess('Personnel ajouté avec succès');
        utils.hideModal('personnel-modal');
        form.reset();
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de l\'ajout du personnel');
    } finally {
        utils.hideLoading();
    }
}

async function handleReportSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const data = utils.getFormData(form);

    const errors = utils.validateForm(data, {
        title: ['required'],
        content: ['required'],
        department: ['required'],
        classification: ['required']
    });

    if (errors) {
        utils.showFormErrors(form, errors);
        return;
    }

    try {
        utils.showLoading('Création du rapport...');
        const response = await utils.fetchAPI('reports', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.error) {
            throw new Error(response.error);
        }

        // Assign department to report
        const departmentId = data.department; // Assuming department is selected in the form
        await utils.fetchAPI(`reports/${response.id}/assign-department`, {
            method: 'POST',
            body: JSON.stringify({ department_id: departmentId })
        });

        // Update visibility of the report
        await utils.fetchAPI(`reports/${response.id}/visibility`, {
            method: 'POST',
            body: JSON.stringify({ visibility: true }) // Set visibility to true by default
        });

        utils.showSuccess('Rapport créé avec succès');
        utils.hideModal('report-modal');
        form.reset();
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de la création du rapport: ' + error.message);
    } finally {
        utils.hideLoading();
    }
}




async function handleSanctionSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const data = utils.getFormData(form);

    const errors = utils.validateForm(data, {
        user_id: ['required'],
        reason: ['required'],
        severity: ['required']
    });

    if (errors) {
        utils.showFormErrors(form, errors);
        return;
    }

    try {
        utils.showLoading('Ajout de la sanction...');
        const response = await utils.fetchAPI('sanctions', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        utils.showSuccess('Sanction ajoutée avec succès');
        utils.hideModal('sanction-modal');
        form.reset();
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de l\'ajout de la sanction');
    } finally {
        utils.hideLoading();
    }
}

async function handleBudgetSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const data = utils.getFormData(form);

    const errors = utils.validateForm(data, {
        department: ['required'],
        amount: ['required', 'number'],
        purpose: ['required']
    });

    if (errors) {
        utils.showFormErrors(form, errors);
        return;
    }

    try {
        utils.showLoading('Soumission de la demande de budget...');
        const response = await utils.fetchAPI(`departments/${data.department}/budget-requests`, {
            method: 'POST',
            body: JSON.stringify({
                amount: parseFloat(data.amount),
                purpose: data.purpose
            })
        });
        
        utils.showSuccess('Demande de budget soumise avec succès');
        utils.hideModal('budget-modal');
        form.reset();
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de la soumission de la demande de budget');
    } finally {
        utils.hideLoading();
    }
}

// Department management functions
async function manageDepartment(id) {
    try {
        const response = await utils.fetchAPI(`departments/${id}`);
        window.location.href = `/department.html?id=${id}`;
    } catch (error) {
        utils.showError('Erreur lors du chargement du département');
    }
}

async function validateReport(id) {
    try {
        utils.showLoading('Validation du rapport...');
        const response = await utils.fetchAPI(`reports/${id}/validate`, {
            method: 'POST'
        });
        
        utils.showSuccess('Rapport validé avec succès');
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors de la validation du rapport');
    } finally {
        utils.hideLoading();
    }
}

async function rejectReport(id) {
    try {
        utils.showLoading('Rejet du rapport...');
        const response = await utils.fetchAPI(`reports/${id}/reject`, {
            method: 'POST'
        });
        
        utils.showSuccess('Rapport rejeté');
        await loadUserData(window.auth.getCurrentUser());
    } catch (error) {
        utils.showError('Erreur lors du rejet du rapport');
    } finally {
        utils.hideLoading();
    }
}

// Make functions available globally
window.manageDepartment = manageDepartment;
window.validateReport = validateReport;
window.rejectReport = rejectReport;
window.handleDepartmentSubmit = handleDepartmentSubmit;
window.handlePersonnelSubmit = handlePersonnelSubmit;
window.handleReportSubmit = handleReportSubmit;
window.handleSanctionSubmit = handleSanctionSubmit;
window.handleBudgetSubmit = handleBudgetSubmit;
