<?php
  $inData = getRequestInfo();
  
 	$firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $phoneNumber = $inData["phoneNumber"];
  $emailAddress = $inData["emailAddress"];
	$userId = $inData["userId"]; 

	$conn = new mysqli("localhost", "TheBeast2", "WeLoveCOP4331", "COP4331"); 
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
  else
  {
    $stmt = $conn->prepare("DELETE FROM Contacts WHERE FirstName=? AND UserID=? AND LastName=?"); 
		$stmt->bind_param("sss", $firstName, $userId, $lastName);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");        
  }
  
 	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}
 
	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	} 
 
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
   
 ?>