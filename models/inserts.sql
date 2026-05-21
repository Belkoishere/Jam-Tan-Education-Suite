INSERT INTO Staff (StaffFirstName, StaffLastName, 
StaffTitle, Town, StaffContact1, StaffContact2, Email,
StaffPassword, StaffAccessLevel) 
VALUES
('Belko', 'Diallo', 'Mr', 'Ziniare', '56743817', NULL, 
'belkojdiallo@jamtan.com', 
'$2y$10$BPwjpMmtHFBs4BWXGASWreZsz5x1BB6tvaMFGHtv6X4wNYfu9c6du', 'Teacher'), -- Password: Password123!
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
"Boureima", "Diallo", "Aisha", "Diallo", "STI3");

("Hamidou", "Diallo", "2016-02-03", "Barkoundouba", "Male", 4, "06363917",
"Mamoudou", "Abdoul", "Aisha", "Abdoul", "STI4"),

("Hama", "Dicko", "2019-02-03", "Ziniare", "Male", 3, "54743819",
"Boureima", "Dicko", "Absatou", "Dicko", "STI5"),

("Aminata", "Ouédraogo", "2016-02-03", "Ziniare", "Female", 4, "54743817",
"Boureima", "Ouédraogo", "Aisha", "Ouédraogo", "STI6");

INSERT INTO Enrollment (StudentID, ProgramID)
VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 1),
(5, 2),
(6, 1);

INSERT INTO StudentAttendance (Attendance, EnrollmentID, AttendanceDate)
VALUES
("Present", 1, "2025-05-01"),
("Present", 1, "2025-05-08"),
("Absent", 1, "2026-01-08"),
("Absent", 1, "2026-01-15"),
("Absent", 1, "2026-02-15"),
("Present", 2, "2026-02-15"),
("Present", 2, "2026-02-22"),
("Absent", 2, "2026-03-14");
("Present", 3, "2025-05-01"),
("Present", 3, "2026-05-08"),
("Absent", 3, "2026-01-08"),
("Absent", 3, "2026-01-15"),
("Absent", 3, "2026-02-15"),
("Present", 4, "2026-01-08"),
("Present", 4, "2026-01-15"),
("Absent", 4, "2026-02-15");
("Present", 5, "2026-02-15"),
("Present", 5, "2026-02-22"),
("Absent", 5, "2026-03-14"),
("Absent", 5, "2026-04-14"),
("Absent", 5, "2026-05-14"),
("Present", 6, "2025-05-01"),
("Present", 6, "2025-05-08"),
("Absent", 6, "2026-01-08");

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
("Devoir de mathématiques", "2025-05-19", 10, 6, 2, 2),
("Devoir de geographie", "2025-06-18", 10, 7, 2, 2),
("Examen d'anglais", "2026-04-08", 20, 14, 1, 1),
("Examen de francais", "2026-04-13", 20, 12, 1, 1),
("Devoir de mathématiques", "2026-05-19", 10, 6, 2, 2),
("Devoir de geographie", "2026-06-18", 10, 7, 2, 2);

INSERT INTO Grade (Grade, Pass, AssessmentID, EnrollmentID)
VALUES
(18, "Pass", 1, 1),
(11, "Fail", 2, 1),
(5, "Fail", 3, 2),
(6, "Fail", 4, 2);