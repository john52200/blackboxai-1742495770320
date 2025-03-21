import React, { useEffect, useState } from 'react';
import axios from 'axios';

const PersonnelManagement = () => {
    const [personnel, setPersonnel] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchPersonnel = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/personnel'); // Adjust the endpoint as necessary
            setPersonnel(response.data);
        } catch (err) {
            setError('Failed to fetch personnel');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchPersonnel();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Personnel Management</h1>
            {loading && <p>Loading personnel...</p>}
            {error && <p className="text-red-500">{error}</p>}
            <ul>
                {personnel.map((person) => (
                    <li key={person.id} className="border-b py-2">
                        {person.name}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default PersonnelManagement;
