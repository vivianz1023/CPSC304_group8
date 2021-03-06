<html>
<head>
    <title>Extra Information Page</title>
    <style>
        body {
            background-image: url("mainBG.png");
            background-repeat: no-repeat, repeat;
            margin: 30px 30px;
        }
        p {font-family:Gill Sans; font-size:20px;}
    </style>
</head>

<body>
<b style="font-family:cursive; font-size:60px"> Extra Information</b>
<br /><br /><br />
<b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Please choose the operations as follows!</b>
<br /><br />

<h2> Find the country with the smallest average age of its Olympic team member.</h2>
<form method="GET" action="Extra.php"> <!--refresh page when submitted-->
    <input type="hidden" id="NestQueryRequest" name="NestQueryRequest">
    <input type="submit" name="NestSubmit"></p>
</form>

<p>Back to Home, please press:&nbsp;
    <a href="https://www.students.cs.ubc.ca/~lzy0606/mainPage.php">
        <button style="font-family:Gill Sans; font-size:18px;">Home</button>
    </a>
</p>

<?php
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}


//print
function printResult($result) { //prints results from a select statement
    echo "<table>";
    echo  "<p style='font-size: 20px; font-family:Gill Sans;background-color:lightskyblue;border: 2px solid #ddd; padding: 6px;line-height:40px; display:inline-block;'>the country with the smallest average age of its Olympic team member is:</p>";
    echo "<tr><th><p style='font-size: 18px; font-family:Gill Sans;'>Coutry</p></th>
              <th><p style='font-size: 18px; font-family:Gill Sans;'>Average Age</p></th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
       
        echo "<tr><td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[0] . "</p></td>
                  <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[1] . "</p></td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_lzy0606", "a71133417", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}


function handleNestRequest() {
    global $db_conn;
    global $finalResult;
    //fing the country whose  average age is minimum
    $result = executePlainSQL(" WITH TEMP AS
                                                  (SELECT os1.Country , AVG(os1.Age) avg
                                                   FROM OlympicTeamMember_Sponsor os1
                                                    GROUP BY os1.Country)


                                                 SELECT TEMP.Country , TEMP.avg
                                                 FROM  TEMP
                                                 WHERE TEMP.avg = (SELECT MIN(TEMP.avg)
                                                                        FROM TEMP)");


    printResult($result);
    OCICommit($db_conn);
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('NestSubmit', $_GET)) {
            handleNestRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_GET['NestQueryRequest'])) {
    handleGETRequest();
}
?>
</body>
</html>