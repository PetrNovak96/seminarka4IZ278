<?php


namespace app\model;


use PDO;

class DepartmentsModel extends EntityModel {

    function __construct() {
        parent::__construct();
    }

    public function tableQuery() {
        $sql = <<<SQL
SELECT
	D.ID,
	D.NAME, 
    COUNT(distinct A1.EMPLOYEE_ID) as DIRECT_NUMBER,
    COUNT(distinct A2.EMPLOYEE_ID) as ALL_NUMBER
FROM DEPARTMENTS D
	LEFT JOIN ASSIGNMENTS A1 
    ON 
    A1.DEPARTMENT_ID = D.ID
    LEFT JOIN ASSIGNMENTS A2 
    ON 
    A2.DEPARTMENT_ID IN (
    SELECT COUNT(ID) FROM (
    SELECT * FROM DEPARTMENTS ORDER BY ID
    ) DEPARTMENTS_SORTED,
    (SELECT @pv := ID FROM DEPARTMENTS) INIT
    WHERE find_in_set(DEPARTMENT_ID, @pv)
    AND length(@pv := concat(@pv, ',', ID))
    )
    OR A2.DEPARTMENT_ID = D.ID
    WHERE D.DELETED IS NULL
    GROUP BY D.ID;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDepartmentsByEmployee($ID) {
        $sql =
            <<<SQL
SELECT
    D.ID, 
    D.NAME 
FROM DEPARTMENTS D
JOIN ASSIGNMENTS A
ON
A.DEPARTMENT_ID = D.ID
AND
A.EMPLOYEE_ID = :id
AND 
A.ENDING IS NULL
WHERE D.DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDepartment($ID) {
        $sql = <<<SQL
SELECT
    D.NAME,
    D.HEAD_ID,
    D.DEPARTMENT_ID,
    E.NAME as HEAD_NAME,
    E.SURNAME,
    D.ROOM,
    D.BUILDING,
    COUNT(DISTINCT A1.EMPLOYEE_ID) as COUNT
FROM DEPARTMENTS D
JOIN EMPLOYEES E
ON
D.HEAD_ID = E.ID
AND 
E.GONE IS NULL
LEFT JOIN ASSIGNMENTS A1 
ON 
A1.DEPARTMENT_ID = D.ID
AND
E.ID = A1.EMPLOYEE_ID
WHERE D.ID = :id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function countSubdepartments($ID) {
        $sql = <<<SQL
SELECT COUNT(DISTINCT ID) AS COUNT
from    (select * from DEPARTMENTS
         order by DEPARTMENT_ID, ID) DEP_SORTED,
        (select @pv := :id) initialisation
where   find_in_set(DEPARTMENT_ID, @pv)
and     length(@pv := concat(@pv, ',', id));
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetch(PDO::FETCH_ASSOC)['COUNT'];
    }

    public function getSubdepartments($ID) {
        $sql = <<<SQL
SELECT 
    DEP_SORTED.ID as ID,
    DEP_SORTED.NAME as NAME,
    DEP_SORTED.HEAD_ID as HEAD_ID,
    E.NAME as HEAD_NAME,
    E.SURNAME as HEAD_SURNAME
from    (select * from DEPARTMENTS
         order by DEPARTMENT_ID, ID) DEP_SORTED,
        (select @pv := :id) initialisation,
        (select * from EMPLOYEES order by ID) E       
where   find_in_set(DEPARTMENT_ID, @pv)
and     length(@pv := concat(@pv, ',', DEP_SORTED.id))
and E.ID = DEP_SORTED.HEAD_ID
and DEP_SORTED.DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countSubMembers($ID) {
        $sql = <<<SQL
SELECT 
COUNT(DISTINCT E.ID) AS COUNT
FROM EMPLOYEES E
JOIN ASSIGNMENTS A
ON 
A.EMPLOYEE_ID = E.ID
AND
A.ENDING IS NULL
AND 
(A.DEPARTMENT_ID IN (
SELECT DISTINCT ID
FROM    (SELECT * FROM DEPARTMENTS
         ORDER BY DEPARTMENT_ID, ID) DEP_SORTED,
        (SELECT @pv := :id) initialisation
WHERE   find_in_set(DEPARTMENT_ID, @pv)
AND     LENGTH(@pv := concat(@pv, ',', id))
)
OR A.DEPARTMENT_ID = :id)
WHERE E.GONE IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetch(PDO::FETCH_ASSOC)['COUNT'];
    }

    public function getDepartments() {
        $sql = <<<SQL
SELECT 
    ID,
    NAME
FROM DEPARTMENTS
WHERE DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exists($ID) {
        $sql = <<<SQL
SELECT 
    ID
FROM DEPARTMENTS
WHERE ID = :id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function saveDepartment(array $department) {
        $department_id = !empty($department['department']) && $department['department'] != 0;
        $sql = 'INSERT INTO DEPARTMENTS(NAME, ROOM, BUILDING';
        $sql.= $department_id ? ', DEPARTMENT_ID' : '';
        $sql.= ', HEAD_ID) VALUES(';
        $sql.= ':name, ';
        $sql.= ':room, ';
        $sql.= ':building';
        $sql.= $department_id ? ', '.':department_id' : '';
        $sql.= ', :head_id);';
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':name', $department['NAME'], PDO::PARAM_STR);
        $query->bindValue(':room', $department['ROOM'], PDO::PARAM_STR);
        $query->bindValue(':building', $department['BUILDING'], PDO::PARAM_STR);
        $query->bindValue(':head_id', $department['HEAD_ID'], PDO::PARAM_INT);
        if ($department_id)
            $query->bindValue(':department_id', $department['department'], PDO::PARAM_INT);

        $query->execute();
    }

    public function updateDepartment($ID, array $department) {
        $department_id = !empty($department['department']) && $department['department'] != 0;

        $sql = 'UPDATE DEPARTMENTS SET ';
        $sql.= 'NAME = :name, ';
        $sql.= 'ROOM = :room, ';
        $sql.= 'BUILDING = :building, ';
        $sql.= 'DEPARTMENT_ID = ';
        $sql.= ($department_id)? ':department_id' : 'NULL';
        $sql.= ', ';
        $sql.= 'HEAD_ID = :head_id ';
        $sql.= 'WHERE ID = :id ;';
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':name', $department['NAME'], PDO::PARAM_STR);
        $query->bindValue(':room', $department['ROOM'], PDO::PARAM_STR);
        $query->bindValue(':building', $department['BUILDING'], PDO::PARAM_STR);
        $query->bindValue(':head_id', $department['HEAD_ID'], PDO::PARAM_INT);
        $query->bindValue(':id', $ID, PDO::PARAM_INT);
        if ($department_id)
            $query->bindValue(':department_id', $department['department'], PDO::PARAM_INT);
        $query->execute();
    }

    public function delete($id) {

        if (!$this->exists($id)) {
            return 'Pracovník s ID '.$id.' neexistuje.';
        } elseif($this->isHeadDepartment($id)) {
            return 'Toto oddělení má alespoň jedno pododdělení.';
        } elseif ($this->organizesEvent($id)) {
            return 'Toto oddělení organizuje alespoň jednu akci.';
        } else {
            $this->setDeleted($id, date('Y-m-d'));
        }
    }

    public function isHeadDepartment($id) {
        $sql = <<<SQL
SELECT 
    ID
FROM DEPARTMENTS
WHERE DEPARTMENT_ID = :id
AND DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $id]);
        return !empty($query->fetchAll(PDO::FETCH_ASSOC));
    }

    private function setDeleted($id, string $date) {
        $sql = <<<SQL
UPDATE DEPARTMENTS SET DELETED = :deleted 
WHERE ID = :id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':deleted', $date, PDO::PARAM_STR);
        $query->execute();
    }

    private function organizesEvent($id) {
        $sql = <<<SQL
SELECT 
    ID
FROM EVENTS
WHERE ORGANIZATOR_ID = :id
AND DELETED IS NULL;   
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $id]);
        return !empty($query->fetchAll(PDO::FETCH_ASSOC));
    }
}