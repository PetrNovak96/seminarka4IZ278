<?php


namespace app\model;


use PDO;

class EventsModel extends EntityModel {

    function __construct() {
        parent::__construct();
    }

    public function tableQuery($page = 0) {
        $offset = $page * 10;
        $sql = <<<SQL
SELECT
	V.ID,
	V.NAME,
	CONCAT(COUNT(distinct P.EMPLOYEE_ID), '/', V.CAPACITY) as NUMBER,
	(UNIX_TIMESTAMP(V.BEGINNING)) as TIME
FROM EVENTS V
LEFT JOIN PARTICIPATIONS P
ON
P.EVENT_ID = V.ID
LEFT JOIN EMPLOYEES E
ON 
E.ID = P.EMPLOYEE_ID
WHERE V.DELETED IS NULL
AND 
E.GONE IS NULL
GROUP BY V.ID
ORDER BY V.NAME ASC
LIMIT 10
OFFSET :offset;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    // event to string
    public function evTStr($timestamp) {

        $r = '';
        if ($timestamp < 0) {
            $timestamp = (-1) * $timestamp;
            $r .= 'Proběhne za ';
            $arr = $this->dateArray($timestamp);
            if ($arr['y']) {
                $r .= $this->timeToString($arr['y'], ['rok', 'roky', 'let']);
            } else if ($arr['d'])  {
                $r .= $this->timeToString($arr['d'], ['den', 'dny', 'dnů']);
            } else {
                if ($arr['h'])
                    $r .= $this->timeToString($arr['h'], ['hodinu', 'hodiny', 'hodin']);
                if ($arr['h'] && $arr['m'])
                    $r .= ' a ';
                if ($arr['m'])
                    $r .= $this->timeToString($arr['m'], ['minutu', 'minuty', 'minut']);
            }
        } else {
            $r .= 'Proběhla před ';
            $arr = $this->dateArray($timestamp);
            if ($arr['y']) {
                $r .= $this->timeToString($arr['y'], ['rokem', 'lety', 'lety']);
            } else if ($arr['d'])  {
                $r .= $this->timeToString($arr['d'], ['dnem', 'dny', 'dny']);
            } else {
                if ($arr['h'])
                    $r .= $this->timeToString($arr['h'], ['hodinou', 'hodinami', 'hodinami']);
                if ($arr['h'] && $arr['m'])
                    $r .= ' a ';
                if ($arr['m'])
                    $r .= $this->timeToString($arr['m'], ['minutou', 'minutami', 'minutami']);

            }
        }
        return $r;
    }

    private function timeToString($period, array $str) {
        $r = '';
        switch ($period) {
            case 1 : $r .= '1 '.$str[0]; break;
            case 2:case 3:case 4: $r .= $period.' '.$str[1]; break;
            default : $r .= $period.' '.$str[2]; break;
        }
        return $r;
    }

    private function dateArray($timestamp) {
        $r = array();
        $r['y'] = floor($timestamp / (60 * 60 * 24 * 365));
        $timestamp -= $r['y'] * (60 * 60 * 24 * 365);
        $r['d'] = floor($timestamp / (60 * 60 * 24));
        $timestamp -= $r['d'] * (60 * 60 * 24);
        $r['h'] = floor($timestamp / (60 * 60));
        $timestamp -= $r['h'] * (60 * 60);
        $r['m'] = floor($timestamp / 60);
        $timestamp -= $r['m'] * 60;
        return $r;
    }

    public function findEventsByEmployee($ID) {
        $sql =
            <<<SQL
SELECT
    V.ID, 
    V.NAME 
FROM EVENTS V
JOIN PARTICIPATIONS P
ON
P.EVENT_ID = V.ID
AND
P.EMPLOYEE_ID = :id
AND 
V.ENDING > CURRENT_TIMESTAMP;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAbsolvedEvents($ID) {
        $sql =
            <<<SQL
SELECT
    V.ID, 
    V.NAME,
    V.BEGINNING,
    V.ENDING,
    D.NAME ORGANIZES,
    P.POSTED
FROM EVENTS V
LEFT JOIN DEPARTMENTS D
ON 
D.ID = V.ORGANIZATOR_ID
JOIN PARTICIPATIONS P
ON
P.EVENT_ID = V.ID
AND
P.REPORTED = 1
AND
P.EMPLOYEE_ID = :id ;
SQL;
        $query=self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findEvent($ID) {
        $sql = <<<SQL
SELECT
    V.NAME,
    V.ORGANIZATOR_ID,
    V.BEGINNING,
    V.ENDING,
    V.PLACE,
    V.CAPACITY,
    V.DESCRIPTION,
    COUNT(DISTINCT P.EMPLOYEE_ID) AS COUNT,
    D.NAME AS ORGANIZATOR
FROM EVENTS V
LEFT JOIN PARTICIPATIONS P 
ON 
V.ID = P.EVENT_ID
LEFT JOIN DEPARTMENTS D
ON 
V.ORGANIZATOR_ID = D.ID
WHERE V.ID = :id
GROUP BY V.ID ;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute([':id' => $ID]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function saveEvent(array $event) {
        //volitelne PLACE, BE_DATE, BE_TIME, EN_DATE, EN_TIME
        //povinne NAME, DESCRIPTION, CAPACITY, department
        $place = !empty($event['PLACE']);
        $be_date = !empty($event['BE_DATE']);
        $en_date = !empty($event['EN_DATE']);
        if ($be_date) {
            $be = $event['BE_DATE'].' '.$event['BE_TIME'];
        }
        if ($en_date) {
            $en = $event['EN_DATE'].' '.$event['EN_TIME'];
        }
        $sql = 'INSERT INTO EVENTS(NAME, DESCRIPTION, CAPACITY';
        $sql.= $place ? ', PLACE' : '';
        $sql.= $be_date ? ', BEGINNING' : '';
        $sql.= $en_date ? ', ENDING' : '';
        $sql.= ', ORGANIZATOR_ID) VALUES(';
        $sql.= ':name, ';
        $sql.= ':description, ';
        $sql.= ':capacity';
        $sql.= $place ? ', '.':place' : '';
        $sql.= $be_date ? ', '.':beginning' : '';
        $sql.= $en_date ? ', '.':ending' : '';
        $sql.= ', :department);';
        $data = [
            ':name' => $event['NAME'],
            ':description' => $event['DESCRIPTION'],
            ':capacity' => $event['CAPACITY'],
            ':department' => $event['department'],
        ];
        if ($place) $data[':place'] = $event['PLACE'];
        if ($be_date) $data[':beginning'] = $be;
        if ($en_date) $data[':ending'] = $en;
        $query = self::$pdo->prepare($sql);
        $query->execute($data);
    }

    public function updateEvent($ID, array $event) {
        $place = !empty($event['PLACE']);
        $be_date = !empty($event['BE_DATE']);
        $en_date = !empty($event['EN_DATE']);
        if ($be_date) {
            $be = $event['BE_DATE'].' '.$event['BE_TIME'];
        }
        if ($en_date) {
            $en = $event['EN_DATE'].' '.$event['EN_TIME'];
        }
        $sql = 'UPDATE EVENTS SET ';
        $sql.= 'NAME = :name, ';
        $sql.= 'DESCRIPTION = :description, ';
        $sql.= 'CAPACITY = :capacity, ';
        $sql.= 'PLACE = ';
        $sql.= ($place)? ':place' : 'NULL';
        $sql.= ', ';
        $sql.= 'BEGINNING = ';
        $sql.= ($be_date)? ':beginning' : 'NULL';
        $sql.= ', ';
        $sql.= 'ENDING = ';
        $sql.= ($en_date)? ':ending' : 'NULL';
        $sql.= ', ';
        $sql.= 'ORGANIZATOR_ID = :department ';
        $sql.= 'WHERE ID = :id ;';
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':name', $event['NAME'], PDO::PARAM_STR);
        $query->bindValue(':description', $event['DESCRIPTION'], PDO::PARAM_STR);
        $query->bindValue(':capacity', $event['CAPACITY'], PDO::PARAM_INT);
        $query->bindValue(':department', $event['department'], PDO::PARAM_INT);
        $query->bindValue(':id', $ID, PDO::PARAM_INT);
        if ($place)
        $query->bindValue(':place', $event['PLACE'], PDO::PARAM_STR);
        if ($be_date)
            $query->bindValue(':beginning', $be, PDO::PARAM_STR);
        if ($en_date)
            $query->bindValue(':ending', $en, PDO::PARAM_STR);
        $query->execute();
    }

    public function delete($id) {
        if (!$this->exists($id)) {
            return 'Akce s ID '.$id.' neexistuje.';
        } else {
            $this->setDeleted($id, date('Y-m-d'));
        }
    }

    private function setDeleted($id, string $date) {
        $sql = <<<SQL
UPDATE EVENTS SET DELETED = :deleted 
WHERE ID = :event_id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':event_id', $id, PDO::PARAM_INT);
        $query->bindValue(':deleted', $date, PDO::PARAM_STR);
        $query->execute();
    }

    public function exists($id): bool {
        $sql = <<<SQL
SELECT 
    ID
FROM EVENTS
WHERE ID = :id
AND DELETED IS NULL;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function getEvents() {
        $sql = <<<SQL
SELECT 
    ID,
    NAME
FROM EVENTS
WHERE DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateParticipations($formData) {
        /*'EVENT_ID','participants'*/
        $eventId = $formData['EVENT_ID'];
        $participants = $formData['participants'];
        $employeesModel = new EmployeesModel();
        $currentParticipants = $employeesModel->findParticipants($eventId);
        $temp = [];
        foreach ($currentParticipants as $currentParticipant) {
            $temp[] = $currentParticipant['ID'];
        }
        $currentParticipants = $temp;

        foreach ($participants as $participant) {
            if (!in_array($participant, $currentParticipants)) {
                $this->saveParticipation($participant, $eventId);
            }
        }

        foreach ($currentParticipants as $currentParticipant) {
            if (!in_array($currentParticipant, $participants)) {
                $this->deleteParticipation($currentParticipant, $eventId);
            }
        }

    }

    private function saveParticipation($participant, $eventId) {
        if (!$this->existsParticipation($participant, $eventId)) {
            $sql = <<<SQL
INSERT INTO PARTICIPATIONS(
EMPLOYEE_ID, EVENT_ID
) VALUES(
:employee_id, :event_id
);   
SQL;
            $query = self::$pdo->prepare($sql);
            $query->bindValue(':employee_id', $participant, PDO::PARAM_INT);
            $query->bindValue(':event_id', $eventId, PDO::PARAM_INT);
            $query->execute();
        }
    }

    private function deleteParticipation($participant, $eventId) {
        $sql = <<<SQL
DELETE FROM PARTICIPATIONS
WHERE EMPLOYEE_ID = :employee_id
AND 
EVENT_ID = :event_id;   
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':employee_id', $participant, PDO::PARAM_INT);
        $query->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $query->execute();
    }

    private function existsParticipation($participant, $eventId) {
        $sql = <<<SQL
SELECT 
    EMPLOYEE_ID
FROM PARTICIPATIONS
WHERE EMPLOYEE_ID = :employee_id
AND EVENT_ID = :event_id;    
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':employee_id', $participant, PDO::PARAM_INT);
        $query->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $query->execute();
        return !empty($query->fetch(PDO::FETCH_ASSOC));
    }

    public function report($employeeId, $eventId) {
        $sql = <<<SQL
UPDATE PARTICIPATIONS
SET REPORTED = 1
WHERE EMPLOYEE_ID = :employee_id
AND 
EVENT_ID = :event_id;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->bindValue(':employee_id', $employeeId, PDO::PARAM_INT);
        $query->bindValue(':event_id', $eventId, PDO::PARAM_INT);
        $query->execute();
    }

    public function endedEvent($id) {
        $event = $this->findEvent($id);
        if (!empty($event)) {
            $ending = $event['ENDING'];
            if ($ending < date('Y-m-d H:i')) {
                return true;
            }
        }
        return false;
    }

    public function getParticipationsStatistic() {
        $sql = <<<SQL
SELECT 
    COUNT(P1.EMPLOYEE_ID) AS REPORTED,
    COUNT(P2.EMPLOYEE_ID) AS UNREPORTED
FROM EVENTS E 
LEFT JOIN PARTICIPATIONS P1
ON E.ID = P1.EVENT_ID
AND P1.REPORTED = 1
LEFT JOIN PARTICIPATIONS P2
ON E.ID = P2.EVENT_ID
AND P2.REPORTED IS NULL
WHERE E.ENDING < CURRENT_TIMESTAMP;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function lastEvents() {
        $sql = <<<SQL
SELECT 
    ID,
    NAME,
    ENDING
FROM EVENTS
WHERE DELETED IS NULL
AND ENDING < CURRENT_TIMESTAMP
ORDER BY ENDING DESC LIMIT 10;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nextEvents() {
        $sql = <<<SQL
SELECT 
    ID,
    NAME,
    BEGINNING
FROM EVENTS
WHERE DELETED IS NULL
AND BEGINNING > CURRENT_TIMESTAMP
ORDER BY BEGINNING ASC LIMIT 10;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemsCount() {
        $sql = <<<SQL
SELECT 
   COUNT(ID) AS COUNT
FROM EVENTS
WHERE DELETED IS NULL;
SQL;
        $query = self::$pdo->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}