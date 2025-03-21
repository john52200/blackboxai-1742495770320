import mysql.connector

def create_database():
    connection = mysql.connector.connect(
        host='localhost',
        user='your_username',  # Replace with your MySQL username
        password='your_password'  # Replace with your MySQL password
    )
    cursor = connection.cursor()
    cursor.execute("CREATE DATABASE IF NOT EXISTS your_database")  # Replace with your database name
    cursor.close()
    connection.close()

def create_tables():
    connection = mysql.connector.connect(
        host='localhost',
        user='your_username',  # Replace with your MySQL username
        password='your_password',  # Replace with your MySQL password
        database='your_database'  # Replace with your MySQL database name
    )
    cursor = connection.cursor()

    # Create departments table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            budget DECIMAL(10, 2) NOT NULL
        )
    ''')

    # Create personnel table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS personnel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            department_id INT,
            role ENUM('user', 'department_head', 'director') NOT NULL,
            FOREIGN KEY (department_id) REFERENCES departments(id)
        )
    ''')

    # Create reports table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS reports (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            department_id INT,
            classification ENUM('Public', 'Restreint', 'Confidentiel') NOT NULL,
            FOREIGN KEY (department_id) REFERENCES departments(id)
        )
    ''')

    # Create sanctions table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS sanctions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            reason TEXT NOT NULL,
            severity ENUM('légère', 'modérée', 'sévère') NOT NULL,
            FOREIGN KEY (user_id) REFERENCES personnel(id)
        )
    ''')

    # Create budget requests table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS budget_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT,
            amount DECIMAL(10, 2) NOT NULL,
            purpose TEXT NOT NULL,
            FOREIGN KEY (department_id) REFERENCES departments(id)
        )
    ''')

    connection.commit()
    cursor.close()
    connection.close()

if __name__ == '__main__':
    create_database()
    create_tables()
