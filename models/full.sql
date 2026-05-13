CREATE DATABASE IF NOT EXISTS JamTanEduSuiteDB;
USE JamTanEduSuiteDB;

-- =======================
-- STUDENT
-- =======================
CREATE TABLE Student (
StudentID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
StudentFirstName VARCHAR(60) NOT NULL,
StudentLastName VARCHAR(60) NOT NULL,
StudentBirthDate DATE NOT NULL,
StudentTown VARCHAR(60) NOT NULL,
StudentGender ENUM('Male', 'Female'),
StudentSchool_Year TINYINT UNSIGNED NOT NULL DEFAULT 0,
StudentPicture VARCHAR(255) NOT NULL DEFAULT 'default',
Contact1 VARCHAR(8) NOT NULL,
Contact2 VARCHAR(8),
FatherFirst_Name VARCHAR(60) NOT NULL,
FatherLast_Name VARCHAR(60) NOT NULL,
MotherFirst_Name VARCHAR(60) NOT NULL,
MotherLast_Name VARCHAR(60) NOT NULL
) ENGINE=InnoDB;

-- =======================
-- PROGRAM CATEGORY
-- =======================
CREATE TABLE ProgramCategory (
CategoryID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
CategoryName VARCHAR(100) NOT NULL
);

-- =======================
-- PROGRAM
-- =======================
CREATE TABLE Program (
ProgramID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
ProgramName VARCHAR(100) NOT NULL,
MinAge INTEGER UNSIGNED,
MaxAge INTEGER UNSIGNED,
CategoryID INTEGER,
FOREIGN KEY (CategoryID) REFERENCES ProgramCategory(CategoryID)
);

-- =======================
-- ENROLLMENT
-- =======================
CREATE TABLE Enrollment (
EnrollmentID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
EnrollmentDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
EnrollmentStatus TINYINT UNSIGNED NOT NULL DEFAULT 0,
StudentID INTEGER NOT NULL,
ProgramID INTEGER NOT NULL,
FOREIGN KEY (StudentID) REFERENCES Student(StudentID),
FOREIGN KEY (ProgramID) REFERENCES Program(ProgramID)
);

-- =======================
-- ATTENDANCE
-- =======================
CREATE TABLE StudentAttendance (
AttendanceID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
AttendanceDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
Attendance ENUM('Absent', 'Present') NOT NULL,
Reason VARCHAR(255),
EnrollmentID INTEGER NOT NULL,
FOREIGN KEY(EnrollmentID) REFERENCES Enrollment(EnrollmentID)
);

-- =======================
-- ASSESSMENT TYPE
-- =======================
CREATE TABLE AssessmentType (
TypeID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
TypeName VARCHAR(100) NOT NULL
);

-- =======================
-- ASSESSMENT
-- =======================
CREATE TABLE Assessment (
AssessmentID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
AssessmentName VARCHAR(100) NOT NULL,
AssessmentPublishDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
AssessmentStartDate DATE,
AssessmentDueDate DATE NOT NULL,
MaxGrade INTEGER UNSIGNED NOT NULL,
PassGrade INTEGER UNSIGNED NOT NULL,
TypeID INTEGER NOT NULL,
ProgramID INTEGER NOT NULL,
FOREIGN KEY (ProgramID) REFERENCES Program(ProgramID),
FOREIGN KEY (TypeID) REFERENCES AssessmentType(TypeID)
);

-- =======================
-- GRADE
-- =======================
CREATE TABLE Grade (
GradeID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
AssessmentID INTEGER NOT NULL,
EnrollmentID INTEGER NOT NULL,
Grade INTEGER UNSIGNED NOT NULL,
Pass ENUM('Fail', 'Pass') NOT NULL,
GradeDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
Feedback VARCHAR(500),
UNIQUE KEY EnrollmentAssessment (EnrollmentID, AssessmentID),
FOREIGN KEY (AssessmentID) REFERENCES Assessment(AssessmentID),
FOREIGN KEY (EnrollmentID) REFERENCES Enrollment(EnrollmentID)
);

-- =======================
-- STAFF
-- =======================
CREATE TABLE Staff (
StaffID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
StaffFirstName VARCHAR(60) NOT NULL,
StaffLastName VARCHAR(60) NOT NULL,
StaffTitle ENUM('Mr', 'Mrs', 'Ms') NOT NULL,
Town VARCHAR(100) NOT NULL,
StaffContact1 VARCHAR(8) NOT NULL,
StaffContact2 VARCHAR(8),
Email VARCHAR(255),
StaffPicture VARCHAR(255) NOT NULL DEFAULT 'default.jpg',
StaffPassword VARCHAR(255) NOT NULL,
StaffAccessLevel ENUM('Teacher', 'Administrator')
);

