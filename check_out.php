<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console
$id = $_GET["id"];
$book = $_GET["book"];
if(isset($id) && is_numeric($id) && isset($book)) {

    
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
    
    
    $selStmt = $db->prepare("SELECT id FROM Books WHERE book=?");
    $selStmt->bind_param("s",$book);
    $selStmt->execute();
    $selResult = $selStmt->get_result();
    if($selResult->num_rows > 0) {
        $id = $selResult->fetch_assoc()["id"];
        echo json_encode(array('error'=>'This book is already checked out to student id '.$id));
        $selStmt->close();
    }
    else {
        $selStmt->close();
        $stmt = $db->prepare("INSERT INTO Books (id, book) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $book);
        $stmt->execute();
    
        if ($stmt->affected_rows>=1) {
             echo json_encode(array('success'=>true));
        } else {
            echo json_encode(array('error'=>'Failed to add to database!'));
        }
        $stmt->close();
    }
    $db->close();
}
else {
    echo json_encode(array('error'=>'Invalid ID!'));
}
?>
