<?php


namespace app\model;


use PDO;

class EventsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function tableQuery() {
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
    GROUP BY V.ID;
SQL;
        $query=self::$pdo->prepare($sql);
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
                $r .= $this->timeToString($arr['h'], ['hodinu', 'hodiny', 'hodin']);
                if ($arr['m']) {
                    $r .= ' a ';
                    $r .= $this->timeToString($arr['m'], ['minutu', 'minuty', 'minut']);
                }
            }
        } else {
            $r .= 'Proběhla před ';
            $arr = $this->dateArray($timestamp);
            if ($arr['y']) {
                $r .= $this->timeToString($arr['y'], ['rokem', 'lety', 'lety']);
            } else if ($arr['d'])  {
                $r .= $this->timeToString($arr['d'], ['dnem', 'dny', 'dny']);
            } else {
                $r .= $this->timeToString($arr['h'], ['hodinou', 'hodinami', 'hodinami']);
                if ($arr['m']) {
                    $r .= ' a ';
                    $r .= $this->timeToString($arr['m'], ['minutou', 'minutami', 'minutami']);
                }
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
P.EMPLOYEE_ID = :id ;
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

    public function dtToString($datetime) {
        $date = date_create($datetime);
        return date_format($date, 'd.m.Y \v\e H:i');
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
}