-- Check user permissions
CREATE USER 'user'@'%' IDENTIFIED BY 'password';

-- Grant necessary permissions
GRANT ALL PRIVILEGES ON . TO 'user'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;

--
