import React, { useEffect, useState } from 'react';
import axios from 'axios';


const Dashboard = () => {
    const [user, setUser] = useState(null);
    const [departments, setDepartments] = useState([]);

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            const decoded = jwt_decode(token);
            setUser(decoded);
        }
        fetchDepartments();
    }, []);

    const fetchDepartments = async () => {
        try {
            const response = await axios.get('http://localhost:5000/api/departments');
            setDepartments(response.data);
        } catch (err) {
            console.error('Failed to fetch departments:', err);
        }
    };

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Dashboard</h1>
            <p>Welcome to the Intranet Dashboard!</p>
            <h2 className="text-xl font-semibold mt-4">Your Departments:</h2>
            <ul>
                {departments.map((department) => (
                    <li key={department.id} className="border-b py-2">
                        {department.name}
                    </li>
                ))}
            </ul>
        </div>
    );
};


export default Dashboard;
