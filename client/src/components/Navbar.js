import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import jwt_decode from 'jwt-decode';


const Navbar = () => {
    const [user, setUser] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            const decoded = jwt_decode(token);
            setUser(decoded);
        }
    }, []);

    return (
        <nav className="bg-gray-800 p-4">
            <div className="container mx-auto flex justify-between items-center">
                <ul className="flex space-x-4">
                    <li>
                        <Link to="/" className="text-white">Login</Link>
                    </li>
                    <li>
                        <Link to="/dashboard" className="text-white">Dashboard</Link>
                    </li>
                    <li>
                        <Link to="/departments" className="text-white">Departments</Link>
                    </li>
                    <li>
                        <Link to="/personnel" className="text-white">Personnel</Link>
                    </li>
                    <li>
                        <Link to="/budget" className="text-white">Budget</Link>
                    </li>
                    <li>
                        <Link to="/missions" className="text-white">Missions</Link>
                    </li>
                    <li>
                        <Link to="/reports" className="text-white">Reports</Link>
                    </li>
                    <li>
                        <Link to="/sanctions" className="text-white">Sanctions</Link>
                    </li>
                </ul>
                {user && (
                    <div className="text-white">
                        <span>{user.name}</span>
                        <button className="ml-4 bg-gray-700 p-2 rounded">Profile</button>
                    </div>
                )}
            </div>

            <div className="container mx-auto">
                <ul className="flex space-x-4">
                    <li>
                        <Link to="/" className="text-white">Login</Link>
                    </li>
                    <li>
                        <Link to="/dashboard" className="text-white">Dashboard</Link>
                    </li>
                    <li>
                        <Link to="/departments" className="text-white">Departments</Link>
                    </li>
                    <li>
                        <Link to="/personnel" className="text-white">Personnel</Link>
                    </li>
                    <li>
                        <Link to="/budget" className="text-white">Budget</Link>
                    </li>
                    <li>
                        <Link to="/missions" className="text-white">Missions</Link>
                    </li>
                    <li>
                        <Link to="/reports" className="text-white">Reports</Link>
                    </li>
                    <li>
                        <Link to="/sanctions" className="text-white">Sanctions</Link>
                    </li>
                </ul>
            </div>
        </nav>
    );
};

export default Navbar;
