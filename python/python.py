import mysql.connector
from mysql.connector import Error
from flask import Flask, request, session, redirect
from dotenv import load_dotenv
import os

# Establish database connection
try:
    connection = mysql.connector.connect(
        host=os.getenv("DB_HOST"),
        user=os.getenv("DB_USER"),
        password=os.getenv("DB_PASSWORD"),
        database=os.getenv("DB_NAME")
    )
except Error as e:
    print("Error connecting to the database:", e)

# Flask app initialization
app = Flask(__name__)

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
    # Fetch user account information from the database
    if 'user_name' in session:
        user_name = session['user_name']
        mail = session['mail']
        return f"<h2>Welcome, {user_name}!</h2><p>Email: {mail}</p>"
    else:
        return "Please log in or sign up"

def login_user(user_name, password):
    try:
        cursor = connection.cursor()
        cursor.execute("SELECT * FROM user WHERE user_name = %s", (user_name,))
        users = cursor.fetchone()

        if user:
            # Validate password
            if password == user[2]:
                # Store user data in session
                session['user_name'] = users[0]
                session['mail'] = users[1]
                return True

        return False
    except Error as e:
        print("Error occurred:", e)

def signup_user(user_name, mail, password):
    try:
        cursor = connection.cursor()
        cursor.execute("INSERT INTO user (user_name, mail, password) VALUES (%s, %s, %s)", (user_name, mail, password))
        connection.commit()
        return True
    except Error as e:
        print("Error occurred:", e)
        return False

if __name__ == '__main__':
    app.run()