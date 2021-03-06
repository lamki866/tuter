<!DOCTYPE html>
<?php include("sesh.php");?>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>tüter</title>	
	<link rel="stylesheet" type="text/css" href="source/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="source/sidebar.css">
	<link rel="stylesheet" type="text/css" href="source/tb.css" />
	<link rel="stylesheet" type="text/css" href="source/buttons.css">
</head>

	<?php 
		if (checkAuth(true) != "") {
	?>

	<?php 	
			include("connect.php");
			$onidid= $_SESSION["onidid"] ;
			$userInfo= $conn->query("SELECT * FROM pinfo WHERE uname='$onidid'");
			$result = $userInfo->fetch_assoc();

	?>

<body class="desktop">
	<div id="wrapper">
		<?php include 'menu.php';?>
		<?php 
		menu($result['fname'], $result['lname']);
		?>
		
		<div id="index-wrap">
				<section id = "profile-edit-header">
					<div class = "container-fluid">
						<div class= "boxed--emph">     <?php echo $result['fname']?>'s</div> profile
					</div>
				</section>
		
		<form action="welcome.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="phil" id="phil" value=<?php echo $onidid ?>>
		
		<div class = "row"> 
			<div class="col-md-4">
				<div class = "panel-body">
					<p>
				    	First Name* : <input type="text" name="fn" id="fn" value="<?php echo $result['fname']?>"required>
						</br>Last Name* : <input type="text" name="ln" id="ln" value="<?php echo $result['lname']?>"required>
						</br></br>
					</p>
					<p>
						Upload Profile Picture  </p> 
						<input type="file" name= "pic" id="pic" accept="image/*">
				</div>
			</div>

			<div class="col-md-4">
					<div class = "panel-body">
					<p>
						I am a
					<select name="year" id="year">
						<option <?php if($result['cstanding']==0){?> selected="selected"<?php } ?> value="0"> -select- </option>
						<option <?php if($result['cstanding']==1){?> selected="selected"<?php } ?> value="1">Freshman</option>
						<option <?php if($result['cstanding']==2){?> selected="selected"<?php } ?> value="2">Sophomore</option>
						<option <?php if($result['cstanding']==3){?> selected="selected"<?php } ?> value="3">Junior</option>
						<option <?php if($result['cstanding']==4){?> selected="selected"<?php } ?> value="4">Senior</option>
						<option <?php if($result['cstanding']==5){?> selected="selected"<?php } ?> value="5">Grad Student</option>
						<option <?php if($result['cstanding']==6){?> selected="selected"<?php } ?> value="6">Other</option>
					</select>
					</br>
					</p>
					<p>
					Account type
					<select name="type" id="type" >
						<option <?php if($result['acctyp']==0){?> selected="selected"<?php } ?> value="0"> -select- </option>
						<option <?php if($result['acctyp']==1){?> selected="selected"<?php } ?> value="1">Student</option>
						<option <?php if($result['acctyp']==2){?> selected="selected"<?php } ?> value="2">Tutor</option>
					</select>
					</p>

					</div>
			</div>	  

			<div class="col-md-4">
					<div class = "panel-body">
						<p>
						Send me sms notifications at  
						<input type="text" class="form-control" name="phn" id="phn" value=<?php echo $result['phonenum']?>> 
						<!--
						<input type="tel" maxlength="10"  name="phn" value=<?php echo $result['phonenum']?>>-->
						</p>
						<p>
							<select name="carrier" id="carrier">
									<option <?php if($result['carrier']==0){?> selected="selected"<?php } ?> value="0">Verizon</option>
									<option <?php if($result['carrier']==1){?> selected="selected"<?php } ?>value="1">AT&T</option>
									<option <?php if($result['carrier']==2){?> selected="selected"<?php } ?>value="2">Sprint</option>
									<option <?php if($result['carrier']==3){?> selected="selected"<?php } ?>value="3">T-Mobile</option>
							</select>
						</p>	
					</div>
			</div>
		</div>	  
					
					<div class=".col-xs-6 .col-md-4">
						<div class = "panel panel-default">
							<div class = "panel-body">
								<p>
								My prefered email:  
								<?php if(isset($result['email'])){ 
									$curemail = $result['email']; 
								} 
								else{ 
									$curemail = "$onidid@oregonstate.edu";
								}
								?>
								<input type="text" class="form-control" name="email" id="email" value=<?php echo $curemail;?>>
								</p>
							</div>
						</div>
					</div>	  
						
					<div class=".col-xs-6 .col-md-4">
						<p>
						Profile Description
							<?php 
							if(file_exists("userfolders/$onidid/description.txt")){
							$myfile = fopen("./userfolders/$onidid/description.txt","r"); 
							$text = fgets($myfile);
							}
							else{
							$text = "";
							}

							?>
							<textarea class="form-control" rows="10" name = "description" id="description"><?php echo $text; ?></textarea>
							<?php fclose($myfile); ?>
						</p>
					</div>		

					<div class="col-md-8">
						<button type="submit" name="update" class="button button--ujarak button--size-m button--border-medium button--text-thick">submit</button>
					</div>						
				</form>
				

				<div class = "col-md-8">
					<p>Your first name, academic year, and profile picture are publicly viewable.
					</br></br>
					Phone numbers and last names are confidential and will only be used to send alerts and notifications.
					</br></br>
					We will not share or sell your private information to third parties.
					</p>
				</div>
		</div>
	</div>
			<script src="source/menu_class.js"></script>
			<script src="source/main_menu.js"></script>
		<?php 
		
		mysqli_close($conn); 
		} else{ ?>
			<META http-equiv="refresh" content="0;URL=edit_profile.php">
		<?php } ?>
</body>
</html>
