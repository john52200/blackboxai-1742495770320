import React, { useEffect, useState } from 'react';
import axios from 'axios';

const AdminPanel = () => {
    const [departments, setDepartments] = useState([]);
    const [grades, setGrades] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchDepartments = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/departments');
            setDepartments(response.data);
        } catch (err) {
            setError('Failed to fetch departments');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    const fetchGrades = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/grades');
            setGrades(response.data);
        } catch (err) {
            setError('Failed to fetch grades');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchDepartments();
        fetchGrades();
    }, []);

    if (loading) return <p>Loading...</p>;
    if (error) return <p className="text-red-500">{error}</p>;

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Admin Panel</h1>
            <h2 className="text-xl font-semibold mt-4">Departments:</h2>
            <ul>
                {departments.map((department) => (
                    <li key={department.id} className="border-b py-2">
                        {department.name}
                    </li>
                ))}
            </ul>
            <h2 className="text-xl font-semibold mt-4">Grades:</h2>
            <ul>
                {grades.map((grade) => (
                    <li key={grade.id} className="border-b py-2">
                        {grade.name}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default AdminPanel;
