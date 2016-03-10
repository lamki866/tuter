<!DOCTYPE html>
<html>
<head>
	<title>Sending your information.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="source/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="source/sidebar.css">
	<link rel="stylesheet" type="text/css" href="source/tb.css" />
	<link rel="stylesheet" type="text/css" href="source/buttons.css">
</head>
<body class="desktop">
	<?php 	
	//check to see if you manually go to welcome.php you can alter things
	//have some alerts and things like "Are you sure that you want to do this" before they say they aren't a tutor anymore and whatnot
	//if they say that they are not a tuter anymore, what to do with their data?
	//look into there being 2 copies in csv file
	//look into validation
	include("sesh.php");
		if (checkAuth(true) != "") {
			include("connect.php");
			$onidid=  "sprousem";//this needs to work later $_SESSION["onidid"] ;
			$tuterID= $_POST["tutor"];
			$userInfo= $conn->query("SELECT * FROM pinfo WHERE uname='$onidid'");
			$result = mysqli_fetch_array($userInfo);
			$tuterInfo= $conn->query("SELECT * FROM tuters WHERE uname='$tuterID'");
			$tuterresult = mysqli_fetch_array($tuterInfo);
			/*
			* Here goes the code for determining if the user has already rated this user
			* This user should only be a tutor
			* Make sure we correctly handle if they have rated
			*/
			//Assuming they haven't rated before
			$numraters = $tuterresult["numraters"];
			$rate = $tuterresult["rating"];
			$rating = $_POST["rate"];
			if ($numraters == 0)
			{
				//just set that rate
				if (!$conn->query("UPDATE tuters SET rating='$rating', numraters='1' WHERE uname='$tuterID'"))
				{
					printf("Welp that didn't work.");
				}
				else
				{
					
					$myfile = fopen("userfolders/$onidid/ratings.csv", "a") or die("Could not open file!");
					//should come here no matter what
					$array = array
					(
					"$tuterID,$rating"
						);
					echo "Rawr2";
					if(!fputcsv($myfile,explode(',',$array[0])))
						echo "The put failed2.";//might want to validate this somewhere
				
					fclose($myfile);
				}
			}
			else
			{
				
				if (!$myfile = fopen("userfolders/$onidid/ratings.csv", "x+"))
				{
					$myfile = fopen("userfolders/$onidid/ratings.csv", "r+") or die("Could not open file!");
				}
				$alreadyExisted = 0;
				$newCSV = array();			
				while (!feof($myfile))
				{
					$currentRow = fgetcsv($myfile);
					echo "The current row is: ";
					print_r($currentRow);
					echo "<BR>";
					if($currentRow[0] == $tuterID)//if the user has already rated this user, then they already have them inside the file
					{
						$alreadyExisted = 1;
						$oldrating = $currentRow[1];
						echo "Rawr3";
						echo "About to put into row: ";
						print_r($currentRow);
						echo "<BR>";
						
						//fputcsv($myfile,$currentRow);
						array_push($newCSV, "$tuterID,$rating");
						//we are now altering the average
						echo "<br>Rate: $rate <br>";
						echo "numraters: $numraters <br>";
						echo "Rating: $rating <br>";
						echo "oldrating: $oldrating <br>";
						$newRate = (($rate*$numraters)+$rating-$oldrating)/$numraters;
						echo "The new rate should be $newRate <br>";
						if (!$conn->query("UPDATE tuters SET rating='$newRate', numraters='$numraters' WHERE uname='$tuterID'"))
						{
							printf("Welp that didn't work.");
						}
					
					}
					else
						array_push($newCSV, "$currentRow[0],$currentRow[1]");
				}
				if (!$alreadyExisted)
				{
					$array = array
					(
					"$tuterID,$rating"
						);
					echo "Rawr4";
					if(!fputcsv($myfile,explode(',',$array[0])))
						echo "The put failed4.";//might want to validate this somewhere
					else
					{
						$newnum = $numraters+1;
						$newRate = (($rate*$numraters)+$rating)/$newnum;
						echo "The new rate should be $newRate";
						if (!$conn->query("UPDATE tuters SET rating='$newRate', numraters='$newnum' WHERE uname='$tuterID'"))
						{
							printf("Welp that didn't work.");
						}
					}
				}
				else
				{
					//now rewrite the new file
					fclose($myfile);
					$myfile = fopen("userfolders/$onidid/ratings.csv", "w");
					echo "Here is the new csv: <br>";
					print_r($newCSV);
					echo "<br>";
					$temp = array_pop($newCSV);
					foreach ($newCSV as $line) {
						echo "This is the line: $line<br>";
						if(!fputcsv($myfile, explode(',', $line)))
							echo "RAAAAAAAAAAAAWR";
					}
				}
				fclose($myfile);
			}
			
	?>

<?php
echo "You gave user: ".$_POST["tutor"]." a ".$_POST["rate"]."!";
?>
<?php }else{ ?>
	<META http-equiv="refresh" content="0;URL=handlerate.php">
<?php } ?>
</body>
</html>