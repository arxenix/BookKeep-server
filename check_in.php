<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console
$book = $_GET["book"];
if(isset($book)) {

    
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
    
    $stmt = $db->prepare("DELETE FROM Books WHERE book=?");
    $stmt->bind_param("s", $book);
    $stmt->execute();
    if ($stmt->affected_rows>=1) {
        echo json_encode(array('success'=>true));
    } else {
        echo json_encode(array('error'=>'Could not find book in database!'));
        exit();
    }

    $stmt->close();
    $db->close();
}
else {
    echo json_encode(array('error'=>'Invalid book!'));
}
?>
