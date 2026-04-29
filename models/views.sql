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