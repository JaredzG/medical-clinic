DROP DATABASE IF EXISTS med_clinic_db;
CREATE DATABASE med_clinic_db;
USE med_clinic_db;

CREATE TABLE IF NOT EXISTS User_Account (
	user_ID INT NOT NULL AUTO_INCREMENT,
    username varchar(35) NOT NULL,
    user_pass varchar(100) NOT NULL,
    user_role varchar(20) NOT NULL
		DEFAULT 'patient',
	user_phone_num varchar(20) NOT NULL,
    user_email_address varchar(45) NOT NULL,
	created_at datetime NOT NULL
		DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime NOT NULL
		DEFAULT CURRENT_TIMESTAMP,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
	PRIMARY KEY (user_ID),
	CHECK (user_role IN ('patient', 'doctor', 'nurse', 'receptionist', 'admin'))
);

CREATE TABLE IF NOT EXISTS Address (
	address_ID INT NOT NULL AUTO_INCREMENT,
	street_address varchar(45) NOT NULL,
    apt_num varchar(20),
    city varchar(20) NOT NULL,
    state varchar(20) NOT NULL,
    zip_code varchar(20) NOT NULL,
    office_add boolean NOT NULL
		DEFAULT false,
    deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (address_ID)
);

CREATE TABLE IF NOT EXISTS Department (
	department_number int NOT NULL AUTO_INCREMENT,
    dep_name varchar(45) NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT FALSE,
    PRIMARY KEY (department_number),
    UNIQUE (dep_name)
);

