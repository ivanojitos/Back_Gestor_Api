<?php

namespace App\Library;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use PDO;
use Carbon\Carbon;

class conexionDB
{


    public static function connect()
    {
        $server = "SRVCORPODESA1, 1433";
        $user = "sa";
        $pass = 'Alvarez10.';
        $db = "Gestor_Fut";
        $conn = new PDO("sqlsrv:Server={$server};Database={$db};", $user, $pass);
        return $conn;
    }


    public static function execute($sql, $conn)
    {
        $query = $conn->prepare($sql);
        $query->execute();
        return $query;
    }
}
