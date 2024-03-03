<?php
// Get the username from the URL parameters
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Save it as a javascript variable
echo "<script>";
echo "let username = '" . $username . "';";
echo "</script>"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search History</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f8f8;
    }

    #tableDiv {
      max-width: 800px;
      margin: 20px auto;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .dayContainer {
      margin-bottom: 20px;
    }

    .dayContainer h2 {
      background-color: #333;
      color: #fff;
      padding: 10px;
      margin: 0;
      border-radius: 4px 4px 0 0;
    }

    .entry {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    .entry a {
      color: #0066cc;
      text-decoration: none;
      margin-right: 10px;
    }

    .entry p {
      margin: 0;
    }
	
	.entry-time {
	  float: right;
	  color: #888;
	}
	
	.grid-container {
		display: grid;
		grid-template-columns: auto auto auto;
		padding: 20px;
		text-align: center;
	}

	.grid-item {
		padding: 20px;
		text-align: center;
	}
	
	#logo:hover {
		cursor: pointer;
	}
	
	.delete-button {
      background-color: #ff4d4d;
      color: #fff;
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="grid-container">
    <div class="grid-item"></div>		  
	<div class="grid-item">
		<img src="Logo.png" alt="My Logo" id="logo">
	</div>
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
	<div class="grid-item">
		<div id="tableDiv"></div>
	</div>
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
    <div class="grid-item"></div>
	<div class="grid-item"></div>
  </div>

<script>

  // Function to get all entries from the database
  function getAllEntries() {
	  
	document.getElementById('tableDiv').textContent = "";
	
	// Modify the URL to include the username parameter
    var url = "GetWebsiteVisits.php?username=" + encodeURIComponent(username);
	  
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Parse the JSON response
        var entriesList = JSON.parse(this.responseText);
		
		// If statement is validation to see if any search history is stored for the user - if not then say this to the user
		if (entriesList.length > 0) {
			// Loop checks if the next entry has a date that's the same as the current entry's date
			// If yes, continue adding entries to the list that will be passed to displayDayHistory
			// If no (i.e., date is different so need to start a new div), go to displayDayHistory, passing all entries of the current day
			let i = 0;
			daysEntryList = [];
			while (i < entriesList.length - 1) {
				  // Check if the data (excluding the time part) is equal to the next entry's date
				  if (entriesList[i].Date.split(" ")[0] == entriesList[i+1].Date.split(" ")[0]) {
					// Add the current entry to the current day list
					daysEntryList.push(entriesList[i]);
				  } else {
					// Add entry to current day list and call displayDayHistory so that the day can be written into it's own div
					daysEntryList.push(entriesList[i]);
					displayDayHistory(daysEntryList, i);
					daysEntryList = [];
				  }
			  i = i + 1;
			}
			daysEntryList.push(entriesList[entriesList.length - 1]);
			displayDayHistory(daysEntryList, entriesList.length - 1);
		} else {
			// Create a new p element for displaying the message to the user
		  var newP = document.createElement('p');
		  newP.textContent = "Search history is empty";
		  
		  // Append the new div to the tableDiv
		  document.getElementById('tableDiv').appendChild(newP);
		}
      }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
  }

  // Create a new div for the current day in the while loop
  function displayDayHistory(daysEntryList, i) {
    // Extract the date
    let date = daysEntryList[0].Date;
    // Split the date and time
    let [datePart, timePart] = date.split(' ');

    // Create a new div element
    var newDiv = document.createElement('div');
    newDiv.className = 'dayContainer';

    var div = "div";
    // Set the id attribute
    newDiv.id = div.concat(i.toString());

    // Create a new h2 element
    var newH2 = document.createElement('h2');
    newH2.textContent = datePart;

    // Append the new h2 to the div
    newDiv.appendChild(newH2);

    // Loop through all of the day's entries to display the time, title, and URL to the user
    for (j=0; j<daysEntryList.length; j++) {
	  // Get the data, title, date and time from entry j
      title = daysEntryList[j].Title;
      url = daysEntryList[j].URL;
      [datePart, timePart] = daysEntryList[j].Date.split(' ');

      // Create a new div for each entry
      var entryDiv = document.createElement('div');
      entryDiv.className = 'entry';

      // Create a new a element
      var newA = document.createElement('a');
      newA.href = url;
      newA.textContent = title;

      // Create a new p element for displaying the URL
      var newP = document.createElement('p');
      newP.id = "p".concat(j.toString());
      newP.innerHTML = url + '<span class="entry-time"> ' + formatTime(timePart) + '</span>';


      // Append the new a and new p to the entry div
      entryDiv.appendChild(newA);
      entryDiv.appendChild(newP);

      // Append the entry div to the main day div
      newDiv.appendChild(entryDiv);
	  
	  // Create a new button for deleting the entry
      var deleteButton = document.createElement('button');
      deleteButton.className = 'delete-button';
      deleteButton.textContent = 'Delete';
	  // Set the id of the delete button to match the id of the entry in the searchHistory database so that it can be used in the deletion query
	  deleteButton.id = 'delete-button-' + (daysEntryList[j].Id).toString();
	  
	  // Use a function to capture the current entry ID
	  deleteButton.onclick = createDeleteHandler(daysEntryList[j].Id);

      // Append the delete button to the entry div
      entryDiv.appendChild(deleteButton);

      // Append the entry div to the main day div
      newDiv.appendChild(entryDiv);
    }
	
	// Append the new div to the tableDiv
    document.getElementById('tableDiv').appendChild(newDiv);
  }
  
  function createDeleteHandler(entryId) {
    return function () {
      console.log(entryId);
      deleteEntry(entryId);
    };
  }
  
  function deleteEntry(id) {
  
  // Show a warning message to the user confirming asking if they're sure they want to delete their account
  var result = window.confirm("Attention! Are you sure that you want to delete this entry?");

	if (result) {
		// User clicked OK

		$.ajax({
		  type: "POST",
		  url: "DeleteSearchHistoryEntry.php",
		  data: { id: id },
		  success: function(response) {
			// Handle the server's response
			alert(response);

			// If registration is a success then set website cookie for the username to remember the user
			if (response.includes("successful")) {
				getAllEntries();			// Re write the webpage with the exception of the deleted entry
			}
		  }
		});  
	} else {
		// User clicked Cancel
		alert("Entry deletion cancelled");
	}
  
  }
  
  // Function to format the time
  function formatTime(time) {
	// Assuming the time is in HH:mm:ss format
	return time.split(':')[0] + ':' + time.split(':')[1];
  }
  
  // Add a click event listener to the image
  document.getElementById("logo").addEventListener("click", function() {
      // Redirect the user back to the home page
      window.location.href = "SearchEngineFrontEnd.php";
  });

  getAllEntries();
</script>

</body>
</html>
