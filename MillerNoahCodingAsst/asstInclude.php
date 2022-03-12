<?php
//http://localhost/MillerNoahCodingAsst/asstMain.php
function WriteHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo "
        <!doctype html> 
        <html lang = \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title>$TitleBar</title>\n
            <link rel=\"stylesheet\" type=\"text/css\" href=\"asstStyle.css\"/>
            <link rel=\"icon\" href=\"favicon.png\" sizes=\"any\">
    </head>
    <body>\n
    <h1>$Heading</h1>\n
    ";
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful, 
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. Error: "
              . $mysqlObj->connect_error . "</p>";
     // stop executing the php script
     exit;
    }
    return ($mysqlObj);
}

function DisplayLabel($prompt)
{
    echo "<label>" . $prompt . "</label>";
}

function DisplayTextbox($Input, $Name, $Size, $Value = 0)
{
    echo "<input type = $Input name = \"$Name\" size = $Size value = \"$Value\">";
}

function DisplayContactInfo()
{
    echo "<footer>Questions? Comments?
    <a href= \"mailto:noah.miller@student.sl.on.ca\">Email me!
    </a></footer>";
}

function DisplayImage($filename, $alt, $height, $width)
{
    echo "
    <img src = \"$filename\" alt = \"$alt\" height = $height width = $width>";
}

function DisplayButton($name, $text, $filename, $alt)
{
    if ($filename == "")
        echo "<button name = \"$name\" type = submit text = \"$text\" alt = \"$alt\"></button>";
    else
        echo "<button name = \"$name\" type = submit text = \"$text\" alt = \"$alt\"><img src = \"$filename\"></button>";
}

function WriteFooters()
{
    DisplayContactInfo();
    echo "</body>\n";
    echo "</html>\n";
}
?>