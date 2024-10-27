from flask import Flask, request, session, redirect
from dotenv import load_dotenv
import os
from supabase import create_client

# Load environment variables
load_dotenv()

# Initialize Supabase client
url = os.getenv("DB_URL")
key = os.getenv("DB_KEY")
supabase = create_client(url, key)

# Flask app initialization
app = Flask(__name__)
app.secret_key = os.getenv("SECRET_KEY")  # Ensure you set a secret key for session management

@app.route('/', methods=['POST'])
def login_signup():
    if request.method == 'POST':
        if 'login' in request.form:
            # Login functionality
            user_name = request.form['user_name']
            password = request.form['password']
            login_success = login_user(user_name, password)
            if login_success:
                return redirect('/profile')
            else:
                return "Invalid username or password"
        elif 'signup' in request.form:
            # Sign-up functionality
            user_name = request.form['user_name']
            mail = request.form['mail']
            password = request.form['password']
            signup_success = signup_user(user_name, mail, password)
            if signup_success:
                return redirect('/profile')
            else:
                return "Error signing up"

@app.route('/profile')
def profile():
    # Fetch user account information from the session
    if 'user_name' in session:
        user_name = session['user_name']
        mail = session['mail']
        return f"<h2>Welcome, {user_name}!</h2><p>Email: {mail}</p>"
    else:
        return "Please log in or sign up"

def login_user(user_name, password):
    try:
        # Fetch user by username
        response = supabase.table('users').select('*').eq('user_name', user_name).execute()
        users = response.data

        if users and len(users) == 1:
            # User found, verify the password
            user = users[0]
            hashed_password = user['password']  # Assuming password is hashed in the database

            if password == hashed_password:  # You should verify against hashed password
                # Store user data in session
                session['user_name'] = user['user_name']
                session['mail'] = user['mail']
                return True

        return False
    except Exception as e:
        print("Error occurred:", e)
        return False

def signup_user(user_name, mail, password):
    try:
        # Insert the user into Supabase
        response = supabase.table('users').insert({
            'user_name': user_name,
            'mail': mail,
            'password': password  # Ensure to hash this password before saving
        }).execute()

        if response.status_code == 201:
            return True
        else:
            print("Error occurred during sign up:", response.error)
            return False
    except Exception as e:
        print("Error occurred:", e)
        return False

if __name__ == '__main__':
    app.run()