-- =======================
-- ASSIGNMENT
-- =======================
CREATE TABLE Assignment (
AssignmentID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
AssignmentDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
AssignmentStatus TINYINT UNSIGNED NOT NULL DEFAULT 0,
StaffID INTEGER NOT NULL,
ProgramID INTEGER NOT NULL,
FOREIGN KEY (StaffID) REFERENCES Staff(StaffID),
FOREIGN KEY (ProgramID) REFERENCES Program(ProgramID)
);

-- =======================
-- STAFF ATTENDANCE
-- =======================
CREATE TABLE StaffAttendance (
AttendanceID INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
AttendanceDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
Attendance ENUM('Absent', 'Present') NOT NULL,
AssignmentID INTEGER NOT NULL,
FOREIGN KEY (AssignmentID) REFERENCES Assignment(AssignmentID)
);

INSERT INTO Staff (StaffFirstName, StaffLastName, 
StaffTitle, Town, StaffContact1, StaffContact2, Email,
StaffPassword, StaffAccessLevel) 
VALUES
('Belko', 'Diallo', 'Mr', 'Ziniare', '56743817', NULL, 
'belkojdiallo@gmail.com', 
'$2y$10$zQquxYGs./WixGZ0lAu5u.S/HMs0OY1t5vxuzFnS2eX1rHykMILIO', 'Teacher'); -- Password: Belko!=0;

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
('Azis', 'Abdoul', '2016-02-03', 'Barkoundouba', 'Male', 4, '56763817',
'Mamoudou', 'Abdoul', 'Aisha', 'Abdoul', 'STI1'),

('Aisha', 'Diallo', '2018-02-03', 'Ziniare', 'Female', 3, '56743817',
'Boureima', 'Diallo', 'Absatou', 'Diallo', 'STI2'),

('Belko', 'Diallo', '2016-02-03', 'Ziniare', 'Male', 4, '56743817',
'Boureima', 'Diallo', 'Aisha', 'Diallo', 'STI3');

INSERT INTO Enrollment (StudentID, ProgramID)
VALUES
(1, 1),
(2, 2),
(3, 2);

INSERT INTO StudentAttendance (Attendance, EnrollmentID)
VALUES
('Present', 1),
('Present', 1),
('Absent', 1),
('Absent', 1),
('Absent', 1),
('Present', 2),
('Present', 2),
('Absent', 2);

INSERT INTO AssessmentType (TypeName)
VALUES ('Exam'),
('Assignment'),
('Open book Exam'),
('Quiz');

INSERT INTO Assessment (AssessmentName, AssessmentDueDate, MaxGrade, PassGrade,
TypeID, ProgramID)
VALUES
('Examen de biologie', '2025-04-08', 20, 14, 1, 1),
('Examen de francais', '2025-04-13', 20, 12, 1, 1),
('Quiz de mathématiques', '2025-05-19', 10, 6, 4, 2),
('Quiz de geographie', '2025-06-18', 10, 7, 4, 2),
('Examen danglais', '2026-04-08', 20, 14, 1, 1),
('Examen de francais', '2026-04-13', 20, 12, 1, 1),
('Quiz de mathématiques', '2026-05-19', 10, 6, 4, 2),
('Quiz de geographie', '2026-06-18', 10, 7, 4, 2);

INSERT INTO Grade (Grade, Pass, AssessmentID, EnrollmentID)
VALUES
(18, 'Pass', 1, 1),
(11, 'Fail', 2, 1),
(5, 'Fail', 3, 2),
(6, 'Fail', 4, 2);

DELIMITER //

CREATE PROCEDURE TeacherDashboard (InStaffID INTEGER)

BEGIN

-- Risk summaries
SELECT 
    p.ProgramID,
    p.ProgramName,
    pc.CategoryName,

    COUNT(DISTINCT CASE 
        WHEN srl.RiskLevel = 'High Risk' THEN srl.StudentID 
    END) AS HighRisk,

    COUNT(DISTINCT CASE 
        WHEN srl.RiskLevel = 'Moderate Risk' THEN srl.StudentID 
    END) AS ModerateRisk,

    ROUND((
        COUNT(DISTINCT CASE 
            WHEN srl.RiskLevel IN ('High Risk', 'Moderate Risk') 
            THEN srl.StudentID 
        END)
        / NULLIF(COUNT(DISTINCT s.StudentID), 0)
    ) * 100.0, 0) AS PercentageAtRisk

