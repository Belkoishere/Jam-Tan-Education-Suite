INSERT INTO Staff (StaffFirstName, StaffLastName, 
StaffTitle, Town, StaffContact1, StaffContact2, Email,
StaffPassword, StaffAccessLevel) 
VALUES
-- Teacher account1
('Belko', 'Diallo', 'Mr', 'Ziniare', '56743817', NULL, 
'belkojdiallo@jamtan.com', 
'$2y$10$BPwjpMmtHFBs4BWXGASWreZsz5x1BB6tvaMFGHtv6X4wNYfu9c6du', 'Teacher'), -- Password: Password123!

-- Teacher account2
('Abdoul', 'Azis', 'Mr', 'Ziniare', '55678916', NULL, 
'belkojdiallo@jamtan.com', 
'$2y$10$Y10oPwogCqbiVr8vOt/SLe0W.NZ6AiaaPLQFs4UoXw6LgJPUUd7Ai', 'Teacher'), -- Password: Password1212!

-- Administrator account
('Aisha', 'Bande', 'Mrs', 'Barkoundouba', '56843819', NULL, 
'aishabande@jamtan.com', 
'$2y$10$wfgQ1vO44DPiPD5/ZjItYuriyHJ7mTACUyWtUapvjV139RstWXS5y', 'Administrator'); -- Password: OtherPassword123!

INSERT INTO ProgramCategory (CategoryName) VALUES
('Jam Tan Primaire'),
('Club Jam Naati');

INSERT INTO Program (ProgramName, MinAge, MaxAge, CategoryID) VALUES
('CM2', NULL, NULL, 1),
('6 - 8 ans', 6, 8, 2),

('CM1', NULL, NULL, 1),
('6 - 8 ans (Group 2)', 6, 8, 2);

INSERT INTO Assignment (StaffID, ProgramID) 
VALUES
(1, 2),
(1, 1),
(2, 3),
(2, 4);

INSERT INTO StaffAttendance (AttendanceDate, Attendance, AssignmentID);
VALUES 
("2026-04-06", "Present", 1),
("2026-05-06", "Present", 1),
("2026-06-01", "Absent", 1),

("2026-04-06", "Present", 2),

("2026-04-06", "Present", 3),
("2026-05-06", "Absent", 3),
("2026-06-01", "Absent", 3),

("2026-04-06", "Present", 4);

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
"Boureima", "Ouédraogo", "Aisha", "Ouédraogo", "STI6"),


("Oumarou", "Abdoul", "2016-02-03", "Barkoundouba", "Male", 4, "56763817",
"Mamoudou", "Abdoul", "Aisha", "Abdoul", "STI7"),

("Binta", "Diallo", "2019-02-03", "Ziniare", "Female", 3, "59041813",
"Alfa", "Diallo", "Aminata", "Diallo", "STI8"),

("Boukary", "Diallo", "2015-02-03", "Ziniare", "Male", 4, "86741817",
"Daniel", "Diallo", "Aisha", "Diallo", "STI9"),

("Alfa", "Diallo", "2016-02-03", "Barkoundouba", "Male", 4, "06363917",
"Mamoudou", "Abdoul", "Aisha", "Abdoul", "STI10"),

("Ousseni", "Dicko", "2019-02-03", "Ziniare", "Male", 3, "54743819",
"Hama", "Dicko", "Absatou", "Dicko", "STI11"),

("Aisha", "Ouédraogo", "2016-02-03", "Ziniare", "Female", 4, "54743817",
"Belko", "Ouédraogo", "Aisha", "Ouédraogo", "STI12");


INSERT INTO Enrollment (StudentID, ProgramID)
VALUES
(1, 1),
(3, 1),
(4, 1),
(6, 1),
(2, 2),
(5, 2),

(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 4),
(12, 4);

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
("Present", 6, "2026-07-15"),


("Present", 7, "2025-08-01"),
("Present", 7, "2025-09-08"),
("Present", 7, "2025-10-08"),
("Present", 7, "2025-11-15"),
("Present", 7, "2025-12-15"),

("Present", 7, "2026-01-01"),
("Present", 7, "2026-02-08"),
("Present", 7, "2026-03-08"),
("Absent", 7, "2026-04-15"),
("Absent", 7, "2026-05-15"),
("Absent", 7, "2026-06-15"),
("Absent", 7, "2026-07-15"),

("Present", 8, "2025-08-01"),
("Absent", 8, "2025-09-08"),
("Absent", 8, "2025-10-08"),
("Absent", 8, "2025-11-15"),
("Present", 8, "2025-12-15"),

("Absent", 8, "2026-01-01"),
("Present", 8, "2026-02-08"),
("Absent", 8, "2026-03-08"),
("Present", 8, "2026-04-15"),
("Absent", 8, "2026-05-15"),
("Present", 8, "2026-06-15"),
("Absent", 8, "2026-07-15"),

