DELIMITER //

CREATE PROCEDURE TeacherDashboard (InStaffID INTEGER)

BEGIN

-- Your programs
SELECT Program.ProgramName, ProgramCategory.CategoryName 
FROM assignment
INNER JOIN Program ON Assignment.ProgramID = Program.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE assignment.StaffID = InStaffID;

-- Upcoming assessments
SELECT Assessment.AssessmentName, Assessment.AssessmentDueDate, Program.ProgramName,
ProgramCategory.CategoryName FROM Staff
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
(COUNT(StudentAttendance.AttendanceID) > 0 AND AVG(StudentAttendance.Attendance-1) < 0.5)
OR (COUNT(Grade.GradeID) > 0 AND AVG(Grade.Pass-1) < 0.9);


-- Average attendance for the month
SELECT ROUND(AVG(StudentAttendance.Attendance-1) * 100, 0) AS AverageAttendance, 
Program.ProgramName, ProgramCategory.CategoryName
FROM StudentAttendance
INNER JOIN Enrollment ON StudentAttendance.EnrollmentID = Enrollment.EnrollmentID
INNER JOIN Program ON Enrollment.ProgramID = Program.ProgramID
INNER JOIN Assignment ON Program.ProgramID = Assignment.ProgramID
INNER JOIN ProgramCategory ON Program.CategoryID = ProgramCategory.CategoryID
WHERE Assignment.StaffID = InStaffID AND MONTH(StudentAttendance.AttendanceDate) = MONTH(CURRENT_DATE)
GROUP BY Program.ProgramID;

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