FROM Assignment a
JOIN Program p ON a.ProgramID = p.ProgramID
JOIN ProgramCategory pc ON p.CategoryID = pc.CategoryID

-- Total students
LEFT JOIN Enrollment e ON e.ProgramID = p.ProgramID
LEFT JOIN Student s ON s.StudentID = e.StudentID

-- Combined risk view
LEFT JOIN StudentsRiskLevel srl 
    ON srl.ProgramID = p.ProgramID 
    AND srl.StudentID = s.StudentID

WHERE a.StaffID = 1

GROUP BY 
    p.ProgramID,
    p.ProgramName,
    pc.CategoryName;

-- Upcoming assessments
SELECT Assessment.AssessmentName, Assessment.AssessmentDueDate, Program.ProgramName,
ProgramCategory.CategoryName, DATEDIFF(Assessment.AssessmentDueDate, CURRENT_DATE) AS DaysRemaining FROM Staff
INNER JOIN Assignment ON Staff.StaffID = Assignment.StaffID
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID  
INNER JOIN Assessment ON Assessment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Staff.StaffID = InStaffID AND Assessment.AssessmentDueDate >= CURRENT_DATE
ORDER BY Assessment.AssessmentDueDate;

-- Students at risk
SELECT 
    s.StudentFirstName, 
    s.StudentLastName,
    s.StudentID,
    p.ProgramName,
    pc.CategoryName,

    ROUND(AVG(sa.Attendance - 1) * 100, 0) AS AverageAttendance,
    ROUND(AVG(g.Pass - 1) * 100, 0) AS PassRate,

    srl.RiskLevel

FROM Staff st
INNER JOIN Assignment a ON st.StaffID = a.StaffID
INNER JOIN Program p ON a.ProgramID = p.ProgramID
INNER JOIN Enrollment e ON p.ProgramID = e.ProgramID
INNER JOIN ProgramCategory pc ON p.CategoryID = pc.CategoryID
INNER JOIN Student s ON e.StudentID = s.StudentID

LEFT JOIN StudentAttendance sa ON e.EnrollmentID = sa.EnrollmentID
LEFT JOIN Grade g ON e.EnrollmentID = g.EnrollmentID 

INNER JOIN StudentsRiskLevel srl 
    ON srl.StudentID = s.StudentID
    AND srl.ProgramID = p.ProgramID

WHERE st.StaffID = InStaffID

GROUP BY 
    e.EnrollmentID,
    s.StudentFirstName, 
    s.StudentLastName,
    s.StudentID,
    p.ProgramName,
    pc.CategoryName,
    srl.RiskLevel

HAVING srl.RiskLevel IN ('High Risk', 'Moderate Risk')

ORDER BY 
    CASE 
        WHEN srl.RiskLevel = 'Moderate Risk' THEN 2
        WHEN srl.RiskLevel = 'High Risk' THEN 1
    END,
    PassRate DESC, AverageAttendance DESC

LIMIT 5;


-- Average attendance for the month

-- SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
-- Program.ProgramName, ProgramCategory.CategoryName,
-- (ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) - 
-- (SELECT NULLIF(ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0), 0)
-- FROM StudentAttendance
-- INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
-- INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
-- INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
-- INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
-- WHERE Assignment.StaffID = InStaffID AND MONTH(StudentAttendance.AttendanceDate) = (MONTH(CURRENT_DATE)-1))) AS Change
-- FROM StudentAttendance
-- INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
-- INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
-- INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
-- INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
-- WHERE Assignment.StaffID = InStaffID AND MONTH(StudentAttendance.AttendanceDate) = MONTH(CURRENT_DATE)
-- GROUP BY Program.ProgramID;

