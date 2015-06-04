<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

    
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "c9";
    $dbport = 3306;

    // Create connection
    $db = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($db->connect_error) {
        echo json_encode(array('error'=>'Failed to connect to database!'));
        exit();
    }
    $sql = "SELECT COUNT(*) FROM Books;";
    $result = $db->query($sql);
    
    
    if ($result->num_rows > 0) {
        // output data of each row
        $numBooks = $result->fetch_assoc()["COUNT(*)"];
        echo json_encode(array('numBooks'=>$numBooks));
    } else {
        echo json_encode(array('error'=>'Failed to query database!'));
        exit();
    }
    $db->close();
?>
