INSERT INTO Staff (StaffFirstName, StaffLastName, 
StaffTitle, Town, StaffContact1, StaffContact2, Email,
StaffPassword, StaffAccessLevel) 
VALUES
-- Teacher account
('Belko', 'Diallo', 'Mr', 'Ziniare', '56743817', NULL, 
'belkojdiallo@jamtan.com', 
'$2y$10$BPwjpMmtHFBs4BWXGASWreZsz5x1BB6tvaMFGHtv6X4wNYfu9c6du', 'Teacher'), -- Password: Password123!

-- Administrator account
('Aisha', 'Bande', 'Mrs', 'Barkoundouba', '56843819', NULL, 
'aishabande@jamtan.com', 
'$2y$10$wfgQ1vO44DPiPD5/ZjItYuriyHJ7mTACUyWtUapvjV139RstWXS5y', 'Administrator'); -- Password: OtherPassword123!

INSERT INTO ProgramCategory (CategoryName) VALUES
('Jam Tan Primaire'),
('Club Jam Naati');

INSERT INTO Program (ProgramName, MinAge, MaxAge, CategoryID) VALUES
('CM2', NULL, NULL, 1),
('6 - 8 ans', 6, 8, 2);

INSERT INTO Assignment (StaffID, ProgramID) 
VALUES
(1, 2),
(1, 1);

INSERT INTO Student (StudentFirstName, StudentLastName, 
StudentBirthDate, StudentTown, StudentGender, StudentSchool_Year, 
Contact1, FatherFirst_Name, FatherLast_Name, 
MotherFirst_Name, MotherLast_Name, StudentPicture)
VALUES
("Azis", "Abdoul", "2016-02-03", "Barkoundouba", "Male", 4, "56763817",
"Mamoudou", "Abdoul", "Aisha", "Abdoul", "STI1"),

("Aisha", "Diallo", "2019-02-03", "Ziniare", "Female", 3, "59041813",
"Hama", "Diallo", "Aminata", "Diallo", "STI2"),

("Belko", "Diallo", "2015-02-03", "Ziniare", "Male", 4, "86741817",
"Boureima", "Diallo", "Aisha", "Diallo", "STI3"),

("Hamidou", "Diallo", "2016-02-03", "Barkoundouba", "Male", 4, "06363917",
"Mamoudou", "Abdoul", "Aisha", "Abdoul", "STI4"),

("Hama", "Dicko", "2019-02-03", "Ziniare", "Male", 3, "54743819",
"Boureima", "Dicko", "Absatou", "Dicko", "STI5"),

("Aminata", "Ouédraogo", "2016-02-03", "Ziniare", "Female", 4, "54743817",
"Boureima", "Ouédraogo", "Aisha", "Ouédraogo", "STI6");

INSERT INTO Enrollment (StudentID, ProgramID)
VALUES
(1, 1),
(3, 1),
(4, 1),
(6, 1),
(2, 2),
(5, 2);

INSERT INTO StudentAttendance (Attendance, EnrollmentID, AttendanceDate)
VALUES
("Present", 1, "2025-08-01"),
("Present", 1, "2025-09-08"),
("Present", 1, "2025-10-08"),
("Present", 1, "2025-11-15"),
("Present", 1, "2025-12-15"),

("Present", 1, "2026-01-01"),
("Present", 1, "2026-02-08"),
("Present", 1, "2026-03-08"),
("Absent", 1, "2026-04-15"),
("Absent", 1, "2026-05-15"),
("Absent", 1, "2026-06-15"),
("Absent", 1, "2026-07-15"),

("Present", 2, "2025-08-01"),
("Absent", 2, "2025-09-08"),
("Absent", 2, "2025-10-08"),
("Absent", 2, "2025-11-15"),
("Present", 2, "2025-12-15"),

("Absent", 2, "2026-01-01"),
("Present", 2, "2026-02-08"),
("Absent", 2, "2026-03-08"),
("Present", 2, "2026-04-15"),
("Absent", 2, "2026-05-15"),
("Present", 2, "2026-06-15"),
("Absent", 2, "2026-07-15"),

("Absent", 3, "2025-08-01"),
("Absent", 3, "2025-09-08"),
("Present", 3, "2025-10-08"),
("Absent", 3, "2025-11-15"),
("Absent", 3, "2025-12-15"),

