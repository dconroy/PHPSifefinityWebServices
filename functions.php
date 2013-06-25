<?php

function LoginRequest($sitefinityHost, $usersServiceUrl, $username, $password, $cookie){    
     
      //create credential array for json encoding, and get length for http header
      $creds = array("MembershipProvider"=>"", "Password"=>$password,"Persistent"=>TRUE,"UserName"=>$username);
      $credentials =  json_encode($creds);
			$byte_array =  unpack('C*', $credentials );
			$content_length=sizeof($byte_array);
			
		  $url = $sitefinityHost.$usersServiceUrl."Authenticate";
      //build http login
		  $headers = array(
				'Content-Type: application/json',
				"Content-Length: $content_length",
				'Expect: 100-continue',
				'Connection: Keep-Alive',
				'Accept:'
				
			 );
			$session = curl_init($url);
			curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 
			curl_setopt($session, CURLOPT_POST, TRUE);
			curl_setopt($session, CURLOPT_COOKIEJAR, $cookie );
		  curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($session, CURLOPT_POSTFIELDS, $credentials);
						
			$response = curl_exec($session);
			
      //check if logged in correctly
		  if(strlen($response)>0) //needed because of switch statement treating null as zero
       {
							
               switch ($response)
                    {
                        case "0":
                            echo "Logged in successfully!";
                            return true;
                        case "3":
                        case "6":
                        case "9":
                            echo("There is a user with the same credentials logged in the system form another application or browser.");
                            return false;
                        default:
                            return false;
                            echo("Login Failed");
                    }
                    // Result Values:
                    // 0 = Success, (User was successfully registered as logged in)
                    // 1 = UserLimitReached, (The limit of maximum simultaneous logged in users is reached)
                    // 2 = UserNotFound, (User not found in any provider)
                    // 3 = UserLoggedFromDifferentIp, (User is already logged in from different IP address)
                    // 4 = SessionExpired, (Indicates that the user logical session has expired)
                    // 5 = UserLoggedOff, (User have the authentication cookie but does not have logged in the database or user is already logged out.)
                    // 6 = UserLoggedFromDifferentComputer, (More than one users trying to login from the same IP but from different computers.)
                    // 7 = Unknown, (Invalid username or password specified.)
                    // 8 = NeedAdminRights, (User is not administrator to logout other users)
                    // 9 = UserAlreadyLoggedIn, (User is already logged in from different application or browser)
                    // 10 = UserRevoked, (User was revoked. The reason is that the user was deleted or user rights and role membership was changed.)
          } else {
            
            echo "<br>Could not connect to authentication service";

          }
}

function PrintNews($sitefinityHost,$newsServiceUrl, $cookie){   
 
      $news_url = $sitefinityHost.$newsServiceUrl."";
	
	   	$news_session = curl_init($news_url);
  		curl_setopt($news_session, CURLOPT_POST, FALSE);
			curl_setopt($news_session, CURLOPT_COOKIEFILE, $cookie); 
	    curl_setopt($news_session, CURLOPT_RETURNTRANSFER, FALSE);
			$response = curl_exec($news_session);
		
			echo "<pre>";
			echo $response;
			echo "</pre>";
			curl_close($news_session);
		
}
		
		
function CreateNews($sitefinityHost,$newsServiceUrl, $cookie) {    
		    
    $title ="The Title";
    $newurl="The-URL";
    $author="Web Service";
    $sourceurl="http://www.daveconroy.com";
    $sourcename="Dave Conroy";
    $content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque scelerisque elit ut fermentum lobortis. Pellentesque porta nec ante ut luctus. Morbi venenatis, metus vel tempus ornare, lorem justo scelerisque elit, sit amet pretium odio nibh sit amet justo. Nulla ac sollicitudin metus. Duis id leo posuere, euismod magna a, lacinia orci. Pellentesque tincidunt massa posuere orci eleifend congue. Etiam et erat adipiscing, volutpat lorem id, dignissim mi. Nulla rutrum magna eu rhoncus feugiat. Suspendisse cursus fringilla est, et placerat quam lobortis a. Sed vel condimentum ipsum, a molestie lorem.";
   
   //build News Service URL
    $url = $sitefinityHost.$newsServiceUrl."00000000-0000-0000-0000-000000000000/";		
			
    //build the News JSON
      
    $objDateTime = new DateTime('NOW');
    $date=$objDateTime->format('U'); 
    $date=$date."000"; //add milliseconds required by sitefinity
		$myDate= date("U");
		$newsStory = array("Item"=>array(
                               "Title"=>array("PersistedValue"=>"$title",
                                                       "Value"=>"$title"),
                               "UrlName"=>array("PersistedValue"=>"$newurl",
                                                         "Value"=>"$newurl"),    
                               "ItemDefaultUrl"=>array("PersistedValue"=>"$newurl",
                                                         "Value"=>"$newurl"),
                               "Author"=>array("PersistedValue"=>"$author",
                                                         "Value"=>"$author"),                
                               "DefaultUrl"=>"$newurl",  
                               "SourceName"=>"$sourcename", 
                               "SourceSite"=>"$sourceurl", 
                               "Content"=>array("PersistedValue"=>"$content",
                                                         "Value"=>"$content"),    
                               "PublicationDate"=>"/Date($date)/")
										);
   
    //encode news statement 		
    $newsStory =  json_encode($newsStory);
   
    //start HTTP Command session
    $headers = array(
              'Content-Type: application/json',
              'Expect: 100-continue',
              'Accept:'
				
			 );		
		
    $session = curl_init($url);
		curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($session, CURLOPT_COOKIEFILE, $cookie); 
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($session, CURLOPT_POSTFIELDS, $newsStory);
		curl_setopt($session, CURLOPT_COOKIEJAR, $cookie );
		$response = curl_exec($session);
       
  	echo "<pre>";
	  echo $response;
		echo "</pre>";
		curl_close($session);

   
}
        
        
 
