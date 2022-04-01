<!DOCTYPE html>
<html>

<head>
    <br />
    <title>LivePlatform Page</title>
    <style>
        body {
            background-image: url("mainBG.png");
            background-repeat: no-repeat, repeat;
            margin: 10px 30px;
        }
        p {font-family: Gill Sans; font-size: 20px;}
        TD{font-family: Gill Sans; font-size: 18px;}
    </style>
</head>

<body>
    <b style="font-family:cursive; font-size:60px">Live Platform Information</b>
    <br /><br /><br />
    <b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Please choose the operations as follows!</b>
    <br /><br /><br />

    <p>Search the competition events broadcasted by a certain live platform:</p>
    <form style="font-family:Gill Sans; font-size:20px;" 
    method="GET" action="livePlatform.php">
        <input type="hidden" id="getEventsByPlatform" name="getEventsByPlatform">
        LivePlatform Name: &nbsp; <input type="text" name="lpName">&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Submit" name="LPNameSubmit"></p>
    </form>
    

    <p>Look for the competition events broadcasted by all the platforms which use a certain language:</p>
    <form style="font-family:Gill Sans; font-size:20px;" 
    method="GET" action="livePlatform.php">
        <input type="hidden" id="getLEvents" name="getLEvents">
        Language: &nbsp; <input type="text" name="lg">&nbsp;
        <input style="font-family:Gill Sans; font-size:18px;" type="submit" value="Submit" name="LABSubmit"></p>
    </form>
    <br /><br />

    <form method="GET" action="https://www.students.cs.ubc.ca/~lzy0606/mainPage.php">
        <input style="font-family:Gill Sans; font-size:18px;" 
        type="submit" value="Back" name="Back"></p>
    </form>



    <?php
    $success = True;
    $db_conn = NULL; 
    $show_debug_alert_messages = False; 

    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr)
    {   global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        
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

    function printResult($result)
    {   

        echo "<table>";

        $current = OCI_Fetch_Array($result, OCI_BOTH)[0];

        if ($current == NULL){
            echo "<font color='green' face='Bradley Hand' size='5'><br /> Sorry! No such Competition Events.</font>";

        } else {
            echo "<font color='green' face='Bradley Hand' size='5'> Competition Events Searching Result:<br /></font><br />";
            echo "<table>";
            echo "<tr><td style='background-color:#bee6ff; border: 2px solid #ddd; padding: 6px;'>$current</td></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td style='background-color: #bee6ff; border: 2px solid #ddd; padding: 6px;'>$row[0]</td></tr>";
        }
        echo "</table>";

        }

        
    }

    function connectToDB()
    {
        global $db_conn;

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

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }


    function handleSelectRequest()
    {
        global $db_conn;

        $chosenLpName = $_GET['lpName'];

        $check = executePlainSQL("SELECT Name 
                                  FROM   LivePlatform
                                  WHERE  Name = '" . $chosenLpName . "'");

        if (OCI_Fetch_Array($check, OCI_BOTH)[0] == NULL){
            echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Warning: No such live platforms. Please try again.</font>";
        } else {
            $plan = "SELECT C.CompetitionEvent_Name 
                     FROM Streaming S, CompetitionEvent C 
                     WHERE S.CompetitionEvent_ID = C.ID 
                     AND S.LivePlatform_Name = '" . $chosenLpName . "'";

        $result = executePlainSQL($plan);

        if ($result != false) {
            printResult($result);
        }

        }
    }

    function handleDivisionRequest()
    {
        global $db_conn;

        $chosenLg = $_GET['lg'];

        $check = executePlainSQL("SELECT Language 
                                  FROM   LivePlatform
                                  WHERE  Language = '" . $chosenLg . "'");

        if (OCI_Fetch_Array($check, OCI_BOTH)[0] == NULL){
            echo "<font color='red' face='Bradley Hand' size='5'><br />&nbsp; Warning: No such languages. Please try again.</font>";
        } else {

        $plan = "SELECT  CompetitionEvent_Name 
                 FROM    CompetitionEvent CE 
                 WHERE NOT EXISTS ( (SELECT LP.Name 
                                     FROM  LivePlatform LP   
                                     WHERE  LP.Language= '". $chosenLg ."') 
                                     MINUS (SELECT S.LivePlatform_Name 
                                            FROM   Streaming S
                                            WHERE  S.CompetitionEvent_ID = CE.ID))";

        $result = executePlainSQL($plan);

        if ($result != false) {
            printResult($result);
        }
    }
    }

    function handleGETRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('LPNameSubmit', $_GET)) {
                handleSelectRequest();
            } else if (array_key_exists('LABSubmit', $_GET)) {
                handleDivisionRequest();
            }
            disconnectFromDB();
        }
    }

    if (isset($_GET['getEventsByPlatform']) || isset($_GET['getLEvents'])) {
        handleGETRequest();
    }

    ?>
</body>

</html>