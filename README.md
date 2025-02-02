Secure AMC Student Management System

✅ User Roles & Permissions
Admin:	Full access (CRUD functions & user management)
Faculty:	Manage student records related to their courses
Student:	View-only access to personal records & grades

🛠 CRUD Operations
1. User Management
Admin & Faculty
🔹 Create: Register students, faculty, and admin users.
🔹 Read: View all student records.
🔹 Update: Modify student details.
🔹 Delete: Only Admin can delete student records.
2. Class Management
Admin & Faculty
🔹 Create: Add new classes (semester/term-based).
🔹 Read: View all classes.
🔹 Update: Modify class details.
🔹 Delete: Only Admin can delete classes.
3. Course Management
Admin & Faculty
🔹 Create: Add courses (name, code, start/end date).
🔹 Read: View all course details.
🔹 Update: Edit course details.
🔹 Delete: Only Admin can delete courses.
4. Grade Management
Admin & Faculty
🔹 Create: Assign grades and scores to students.
🔹 Read: View student grade records.
🔹 Update: Modify student grades.
🔹 Delete: Only Admin can delete grades (only if course has ended).

🔒 Security Features Implemented
Authentication:	Secure login system using PHP sessions with password hashing.
Authorization: Role-based access control (RBAC) to restrict unauthorized access.
CSRF Protection:	Implemented CSRF tokens in forms to prevent cross-site request forgery attacks.
Input Validation:	Server-side validation to prevent SQL injection, XSS, and other input attacks.
Password Hashing:	Passwords are stored securely using password_hash().
Secure Error Handling:	Custom error messages to prevent exposing system details.

Credentials
Admin
🔹 Username: Cat
🔹 Password: password
🔹 Email: cat@admin.com
🔹 Role: Admin

Faculty
🔹 Username: Capy
🔹 Password: password
🔹 Email: capy@faculty.com
🔹 Role: Faculty

Student
🔹 Name: Snake
🔹 Email: snake@student.com
🔹 Phone: password
🔹 Student Number: 1234567x
🔹 Course: Cybersecurity & Digital Forensics
🔹 Department: IIT
🔹 Password: password
