from flask import Flask, request, jsonify, session
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from flask_login import LoginManager, login_user, logout_user, login_required, current_user
from authlib.integrations.flask_client import OAuth
from datetime import datetime
import os
from dotenv import load_dotenv
from functools import wraps

load_dotenv()

app = Flask(__name__)
app.config['SECRET_KEY'] = os.getenv('SECRET_KEY', 'your-secret-key-goes-here')
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///intranet.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

# Initialize extensions
db = SQLAlchemy(app)
CORS(app, supports_credentials=True)
login_manager = LoginManager()
login_manager.init_app(app)
oauth = OAuth(app)

# Configure Auth0
auth0 = oauth.register(
    'auth0',
    client_id=os.getenv('AUTH0_CLIENT_ID'),
    client_secret=os.getenv('AUTH0_CLIENT_SECRET'),
    api_base_url=f"https://{os.getenv('AUTH0_DOMAIN')}",
    access_token_url=f"https://{os.getenv('AUTH0_DOMAIN')}/oauth/token",
    authorize_url=f"https://{os.getenv('AUTH0_DOMAIN')}/authorize",
    client_kwargs={
        'scope': 'openid profile email',
    },
)

# Import models
from init_db import User, Department, BudgetRequest, Report, Sanction

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

def requires_roles(*roles):
    def decorator(f):
        @wraps(f)
        def decorated_function(*args, **kwargs):
            if not current_user.is_authenticated or current_user.role not in roles:
                return jsonify({'error': 'Unauthorized'}), 403
            return f(*args, **kwargs)
        return decorated_function
    return decorator

# Auth routes
@app.route('/auth/login', methods=['POST'])
def login():
    data = request.get_json()
    user = User.query.filter_by(email=data.get('email')).first()
    
    if user:
        login_user(user)
        return jsonify({
            'id': user.id,
            'email': user.email,
            'name': user.name,
            'role': user.role,
            'department': user.department
        })
    return jsonify({'error': 'Invalid credentials'}), 401

@app.route('/auth/logout')
@login_required
def logout():
    logout_user()
    return jsonify({'message': 'Logged out successfully'})

@app.route('/auth/callback')
def auth0_callback():
    auth0.authorize_access_token()
    resp = auth0.get('userinfo')
    userinfo = resp.json()

    user = User.query.filter_by(auth0_id=userinfo['sub']).first()
    if not user:
        user = User(
            email=userinfo['email'],
            name=userinfo.get('name', userinfo['email']),
            role='user',  # Default role
            department='Non assigné',
            auth_type='auth0',
            auth0_id=userinfo['sub']
        )
        db.session.add(user)
        db.session.commit()

    login_user(user)
    return jsonify({
        'id': user.id,
        'email': user.email,
        'name': user.name,
        'role': user.role,
        'department': user.department
    })

# Department routes
@app.route('/api/departments', methods=['GET'])
@login_required
def get_departments():
    departments = Department.query.all()
    return jsonify([{
        'id': d.id,
        'name': d.name,
        'status': d.status,
        'budget': d.budget
    } for d in departments])

@app.route('/api/departments/<int:dept_id>/budget-requests', methods=['POST'])
@login_required
def create_budget_request(dept_id):
    data = request.get_json()
    budget_request = BudgetRequest(
        department_id=dept_id,
        amount=data['amount'],
        purpose=data['purpose'],
        requested_by=current_user.id
    )
    db.session.add(budget_request)
    db.session.commit()
    return jsonify({'message': 'Budget request created successfully'})

# Report routes
@app.route('/api/reports', methods=['GET'])
@login_required
def get_reports():
    reports = Report.query.all()
    return jsonify([{
        'id': r.id,
        'title': r.title,
        'content': r.content,
        'department_id': r.department_id,
        'author_id': r.author_id,
        'status': r.status,
        'classification': r.classification,
        'created_at': r.created_at.isoformat()
    } for r in reports])

@app.route('/api/reports', methods=['POST'])
@login_required
def create_report():
    data = request.get_json()
    report = Report(
        title=data['title'],
        content=data['content'],
        department_id=data['department_id'],
        author_id=current_user.id,
        classification=data.get('classification', 'Public')
    )
    db.session.add(report)
    db.session.commit()
    return jsonify({'message': 'Report created successfully'})

# Sanction routes
@app.route('/api/sanctions', methods=['GET'])
@login_required
@requires_roles('director', 'department_head')
def get_sanctions():
    sanctions = Sanction.query.all()
    return jsonify([{
        'id': s.id,
        'user_id': s.user_id,
        'issued_by': s.issued_by,
        'reason': s.reason,
        'severity': s.severity,
        'status': s.status,
        'created_at': s.created_at.isoformat()
    } for s in sanctions])

@app.route('/api/sanctions', methods=['POST'])
@login_required
@requires_roles('director', 'department_head')
def create_sanction():
    data = request.get_json()
    sanction = Sanction(
        user_id=data['user_id'],
        issued_by=current_user.id,
        reason=data['reason'],
        severity=data['severity']
    )
    db.session.add(sanction)
    db.session.commit()
    return jsonify({'message': 'Sanction created successfully'})

# User routes
@app.route('/api/users', methods=['GET'])
@login_required
def get_users():
    users = User.query.all()
    return jsonify([{
        'id': u.id,
        'email': u.email,
        'name': u.name,
        'role': u.role,
        'department': u.department
    } for u in users])

if __name__ == '__main__':
    app.run(debug=True)
