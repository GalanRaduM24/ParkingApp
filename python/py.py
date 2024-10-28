import os

# Generate a random secret key
secret_key = os.urandom(24).hex()
print(secret_key)