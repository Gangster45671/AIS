<?php
	//if user cookie is set, insert/update into user list
	if (isset($_GET["newaccount"])) {
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "AIS-Archives";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "INSERT INTO users (user, lastactive)
		VALUES ('" . $_GET["newaccount"] . "', '" . gmdate('Y-m-d h:i:s', time()) . "')";

		if ($conn->query($sql) === TRUE) {
		    //echo "New record created successfully";
		} else {
		    die( "Error: " . $sql . "<br>" . $conn->error);
		}

		$conn->close();
		header("Location: index.php");
	}

		if (isset($_GET["deleteaccount"])) {
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "AIS-Archives";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		//delete user from user list
		$sql = "DELETE FROM users WHERE user='" . $_GET["deleteaccount"] . "'";

		if ($conn->query($sql) === TRUE) {
		    //echo "New record created successfully";
		} else {
		    die( "Error: " . $sql . "<br>" . $conn->error);
		}

		//move all conversations to backup table
		$sql = "INSERT INTO backup SELECT * FROM interactions WHERE user='" . $_GET["deleteaccount"] . "'";

		if ($conn->query($sql) === TRUE) {
		    //echo "New record created successfully";
		} else {
		    die( "Error: " . $sql . "<br>" . $conn->error);
		}

		//delete all conversations from conversation table
		$sql = "DELETE FROM interactions WHERE user='" . $_GET["deleteaccount"] . "'";

		if ($conn->query($sql) === TRUE) {
		    //echo "New record created successfully";
		} else {
		    die( "Error: " . $sql . "<br>" . $conn->error);
		}

		$conn->close();
		header("Location: index.php");
	}
	if (isset($_COOKIE["account"])) {
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "AIS-Archives";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "UPDATE users SET lastactive='" . gmdate('Y-m-d h:i:s', time()) . "' WHERE user='" . $_COOKIE["account"] . "'";

		if ($conn->query($sql) === TRUE) {
		    //echo "Record updated successfully";
		} else {
		    die( "Error updating record: " . $conn->error);
		}

		$conn->close();
	}

	//if 'posting' then upload comment and id to database
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	if (!(empty($_POST["comment"]))) {
    		$servername = "localhost";
			$username = "root";
			$password = "root";
			$dbname = "AIS-Archives";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 

			$sql = "INSERT INTO interactions (user, comm, isadmin)
			VALUES ('" . $_COOKIE["account"] . "', '" . $_POST["comment"] . "', FALSE)";

			if ($conn->query($sql) === TRUE) {
			    echo '<div class="container"><div class="alert alert-success alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Your comment was posted.</div></div>';
			} else {
			    die("Error: " . $sql . "<br>" . $conn->error);
			}

			$conn->close();
    	}
    }
