<?php
//http://localhost/MillerNoahCodingAsst/asstMain.php
require_once("asstInclude.php");
require_once("clsCreateRoadTestTable.php");
// Noah Miller, October 25th, 2021
// Main:
date_default_timezone_set ('America/Toronto');
$mysqlObj; 
$TableName = "RoadTest";
WriteHeaders("Noah Miller Coding Assignment", "Noah Miller Coding Assignment");
//Nested if statement to display the appropriate forms
if (isset($_POST['btnCreate']))
    CreateTableForm($mysqlObj, $TableName);
    else
        if (isset($_POST['btnAdd']))
            AddRecordForm();
        else
            if (isset($_POST['btnDisplay']))
                DisplayDataForm($mysqlObj, $TableName);
                else
                    if (isset($_POST['btnSave']))
                        SaveRecordToTableForm($mysqlObj, $TableName);
                        else
                            if (isset($_POST['btnHome']))
	                        DisplayMainForm();
else
    DisplayMainForm();
if (isset($mysqlObj)) $mysqlObj->close();
WriteFooters();

//end of main

function DisplayMainForm()
{
    echo "<form action ='asstMain.php' method=post>";
    DisplayButton("btnCreate", "Create Button", "create.png", "Create");
    DisplayButton("btnAdd", "Add Record", "add.png", "Add");
    DisplayButton("btnDisplay", "Display Data", "display.png", "Display");
    echo "</form>";
}

function CreateTableForm(&$mysqlObj,&$TableName)
{
    $newTable = new clsCreateRoadTestTable;
    $newTable->createTheTable($mysqlObj,$TableName);

    echo "<form action = ? method=post><br>";
    DisplayButton("btnHome", "Home Button", "home.png", "Home");
    echo "</form>";
}

function AddRecordForm()
{
    echo "<form action = ? method=post>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("License Plate: ");
    echo "<br>";
    DisplayTextbox("text", "License", 10, "0000000000");
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Date Stamp: ");
    echo "<br>";
    DisplayTextbox("date", "Date", 10, date('Y-m-d'));
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Time Stamp: ");
    echo "<br>";
    DisplayTextbox("time", "Time", 10, date("h:i"));
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Number of Passengers: ");
    echo "<br>";
    DisplayTextbox("number", "Passengers", 2, "3");
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Incident Free: ");
    echo "<br>";
    DisplayTextbox("checkbox", "Incident", 1, 1);
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Danger Status: ");
    echo "<br>";
    echo "<select name = \"Danger\" size = 4>";
    echo "<option value = Low>Low</option>";
    echo "<option value = Medium selected = \"Medium\">Medium</option>";
    echo "<option value = High>High</option>";
    echo "<option value = Critical>Critical</option>";
    echo "</select>";
    echo "</div><br>";

    echo "<div class=\"DataPair\">";
    DisplayLabel("Speed: ");
    echo "<br>";
    DisplayTextbox("number", "Speed", 4, "100");
    echo "</div><br>";

    DisplayButton("btnSave", "Save Button", "save.png", "Save");
    DisplayButton("btnHome", "Home Button", "home.png", "Home");
    echo "</form>"; 
}

function SaveRecordToTableForm(& $mysqlObj, & $TableName)
{
    $mysqlObj = CreateConnectionObject();

    $query = "Insert Into $TableName (licensePlate, dateTimeStamp, nbrPassengers, incidentFree,
                                     dangerStatus, speed, cost) Values (?, ?, ?, ?, ?, ?, ?)";

    if ($_POST["Danger"] == "Low")
        $DangerStat = "L";
    else if ($_POST["Danger"] == "Medium")
        $DangerStat = "M";
    else if ($_POST["Danger"] == "High")
        $DangerStat = "H";
    else
        $DangerStat = "C";
    
    $Cost = 100 * $_POST["Passengers"] + 5000;

    $DateTime = $_POST["Date"] . " " . $_POST["Time"];

    $stmt = $mysqlObj->prepare($query);
    if ($stmt == false)
    {
        echo "Prepare failed on $query " . $mysqlObj->error;
        exit;
    }

    $BindSuccess = $stmt->bind_param("ssiisii", $_POST["License"], $DateTime, $_POST["Passengers"],
                                    $_POST["Incident"], $DangerStat, $_POST["Speed"], $Cost);
    if ($BindSuccess)
        $Success = $stmt->execute();
    else
        echo "Bind failed " . $stmt->error;
    
    if ($Success)
        echo "Record sucessfully added to " . $TableName . ".";
    else
        echo "Unable to add record to " . $TableName . ".";
        
    $stmt->close();
    echo "<form action = ? method=post><br>";
    DisplayButton("btnHome", "Home Button", "home.png", "Home");
    echo "</form>"; 
}

function DisplayDataForm(& $mysqlObj, & $TableName)
{
    $mysqlObj = CreateConnectionObject();
    $stmt = $mysqlObj->prepare("SELECT * From $TableName ORDER BY dangerStatus DESC");
    $stmt->execute();
    $success = $stmt->bind_result($License, $Date, $Passengers, $Incident, $Danger, $Speed, $TotalCost);

    if ($success);
    {
        echo "<center><table cellpadding=5 cellspacing=0 border=2>";
        echo "<tr>";
        echo "<th>License Plate</th>";
        echo "<th>Date & Time</th>";
        echo "<th>Number of Passengers</th>";
        echo "<th>Incident Free</th>";
        echo "<th>Danger Status</th>";
        echo "<th>Speed</th>";
        echo "<th>Cost</th>";
        echo "</tr>";
        while ($stmt->fetch())
        {
            echo "<tr>";

            echo "<td>";
            echo $License;
            echo "</td>";

            echo "<td>";
            $CalDate = substr($Date, 0, 11);
            $Time = substr($Date, 11);
            echo $CalDate . " at " . $Time;
            echo "</td>";

            echo "<td>";
            echo $Passengers;
            echo "</td>";

            echo "<td>";
            if ($Incident == true)
                echo "Yes";
            else
                echo "No";
            echo "</td>";

            echo "<td>";
            if ($Danger == "L")
                echo "Low";
            else if ($Danger == "M")
                echo "Medium";
            else if ($Danger == "H")
                echo "High";
            else 
                echo "Critical";
            echo "</td>";

            echo "<td>";
            echo $Speed;
            echo "</td>";

            echo "<td>";
            echo $TotalCost;
            echo "</td>";

            echo "</tr>";
        }
            echo "</table></center><br>";
    }
    
    $stmt->close();

    echo "<form action = ? method=post>";
    DisplayButton("btnHome", "Home Button", "home.png", "Home");
    echo "</form>";
}

?>