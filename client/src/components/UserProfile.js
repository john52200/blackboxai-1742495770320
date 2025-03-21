import React, { useState, useEffect } from 'react';
import axios from 'axios';

const UserProfile = () => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            const fetchUserProfile = async () => {
                try {
                    const response = await axios.get('http://localhost:5000/api/users/profile', {
                        headers: { Authorization: `Bearer ${token}` },
                    });
                    setUser(response.data);
                } catch (err) {
                    setError('Failed to fetch user profile');
                    console.error('Fetch error:', err);
                } finally {
                    setLoading(false);
                }
            };
            fetchUserProfile();
        } else {
            setLoading(false);
        }
    }, []);

    if (loading) return <p>Loading profile...</p>;
    if (error) return <p className="text-red-500">{error}</p>;

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">User Profile</h1>
            {user && (
                <div>
                    <p><strong>Name:</strong> {user.name}</p>
                    <p><strong>Email:</strong> {user.email}</p>
                    <p><strong>Department:</strong> {user.department}</p>
                    {/* Add more user details as needed */}
                </div>
            )}
        </div>
    );
};

export default UserProfile;