-- Average attendance for the month
SELECT 
    p.ProgramName,
    pc.CategoryName,

    ROUND(
        AVG(CASE 
            WHEN MONTH(sa.AttendanceDate) = MONTH(CURRENT_DATE) 
            THEN sa.Attendance - 1 
        END) * 100, 
    0) AS AverageAttendance,

    ROUND(
        (
            AVG(CASE 
                WHEN MONTH(sa.AttendanceDate) = MONTH(CURRENT_DATE) 
                THEN sa.Attendance - 1 
            END)
            -
            AVG(CASE 
                WHEN MONTH(sa.AttendanceDate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                THEN sa.Attendance - 1 
            END)
        ) * 100,
    0) AS Difference

FROM StudentAttendance sa
JOIN Enrollment e ON sa.EnrollmentID = e.EnrollmentID
JOIN Program p ON e.ProgramID = p.ProgramID
JOIN Assignment a ON p.ProgramID = a.ProgramID
JOIN ProgramCategory pc ON p.CategoryID = pc.CategoryID

WHERE a.StaffID = 1

GROUP BY 
    p.ProgramID,
    p.ProgramName,
    pc.CategoryName;

END
//

DELIMITER ;

DELIMITER //
CREATE PROCEDURE YourAttendance(IN InStaffID INTEGER)
BEGIN

SELECT Program.ProgramName, ProgramCategory.CategoryName,
ROUND(AVG(StaffAttendance.Attendance-1) * 100, 0) AS AverageAttendance
FROM Staff 
INNER JOIN Assignment ON Staff.StaffID = Assignment.StaffID
INNER JOIN StaffAttendance ON Assignment.AssignmentID = StaffAttendance.AssignmentID
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID 
WHERE Staff.StaffID = 1 AND Year(StaffAttendance.AttendanceDate) = Year(CURRENT_DATE)
GROUP BY Program.ProgramID;

END
//

DELIMITER ;

DELIMITER //
CREATE PROCEDURE StudentInfo(IN InStudentID INTEGER)
BEGIN

SELECT *, TIMESTAMPDIFF(YEAR, Student.StudentBirthDate, CURDATE()) AS Age
FROM Student
WHERE Student.StudentID = InStudentID;

SELECT Enrollment.EnrollmentDate, Program.ProgramName, 
ProgramCategory.CategoryName
FROM Enrollment 
INNER JOIN Program
ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory
ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Enrollment.StudentID = InStudentID;

SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
Program.ProgramName, ProgramCategory.CategoryName, Program.ProgramID
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Enrollment.StudentID = InStudentID 
AND YEAR(StudentAttendance.AttendanceDate) = YEAR(CURRENT_DATE)
GROUP BY Program.ProgramID;

SELECT 
Program.ProgramID,
Program.ProgramName,
ProgramCategory.CategoryName,
ROUND(AVG(Grade.Pass-1)*100, 0) AS PassRate
FROM Enrollment
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID 
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
INNER JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID
INNER JOIN Assessment ON Grade.AssessmentID = Assessment.AssessmentID
WHERE Enrollment.StudentID = InStudentID
AND YEAR(Grade.GradeDate) = YEAR(CURRENT_DATE)
GROUP BY Program.ProgramID;

END
//
DELIMITER ;

CREATE VIEW StudentsRiskLevel AS
SELECT
Student.StudentID,
Student.StudentFirstName,
Student.StudentLastName,
Enrollment.ProgramID,
CASE
    WHEN 
        (AVG(StudentAttendance.Attendance - 1) <= 0.65 AND AVG(Grade.Pass - 1) < 0.9)
        OR
        ((AVG(StudentAttendance.Attendance - 1) > 0.65 AND AVG(StudentAttendance.Attendance - 1) <= 0.85)
            AND AVG(Grade.Pass - 1) < 0.9)
        OR
        ((AVG(StudentAttendance.Attendance - 1) <= 0.65)
            AND (AVG(Grade.Pass - 1) >= 0.9 AND AVG(Grade.Pass - 1) < 1))
    THEN 'High Risk'

    WHEN 
        (AVG(StudentAttendance.Attendance - 1) > 0.65 
        AND AVG(StudentAttendance.Attendance - 1) <= 0.85
        AND AVG(Grade.Pass - 1) >= 0.9 
        AND AVG(Grade.Pass - 1) < 1)
        OR 
        (AVG(StudentAttendance.Attendance - 1) <= 0.65 AND AVG(Grade.Pass - 1) = 1)
        OR
        (AVG(StudentAttendance.Attendance - 1) > 0.85 AND AVG(Grade.Pass - 1) < 0.9)
    THEN 'Moderate Risk'

    ELSE 'Low/No Risk'
END AS RiskLevel

FROM Enrollment
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
INNER JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
INNER JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID

GROUP BY 
Enrollment.EnrollmentID,
Student.StudentID,
Student.StudentFirstName,
Student.StudentLastName,
Enrollment.ProgramID;
