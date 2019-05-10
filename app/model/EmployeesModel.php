<?php


namespace app\model;
use \PDO;

class EmployeesModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getEmployees($offset = 0)
    {
        $sql =
            <<<SQL
SELECT 
    ID, 
    NAME,
    SURNAME
FROM EMPLOYEES 
WHERE 
    PASSWORD IS NULL 
LIMIT 10 OFFSET :offset ;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function tableQuery() {
        $sql =
            <<<SQL
SELECT
	E.ID,
	E.NAME, 
    E.SURNAME, 
    FLOOR(DATEDIFF(CURRENT_DATE, E.BIRTH)/365) as AGE,
    COUNT(V.ID) as NUMBER_OF_EVENTS
FROM EMPLOYEES E 
	LEFT JOIN PARTICIPATIONS P 
    ON 
    P.EMPLOYEE_ID = E.ID
    LEFT JOIN EVENTS V
    ON 
    V.ID = P.EVENT_ID AND
    V.BEGINNING > CURRENT_TIME
    GROUP BY E.ID;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findEmployee($ID) {
        $sql =
            <<<SQL
SELECT
    E.NAME,
    E.SURNAME,
    E.EMAIL,
    FLOOR(DATEDIFF(CURRENT_DATE, E.BIRTH)/365) as AGE,
    E.ENTERED,
    E.GONE,
    COUNT(DISTINCT V.ID) ABSOLVED
FROM EMPLOYEES E
LEFT JOIN PARTICIPATIONS P
ON 
P.EMPLOYEE_ID = E.ID
AND
P.REPORTED = 1
LEFT JOIN EVENTS V
ON
P.EVENT_ID = V.ID
AND
V.BEGINNING < CURRENT_TIME
WHERE E.ID = :id ;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetch(PDO::FETCH_ASSOC);

    }

    public function findParticipants($ID) {
        $sql = <<<SQL
SELECT
    E.ID,
    E.NAME,
    E.SURNAME,
    P.REPORTED
FROM EMPLOYEES E
JOIN PARTICIPATIONS P
ON 
P.EMPLOYEE_ID = E.ID
AND 
P.EVENT_ID = :id
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMembers($ID) {
        $sql = <<<SQL
SELECT 
 E.ID,
 E.NAME,
 E.SURNAME,
 A.BEGINNING
FROM EMPLOYEES E
JOIN ASSIGNMENTS A
ON 
A.EMPLOYEE_ID = E.ID
AND 
A.DEPARTMENT_ID = :id
AND
(A.ENDING IS NULL
OR
A.ENDING > CURRENT_DATE)
GROUP BY
E.ID;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dToString($date) {
        $date = date_create($date);
        return date_format($date, 'd.m.Y');
    }

    public function saveEmployee(array $employee) {
        $entered = !empty($employee['ENTERED']);
        $sql = 'INSERT INTO EMPLOYEES(NAME, SURNAME, EMAIL, BIRTH';
        $sql.= $entered ? ', ENTERED)' : ')';
        $sql.= ' VALUES(';
        $sql.= ':name, ';
        $sql.= ':surname, ';
        $sql.= ':email, ';
        $sql.= ':birth';
        $sql.= $entered ? ', '.':entered);' : ');';
        $data = [
            ':name' => $employee['NAME'],
            ':surname' => $employee['SURNAME'],
            ':email' => $employee['EMAIL'],
            ':birth' => $employee['BIRTH'],
        ];
        if ($entered) $data[':entered'] = $employee['ENTERED'];
        $query = self::$pdo->prepare($sql);
        $query->execute($data);
    }


}