("Present", 3, "2026-01-01"),
("Present", 3, "2026-02-08"),
("Absent", 3, "2026-03-08"),
("Absent", 3, "2026-04-15"),
("Present", 3, "2026-05-15"),
("Absent", 3, "2026-06-15"),
("Present", 3, "2026-07-15"),

("Present", 4, "2025-08-01"),
("Absent", 4, "2025-09-08"),
("Present", 4, "2025-10-08"),
("Absent", 4, "2025-11-15"),
("Present", 4, "2025-12-15"),

("Present", 4, "2026-01-01"),
("Present", 4, "2026-02-08"),
("Absent", 4, "2026-03-08"),
("Absent", 4, "2026-04-15"),
("Present", 4, "2026-05-15"),
("Absent", 4, "2026-06-15"),
("Present", 4, "2026-07-15"),

("Present", 5, "2025-08-01"),
("Absent", 5, "2025-09-08"),
("Present", 5, "2025-10-08"),
("Absent", 5, "2025-11-15"),
("Present", 5, "2025-12-15"),

("Present", 5, "2026-01-01"),
("Present", 5, "2026-02-08"),
("Absent", 5, "2026-03-08"),
("Present", 5, "2026-04-15"),
("Absent", 5, "2026-05-15"),
("Absent", 5, "2026-06-15"),
("Absent", 5, "2026-07-15"),

("Present", 6, "2025-08-01"),
("Absent", 6, "2025-09-08"),
("Present", 6, "2025-10-08"),
("Present", 6, "2025-11-15"),
("Absent", 6, "2025-12-15"),

("Absent", 6, "2026-01-01"),
("Present", 6, "2026-02-08"),
("Present", 6, "2026-03-08"),
("Present", 6, "2026-04-15"),
("Present", 6, "2026-05-15"),
("Present", 6, "2026-06-15"),
("Present", 6, "2026-07-15");

INSERT INTO assessmenttype (TypeName)
VALUES ("Exam"),
("Assignment"),
("Open book Exam"),
("Quiz");

INSERT INTO Assessment (AssessmentName, AssessmentDueDate, MaxGrade, PassGrade,
TypeID, ProgramID)
VALUES
("Examen de biologie", "2025-04-08", 20, 14, 1, 1),
("Examen de francais", "2025-04-13", 20, 12, 1, 1),
("Examen d'anglais", "2026-04-08", 20, 14, 1, 1),
("Examen de francais", "2026-04-13", 20, 12, 1, 1),

("Examen d'histoire", "2026-10-08", 20, 14, 1, 1),
("Examen de science", "2026-11-13", 20, 12, 1, 1),
("Examen d'anglais", "2026-11-08", 20, 14, 1, 1),
("Examen de francais", "2026-11-13", 20, 12, 1, 1),

("Devoir de mathématiques", "2025-05-19", 10, 6, 2, 2),
("Devoir de geographie", "2025-06-18", 10, 7, 2, 2),
("Devoir de mathématiques", "2026-05-19", 10, 6, 2, 2),
("Devoir de geographie", "2026-06-18", 10, 7, 2, 2),

("Devoir d'histoire", "2026-09-19", 10, 6, 2, 2),
("Devoir de science", "2026-09-18", 10, 7, 2, 2),
("Devoir de dessin", "2026-08-19", 10, 6, 2, 2),
("Devoir de mathématiques", "2026-07-18", 10, 7, 2, 2);

INSERT INTO Grade (Grade, Pass, AssessmentID, EnrollmentID)
VALUES
(18, "Pass", 1, 1),
(11, "Pass", 2, 1),
(18, "Pass", 3, 1),
(11, "Pass", 4, 1),

(18, "Pass", 1, 2),
(11, "Fail", 2, 2),
(18, "Pass", 3, 2),
(11, "Fail", 4, 2),

(18, "Pass", 1, 3),
(11, "Fail", 2, 3),
(18, "Pass", 3, 3),
(11, "Fail", 4, 3),

(18, "Pass", 1, 4),
(11, "Fail", 2, 4),
(18, "Pass", 3, 4),
(11, "Fail", 4, 4),


(6, "Pass", 5, 5),
(6, "Fail", 6, 5),
(8, "Pass", 7, 5),
(6, "Fail", 8, 5),

(6, "Pass", 5, 6),
(6, "Pass", 6, 6),
(8, "Pass", 7, 6),
(6, "Pass", 8, 6);
