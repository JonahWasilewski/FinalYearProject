<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Engine</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>  
  function signIn() {
    var username = document.getElementById("usernameEntry").value;
    var password = document.getElementById("passwordEntry").value;

	// Remove whitespace from variables
	username = username.replace(/\s/g, "");
	password = password.replace(/\s/g, "");
	
	if (username != "" && password != "") {
		$.ajax({
		  type: "POST",
		  url: "SignIn.php",
		  data: { username: username, password: password },
		  success: function(response) {
			// Handle the server's response
			alert(response);

			// If login is a success then set website cookie for the username to remember the user
			if (response.includes("successful")) {
				setCookie("username", username, 1); // Call set cookie function
				checkCookie();			// CheckCookie will see that the user is now signed in and remove the sign in and register options from view
				updateUsernameInfo();	// Need to change this username as well
			}
			
		  }
		});
	} else {
		alert("Username and password can't be empty!");
	}
  }
  
  function register() {
	var fname = document.getElementById("firstNameEntry").value;
    var lname = document.getElementById("lastNameEntry").value;
    var username = document.getElementById("registerUsernameEntry").value;
    var password = document.getElementById("registerPasswordEntry").value;
	var dob = document.getElementById("dobEntry").value;

	// Remove whitespace from variables
	fname = fname.replace(/\s/g, "");
	lname = lname.replace(/\s/g, "");
	username = username.replace(/\s/g, "");
	password = password.replace(/\s/g, "");
	dob = dob.replace(/\s/g, "");

	if (fname != "" && lname != "" && username != "" && password != "" && dob != "") {
		$.ajax({
		  type: "POST",
		  url: "Register.php",
		  data: { fname: fname, lname: lname, username: username, password: password, dob: dob },
		  success: function(response) {
			// Handle the server's response
			alert(response);

			// If registration is a success then set website cookie for the username to remember the user
			if (response.includes("successful")) {
				setCookie("username", username, 1); // Call set cookie function
				checkCookie();			// CheckCookie will see that the user is now signed in and remove the sign in and register options from view
				updateUsernameInfo();	// Need to change this username as well
			}
			
		  }
		});
	} else {
		alert("All fields must be filled!");
	}
  }
  
  function deleteAccount() {
  
  // Show a warning message to the user confirming asking if they're sure they want to delete their account
  var result = window.confirm("Attention! Are you sure that you want to delete you account?");

	if (result) {
		// User clicked OK
		
		// Get username using cookies
		var userName = getCookie("username");

		$.ajax({
		  type: "POST",
		  url: "DeleteAccount.php",
		  data: { username: userName },
		  success: function(response) {
			// Handle the server's response
			alert(response);

			// If registration is a success then set website cookie for the username to remember the user
			if (response.includes("successful")) {
				logout();			// Make sure the user is no longer signed in - as the account no longer exists
			}
			
		  }
		});  
	} else {
		// User clicked Cancel
		alert("Account deletion cancelled");
	}
  
  }
