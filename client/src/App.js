import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import UserProfile from './components/UserProfile';
import AdminPanel from './components/AdminPanel';

import Navbar from './components/Navbar';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import DepartmentManagement from './components/DepartmentManagement';
import PersonnelManagement from './components/PersonnelManagement';
import BudgetManagement from './components/BudgetManagement';
import MissionManagement from './components/MissionManagement';
import ReportManagement from './components/ReportManagement';
import SanctionManagement from './components/SanctionManagement';

const App = () => {
    return (
        <Router>
            <Navbar />
            <Switch>
                <Route path="/" exact component={Login} />
                <Route path="/dashboard" component={Dashboard} />
                <Route path="/departments" component={DepartmentManagement} />
                <ProtectedRoute path="/profile" component={UserProfile} />
                <ProtectedRoute path="/admin" component={AdminPanel} />


                <Route path="/personnel" component={PersonnelManagement} />
                <Route path="/budget" component={BudgetManagement} />
                <Route path="/missions" component={MissionManagement} />
                <Route path="/reports" component={ReportManagement} />
                <Route path="/sanctions" component={SanctionManagement} />
            </Switch>
        </Router>
    );
};

export default App;
