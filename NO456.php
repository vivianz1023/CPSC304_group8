<html>
<head>
    <title>CPSC 304 PHP/Oracle Demonstration</title>
</head>

<body>

<h2>select athletes from a country</h2>
<form method="POST" action="NO456.php">
    <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
    Country Name: <input type="text" name="countryName"> <br /><br />

    <input type="submit" value="Select" name="selectSubmit"></p>
</form>

<hr />

<h2>find competitions that each athlete participates</h2>


<form method="GET" action="NO456.php">
    <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
    <input type="submit" value="join" name="joinSubmit"></p>
</form>

<hr />

<h2>find the number of specific medals with athlete names in a selected country</h2>
<form method="POST" action="NO456.php">
    <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
    Country Name: <input type="text" name="countryName"> <br /><br />
    Medal Type:
    <select id="medal" name="medal">
        <option value=' '>  </option>
        <option value='Gold'>Gold Medal</option>
        <option value='Silver'>Silver Medal</option>
        <option value='Bronze'>Bronze Medal</option>
    </select>
    <input type="submit" value="projection" name="projectionSubmit"></p>
</form>


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
function printAthlete_Country($result) {
    echo "<br> Athlete Names <br>";
    echo "<table>";
    echo "<tr>
          <th>Name</th>
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td></tr>";
    }

    echo "</table>";
}

function printAthlete_Competition($result) {
    echo "<br> All Athletes and their Competitions: <br>";
    echo "<table>";
    echo "<tr>
          <th>Athlete</th>
          <th>Competition Event</th> 
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td>
                  <td>" . $row[1] . "</td></tr>";
    }

    echo "</table>";
}


function printAthlete_Medal($result) {
    echo "<br> Athletes and their Medal Number: <br>";
    echo "<table>";
    echo "<tr>
          <th>Athlete</th>
          <th>Medal Number</th> 
          <tr>";

    While ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td>
                  <td>" . $row[1] . "</td></tr>";
    }

    echo "</table>";
}


function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_yzhu33", "a38246906", "dbhost.students.cs.ubc.ca:1522/stu");

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

//SELECT
function handleSelectRequest() {
    global $db_conn;
    $country = $_POST['countryName'];
    $result = executePlainSQL("SELECT DISTINCT SN.Name FROM OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN, Athlete A WHERE OTMS.ID = A.ID and OTMS.SinNumOrEqual = SN.SinNumOrEqual and OTMS.Country='" . $country . "'");
    printAthlete_Country($result);
    OCICommit($db_conn);
}

//JOIN
function handleJoinQueryRequest() {
    global $db_conn;
    $result = executePlainSQL(
        "SELECT SN.Name, CE.CompetitionEvent_Name
                    FROM Participate p
                    JOIN OlympicTeamMember_Sponsor OTMS
                    ON p.Athlete_ID = OTMS.ID
                    JOIN SinNumOrEqual_Name SN
                    ON SN.SinNumOrEqual = OTMS.SinNumOrEqual
                    JOIN CompetitionEvent CE
                    ON CE.ID = p.CompetitionEvent_ID");
    printAthlete_Competition($result);
    OCICommit($db_conn);
}

//PROJECTION
function handleProjectionQueryRequest() {
    global $db_conn;
    $country = $_POST['countryName'];
    $medal = $_POST['medal'];
    if ($medal == "Gold") {
        $result = executePlainSQL("SELECT SN.Name, A.GoldNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
    } elseif ($medal == "Silver") {
        $result = executePlainSQL("SELECT SN.Name, A.SilverNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
    } else{
        $result = executePlainSQL("SELECT SN.Name, A.BronzeNumber
                                      FROM Athlete A, OlympicTeamMember_Sponsor OTMS, SinNumOrEqual_Name SN
                                      WHERE A.ID = OTMS.ID AND OTMS.SinNumOrEqual = SN.SinNumOrEqual AND OTMS.Country = '" . $country . "'");
    }

    printAthlete_Medal($result);
    OCICommit($db_conn);
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('selectQueryRequest', $_POST)) {
            handleSelectRequest();
        } elseif (array_key_exists('projectionQueryRequest', $_POST)) {
            handleProjectionQueryRequest();
        }

    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('joinQueryRequest', $_GET)) {
            handleJoinQueryRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['selectSubmit']) || isset($_POST['projectionSubmit'])) {
    handlePOSTRequest();
} else if (isset($_GET['joinSubmit'])) {
    handleGETRequest();
}
?>
</body>
</html>

