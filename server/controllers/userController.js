const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const db = require('../server'); // Import the database connection

// User registration
exports.register = (req, res) => {
    const { username, password } = req.body;

    // Hash the password
    bcrypt.hash(password, 10, (err, hash) => {
        if (err) {
            return res.status(500).json({ error: err });
        }

        // Save user to database
        db.query('INSERT INTO users (username, password) VALUES (?, ?)', [username, hash], (error, results) => {
            if (error) {
                return res.status(500).json({ error: error });
            }
            res.status(201).json({ message: 'User registered successfully!' });
        });
    });
};

// User login
exports.login = (req, res) => {
    const { username, password } = req.body;

    // Find user in database
    db.query('SELECT * FROM users WHERE username = ?', [username], (error, results) => {
        if (error || results.length === 0) {
            return res.status(401).json({ message: 'Authentication failed' });
        }

        // Compare password
        bcrypt.compare(password, results[0].password, (err, match) => {
            if (err || !match) {
                return res.status(401).json({ message: 'Authentication failed' });
            }

            // Generate JWT token
            const token = jwt.sign({ userId: results[0].id }, 'your_jwt_secret', { expiresIn: '1h' });
            res.status(200).json({ token });
        });
    });
};
