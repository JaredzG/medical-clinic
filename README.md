# Medical Clinic Link: http://ec2-34-229-11-238.compute-1.amazonaws.com/index.php

Of the Code Files that are found on the GitHub Repository page for this project:
The .php files not in the ‘includes’ folder hold the code for the web pages that are actually shown to the end user
The .inc.php files found in the ‘includes’ folder are not shown to the end user. They hold code that processes internal information for the correlated web pages that are shown to the end user. These files make use of the functions.inc.php and dbh.inc.php files also found in the ‘includes’ folder.
Inside the ‘includes’ folder, functions.inc.php is used to hold all of the functions that the web app uses to process information. The dbh.inc.php file establishes and defines the connection to the web app database.

User Accounts
Admin:
Username: admin1
Password: adminpass
Doctor:	
Username: doc1/doc2/…
Password: docpass	
Receptionist:
Username: rec1
Password: recpass
Patient: 
Username: pat1/pat2/…
Password: patpass
Nurse:
Username: nurse1/nurse2/…
Password: nursepass

How to Schedule an Appointment
As a PATIENT: Select ‘Make an Appointment’ in the navigation bar and enter the required information. Trigger 2 can occur if a patient attempts to make an appointment without a referral. 
As a RECEPTIONIST: similar as a patient user, however the receptionist will only need to enter a patient’s username

How to Refer a Patient to a Specialist as a primary DOCTOR
Select ‘Referrals’ in the navigation bar and choose a patient and the specialist you’re referring them to from the drop-down. Click ‘Make a Referral’ and the newly made referral will appear in the ‘Active Referrals’ tab

How to sign up an employee as an ADMIN 
As an ADMIN: Select ‘Register Employees’ from the navigation bar and fill out the account information and assign their role with the dropdown. Next, complete the employee's personal information form.

How to mark an appointment as complete and charge a patient’s account as a RECEPTIONIST (Trigger 1) 
After logging on as a RECEPTIONIST, select ‘View Appointments’ and adjust the date range to view appointments within a time range. As a receptionist, you can approve appointments under the Actions column in the Scheduled Appointments section. That appointment will then move to the ‘Approved Appointments’ section. When a patient has visited the clinic and finished their appointment, the receptionist may mark the appointment as complete and it will move to the Completed Appointments section.

How to view reports as an ADMIN
Select the report you wish to view in the navigation bar. For the Doctor and Department reports, enter a time range and select submit to view. For the Medication report, simply click submit and it will generate the report. 

User roles and types of data that can be read, added, modified, and removed according to the user authorization
ADMIN
Add, Remove, and Update: Departments, Clinics, Offices, Employees, Patients
View Medicine, Medical Records
View Doctor, Department and Medication Reports

DOCTOR
i.	View Medical Records, Patients, and scheduled appointments.
ii.	Add, Remove, and Update: Specialist referrals, Medication
iii.	Modify information in settings (Name, Credentials, Address, etc.)
iv. 	Modify Medical Records

NURSE
View Appointments
Modify Personal Info

RECEPTIONIST
 i.    Read, Write, and Modify: Appointments.
Ii.    View: Transactions, Referrals.
iii.   Modify Personal Info

PATIENT
Schedule and view appointments
Modify account information (Name, address, delete account, etc.)
View Medical Record, Balance, Transactions
Create an account

The semantic constraints which are implemented as triggers
Trigger 1
 When patient’s appointment is completed by receptionist, their account will be charged $50, thus increasing their calculated balance by $50
 
Trigger 2
When a patient tries to schedule an appointment with a specialist doctor without a referral from their primary doctor then an error will be raised and prevent the patient from making that appointment.

The code for the triggers can be found at the bottom of the med_clinic_db.sql file

Types of queries/reports available in the application
Doctor Report: Displays how many appointments have been completed and patients treated per doctor for a given range of time/date. This report might be used by Supervisors to keep track of doctor’s quota requirements.
located in medical-clinic/includes/functions.inc.php line 1332

Department Report: Displays how many doctors are working in a department, total appointments made in that department, newly admitted patient count, and revenue for a given range of time/date. This report might be useful for Department Administrators to evaluate whether they should allocate more doctors to a department based on the amount of patients and revenue.
located in medical-clinic/includes/functions.inc.php line 1346

Medication Report: Displays percentages of medication users categorized by ethnicity as well as patient count, average patient weight, height, and age per medication. This report might be useful for scientists conducting research on a given medication. 
located in medical-clinic/includes/functions.inc.php line 1360
