<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console
$id = $_GET["id"];
if(isset($id) && is_numeric($id)) {

    
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
    $stmt = $db->prepare("SELECT book FROM Books WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            array_push($books,$row["book"]);
        }
    } else {
        echo json_encode(array('error'=>'No results'));
        exit();
    }
    $stmt->close();
    $db->close();
    
    echo json_encode(array('books'=>$books));
}
else {
    echo json_encode(array('error'=>'Invalid ID!'));
}
?>