("Absent", 9, "2025-08-01"),
("Absent", 9, "2025-09-08"),
("Present", 9, "2025-10-08"),
("Absent", 9, "2025-11-15"),
("Absent", 9, "2025-12-15"),

("Present", 9, "2026-01-01"),
("Present", 9, "2026-02-08"),
("Absent", 9, "2026-03-08"),
("Absent", 9, "2026-04-15"),
("Present", 9, "2026-05-15"),
("Absent", 9, "2026-06-15"),
("Present", 9, "2026-07-15"),

("Present", 10, "2025-08-01"),
("Absent", 10, "2025-09-08"),
("Present", 10, "2025-10-08"),
("Absent", 10, "2025-11-15"),
("Present", 10, "2025-12-15"),

("Present", 10, "2026-01-01"),
("Present", 10, "2026-02-08"),
("Absent", 10, "2026-03-08"),
("Absent", 10, "2026-04-15"),
("Present", 10, "2026-05-15"),
("Absent", 10, "2026-06-15"),
("Present", 10, "2026-07-15"),

("Present", 11, "2025-08-01"),
("Absent", 11, "2025-09-08"),
("Present", 11, "2025-10-08"),
("Absent", 11, "2025-11-15"),
("Present", 11, "2025-12-15"),

("Present", 11, "2026-01-01"),
("Present", 11, "2026-02-08"),
("Absent", 11, "2026-03-08"),
("Present", 11, "2026-04-15"),
("Absent", 11, "2026-05-15"),
("Absent", 11, "2026-06-15"),
("Absent", 11, "2026-07-15"),

("Present", 12, "2025-08-01"),
("Absent", 12, "2025-09-08"),
("Present", 12, "2025-10-08"),
("Present", 12, "2025-11-15"),
("Absent", 12, "2025-12-15"),

("Absent", 12, "2026-01-01"),
("Present", 12, "2026-02-08"),
("Present", 12, "2026-03-08"),
("Present", 12, "2026-04-15"),
("Present", 12, "2026-05-15"),
("Present", 12, "2026-06-15"),
("Present", 12, "2026-07-15");

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
("Devoir de mathématiques", "2026-07-18", 10, 7, 2, 2),


("Examen de biologie", "2025-04-08", 20, 14, 1, 3),
("Examen de francais", "2025-04-13", 20, 12, 1, 3),
("Examen d'anglais", "2026-04-08", 20, 14, 1, 3),
("Examen de francais", "2026-04-13", 20, 12, 1, 3),

("Examen d'histoire", "2026-10-08", 20, 14, 1, 3),
("Examen de science", "2026-11-13", 20, 12, 1, 3),
("Examen d'anglais", "2026-11-08", 20, 14, 1, 3),
("Examen de francais", "2026-11-13", 20, 12, 1, 3),

("Devoir de mathématiques", "2025-05-19", 10, 6, 2, 4),
("Devoir de geographie", "2025-06-18", 10, 7, 2, 4),
("Devoir de mathématiques", "2026-05-19", 10, 6, 2, 4),
("Devoir de geographie", "2026-06-18", 10, 7, 2, 4),

("Devoir d'histoire", "2026-09-19", 10, 6, 2, 4),
("Devoir de science", "2026-09-18", 10, 7, 2, 4),
("Devoir de dessin", "2026-08-19", 10, 6, 2, 4),
("Devoir de mathématiques", "2026-07-18", 10, 7, 2, 4);

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


(6, "Pass", 9, 5),
(6, "Fail", 10, 5),
(8, "Pass", 11, 5),
(6, "Fail", 12, 5),

(6, "Pass", 9, 6),
(6, "Pass", 10, 6),
(8, "Pass", 11, 6),
(6, "Pass", 12, 6),


(18, "Pass", 17, 7),
(11, "Pass", 18, 7),
(18, "Pass", 19, 7),
(11, "Pass", 20, 7),

(18, "Pass", 17, 8),
(11, "Fail", 18, 8),
(18, "Pass", 19, 8),
(11, "Fail", 20, 8),

(18, "Pass", 17, 9),
(11, "Fail", 18, 9),
(18, "Pass", 19, 9),
(11, "Fail", 20, 9),

(18, "Pass", 17, 10),
(11, "Fail", 18, 10),
(18, "Pass", 19, 10),
(11, "Fail", 20, 10),


(6, "Pass", 25, 11),
(6, "Fail", 26, 11),
(8, "Pass", 27, 11),
(6, "Fail", 28, 11),

(6, "Pass", 25, 12),
(6, "Pass", 26, 12),
(8, "Pass", 27, 12),
(6, "Pass", 28, 12);
