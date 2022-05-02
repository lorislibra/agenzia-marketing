<?php

require_once("src/repositories/manager.php");
require_once("src/entities/user.php");

$connection = DbManager::build_connection_from_env();
echo("Connected!\n");

$regioni = array(
    'Abruzzo',
    'Basilicata',
    'Calabria' ,
    'Campania',
    'Emilia Romagna',
    'Friuli Venezia Giulia',
    'Lazio',
    'Liguria',
    'Lombardia',
    'Marche',
    'Molise',
    'Piemonte',
    'Puglia',
    'Sardegna',
    'Sicilia',
    'Toscana',
    'Trentino Alto Adige',
    'Umbria',
    'Valle d\'Aosta',
    'Veneto'
);

$nord = array(
    'Valle d\'Aosta',
    'Veneto',
    'Emilia Romagna',
    'Trentino Alto Adige',
    'Friuli Venezia Giulia',
    'Liguria',
    'Lombardia',
    'Piemonte',
);
$centro = array(
    'Lazio',
    'Toscana',
    'Marche',
    'Umbria',
);
$sud = array(
    'Abruzzo',
    'Basilicata',
    'Calabria' ,
    'Campania',
    'Molise',
    'Puglia',
    'Sardegna',
    'Sicilia',
);

function users(PDO $connection)
{
    global $regioni, $nord, $centro, $sud;

    $questions = array();
    $questions2 = array();
    $query = "\nINSERT IGNORE INTO user (id, email, password, role_id) VALUES ";
    $query2 = "\nINSERT IGNORE INTO user_region (user_id, region_id) VALUES ";


    for ($i=0; $i < count($regioni)+1+3+3; $i++) { 
        $query .= "(?,?,?,?),";
    }
    for ($i=0; $i <count($regioni)*(3+1+1+1); $i++) { 
        $query2 .= "(?,?),";
    }
    $query[strlen($query)-1] = ";";
    $query2[strlen($query2)-1] = ";";

    for ($i=0; $i < count($regioni); $i++) {
        $name = str_replace("'", "", str_replace(" ", ".", strtolower($regioni[$i]))); 
        array_push($questions, $i+1, $name . "@barsanti.edu.it", "ciao1234", 4);
        array_push($questions2, $i+1, $i+1);
    }
    
    $i++;
    array_push($questions, $i++, "lollato" . "@barsanti.edu.it", "ciao1234", 1);
    array_push($questions, $i++, "libralato" . "@barsanti.edu.it", "ciao1234", 1);
    array_push($questions, $i++, "rostin" . "@barsanti.edu.it", "ciao1234", 1);
    array_push($questions, $i++, "warehouse" . "@barsanti.edu.it", "ciao1234", 2);

    for ($j=0; $j < 4; $j++) { 
        for ($k=0; $k < count($regioni); $k++) { 
            array_push($questions2, $i-4+$j, $k+1);    
        }
    }

    $regions_fn = function (int $index, array &$region_group, array &$questions2) {
        global $regioni;

        for ($j=0; $j < count($region_group); $j++) {
            for ($p=0; $p < count($regioni); $p++) { 
                if ($regioni[$p] == $region_group[$j]) {
                    array_push($questions2, $index, $p+1);
                    break;
                }
            }
        }
    };

    array_push($questions, $i++, "nord" . "@barsanti.edu.it", "ciao1234", 3);
    array_push($questions, $i++, "centro" . "@barsanti.edu.it", "ciao1234", 3);
    array_push($questions, $i++, "sud" . "@barsanti.edu.it", "ciao1234", 3);

    $regions_fn($i-3, $nord, $questions2);
    $regions_fn($i-2, $centro, $questions2);
    $regions_fn($i-1, $sud, $questions2);

    $stmt = $connection->prepare($query);
    $stmt->execute($questions);

    $stmt = $connection->prepare($query2);
    $stmt->execute($questions2);
}



function regions(PDO $connection)
{
    global $regioni;

    $questions = array();
    $query = "\nINSERT IGNORE INTO region (id, name) VALUES ";

    for ($i=0; $i < count($regioni); $i++) { 
        $query .= "(?,?),";
    }
    $query[strlen($query)-1] = ";";

    for ($i=0; $i < count($regioni); $i++) {
        array_push($questions, $i+1, $regioni[$i]);
    }

    $stmt = $connection->prepare($query);
    $stmt->execute($questions);
}

function sell_points(PDO $connection)
{
    global $regioni;

    $questions = array();
    $query = "\nINSERT IGNORE INTO sell_point (id, name, region_id, address) VALUES ";

    for ($i=0; $i < count($regioni); $i++) { 
        $query .= "(?,?,?,?),";
    }
    $query[strlen($query)-1] = ";";

    for ($i=0; $i < count($regioni); $i++) {
        array_push($questions, $i+1, "centro commerciale " .$regioni[$i], $i+1, "via roma 1");
    }

    $stmt = $connection->prepare($query);
    $stmt->execute($questions);
}

regions($connection);
users($connection);
sell_points($connection);

echo("Inserted!\n");
?>