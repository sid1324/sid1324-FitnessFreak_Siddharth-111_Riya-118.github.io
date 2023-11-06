import mysql.connector
import os
from email.message import EmailMessage
import ssl
import smtplib

# Database credentials
db_host = 'localhost'
db_name = 'FitnessFreak'
db_user = 'root'
db_pass = ''

# Connect to the database
try:
    conn = mysql.connector.connect(
        host=db_host,
        user=db_user,
        password=db_pass,
        database=db_name
    )
    if conn.is_connected():
        print(f"Connected to database '{db_name}'")
except Exception as e:
    print(f"Error: {e}")

# Query to select email addresses from 'users'
query = "SELECT email FROM users"

try:
    email_sender="thaparriya88@gmail.com"
    email_passwd="snvd rovx cgat zyac"
    subject='FitnessFreak Workout Reminder!!'
    body="""
    Time to workout!
    """
    cursor = conn.cursor()
    cursor.execute(query)

    # Fetch all email addresses
    email_addresses = cursor.fetchall()

    # Prepare and send emails
    for email_address in email_addresses:
        # Convert the tuple result to a string
        email_rcvr = email_address[0]

        em = EmailMessage()
        em['From'] = email_sender
        em['To'] = email_rcvr
        em['Subject'] = subject
        em.set_content(body)

        ssl.create_default_context()

        with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
            smtp.login(email_sender, email_passwd)
            smtp.sendmail(email_sender, email_rcvr, em.as_string())

            print(f"Email sent to: {email_rcvr}")

except Exception as e:
    print(f"Error: {e}")

finally:
    cursor.close()
    conn.close()
    print("Connection closed.")
