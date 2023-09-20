<?php
  $inData = getRequestInfo();
  
  
  // From Javascript (variable names from JSON)
  $username = $inData["login"];
  $password = $inData["password"];
  $firstName = $inData["firstName"];
  $lastName =  $inData["lastName"]; 
  
	$conn = new mysqli("localhost", "TheBeast2", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
 else
 {
    
    // Returning information
		$stmt2 = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=?");
		$stmt2->bind_param("s", $inData["login"]);
		$stmt2->execute();
		$result = $stmt2->get_result();
    
		if( $row = $result->fetch_assoc()  )
		{
      var_dump(http_response_code(409));
		}
		else
		{
      // Insertion into database
  		$stmt = $conn->prepare("INSERT into Users (Login,Password,FirstName,LastName) VALUES(?,?,?,?)");
      $stmt->bind_param("ssss", $username, $password, $firstName, $lastName);
   		$stmt->execute();
      
      $returnstmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=?");
  		$returnstmt->bind_param("s", $inData["login"]);
  		$returnstmt->execute();
  		$result = $returnstmt->get_result();      
  	  $row = $result->fetch_assoc();  
 			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID']);    		                    
		}
		$stmt->close();
    $stmt2-> close();
    $returnstmt->close();
		$conn->close();    
 }
 
 
 
 	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $firstName, $lastName, $id )
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
 
 	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
 ?>