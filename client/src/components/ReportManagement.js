import React, { useEffect, useState } from 'react';
import axios from 'axios';

const ReportManagement = () => {
    const [reports, setReports] = useState([]);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    const fetchReports = async () => {
        setLoading(true);
        try {
            const response = await axios.get('http://localhost:5000/api/reports'); // Adjust the endpoint as necessary
            setReports(response.data);
        } catch (err) {
            setError('Failed to fetch reports');
            console.error('Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchReports();
    }, []);

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">Report Management</h1>
            {loading && <p>Loading reports...</p>}
            {error && <p className="text-red-500">{error}</p>}
            <ul>
                {reports.map((report) => (
                    <li key={report.id} className="border-b py-2">
                        {report.title}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default ReportManagement;
