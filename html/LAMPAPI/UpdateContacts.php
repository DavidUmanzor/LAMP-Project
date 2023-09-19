<?php
	$inData = getRequestInfo();
  
	$phoneNumber = $inData["phoneNumber"];
	$emailAddress = $inData["emailAddress"];
	$newFirstName = $inData["newFirstName"];
	$newLastName = $inData["newLastName"];

	$contactId = $inData["id"]; 

	$conn = new mysqli("localhost", "TheBeast2", "WeLoveCOP4331", "COP4331"); 
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
  else
  {
    $stmt = $conn->prepare("UPDATE FROM Contacts WHERE ID=? SET FirstName=?, LastName=?, Phone=?, Email=?"); 
		$stmt->bind_param("issss", $contactId, $newFirstName, $newLastName, $phoneNumber, $emailAddress);
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