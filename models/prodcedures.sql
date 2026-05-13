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

WHERE a.StaffID = InStaffID

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

WHERE a.StaffID = InStaffID

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