CREATE TABLE IF NOT EXISTS Doctor (
	doc_ID INT NOT NULL AUTO_INCREMENT,
    ssn int NOT NULL,
    dep_num int NOT NULL
		DEFAULT 1,
    f_name varchar(45) NOT NULL,
    m_name varchar(20)
		DEFAULT NULL,
    l_name varchar(45) NOT NULL,
    address_ID INT NOT NULL,
    credentials varchar(45),
    sex char NOT NULL,
    doc_user INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (doc_ID),
    UNIQUE (ssn, doc_user),
    FOREIGN KEY (dep_num)
		REFERENCES Department (department_number)
        ON DELETE RESTRICT,
	FOREIGN KEY (doc_user)
		REFERENCES User_Account (user_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (address_ID)
		REFERENCES Address (address_ID)
        ON DELETE RESTRICT,
	CHECK (sex IN ('M', 'F'))
);

CREATE TABLE IF NOT EXISTS Nurse (
	nurse_ID INT NOT NULL AUTO_INCREMENT,
    ssn int NOT NULL,
    dep_num int,
    f_name varchar(20) NOT NULL,
    m_name varchar(20)
		DEFAULT NULL,
    l_name varchar(20) NOT NULL,
    sex char NOT NULL,
    nurse_user INT NOT NULL,
    registered BOOLEAN NOT NULL
		DEFAULT false,
    address_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (nurse_ID),
    UNIQUE (ssn, nurse_user),
    FOREIGN KEY (dep_num)
		REFERENCES Department (department_number)
        ON DELETE RESTRICT,
	FOREIGN KEY (nurse_user)
		REFERENCES User_Account (user_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (address_ID)
		REFERENCES Address (address_ID)
        ON DELETE RESTRICT,
	CHECK (sex IN ('M', 'F'))
);

CREATE TABLE IF NOT EXISTS Receptionist (
	rec_ID INT NOT NULL AUTO_INCREMENT,
    ssn int NOT NULL,
    f_name varchar(20) NOT NULL,
    m_name varchar(20)
		DEFAULT NULL,
    l_name varchar(20) NOT NULL,
    sex char NOT NULL,
    rec_user INT NOT NULL,
    address_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (rec_ID),
    UNIQUE (ssn, rec_user),
	FOREIGN KEY (rec_user)
		REFERENCES User_Account (user_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (address_ID)
		REFERENCES Address (address_ID)
        ON DELETE RESTRICT,
    CHECK (sex IN ('M', 'F'))
);

CREATE TABLE IF NOT EXISTS Office (
	office_ID INT NOT NULL AUTO_INCREMENT,
    dep_number int,
    address_ID INT NOT NULL,
    phone_number varchar(20) NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (office_ID),
    FOREIGN KEY (dep_number)
		REFERENCES Department (department_number)
		ON DELETE RESTRICT,
	FOREIGN KEY (address_ID)
		REFERENCES Address (address_ID)
		ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Doctor_Works_In_Office (
	office_ID INT NOT NULL,
    doctor_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (office_ID, doctor_ID),
    FOREIGN KEY (office_ID)
		REFERENCES Office (office_ID)
		ON DELETE RESTRICT,
    FOREIGN KEY (doctor_ID)
		REFERENCES Doctor (doc_ID)
		ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Nurse_Works_In_Office (
	office_ID INT NOT NULL,
    nurse_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (office_ID, nurse_ID),
    FOREIGN KEY (office_ID)
		REFERENCES Office (office_ID)
		ON DELETE RESTRICT,
    FOREIGN KEY (nurse_ID)
		REFERENCES Nurse (nurse_ID)
		ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Nurse_Works_With_Doctor (
	nurse_ID INT NOT NULL,
    doc_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (nurse_ID, doc_ID),
    FOREIGN KEY (nurse_ID)
		REFERENCES Nurse (Nurse_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (doc_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Medicine (
	med_ID INT NOT NULL AUTO_INCREMENT,
    brand varchar(20) NOT NULL,
    name varchar(30) NOT NULL,
    description text NOT NULL,
    deleted_flag boolean NOT NULL 
		DEFAULT false,
    PRIMARY KEY (med_ID)
);

CREATE TABLE IF NOT EXISTS Patient (
	patient_ID INT NOT NULL AUTO_INCREMENT,
    ssn int NOT NULL,
    f_name varchar(20) NOT NULL,
    m_name varchar(20)
		DEFAULT NULL,
    l_name varchar(20) NOT NULL,
    sex char NOT NULL,
    pat_user INT NOT NULL,
    address_ID INT NOT NULL,
    clinic_ID INT NOT NULL,
    prim_doc_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (patient_ID),
    UNIQUE (ssn, pat_user),
	FOREIGN KEY (pat_user)
		REFERENCES User_Account (user_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (address_ID)
		REFERENCES Address (address_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (clinic_ID)
		REFERENCES Address (address_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (prim_doc_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT,
    CHECK (sex IN ('M', 'F'))
);

CREATE TABLE IF NOT EXISTS Doctor_Prescribes_Medicine_To_Patient (
	doc_ID INT NOT NULL,
    med_ID INT NOT NULL,
    pat_ID INT NOT NULL,
    PRIMARY KEY (doc_ID, med_ID, pat_ID),
    FOREIGN KEY (doc_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (med_ID)
		REFERENCES Medicine (med_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (pat_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS Doctor_For_Patient (
	doc_ID INT NOT NULL,
    pat_ID INT NOT NULL,
    deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (doc_ID, pat_ID),
    FOREIGN KEY (doc_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (pat_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Medical_Record (
	pat_ID INT NOT NULL,
    allergies text,
    diagnoses text,
    immunizations text,
    progress text,
    treatment_plan text,
	inch_height int,
    pound_weight int,
	b_date date NOT NULL,
    ethnicity varchar(20) NOT NULL,
    race varchar(20) NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (pat_ID),
    FOREIGN KEY (pat_ID) 
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Doctor_Maintains_Medical_Record (
	pat_ID INT NOT NULL,
    doc_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (doc_ID, pat_ID),
    FOREIGN KEY (pat_ID)
		REFERENCES Medical_Record (pat_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (doc_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Medical_Record_Contains_Medicine (
	pat_ID INT NOT NULL,
    med_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (pat_ID, med_ID),
    FOREIGN KEY (pat_ID)
		REFERENCES Medical_Record (pat_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (med_ID)
		REFERENCES Medicine (med_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Appointment (
	app_ID INT NOT NULL AUTO_INCREMENT,
    date_time datetime NOT NULL,
    reason varchar(100),
    office_ID INT NOT NULL,
    doctor_ID INT NOT NULL,
    patient_ID INT NOT NULL,
    receptionist_ID INT,
    status_flag INT NOT NULL
		DEFAULT 0,
    PRIMARY KEY (app_ID, date_time),
	FOREIGN KEY (office_ID)
		REFERENCES Office (office_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (doctor_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (patient_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (receptionist_ID)
		REFERENCES Receptionist (rec_ID)
        ON DELETE RESTRICT,
        -- 0: Scheduled, 1: Approved, 2: Completed, 3: Cancelled
	CHECK (status_flag IN (0, 1, 2, 3))
);

CREATE TABLE IF NOT EXISTS Transaction (
	transaction_ID INT NOT NULL AUTO_INCREMENT,
    patient_ID INT NOT NULL,
    transaction_date timestamp NOT NULL
		DEFAULT current_timestamp,
	app_ID INT NOT NULL,
    amount numeric(6,2),
    payment_ID INT
		DEFAULT NULL,
    PRIMARY KEY (transaction_ID),
    FOREIGN KEY (patient_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (app_ID)
		REFERENCES Appointment (app_ID)
        ON DELETE RESTRICT
);

ALTER TABLE Appointment
ADD COLUMN payment_ID INT AFTER patient_ID;
ALTER TABLE Appointment
ADD CONSTRAINT fk_payment_ID
FOREIGN KEY (payment_ID)
	REFERENCES Transaction (transaction_ID)
    ON DELETE RESTRICT;

CREATE TABLE IF NOT EXISTS Nurse_Works_On_Appointment (
	nurse_ID INT NOT NULL,
    appointment_ID INT NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (nurse_ID, appointment_ID),
    FOREIGN KEY (nurse_ID)
		REFERENCES Nurse (nurse_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (appointment_ID)
		REFERENCES Appointment (app_ID)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS Emergency_Contact (
	patient_ID INT NOT NULL,
    f_name varchar(20) NOT NULL,
    m_name varchar(20)
		DEFAULT NULL,
    l_name varchar(20) NOT NULL,
    relationship varchar(20),
    phone_num varchar(20) NOT NULL,
    sex char NOT NULL,
	deleted_flag BOOLEAN NOT NULL
		DEFAULT false,
    PRIMARY KEY (patient_ID),
    FOREIGN KEY (patient_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT,
	CHECK (sex IN ('M', 'F'))
);

CREATE TABLE IF NOT EXISTS Referral (
	date_time datetime NOT NULL
		DEFAULT CURRENT_TIMESTAMP,
	primary_ID INT NOT NULL,
    pat_ID INT NOT NULL,
    specialist_ID INT NOT NULL,
    deleted_flag BOOLEAN NOT NULL
		DEFAULT FALSE,
	PRIMARY KEY (date_time, primary_ID),
    FOREIGN KEY (primary_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (pat_ID)
		REFERENCES Patient (patient_ID)
        ON DELETE RESTRICT,
	FOREIGN KEY (specialist_ID)
		REFERENCES Doctor (doc_ID)
        ON DELETE RESTRICT
);

INSERT INTO User_Account (username, user_pass, user_role, user_phone_num,
user_email_address) VALUES ('admin1', 'adminpass', 'admin', '1234567890',
'admin1@gmail.com');

DELIMITER $$

CREATE TRIGGER after_completed_appointment
BEFORE UPDATE
ON Appointment FOR EACH ROW
BEGIN
IF new.status_flag = 2 THEN
	IF new.payment_ID IS NULL THEN
		INSERT INTO Transaction (patient_ID, app_ID, amount)
		VALUES (new.patient_ID, new.app_ID, 50.00);
	END IF;
END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER before_appointment_with_specialist
BEFORE INSERT
ON Appointment FOR EACH ROW
BEGIN
 IF (new.doctor_ID NOT IN (SELECT specialist_ID FROM Referral WHERE (pat_ID = new.patient_ID AND deleted_flag = 0))
	AND new.doctor_ID <> (SELECT prim_doc_ID FROM Patient WHERE Patient.patient_ID = new.patient_ID)) THEN
		SET new.patient_ID = NULL;
END IF;
END$$

DELIMITER ;