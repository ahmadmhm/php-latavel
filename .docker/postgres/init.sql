CREATE DATABASE asanbar_ag_test_db;
CREATE DATABASE asanbar_ag_location;
CREATE DATABASE asanbar_ag_test_location;
CREATE USER asanbar_ag_test_db_user WITH ENCRYPTED PASSWORD '123456';
CREATE USER asanbar_ag_location_db_user WITH ENCRYPTED PASSWORD '123456';
CREATE USER asanbar_ag_location_test_db_user WITH ENCRYPTED PASSWORD '123456';
GRANT ALL PRIVILEGES ON DATABASE asanbar_ag_test_db TO asanbar_ag_test_db_user;
GRANT ALL PRIVILEGES ON DATABASE asanbar_ag_location TO asanbar_ag_test_db_user;
GRANT ALL PRIVILEGES ON DATABASE asanbar_ag_test_location TO asanbar_ag_test_db_user;
