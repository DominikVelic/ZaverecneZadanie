-- Check user permissions
CREATE USER 'user'@'%' IDENTIFIED BY 'password';
SHOW GRANTS FOR 'user'@'%';

-- Grant necessary permissions
GRANT ALL PRIVILEGES ON . TO 'user'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;