</script>

  <style>
 /* Basic CSS for styling */
    body {
      font-family: Arial, sans-serif;
	  font-size: 16px;
      line-height: 1.6;
      text-align: center;
      background-color: #f0f0f0;
	  color: #333;
      margin: 0;
      padding: 0;
    }

    #search-container {
      margin-top: 50px;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #3498db;
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

    input[type="text"] {
      padding: 10px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    #search-results {
      margin-top: 20px;
    }
	
	/* Buttons used to open the forms - fixed at the bottom of the page */
	.open-button, #settings-button {
		background-color: gray;
		color: white;
		padding: 16px 20px;
		margin: 4px 2px;
		border: none;
		cursor: pointer;
		opacity: 0.8;
		width: 180px;
	}
	
	/* Improved button styles */
	.open-button, #settings-button, .btn, #cookie-consent-popup button {
		background-color: #3498db;
		color: #fff;
		padding: 10px 20px;
		border: none;
		cursor: pointer;
		border-radius: 5px;
		margin: 4px 2px;
		opacity: 0.8;
	}

	/* Button hover effect */
	.open-button:hover, #settings-button:hover, .btn:hover, #cookie-consent-popup button:hover {
		opacity: 1;
	}

	/* The popup form - hidden by default */
	.form-popup {
		display: none; 
		position: fixed;
		bottom: 0;
		right: 15px;
		border: 3px solid #f1f1f1;
		z-index: 9;
	}

	/* Add styles to the form container */
	.form-div-container {
		max-width: 300px;
		padding: 10px;
		background-color: white;
	}

	/* Full-width input fields */
	.form-div-container input[type=text],
	.form-div-container input[type=password],
	.form-div-container input[type=date],
	.form-div-container input[type=text]:focus,
	.form-div-container input[type=password]:focus,
	.form-div-container input[type=date]:focus {
		width: 100%;
		padding: 12px; /* Adjusted padding for a more uniform look */
		margin: 8px 0;
		box-sizing: border-box;
		border: 1px solid #ccc;
	}

	/* Set a common style for labels */
	.form-div-container label {
		margin-top: 8px;
		display: block;
	}

	/* Set a style for the submit/login button */
	.form-div-container .btn {
		background-color: #04AA6D;
		color: white;
		padding: 14px 18px; /* Adjusted padding for a more uniform look */
		border: none;
		cursor: pointer;
		width: 100%;
		margin-bottom: 10px;
		opacity: 0.8;
	}

	/* Add a red background color to the cancel button */
	.form-div-container .cancel {
		background-color: red;
	}

	/* Add some hover effects to buttons */
	.form-div-container .btn:hover,
	.open-button:hover, #settings-button:hover {
		opacity: 1;
	}
	
	#menu {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 10;
    }

    #menu a {
      padding: 10px;
      text-decoration: none;
      color: white;
      background-color: #3498db;
      margin-right: 10px;
      border-radius: 5px;
    }
	
	#cookie-info {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #333;
      padding: 10px;
      text-align: center;
      color: white;
      font-size: 14px;
	  z-index: 9999; /* Set a high z-index to bring the banner to the front */
    }

    #cookie-consent button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }
	
	#delete-account-button:disabled, #search-history-button:disabled {
      background-color: gray;
      cursor: not-allowed;
    }
	
	#cookie-info-container {
	  display: none;
	  position: fixed;
	  background: white;
	  padding: 20px;
	  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
	  z-index: 1000;
	  /* Remove margin-left and margin-right */
	  left: 50%;
	  top: 50%;
	  transform: translate(-50%, -50%);
	  width: 75%;
	}
	
	#cookie-consent-popup, #privacy-settings-popup {
	  display: block;
	  position: fixed;
	  background: white;
	  padding: 20px;
	  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
	  z-index: 1000;
	  /* Remove margin-left and margin-right */
	  left: 50%;
	  top: 50%;
	  transform: translate(-50%, -50%);
	  width: 75%;
	}

	#overlay {
	  display: block;
	  position: fixed;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  background: rgba(0, 0, 0, 0.5);
	  z-index: 999;
	}
	
	.privacy-setting {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
	
  </style>
</head>
<body>

  <!-- Cookie Consent Banner -->
  <div style="display: none" id="cookie-consent-popup">
    <p id="cookieRequestText" style="text-align:left">This site uses cookies to enhance the user experience, do you agree to the use of cookies to store relevant information</p>
    <button onclick="acceptCookies()">Got it!</button>
	<button onclick="rejectCookies()">Reject</button>
	<button onclick="openCookieInfoPopup()">More info</button>	
  </div>

  <div class="grid-container">
    <div class="grid-item"></div>		  
	<div class="grid-item">
		<img src="Logo.png" alt="My Logo" id="logo">
	</div>
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
	<div class="grid-item">
		<div id="search-container">
		<form>
		  <input id='query' type="text" name="query" placeholder="Enter your search query">
		  <br><br>
		  <input type="button" value="Search" id="search-button" onclick="validateQuery()">
		</form>
	  </div>
	</div>
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
    <div class="grid-item"></div>
	<div class="grid-item"></div>
  </div>
  
  <div id="account-forms" class="grid-item" style="position: fixed; bottom: 23px; right: 28px; width: 280px;">

	<p id="open-buttons-p">Already got an account or want to make one?<p>
	  
	<button class="open-button" onclick="openForm('signInForm')">Sign In</button>

	<div class="form-popup" id="signInForm">
	  <div class="form-div-container">
	    <h1>Sign In</h1>
		<form method="post">
			<label for="usernameEntry"><b>Username</b></label>
			<input type="text" id="usernameEntry" placeholder="Username" name="username" required autocomplete="username">

			<label for="passwordEntry"><b>Password</b></label>
			<input type="password" id="passwordEntry" name="password" required>

			<button type="button" class="btn" onclick="signIn()">Submit</button>
			<button type="button" class="btn cancel" onclick="closeForm('signInForm')">Close</button>
		</form>
	  </div>
	</div>
	  
	<button class="open-button" onclick="openForm('registerForm')">Register</button>
	
	<div class="form-popup" id="registerForm">
	  <div class="form-div-container">
	    <h1>Register</h1>
			<label for="firstNameEntry"><b>First name</b></label>
			<input type="text" id="firstNameEntry" placeholder="First Name" name="fname" required>

			<label for="lastNameEntry"><b>Last Name</b></label>
			<input type="text" id="lastNameEntry" placeholder="Last Name" name="lname" required>
				
			<label for="registerUsernameEntry"><b>Username</b></label>
			<input type="text" id="registerUsernameEntry" placeholder="Username" name="username" required autocomplete="username">

			<label for="registerPasswordEntry"><b>Password</b></label>
			<input type="password" id="registerPasswordEntry" name="password" required>
				
			<label for="dobEntry"><b>Date Of Birth</b></label>
			<input type="date" id="dobEntry" name="dob" required>

			<button type="button" class="btn" onclick="register()">Submit</button>
			<button type="button" class="btn cancel" onclick="closeForm('registerForm')">Close</button>
	  </div>
	</div>
	
	<button id="settings-button" onclick="openSettings()">Settings</button>
	<div class="form-popup" id="settingsContainer">
		<div class="form-div-container">
			<h1>Settings</h1>
			<button type="button" class="btn" id="delete-account-button" onclick="deleteAccount()" disabled>Delete Account</button>
			<button type="button" class="btn" id="search-history-button" onclick="searchHistory()" disabled>Search History</button>
			<button type="button" class="btn" id="privacy-settings-button" onclick="privacySettings()">Privacy Settings</button>
			<button type="button" class="btn cancel" onclick="closeSettings()">Close</button>
		</div>
	</div>
	
	<div id="auth-status"></div>
	
	<div id="overlay"></div>
	
	<div id="cookie-info-container">
		<p id="cookiePopupText" style="text-align:left"></p>
		<button onclick="closeCookieInfoPopup()">Close</button>
	</div>
	
	<div id="privacy-settings-popup" style="display:none">
		<h2>Privacy Settings</h2>

		<div class="privacy-setting">
			<span id="username-info" onclick="togglePrivacyInfo('change-username-span')"></span>
			<span id="change-username-span" class="privacy-info" style="display:none;">
			
			Change username?
			<input type="text" id="changeUserNameEntry" placeholder="New Username" name="newUsername" required>
			<button type="button" class="btn" onclick="changeUsername()">Submit</button>
			
			</span>
		</div>
		
		<div class="privacy-setting">
			<span id="name-info" onclick="togglePrivacyInfo('change-name-span')">Name - </span>
			<span id="change-name-span" class="privacy-info" style="display:none;">
			
			Change first or last name?<br>
			<input type="text" id="changeFirstNameEntry" placeholder="First Name" name="newFirstName" required>
			<input type="text" id="changeLastNameEntry" placeholder="Last Name" name="newLastName" required>
			<button type="button" class="btn" onclick="changeName()">Submit</button>
			
			</span>
		</div>
		
		<div class="privacy-setting">
			<span id="password-info" onclick="togglePrivacyInfo('change-password-span')">Password</span>
			<span id="change-password-span" class="privacy-info" style="display:none;">
			
			Change password?<br>
			<input type="text" id="currentPasswordEntry" placeholder="Current Password" name="currentPassword" required>
			<input type="text" id="changePasswordEntry1" placeholder="New Password" name="newPassword1" required>
			<input type="text" id="changePasswordEntry2" placeholder="Confirm Password" name="newPassword2" required>
			<button type="button" class="btn" onclick="changePassword()">Submit</button>
			
			</span>
		</div>

		<button type="button" class="btn cancel" onclick="closePrivacySettingsPopup()">Close</button>
	</div>
</div>

<script>

  // Function to check if the "visitedBefore" cookie exists
    function checkVisitedBefore() {
      var visitedBefore = getCookie("visitedBefore");
      if (visitedBefore) {
        // The user has visited before, hide the cookie consent popup
        document.getElementById("cookie-consent-popup").style.display = "none";
        document.getElementById("overlay").style.display = "none";
      } else {
		document.getElementById("cookie-consent-popup").style.display = "none";
        document.getElementById("overlay").style.display = "none";
	  }
    }

    // Function to set the "visitedBefore" cookie
    function setVisitedBeforeCookie() {
      setCookie("visitedBefore", "true", 365);
    }

  function validateQuery() {
    var query = document.getElementById('query').value.trim();

    if (query === "") {
      alert("Please enter a search query.");
      return false; // Prevent form submission
    }

	// Get the username from the cookie
	var username = getCookie("username");

	// Check if the username cookie exists
	if (username) {
		// Construct the URL for the PHP script with the username and query as parameters
		var url = "RetrieveWebsiteResults.php?username=" + encodeURIComponent(username) + "&query=" + encodeURIComponent(query);

		// Redirect the user to the new page
		window.location.href = url;
	} else {
		// Construct the URL for the PHP script with just the query as a parameter
		var url = "RetrieveWebsiteResults.php?query=" + encodeURIComponent(query);

		// Redirect the user to the new page
		window.location.href = url;
	}
  }

  // Function to set a cookie indicating user's consent
  function acceptCookies() {
    setCookie("cookieConsent", "true", 365);
	setVisitedBeforeCookie();
    document.getElementById("cookie-consent-popup").style.display = "none";
	document.getElementById("overlay").style.display = "none";
  }
  
  // Disable use of cookies as the user has rejected them
  function rejectCookies() {
    setCookie("cookieConsent", "false", 365);
	setVisitedBeforeCookie();
    document.getElementById("cookie-consent-popup").style.display = "none";
	document.getElementById("overlay").style.display = "none";
  }

  function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function checkCookie() {
      let userName = getCookie("username");
      let authStatusElement = document.getElementById("auth-status");
      let openButtons = document.getElementsByClassName("open-button");
      let deleteAccountButton = document.getElementById("delete-account-button");
	  let searchHistoryButton = document.getElementById("search-history-button");

      if (userName != "") {
        // User is authenticated
        authStatusElement.innerHTML = "Welcome again " + userName + " | <button onclick='logout()'>Logout</button>";
        document.getElementById("usernameEntry").value = userName;

        for (let x of openButtons) {
          x.style.display = "none";
        }
        document.getElementById("open-buttons-p").style.display = "none";
        closeForm("signInForm");
        closeForm("registerForm");
        document.getElementById("passwordEntry").value = "";

        // Enable the "Delete Account" button
        deleteAccountButton.disabled = false;
		searchHistoryButton.disabled = false;
      } else {
        // User is not authenticated
        authStatusElement.innerHTML = "";

        for (let x of openButtons) {
          x.style.display = "block";
        }
        document.getElementById("open-buttons-p").style.display = "block";

        // Disable the "Delete Account" button
        deleteAccountButton.disabled = true;
		searchHistoryButton.disabled = true;
      }
    }

  function logout() {
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
	document.getElementById("passwordEntry").value = "";
    checkCookie(); // Refresh the authentication status
	updateUsernameInfo();	// Need to change this username as well
  }

	function openForm(formId) {
		document.getElementById(formId).style.display = "block";
	}

	function closeForm(formId) {
	  document.getElementById(formId).style.display = "none";
	}

	function openSettings() {
        document.getElementById("settingsContainer").style.display = "block";
    }

    function closeSettings() {
        document.getElementById("settingsContainer").style.display = "none";
    }

	function openCookieInfoPopup() {
	    assignCookieInfoText();
		document.getElementById("cookie-consent-popup").style.display = "none";
		document.getElementById("cookie-info-container").style.display = "block";
		document.getElementById("overlay").style.display = "block";
	  }

	function closeCookieInfoPopup() {
	  document.getElementById("cookie-info-container").style.display = "none";
	  document.getElementById("cookie-consent-popup").style.display = "block";
	}
	
	function assignCookieInfoText() {
		// define the text to put into the popup to describe the use of cookies to the user 
	    let cookieText = "This website uses cookies to enhance your browsing experience. Cookies are small text files stored on your device that help us analyze site traffic, personalize content, and provide targeted advertisements."; 
		cookieText = cookieText + "<br><br>Jonah Search uses cookies to store information about your search and interaction habits in order to create a specialised and unique search experience that allows you to get the most out of your queries.";
		cookieText = cookieText + "Although the use of cookeis is not essential, accpeting them will greatly improve your experience with the service and give you access to all it's functionalities.";

		// Assign the cookie text to the p tag in the popup
		document.getElementById("cookiePopupText").innerHTML = cookieText;
	}
	
	function searchHistory() {
	
		// Get the username from the cookie
		var username = getCookie("username");

		// Check if the username cookie exists
		if (username) {
			// Construct the URL for the PHP script with the username as a parameter
			var url = "SearchHistory.php?username=" + encodeURIComponent(username);

			// Redirect the user to the new page
			window.location.href = url;
		} else {
			// Handle the case where the username cookie is not set
			alert("Username cookie not found. Please sign in first.");
		}
	}
	
	function privacySettings() {
		document.getElementById("privacy-settings-popup").style.display = "block"
	}
	
	function closePrivacySettingsPopup() {
		document.getElementById("privacy-settings-popup").style.display = "none"
	}
	
	function togglePrivacyInfo(infoId) {
        var infoElement = document.getElementById(infoId);
        if (infoElement.style.display === "none") {
            infoElement.style.display = "block";
        } else {
            infoElement.style.display = "none";
        }
    }
	
	// Function to update the text content of the span showing the username
    function updateUsernameInfo() {
        const usernameInfo = document.getElementById('username-info');
        const username = getCookie("username");

        if (username) {
          usernameInfo.textContent = `Username - ${username}`;
        } else {
          usernameInfo.textContent = 'Username - ';
        }
    }
	
	// Function to retrieve the data related to a given username and column name
	function getEntryFromUsernameAndColumn(columnName, callback) {

		var username = getCookie("username");

		$.ajax({
			type: "POST",
			url: "GetColumnEntry.php",
			data: { username: username, columnName: columnName },
			success: function (response) {
				if (typeof callback === "function") {
					// Invoke the callback function with the response
					callback(response);
				}
			}
		});
	}

	// Function to update the text content of the span showing the first and last name
	function updateFirstLastNameInfo() {
		const nameInfo = document.getElementById('name-info');

		// Call function to get the first name for the given username
		getEntryFromUsernameAndColumn("fname", function (firstName) {
			// Call function to get the last name for the given username
			getEntryFromUsernameAndColumn("lname", function (lastName) {
				nameInfo.textContent = "Name - " + firstName + " " + lastName;
			});
		});
	}
	
	function changeUsername() {
	
		// Get value from the username text box in the div
		var newUsername = document.getElementById('changeUserNameEntry').value;
			
		// Make sure that the input is not empty
		if (newUsername != "") {
				
			var username = getCookie("username");
			
			// Send query to users database to change the username of the current user
			$.ajax({
			  type: "POST",
			  url: "ChangeColumnEntry.php",
			  data: { username: username, columnName: "username", newValue: newUsername },
			  success: function(response) {
				// Handle the server's response
				alert(response);
			  }
			});
		} else {
			alert("Username and password can't be empty!");
		}
	}
	
	function changeName() {
	
		// Get values from the firstname and lastname text boxes in the div
		var newFirstname = document.getElementById('changeFirstNameEntry').value;
		var newLastName = document.getElementById('changeLastNameEntry').value;
			
		// Make sure that the inputs are not empty
		if (newFirstname != "" && newFirstname != "") {
				
			var username = getCookie("username");
			
			// Query users to change first name
			$.ajax({
			  type: "POST",
			  url: "ChangeColumnEntry.php",
			  data: { username: username, columnName: "fname", newValue: newFirstName },
			  success: function(response) {
				// Handle the server's response
				alert(response);
			  }
			});
			
			// Query users to change last name
			$.ajax({
			  type: "POST",
			  url: "ChangeColumnEntry.php",
			  data: { username: username, columnName: "lname", newValue: newLastName },
			  success: function(response) {
				// Handle the server's response
				alert(response);
			  }
			});
			
		} else {
			alert("Username and password can't be empty!");
		}
	}
	
	function changePassword() {
		
		// Get values from all text boxes in the div
		var currentPassword = document.getElementById('currentPasswordEntry').value;
		var newPassword1 = document.getElementById('changePasswordEntry1').value;
		var newPassword2 = document.getElementById('changePasswordEntry2').value;
		
		// Make sure that all of the inputs have been filled and both new password entries are equal
		//  && newPassword1 == newPassword2
		if (currentPassword != "" && newPassword1 != "" && newPassword2 != "") {
			
			var username = getCookie("username");
			
			// Check if currentPassword is a match to the user's password in the db
			$.ajax({
			  type: "POST",
			  url: "GetColumnEntry.php",
			  data: { username: username, columnName: "password" },
			  success: function(response) {
				// Handle the server's response
				alert(response);

				console.log(response);
				console.log(currentPassword);
				if (response === currentPassword) {
					
					// Commit the change to the db
					$.ajax({
					  type: "POST",
					  url: "ChangeColumnEntry.php",
					  data: { username: username, columnName: "password", newValue: newPassword1 },
					  success: function(response) {
						// Handle the server's response
						alert(response);
					  }
					});
				}
			  }
			});
		} else {
			alert("Username and password can't be empty!");
		}
	
	}

	// Call the checkVisitedBefore function when the page loads
    window.onload = function () {
      checkVisitedBefore();
      checkCookie();
	  updateUsernameInfo();
	  updateFirstLastNameInfo();
    };
  
</script>

</body>
</html>