function PrintEvents($sitefinityHost,$eventServiceUrl, $cookie){   
 
      $event_url = $sitefinityHost.$eventServiceUrl."";
	
	   	$event_session = curl_init($event_url);

			curl_setopt($event_session, CURLOPT_POST, FALSE);
			curl_setopt($event_session, CURLOPT_COOKIEFILE, $cookie); 
	    curl_setopt($event_session, CURLOPT_RETURNTRANSFER, FALSE);
			$response = curl_exec($event_session);
		
		
			echo "<pre>";
			echo $response;
			echo "</pre>";
      
      curl_close($event_session);
}
		
		
function CreateEvents($sitefinityHost,$eventServiceUrl, $cookie) {    
		    
    $title = "The Title";
    $newurl="The-URL";
    
    //to add multiple events
    $rand=rand(1, 10000);
    $newurl=$rand."-".$newurl;
    
    
    $author="Web Service";
    $city="Boston";
    $country="USA";
    $sourceurl="http://www.daveconroy.com";
    $sourcename="Dave Conroy";
    $content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque scelerisque elit ut fermentum lobortis. Pellentesque porta nec ante ut luctus. Morbi venenatis, metus vel tempus ornare, lorem justo scelerisque elit, sit amet pretium odio nibh sit amet justo. Nulla ac sollicitudin metus. Duis id leo posuere, euismod magna a, lacinia orci. Pellentesque tincidunt massa posuere orci eleifend congue. Etiam et erat adipiscing, volutpat lorem id, dignissim mi. Nulla rutrum magna eu rhoncus feugiat. Suspendisse cursus fringilla est, et placerat quam lobortis a. Sed vel condimentum ipsum, a molestie lorem.";
   
   //build Event Service URL
    $url = $sitefinityHost.$eventServiceUrl."00000000-0000-0000-0000-000000000000/";		
			
     //build News Content URL
      
    $objDateTime = new DateTime('NOW'); //set event start date to right now.
    $date=$objDateTime->format('U'); 
    $date=$date."000"; //add milliseconds required by sitefinity
	
		$eventStory = array("Item"=>array(
                               "Title"=>array("PersistedValue"=>"$title",
                                                       "Value"=>"$title"),
                               "UrlName"=>array("PersistedValue"=>"$newurl",
                                                         "Value"=>"$newurl"),    
                               "ItemDefaultUrl"=>array("PersistedValue"=>"$newurl",
                                                         "Value"=>"$newurl"),
                               "City"=>array("PersistedValue"=>"$city",
                                                         "Value"=>"$city"),
                               "Country"=>array("PersistedValue"=>"$country",
                                                         "Value"=>"$country"),                                                             
                               "DefaultUrl"=>"$newurl",  
                               "SourceName"=>"$sourcename", 
                               "SourceSite"=>"$sourceurl", 
                               "Content"=>array("PersistedValue"=>"$content",
                                                         "Value"=>"$content"),    
                               "EventStart"=>"/Date($date)/",
                               "EventEnd"=>"/Date($date)/",
                               "PublicationDate"=>"/Date($date)/")
										);
										
   
    //encode Event statement 		
    $eventStory =  json_encode($eventStory);
   
    //start HTTP Command session
   $headers = array(
              'Content-Type: application/json',
              'Expect: 100-continue',
              'Accept:'
				
			 );		
		
    $session = curl_init($url);
		curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 
		curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($session, CURLOPT_COOKIEFILE, $cookie); 
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($session, CURLOPT_POSTFIELDS, $eventStory);
		curl_setopt($session, CURLOPT_COOKIEJAR, $cookie );
		$response = curl_exec($session);
       
		echo "<pre>";
		echo $response;
		echo "</pre>";
	  curl_close($session);
   

}
        
