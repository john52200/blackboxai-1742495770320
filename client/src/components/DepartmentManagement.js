import React, { useEffect, useState } from 'react';
import axios from 'axios';

const DepartmentManagement = () => {
    const [departments, setDepartments] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchDepartments = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/departments'); // Adjust the endpoint as necessary
            setDepartments(response.data);
        } catch (err) {
            setError('Failed to fetch departments');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchDepartments();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Department Management</h1>
            {loading && <p>Loading departments...</p>}
            {error && <p className="text-red-500">{error}</p>}
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

export default DepartmentManagement;
