from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime
import os
from dotenv import load_dotenv

load_dotenv()

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///intranet.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    email = db.Column(db.String(120), unique=True, nullable=False)
    name = db.Column(db.String(120), nullable=False)
    role = db.Column(db.String(50), nullable=False)
    department = db.Column(db.String(100), nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    auth_type = db.Column(db.String(20), default='local')  # 'local' or 'auth0'
    auth0_id = db.Column(db.String(100), unique=True, nullable=True)

    def __init__(self, email, name, role, department, auth_type='local', auth0_id=None):
        self.email = email
        self.name = name
        self.role = role
        self.department = department
        self.auth_type = auth_type
        self.auth0_id = auth0_id

class Department(db.Model):
    __tablename__ = 'departments'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    status = db.Column(db.String(20), default='actif')
    budget = db.Column(db.Float, default=0.0)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

    def __init__(self, name, budget, status='actif'):
        self.name = name
        self.budget = budget
        self.status = status

class BudgetRequest(db.Model):
    __tablename__ = 'budget_requests'
    id = db.Column(db.Integer, primary_key=True)
    department_id = db.Column(db.Integer, db.ForeignKey('departments.id'), nullable=False)
    amount = db.Column(db.Float, nullable=False)
    purpose = db.Column(db.Text, nullable=False)
    status = db.Column(db.String(20), default='en_attente')
    requested_by = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

class Report(db.Model):
    __tablename__ = 'reports'
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(200), nullable=False)
    content = db.Column(db.Text, nullable=False)
    department_id = db.Column(db.Integer, db.ForeignKey('departments.id'), nullable=False)
    author_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    status = db.Column(db.String(20), default='en_attente')
    classification = db.Column(db.String(50), default='Public')
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

class Sanction(db.Model):
    __tablename__ = 'sanctions'
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    issued_by = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    reason = db.Column(db.Text, nullable=False)
    severity = db.Column(db.String(20), nullable=False)
    status = db.Column(db.String(20), default='active')
    created_at = db.Column(db.DateTime, default=datetime.utcnow)

def init_db():
    with app.app_context():
        # Create all tables
        db.create_all()

        # Check if we need to add initial data
        if User.query.count() == 0:
            # Add default users
            default_users = [
                User(
                    email='director@site.com',
                    name='Jean Dupont',
                    role='director',
                    department='Administration'
                ),
                User(
                    email='scientific.head@site.com',
                    name='Marie Laurent',
                    role='department_head',
                    department='Scientifique'
                ),
                User(
                    email='security.head@site.com',
                    name='Pierre Martin',
                    role='department_head',
                    department='Sécurité'
                )
            ]
            for user in default_users:
                db.session.add(user)

            # Add default departments
            departments = [
                Department(name='Département Scientifique', budget=500000),
                Department(name='Département Sécurité', budget=300000),
                Department(name='Département Logistique', budget=200000)
            ]
            for dept in departments:
                db.session.add(dept)

            db.session.commit()
            print("Database initialized with default data.")

if __name__ == '__main__':
    init_db()
    print("Database initialization complete.")