<?php

	$inData = getRequestInfo();
	
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "TheBeast2", "WeLoveCOP4331", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE FirstName OR LastName OR Email OR Phone LIKE ? AND UserID=?");
		$ContactName = "%" . $inData["searchText"] . "%";
		$stmt->bind_param("ss", $ContactName ,$inData["userId"]);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
      $searchResults .= '{';
			$searchResults .= '"firstName":'    . '"' . $row["FirstName"] . '",';
      $searchResults .= '"lastName":'     . '"' . $row["LastName"]  . '",';
      $searchResults .= '"emailAddress":' . '"' . $row["Email"]     . '",';
      $searchResults .= '"phoneNumber":'  . '"' . $row["Phone"]     . '",';
      $searchResults .= '"ID":'           . '"' . $row["ID"]        . '"';
      $searchResults .= '}';
		}
		
		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}
		
		$stmt->close();
		$conn->close();
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>