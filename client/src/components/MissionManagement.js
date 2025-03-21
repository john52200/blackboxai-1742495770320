import React, { useEffect, useState } from 'react';
import axios from 'axios';

const MissionManagement = () => {
    const [missions, setMissions] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchMissions = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/missions'); // Adjust the endpoint as necessary
            setMissions(response.data);
        } catch (err) {
            setError('Failed to fetch missions');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchMissions();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Mission Management</h1>
            {loading && <p>Loading missions...</p>}
            {error && <p className="text-red-500">{error}</p>}
            <ul>
                {missions.map((mission) => (
                    <li key={mission.id} className="border-b py-2">
                        {mission.name}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default MissionManagement;
