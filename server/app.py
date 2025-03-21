import mysql.connector
from flask import Flask, request, jsonify

app = Flask(__name__)

# Database connection
def get_db_connection():
    connection = mysql.connector.connect(
        host='localhost',
        user='your_username',  # Replace with your MySQL username
        password='your_password',  # Replace with your MySQL password
        database='your_database'  # Replace with your MySQL database name
    )
    return connection

# Example route for fetching departments
@app.route('/departments', methods=['GET'])
def get_departments():
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM departments')
    departments = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(departments)

# Example route for fetching personnel
@app.route('/personnel', methods=['GET'])
def get_personnel():
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM personnel')
    personnel = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(personnel)

# Example route for fetching reports
@app.route('/reports', methods=['GET'])
def get_reports():
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM reports')
    reports = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(reports)

# Example route for fetching sanctions
@app.route('/sanctions', methods=['GET'])
def get_sanctions():
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM sanctions')
    sanctions = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(sanctions)

# Example route for fetching budget requests
@app.route('/budget-requests', methods=['GET'])
def get_budget_requests():
    connection = get_db_connection()
    cursor = connection.cursor(dictionary=True)
    cursor.execute('SELECT * FROM budget_requests')
    budget_requests = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(budget_requests)

if __name__ == '__main__':
    app.run(debug=True)
