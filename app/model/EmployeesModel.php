<?php


namespace app\model;
use \PDO;

class EmployeesModel extends EntityModel
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
    PASSWORD IS NULL AND GONE IS NULL
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
    WHERE E.GONE IS NULL
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
    E.BIRTH,
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
E.GONE IS NULL
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
        $id = self::$pdo->lastInsertId();
        foreach ($employee['departments'] as $department_id) {
            $this->saveAssignment(
                $id,
                $department_id,
                date('Y-m-d'));
        }
        return $id;
    }

    public function updateEmloyee($ID, array $employee) {
        $entered = !empty($employee['ENTERED']);
        $sql = 'UPDATE EMPLOYEES SET ';
        $sql.= 'NAME = :name, ';
        $sql.= 'SURNAME = :surname, ';
        $sql.= 'EMAIL = :email, ';
        $sql.= 'ENTERED = ';
        $sql.= ($entered)? ':entered' : 'NULL';
        $sql.= ', ';
        $sql.= 'BIRTH = :birth ';
        $sql.= 'WHERE ID = :id ;';
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':name', $employee['NAME'], PDO::PARAM_STR);
        $query->bindValue(':surname', $employee['SURNAME'], PDO::PARAM_STR);
        $query->bindValue(':email', $employee['EMAIL'], PDO::PARAM_STR);
        $query->bindValue(':birth', $employee['BIRTH'], PDO::PARAM_STR);
        $query->bindValue(':id', $ID, PDO::PARAM_INT);
        if ($entered)
            $query->bindValue(':entered', $employee['ENTERED'], PDO::PARAM_STR);
        $query->execute();

        $departmentsModel = new DepartmentsModel();
        $currentDepartments = $departmentsModel->findDepartmentsByEmployee($ID);
        $temp = [];
        foreach ($currentDepartments as $currentDepartment) {
            $temp[] = $currentDepartment['ID'];
        }
        $currentDepartments = $temp;

        foreach ($employee['departments'] as $department) {
            if (!in_array($department, $currentDepartments)) {
                $this->saveAssignment($ID, $department, date('Y-m-d'));
            }
        }
        foreach ($currentDepartments as $currentDepartment) {
            if (!in_array($currentDepartment, $employee['departments'])) {
                $this->deleteAssignment($ID, $currentDepartment, date('Y-m-d'));
            }
        }
    }

    public function exists($ID): bool {
        $sql = <<<SQL
SELECT 
    ID
FROM EMPLOYEES
WHERE ID = :id
AND GONE IS NULL;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':id', $ID, PDO::PARAM_INT);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function saveAssignment(
        $employee_id,
        $department_id,
        $date) {
        if ($this->existsAssignment($employee_id, $department_id)) {
            $sql = <<<SQL
UPDATE ASSIGNMENTS
SET ENDING = NULL, BEGINNING = :beginning 
WHERE EMPLOYEE_ID = :employee_id
AND 
DEPARTMENT_ID = :department_id ;
SQL;
            $query = self::$pdo->prepare($sql);
            $query->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
            $query->bindValue(':department_id', $department_id, PDO::PARAM_INT);
            $query->bindValue(':beginning', $date, PDO::PARAM_STR);
            $query->execute();
        } else {
            $sql = <<<SQL
INSERT INTO ASSIGNMENTS(
EMPLOYEE_ID, DEPARTMENT_ID, BEGINNING
) VALUES(
:employee_id, :department_id, :beginning
);   
SQL;
            $query = self::$pdo->prepare($sql);
            $query->bindValue(':employee_id', $employee_id, PDO::PARAM_INT);
            $query->bindValue(':department_id', $department_id, PDO::PARAM_INT);
            $query->bindValue(':beginning', $date, PDO::PARAM_STR);
            $query->execute();
        }
    }

    private function deleteAssignment($ID, $currentDepartment, string $date) {
        $sql = <<<SQL
UPDATE ASSIGNMENTS SET ENDING = :ending 
WHERE EMPLOYEE_ID = :employee_id AND DEPARTMENT_ID = :department_id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':employee_id', $ID, PDO::PARAM_INT);
        $query->bindValue(':department_id', $currentDepartment, PDO::PARAM_INT);
        $query->bindValue(':ending', $date, PDO::PARAM_STR);
        $query->execute();
    }

    private function existsAssignment($ID, $department_id) {
        $sql = <<<SQL
SELECT 
    EMPLOYEE_ID
FROM ASSIGNMENTS
WHERE EMPLOYEE_ID = :id
AND DEPARTMENT_ID = :department_id;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID, ':department_id' => $department_id]);
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function delete($id) {
        if (!$this->exists($id)) {
            return 'Pracovník s ID '.$id.' neexistuje.';
        } elseif ($this->isHead($id)) {
            return 'Pracovník je vedoucím alespoň jednoho oddělení.';
        } else {
            $employee = $this->findEmployee($id);
            if ($employee['ENTERED'] > date('Y-m-d')) {
                return 'Pracovník jestě nenastoupil.';
            }
            $this->setGone($id, date('Y-m-d'));
        }
    }

    public function setGone($emloyee_id, $date) {
        $sql = <<<SQL
UPDATE EMPLOYEES SET GONE = :gone 
WHERE ID = :employee_id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':employee_id', $emloyee_id, PDO::PARAM_INT);
        $query->bindValue(':gone', $date, PDO::PARAM_STR);
        $query->execute();
    }

    private function isHead($id) {
        $sql = <<<SQL
SELECT 
    ID
FROM DEPARTMENTS
WHERE HEAD_ID = :id
AND DELETED IS NULL;   
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $id]);
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }
}
