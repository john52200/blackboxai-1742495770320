import React, { useEffect, useState } from 'react';
import axios from 'axios';

const BudgetManagement = () => {
    const [budgets, setBudgets] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchBudgets = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/budgets'); // Adjust the endpoint as necessary
            setBudgets(response.data);
        } catch (err) {
            setError('Failed to fetch budgets');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchBudgets();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Budget Management</h1>
            {loading && <p>Loading budgets...</p>}
            {error && <p className="text-red-500">{error}</p>}
            <ul>
                {budgets.map((budget) => (
                    <li key={budget.id} className="border-b py-2">
                        {budget.name}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default BudgetManagement;