?>
<html>
	<head>
		<title>AIS - Anonymous Interaction System</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- <div class="container">
			<div class="alert alert-danger">
    			<strong>Warning!</strong> This website won't function with out the use of cookies. Please enable them.
  			</div>
		</div> -->

		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
		    <!-- Modal content-->
		    	<div class="modal-content">
			    	<div class="modal-header">
			        	<h4 class="modal-title">Warning! Cookies</h4>
			      	</div>
			      	<div class="modal-body">
			        	<p>This website won't function with out the use of cookies. Please enable them.</p>
			      	</div>
			      	<div class="modal-footer">
						<strong>Once cookies are enabled this message will dissapear.</strong>
		    		</div>
		    	</div>
		    </div>
		</div>
		<nav class="navbar navbar-default navbar-fixed-bottom">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="#">AIS</a>
		    </div>
		    <ul class="nav navbar-nav">
		      <li class="active"><a href="#">Home</a></li>
		      <li><a href="manage.php">Admins</a></li>
		    </ul>
			<form class="navbar-form navbar-left">
		    	<div class="form-group">
		        	<input type="text" class="form-control" placeholder="Ask us a question" id="ask" onkeyup="mailupdate()">
		    	</div>
		      	<a class="btn btn-default" id="askgo" href="mailto:luigi.pizzolito@hotmail.com?subject=Anonymous%20Interaction%20System">
		      		<span class="glyphicon glyphicon-envelope"></span>
		      	</a>
		    </form>
		    <ul class="nav navbar-nav navbar-right">
		    	      <li class="dropdown">
				        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="loged">Logged in as User: <strong>IDIDIDID</strong>
				        <span class="caret"></span></a>
				        <ul class="dropdown-menu">
				          <li><a href="#" data-toggle="modal" data-target="#myModal2">Delete All Interactions</a></li>
				        </ul>
				      </li>
		    	<li class="active dropdown">
		    		
		    	</li>
		    </ul>
		  </div>
		</nav>

		<script type="text/javascript">

		if (!navigator.cookieEnabled) {
			$("#myModal").modal();
		}
		$("#myModal").on('hidden.bs.modal', function () {
	    	if (!navigator.cookieEnabled) {
				$("#myModal").modal();
			}
	    });
		function mailupdate() {
			document.getElementById("askgo").href = "mailto:luigi.pizzolito@hotmail.com?subject=Anonymous%20Interaction%20System&body="+document.getElementById("ask").value;
		}
		function makeid() {
		  var text = "";
		  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

		  for (var i = 0; i < 8; i++)
		    text += possible.charAt(Math.floor(Math.random() * possible.length));

		  return text;
		}
		var exp = new Date();                                   
		exp.setTime(exp.getTime() + (1000 * 60 * 60 * 24 * 31));
		function signup() {
			//window.location.replace("?sign=" + makeid());
			setCookie("account", makeid(), exp);
			window.location.replace("?newaccount="+getCookie("account"));
		}
		// Use this function to retrieve a cookie.
		function getCookie(name){
		var cname = name + "=";               
		var dc = document.cookie;             
		    if (dc.length > 0) {              
		    begin = dc.indexOf(cname);       
		        if (begin != -1) {           
		        begin += cname.length;       
		        end = dc.indexOf(";", begin);
		            if (end == -1) end = dc.length;
		            return unescape(dc.substring(begin, end));
		        } 
		    }
		return null;
		}
		 
		// Use this function to save a cookie.
		function setCookie(name, value, expires) {
		document.cookie = name + "=" + escape(value) + "; path=/" +
		((expires == null) ? "" : "; expires=" + expires.toGMTString());
		}
		 
		// Use this function to delete a cookie.
		function delCookie(name) {
		document.cookie = name + "=; expires=Thu, 01-Jan-70 00:00:01 GMT" +  "; path=/";
		}
		 
		 
		 
		// Function to retrieve form element's value.
		function getValue(element) {
			console.log("ran! get"+element.name);
		var value = getCookie(element.name);
		    if (value != null) element.value = value;
		}
		 
		// Function to save form element's value.
		function setValue(element) {
			console.log("ran! set"+element.name);
		setCookie(element.name, element.value, exp);
		}

		function noscript(strCode){
            var SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
			while (SCRIPT_REGEX.test(strCode)) {
    			strCode = strCode.replace(SCRIPT_REGEX, "");
			}
            //strCode = strCode.replace(/(?:\r\n|\r|\n)/g, '<br />');
            return strCode;
            }

        //setup webpage based on logged in or not
		if (getCookie("account") == null) {
			var ns = document.createElement("style");
			ns.innerHTML = "#signed {display: none;}";
			document.body.appendChild(ns);
			document.getElementById("loged").style.display = "none";
		} else {
			var nss = document.createElement("style");
			nss.innerHTML = "#unsign {display: none;}";
			document.body.appendChild(nss);
			document.getElementById("loged").innerHTML = 'Logged in as User: <strong>'+getCookie("account")+'</strong><span class="caret"></span>';
		}

		function checkcomm() {
			if (document.getElementById("commbox").value == "") {
				//document.getElementById("blankcomm").style.display = "block";
				var bca = document.createElement("div");
				bca.innerHTML = '<div class="container" id="blankcomm"><div class="alert alert-warning alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>No Blank Comments!</strong> Please write something before submitting.</div></div>';
				document.getElementById("alert_placeholder").appendChild(bca);
				} else {
					document.getElementById("commbox").value = noscript(document.getElementById("commbox").value);
					document.getElementById("subcomm").submit();
				}
		}

		function destroysession() {
			var cook = getCookie("account");
			delCookie("account");
			window.location.replace("?deleteaccount="+cook);
		}
		</script>

		<br />
		<?php
			if (isset($_COOKIE["account"])) {
				echo '
					<div class="container">
						<div class="jumbotron">
					    	<h1>Anonymous Interaction System <span class="glyphicon glyphicon-paperclip" data-toggle="tooltip" title="Created and Maintained by Luigi Pizzolito"></span></h1>
					    	<span class="glyphicons glyphicons-puzzle"></span>
					    	<p>Welcome! You can use this interface to submit feedback and/or problems anonymously and get replies.</p>
					  	</div>  
					</div>
				';
			} else {
				echo '
					<div class="container">
						<div class="jumbotron">
					    	<h1>Anonymous Interaction System <span class="glyphicon glyphicon-paperclip" data-toggle="tooltip" title="Created and Maintained by Luigi Pizzolito"></span></h1>
					    	<span class="glyphicons glyphicons-puzzle"></span>
					    	<p>This is a system to submit feedback and/or problems anonymously and get replies. It currently has ';
					    	$servername = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "AIS-Archives";

							// Create connection
							$conn = new mysqli($servername, $username, $password, $dbname);
							// Check connection
							if ($conn->connect_error) {
							    die("Connection failed: " . $conn->connect_error);
							} 

							$usnumb = 0;
	  						$sql = "SELECT user FROM users";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
							    // output data of each row
							    while($row = $result->fetch_assoc()) {
							    	$usnumb = ++$usnumb;
							    }
							    if ($usnumb == 1) {
							    	echo $usnumb . " user.";
							    } else {
							    	echo $usnumb . " users.";
							    }
							} else {
						    	$usnumb = 0;
						    	echo $usnumb . " users.";
							}


	  						$conn->close();
					    	
					    	echo '</p>
					  	</div>  
					</div>
				';
			}
		?>

		<br />
		<span id="unsign">
			<div class="container">
				<button type="button" class="btn btn-block btn-lg btn-info" onclick="signup()">Get Started</button>
			</div>
			<br />
		</span>

		<span id="signed">
			<div class="container">
				<div class="panel panel-default">
	  				<div class="panel-heading"><h4>Our Interactions</h4></div>
	  				<div class="panel-body">
	  					<?php
	  						//get all of comments for user id cookie
					    	$servername = "localhost";
							$username = "root";
							$password = "root";
							$dbname = "AIS-Archives";

							// Create connection
							$conn = new mysqli($servername, $username, $password, $dbname);
							// Check connection
							if ($conn->connect_error) {
							    die("Connection failed: " . $conn->connect_error);
							} 

	  						$sql = "SELECT comm, timestamp, isadmin FROM interactions WHERE user='" . $_COOKIE["account"] . "'";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
							    // output data of each row
							    while($row = $result->fetch_assoc()) {
							    	if ($row["isadmin"] == "1") {
							    		//print all of 'your' comments
							    		echo '
										    <div class="us">
						  						<h1 class="text-right">Us</h1>
							  					<blockquote class="blockquote-reverse">
							    					<p>' . $row["comm"] . '</p>
							    					<footer>This is your comment, posted on ' . $row["timestamp"] . '</footer>
							  					</blockquote>
						  					</div>
					  					';
							    	} else {
							    		//print all of 'our' comments
							    		echo '
										    <div class="you">
						  						<h1>You</h1>
							  					<blockquote>
							    					<p>' . $row["comm"] . '</p>
							    					<footer>This is your comment, posted on ' . $row["timestamp"] . '</footer>
							  					</blockquote>
						  					</div>
					  					';
							    	}
							    }
							} else {
								echo '
									<div class="container text-center">
					  					<h1>Nothing here</h1>
					  					<p>No interactions yet.</p>
					  				</div>
								';
							}


	  						$conn->close();
	  					?>
	  				</div>
	  				<!-- <div class="panel-footer">
	  				</div> -->
				</div>
			</div>
			
			<!-- Modal -->
			<div id="myModal2" class="modal fade" role="dialog">
			  <div class="modal-dialog">

			    <!-- Modal content-->
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			        <h4 class="modal-title">Are you sure that you want to delete all interactions?</h4>
			      </div>
			      <div class="modal-body">
			        <p>The action of deleting all interactions is inreversible. Once they are gone, they are not coming back.</p>
			        <br />
			        <p>The only way of retrieving them is by contacting the webmaster.</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-success" data-dismiss="modal">NO, Keep all my interactions.</button>
			        <button onclick="destroysession()" class="btn btn-danger">YES, Delete all my interactions.</button>
			      </div>
			    </div>

			  </div>
			</div>

			<div id="alert_placeholder">
			</div>
			

			<div class="container">
				<div class="well">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="subcomm">
						<div class="form-group text-center">
	  						<textarea maxlength="9999" id="commbox" class="form-control input-lg" rows="5" style="resize: vertical;" placeholder="Comments, issues, suggestions..." data-toggle="tooltip" title="Remember to be polite. Just because you are anonymous, does not mean that you can be rude, disrespectful, etc..." data-placement="top" name="comment"></textarea>
	  						<script>
							$(document).ready(function(){
							    $('[data-toggle="tooltip"]').tooltip(); 
							});
							</script>
						</div>
					</form>
					<br />
	  				<button class="btn btn-default btn-block" onclick="checkcomm()">Submit</button>
				</div>
			</div>
		</span>
		<br /><br /><br />
	</body>
</html>