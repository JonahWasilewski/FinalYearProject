<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Engine</title>
  <style>
    /* Basic CSS for styling */
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    #search-container {
      margin-top: 100px;

      padding: 20px;
    }

    h1 {
      color: #3498db;
    }
	
	.grid-container {
		display: grid;
		grid-template-columns: auto auto auto;
		padding: 10px;
	}

	.grid-item {
		padding: 20px;
		text-align: center;
		background-color: #f5f5f5;
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
		<div id="search-container">
		<form action="RetrieveWebsiteResults.php" method="GET">
		  <input type="text" name="query" placeholder="Enter your search query">
		  <br><br>
		  <input type="submit" value="Search" id="search-button">
		</form>
	  </div>
	</div>
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
    <div class="grid-item"></div>
	<div class="grid-item"></div>
  </div>
</body>
</html>
