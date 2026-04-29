CREATE VIEW StudentsAtRisk AS
SELECT
Student.StudentID,
Student.StudentFirstName,
Student.StudentLastName,
Enrollment.ProgramID
FROM Enrollment
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
-- left join instead of inner join avoids dropping students who have 0 attendance
LEFT JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
-- left join instead of inner join avoids dropping students who have no grades
LEFT JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID 
GROUP BY Enrollment.EnrollmentID
-- Use having instead of where when filtering with aggregate functions
HAVING
-- Students are considered at risk by attendance or grades to account for students that have
-- no recorded attendance or students who have no recorded grades 
(COUNT(StudentAttendance.AttendanceID) > 0 AND AVG(StudentAttendance.Attendance-1) <= 0.65)
AND ((COUNT(Grade.GradeID) > 0) AND AVG(Grade.Pass-1) < 0.9);

CREATE VIEW StudentsAtModerateRisk AS
SELECT
Student.StudentID,
Student.StudentFirstName,
Student.StudentLastName,
Enrollment.ProgramID
FROM Enrollment
INNER JOIN Student ON Enrollment.StudentID = Student.StudentID
-- left join instead of inner join avoids dropping students who have 0 attendance
LEFT JOIN StudentAttendance ON Enrollment.EnrollmentID = StudentAttendance.EnrollmentID
-- left join instead of inner join avoids dropping students who have no grades
LEFT JOIN Grade ON Enrollment.EnrollmentID = Grade.EnrollmentID 
GROUP BY Enrollment.EnrollmentID
-- Use having instead of where when filtering with aggregate functions  
HAVING
-- Students are considered at risk by attendance or grades to account for students that have
-- no recorded attendance or students who have no recorded grades 
(COUNT(StudentAttendance.AttendanceID) > 0 AND AVG(StudentAttendance.Attendance-1) < 0.85
AND AVG(StudentAttendance.Attendance-1) > 0.65)
OR ((COUNT(Grade.GradeID) > 0) AND AVG(Grade.Pass-1) < 1 AND AVG(Grade.Pass-1) >= 0.9);

