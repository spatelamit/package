
function handleFiles(files) 
{
    // Check for the various File API support.
    if (window.FileReader) 
    {
        // FileReader are supported.
        getAsText(files[0]);
    } 
    else
    {
        document.getElementById("error_message").innerHTML="FileReader are not supported in this browser.";
        return false;
    }
}

function getAsText(fileToRead) 
{
    var reader = new FileReader();
    // Handle errors load
    reader.onload = loadHandler;
    reader.onerror = errorHandler;
    // Read file into memory as UTF-8      
    reader.readAsText(fileToRead);
}

function loadHandler(event) 
{
    var csv = event.target.result;
    processData(csv);             
}

function processData(csv) 
{
    var allTextLines = csv.split(/\r\n|\n/);
    var lines = [];
    while (allTextLines.length) {
        lines.push(allTextLines.shift().split(','));
    }
    console.log(lines);
    drawOutput(lines);
}

function errorHandler(evt) 
{
    if(evt.target.error.name == "NotReadableError") 
    {
        document.getElementById("error_message").innerHTML="CSV file contains some error! Please check your file!";
        return false;
    }
}

function drawOutput(lines)
{
    document.getElementById("error_message").innerHTML="";
    var error=0;
    var table = document.createElement("table");
    for (var i = 0; i < lines.length-1; i++) 
    {
        var row = table.insertRow(-1);
        for (var j = 0; j < lines[i].length; j++) 
        {
            var mobile_numbers1=lines[i][j].replace(/\s/g, "");
            if(mobile_numbers1.length!=10)
                error++;
            else
            {
                var firstNameCell = row.insertCell(-1);
                firstNameCell.appendChild(document.createTextNode(mobile_numbers1));
            }
        }
    }
    
    if(error>0)
    {
        document.getElementById("error_message").innerHTML="Found some invalid mobile numbers in uploaded csv file! Please check...";
        return false;
    }
//document.getElementById("output").appendChild(table);
}