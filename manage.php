<?php

	//if 'posting' then upload comment and id to database
	if (isset($_GET["account"])) {
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
					VALUES ('" . $_GET["account"] . "', '" . $_POST["comment"] . "', TRUE)";

					if ($conn->query($sql) === TRUE) {
					    echo '<div class="container"><div class="alert alert-success alert-dismissable fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Your comment was posted.</div></div>';
					} else {
					    die("Error: " . $sql . "<br>" . $conn->error);
					}

					$conn->close();
		    	}
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
		      <li><a href="index.php">Home</a></li>
		      <li class="active"><a href="manage.php">Admins</a></li>
		    </ul>
		    <?php
		    	if (isset($_GET["account"])) {
		    		echo '
			    		<ul class="nav navbar-nav navbar-right">
			      			<li><a href="manage.php">Back to User List</a></li>
			    		</ul>
		    		';
		    	}
		    ?>
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


		function noscript(strCode){
            var SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
			while (SCRIPT_REGEX.test(strCode)) {
    			strCode = strCode.replace(SCRIPT_REGEX, "");
			}
            //strCode = strCode.replace(/(?:\r\n|\r|\n)/g, '<br />');
            return strCode;
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
		</script>

		<br />
		<div class="container">
			<div class="jumbotron">
		    	<h1>Anonymous Interaction System <span class="glyphicon glyphicon-paperclip" data-toggle="tooltip" title="Created and Maintained by Luigi Pizzolito"></span></h1>
		    	<span class="glyphicons glyphicons-puzzle"></span>
		    	<p>Hello admins! Select users and talk to them.</p>
		  	</div>  
		</div>


		<br />
<?php
if (!(isset($_GET['account']))) {
	echo '
		<span id="unsign">
			<div class="container">
				<div class="panel panel-default">
					<table class="table table-hover table-bordered">
				    <thead>
				      <tr>
				        <th>User</th>
				        <th>Last Active</th>
				        <th>Manage</th>
				      </tr>
				    </thead>
				    <tbody>';
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
							//print users list
	  						$sql = "SELECT user, lastactive FROM users ORDER BY userid DESC";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
							    // output data of each row
							    while($row = $result->fetch_assoc()) {
							    	echo '<tr>
				      						  <td>' . $row["user"] . '</td>
				        					  <td>' . $row["lastactive"] . '</td>
				        					  <td><a href="?account=' . $row["user"] . '" class="small">Manage User</a></td>
				      					  </tr>';
							    }
							} else {
						    	echo '<tr>
			      						  <td colspan="3">No Users.</td>
			      					  </tr>';
							}


	  						$conn->close();

	echo '
				    </tbody>
				  </table>
				</div>
			</div>
			<br />
		</span>
		<script>
			$(document).ready(function(){
			    $(\'[data-toggle="tooltip"]\').tooltip(); 
			});
		</script>
	';
} else {
	  						echo '
	  								<span id="signed">
	  							<div class="container">
								<div class="panel panel-default">
					  				<div class="panel-heading"><h4>Our Interactions</h4></div>
					  				<div class="panel-body">
	  						';
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

							//check if user exists first
							$sql = "SELECT * FROM users WHERE user='" . $_GET["account"] . "'";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) {
							    // check if there is data
							} else {
								header("Location: manage.php");
							}

							//print user interactions
	  						$sql = "SELECT comm, timestamp, isadmin FROM interactions WHERE user='" . $_GET["account"] . "'";
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
	  						echo '
	  									</div>
					  				<!-- <div class="panel-footer">
					  				</div> -->
								</div>
							</div>




			<div id="alert_placeholder">
			</div>
			

			<div class="container">
				<div class="well">
					<form action="';
					echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?account=' . $_GET['account'];
					echo '" method="post" id="subcomm">
						<div class="form-group text-center">
	  						<textarea maxlength="9999" id="commbox" class="form-control input-lg" rows="5" style="resize: vertical;" placeholder="Reply to the users.." data-toggle="tooltip" title="" data-placement="top" name="comment"></textarea>
	  						<script>
							$(document).ready(function(){
							    $(\'[data-toggle="tooltip"]\').tooltip(); 
							});
							</script>
						</div>
					</form>
					<br />
	  				<button class="btn btn-default btn-block" onclick="checkcomm()">Submit</button>
				</div>
			</div>
		</span>
	';
}
?>
		<br /><br /><br />
	</body>
</html>