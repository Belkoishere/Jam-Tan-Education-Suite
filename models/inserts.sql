INSERT INTO Staff (StaffFirstName, StaffLastName, 
StaffTitle, Town, StaffContact1, StaffContact2, Email, 
StaffPicture, StaffPassword) 
VALUES
('Belko', 'Diallo', 'Mr', 'Ziniare', '56743817', NULL, 
'belkojdiallo@gmail.com', NULL, 
'$2y$10$zQquxYGs./WixGZ0lAu5u.S/HMs0OY1t5vxuzFnS2eX1rHykMILIO'); -- Password: Belko!=0;

INSERT INTO Program (ProgramName, MinAge, MaxAge, ProgramType) VALUES
('Jam Tan Primary (CM2)', NULL, NULL, 1),
('Jam Naati (6 - 8 ans)', 6, 8, 1);

INSERT INTO Assignment (StaffID, ProgramID) 
VALUES
(1, 2),
(1, 1);

INSERT INTO Student (StudentFirstName, StudentLastName, 
StudentBirthDate, StudentTown, StudentGender, StudentSchool_Year, 
Contact1, FatherFirst_Name, FatherLast_Name, 
MotherFirst_Name, MotherLast_Name)
VALUES
("Azis", "Abdoul", "2016-02-03", "Barkoundouba", "Male", 4, "56763817",
"Mamoudou", "Abdoul", "Aisha", "Abdoul"),

("Belko", "Diallo", "2018-02-03", "Ziniare", "Male", 3, "56743817",
"Boureima", "Diallo", "Susanna", "Evans");

INSERT INTO Enrollment (StudentID, ProgramID)
VALUES
(1, 1),
(2, 2);

INSERT INTO StudentAttendance (Attendance, EnrollmentID)
VALUES
("Present", 1),
("Present", 1),
("Absent", 1),
("Absent", 1),
("Absent", 1),
("Present", 2),
("Present", 2),
("Absent", 2);

INSERT INTO Assessment (AssessmentName, AssessmentDueDate, MaxGrade, PassGrade,
AssessmentType, ProgramID)
VALUES
("Biology test", "2025-04-08", 20, 14, 1, 1),
("French test", "2025-04-13", 20, 12, 1, 1),
("Math quiz", "2025-05-19", 10, 6, 2, 2),
("Geography quiz", "2025-06-18", 10, 7, 2, 2),
("Biology test", "2026-04-08", 20, 14, 1, 1),
("French test", "2026-04-13", 20, 12, 1, 1),
("Math quiz", "2026-05-19", 10, 6, 2, 2),
("Geography quiz", "2026-06-18", 10, 7, 2, 2);

INSERT INTO Grade (Grade, Pass, AssessmentID, EnrollmentID)
VALUES
(18, "Pass", 1, 1),
(11, "Fail", 2, 1),
(5, "Fail", 3, 2),
(6, "Fail", 4, 2);


