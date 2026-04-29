DELIMITER //

CREATE PROCEDURE TeacherDashboard (InStaffID INTEGER)

BEGIN

-- Risk summaries
SELECT 
    p.ProgramID,
    p.ProgramName,
    pc.CategoryName,

    COUNT(DISTINCT sar.StudentID) AS HighRisk,
    COUNT(DISTINCT smr.StudentID) AS ModerateRisk,

    ROUND ((
        (COUNT(DISTINCT sar.StudentID) + COUNT(DISTINCT smr.StudentID)) 
        / NULLIF(COUNT(DISTINCT s.StudentID), 0)
    ) * 100.0, 0) AS PercentageAtRisk

FROM Assignment a
JOIN Program p ON a.ProgramID = p.ProgramID
JOIN ProgramCategory pc ON p.CategoryID = pc.CategoryID

-- Total students
LEFT JOIN Enrollment e ON e.ProgramID = p.ProgramID
LEFT JOIN Student s ON s.StudentID = e.StudentID

-- Risk groups 
LEFT JOIN StudentsAtRisk sar ON sar.ProgramID = p.ProgramID
LEFT JOIN StudentsAtModerateRisk smr ON smr.ProgramID = p.ProgramID

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
Student.StudentFirstName, 
Student.StudentLastName,
Student.StudentID,
Program.ProgramName,
ProgramCategory.CategoryName,
ROUND(AVG(StudentAttendance.Attendance-1)*100, 0) AS AverageAttendance,
ROUND(AVG(Grade.Pass-1)*100, 0) AS PassRate
FROM Staff
INNER JOIN Assignment ON Staff.StaffID = Assignment.StaffID
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID
INNER JOIN Enrollment ON Program.ProgramID = Enrollment.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
-- left join instead of inner join avoids dropping students who have 0 attendance
LEFT JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
-- left join instead of inner join avoids dropping students who have no grades
LEFT JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID 
WHERE Staff.StaffID = InStaffID
GROUP BY Enrollment.EnrollmentID
-- Use having instead of where when filtering with aggregate functions
HAVING
-- Students are cosidered at risk by attendance or grades to account for students that have
-- no recorded attendance or students who have no recorded grades 
(COUNT(StudentAttendance.AttendanceID) > 0 AND AVG(StudentAttendance.Attendance-1) <= 0.65)
AND ((COUNT(Grade.GradeID) > 0) AND AVG(Grade.Pass-1) < 0.9)
OR
(COUNT(StudentAttendance.AttendanceID) > 0 AND AVG(StudentAttendance.Attendance-1) < 0.85
AND AVG(StudentAttendance.Attendance-1) > 0.65)
OR ((COUNT(Grade.GradeID) > 0) AND AVG(Grade.Pass-1) < 1 AND AVG(Grade.Pass-1) >= 0.9)
ORDER BY PassRate
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
WHERE Staff.StaffID = InStaffID AND Year(StaffAttendance.AttendanceDate) = Year(CURRENT_DATE);

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
ROUND(AVG(Grade.Grade / Assessment.MaxGrade)*100, 0) AS AverageGrade
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

