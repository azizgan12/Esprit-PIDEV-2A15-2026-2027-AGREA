-- Allow the five account types selected during registration, while retaining admin accounts.
ALTER TABLE users
    MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'farmer';
