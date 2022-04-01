<!DOCTYPE html>
<html>
<head>
        <title>Volunteer Page</title>
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
    <b style="font-family:cursive; font-size:60px">Volunteer Information Page</b>
        <br /><br /><br />
        <b style="font-family:Gill Sans; font-size:20px;">Hi, visitor! Please choose the operations as follows!</b> 
        <br /><br />

        <h2>Insert Volunteer</h2>
        <form method="POST" action="VolunteerPage.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            ID: <input type="text" name="ID"> <br /><br />
            Country: <input type="text" name="COUNTRY"> <br /><br />
            Name: <input type="text" name="NAME"> <br /><br />
            Sex: <input type="text" name="S"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>


        <h2>Delete Volunteer</h2>
       
        <form method="POST" action="VolunteerPage.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Delete Volunteer ID : <input type="text" name="deleteID"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>


        <h2>Count and Show the Tuples in Volunteer</h2>
        <form method="GET" action="VolunteerPage.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
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
        $show_debug_alert_messages = FALSE; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
            
      
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
                echo "<br>insert failed, please try again<br>";
                $e = OCI_Error($db_conn);
               //echo htmlentities($e['message']);
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
                    echo "<br><font color='red' face='Bradley Hand' size='5'><br />&nbsp;insert failed, please try again</font> <br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    //echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }
        
        //start here
        function printResult($result) { //prints results from a select statement
           
            echo  "<p style='font-size: 20px; font-family:Gill Sans;background-color:lightskyblue;border: 2px solid #ddd; padding: 6px;line-height:40px; display:inline-block;'>Retrieved data from table Volunteer:</p>";
            echo "<table>";
            echo "<tr>
                  <th><p style='font-size: 18px; font-family:Gill Sans;'>ID</p></th>
                  <th><p style='font-size: 18px; font-family:Gill Sans;'>Country</p></th>
                  <th><p style='font-size: 18px; font-family:Gill Sans;'>Name</p></th>
                  <th><p style='font-size: 18px; font-family:Gill Sans;'>Sex</p></th>
                  </tr>";
    
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[0] . "</p></td>
                          <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[1] . "</p></td>
                          <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[2] . "</p></td>
                          <td><p style ='font:18px Gill Sans;font-size:20px;'>" . $row[3] . "</p></td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_xnie0204", "a76979301", "dbhost.students.cs.ubc.ca:1522/stu");

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

        function handleDeleteRequest() {
            global $db_conn;

            $delete_id = $_POST['deleteID'];
           $result = executePlainSQL("DELETE FROM Volunteer
                             WHERE ID = '". $delete_id . "'
                            "
        );

            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE Volunteer");

            // Create new table
            echo "<br> creating new table <br>";
            // !!
           
            executePlainSQL("CREATE TABLE Volunteer (ID int PRIMARY KEY, 
                                                         COUNTRY char(20),
                                                          NAME char(20),
                                                          S     char(10))
                                                          "); 

           $result = executePlainSQL("SELECT * FROM Volunteer");
            printResult($result);
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['ID'],
                ":bind2" => $_POST['COUNTRY'],
                ":bind3" => $_POST['NAME'],
                ":bind4" => $_POST['S']

            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Volunteer values (:bind1, :bind2,:bind3,:bind4)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Volunteer");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Volunteer: " . $row[0] . "<br>";
            }
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                }
                 else if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } 
                $result = executePlainSQL("SELECT * FROM Volunteer");
                printResult($result);

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }
                $result = executePlainSQL("SELECT * FROM Volunteer");
                printResult($result);
                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        }
		?>


	</body>
</html>
