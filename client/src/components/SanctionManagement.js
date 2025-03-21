import React, { useEffect, useState } from 'react';
import axios from 'axios';

const SanctionManagement = () => {
    const [sanctions, setSanctions] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchSanctions = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/sanctions'); // Adjust the endpoint as necessary
            setSanctions(response.data);
        } catch (err) {
            setError('Failed to fetch sanctions');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchSanctions();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Sanction Management</h1>
            {loading && <p>Loading sanctions...</p>}
            {error && <p className="text-red-500">{error}</p>}
            <ul>
                {sanctions.map((sanction) => (
                    <li key={sanction.id} className="border-b py-2">
                        {sanction.description}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default SanctionManagement;
