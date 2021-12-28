<?php
	$count=0;
	$output="";
	$card1="";
	$card2="";
	$order=0;
	$img_array=array();
	$failed_array=array();
	if(isset($_POST["submit"]))
	{
		if(isset($_FILES["m_files"]['tmp_name']) && isset($_POST["system_count"]))
		{
			$count=count($_FILES["m_files"]["name"]);
			$system_count=$_POST["system_count"];
			$i=0;
			$array=array();
			$rand_system=rand(0,$system_count);
			$rand_file=rand(0,$count);
			$random=true;
			for($pk=0;$pk<$system_count;$pk++)
			{
				$card1="";
				$i=0;
				$order=0;
				foreach($_FILES["m_files"]["name"] as $key=>$filename)
				{
					$uploadDir ="upload/";
					$tempFile   = $_FILES['m_files']['tmp_name'][$key];
					$targetFile = $uploadDir.time().$filename;
					if($pk!=$rand_system)
					{
						if(move_uploaded_file($tempFile, $targetFile))
						{
							
								$img_array[$key]=$targetFile;
							$order=1;
							$card1.="<tr>
										<td>".($key+1)."</td>
										<td>".$filename."</td>
										<td>Sent</td>
									</tr>";
							$i++;
						}
						else
						{
							$card1.="<tr>
										<td>".($key+1)."</td>
										<td>".$filename."</td>
										<td>Failed</td>
									</tr>";
						}
					}
					else
					{
						if($key<$rand_file)
						{
							if(move_uploaded_file($tempFile, $targetFile))
							{
								$order=1;
								$card1.="<tr>
											<td>".($key+1)."</td>
											<td>".$filename."</td>
											<td>Sent</td>
										</tr>";
								$i++;
							}
							else
							{
								$card1.="<tr>
											<td>".($key+1)."</td>
											<td>".$filename."</td>
											<td>Failed</td>
										</tr>";
							}
						}
					}
					//$status,$system no;
					$failed_array[$pk][$key]=$order.",".$pk;
				}
					$button="";
					if(($count-$i)>0 && count($img_array)>0)
					{
						$button="<input type='submit' name='retry' value='RETRY'>";
					}
					if($order==1)
					{	
						$track_img=explode("\n",shell_exec("Netsh WLAN show interfaces"));
						$card1="<div class='card auto'>
										<h2>System : ".($pk+1)."</h2>
										<h3>Total Files count : ".$count."<span style='float:right'>Failed : ".($count-$i)."</span></h3>
										<table id='track'>
											<tr>
												<th>S.No</th>
												<th>File Name</th>
												<th>Status</th>
											</tr>
											".$card1."
										</table>
										".$button."
									</div>";
									
					}	
					else if($order==0)
					{	
						$track_img=explode("\n",shell_exec("Netsh WLAN show interfaces"));
						$card1="<div class='card auto'>
										<h2>System : ".($pk+1)."</h2>
										<h3>Total Files count : ".$count."<span style='float:right'>Failed : ".($count-$i)."</span></h3>
										<table id='track'>
											<tr>
												<th>S.No</th>
												<th>File Name</th>
												<th>Status</th>
											</tr>
											".$card1."
										</table>
										".$button."
									</div>";
									
					}	
					$output.=$card1;
			}				
			foreach($track_img as $img)
			{
				if(!empty($img))
				{
					$card2.="<tr>
								<td>".$img."</td>
						</tr>";
				}
			}
			$card2="<div class='card auto'>
						<h2>Network Status</h2>
						<table id='track'><tr><th>View</th></tr>".$card2."</table>
					</div>";
				$output.="<input type='hidden' name='img_array' value='".serialize($img_array)."'>
							<input type='hidden' name='system_count_re' value=".$system_count.">
							<input type='hidden' name='failed_array' value='".serialize($failed_array)."'>".$card2;
			}
		}
		if(isset($_POST["retry"]))
		{
			$img_array=unserialize($_POST["img_array"]);
			$failed_array=unserialize($_POST["failed_array"]);
			$system_count=$_POST["system_count_re"];
			$count=count($img_array);
			$system_no=0;
			for($pk=0;$pk<$system_count;$pk++)
			{
				$card1="";
				$i=0;
				$order=0;
				foreach($img_array as $key=>$tempFile)
				{
					$system_no=(explode(",",$failed_array[$pk][$key])[1]);
					$uploadDir ="upload/";
					$filename=basename($tempFile);
					$targetFile = $uploadDir.time().$filename;
					if(explode(",",$failed_array[$pk][$key])[0]==0)
					{
						if(copy($tempFile, $targetFile))
						{
							
								$img_array[$key]=$targetFile;
							
							$order=1;
							$card1.="<tr>
										<td>".($key+1)."</td>
										<td>".$filename."</td>
										<td>Sent</td>
									</tr>";
							$i++;
						}
						else
						{
							
							$card1.="<tr>
										<td>".($key+1)."</td>
										<td>".$filename."</td>
										<td>Failed</td>
									</tr>";
						}
					}
					else
					{
						$order=1;
						$i++;
						$card1.="<tr>
										<td>".($key+1)."</td>
										<td>".$filename."</td>
										<td>Sent</td>
									</tr>";
					}
					//$status,$system no;
					$failed_array[$pk][$key]=$order.",".$pk;
				}
					$button="";
					if(($count-$i)>0 && count($img_array)>0)
					{
						$button="<input type='submit' name='retry' value='RETRY'>";
					}
					if($order==1)
					{	
						$track_img=explode("\n",shell_exec("Netsh WLAN show interfaces"));
						$card1="<div class='card auto'>
										<h2>System : ".($system_no+1)."</h2>
										<h3>Total Files count : ".$count."<span style='float:right'>Failed : ".($count-$i)."</span></h3>
										<table id='track'>
											<tr>
												<th>S.No</th>
												<th>File Name</th>
												<th>Status</th>
											</tr>
											".$card1."
										</table>
										".$button."
									</div>";
									
					}	
					else if($order==0)
					{	
						$track_img=explode("\n",shell_exec("Netsh WLAN show interfaces"));
						$card1="<div class='card auto'>
										<h2>System : ".($system_no+1)."</h2>
										<h3>Total Files count : ".$count."<span style='float:right'>Failed : ".($count-$i)."</span></h3>
										<table id='track'>
											<tr>
												<th>S.No</th>
												<th>File Name</th>
												<th>Status</th>
											</tr>
											".$card1."
										</table>
										".$button."
									</div>";
									
					}	
					$output.=$card1;
			}				
			foreach($track_img as $img)
			{
				if(!empty($img))
				{
					$card2.="<tr>
								<td>".$img."</td>
						</tr>";
				}
			} 
			$card2="<div class='card auto'>
						<h2>Network Status</h2>
						<table id='track'><tr><th>View</th></tr>".$card2."</table>
					</div>";
				$output.="<input type='hidden' name='img_array' value='".serialize($img_array)."'>
							<input type='hidden' name='system_count' value='".$system_count."'>
							<input type='hidden' name='failed_array' value='".serialize($failed_array)."'>".$card2;
		}
		
?>
<!DOCTYPE html>
<html>
<head>
<title>Track</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
  <h1 style="color:red">DETECTING NODE FAILURES IN MOBILE WIRELESS NETWORK</h1>
  <h4 style="color:white">Develoed by Sangeetha.</h4>
</div>

<div class="topnav">
  <a href="index.php">Home</a>
  <a href="about.php" >About</a>
  <a href="track.php" class="active">Track</a>
</div>
		<form class="form-horizontal tasi-form" autocomplete="off" name="addform" action="<?php echo $_SERVER["REQUEST_URI"]?>" method="post" enctype="multipart/form-data" >
	<?=$output?>
    <div class="card auto">
			<h2>Test cases: [ With virtual system ]</h2>
		<h3>Upload Files and moniter the system</h3>
		<label for="system_count">No.of Systems connected</label>
		<select name="system_count" >
			<option value=1>1</option>
			<option value=2>2</option>
			<option value=3>3</option>
			<option value=4>4</option>
			<option value=5>5</option>
		</select>
		<input type="file" name="m_files[]"  multiple >
		<input type="submit" name="submit" value="UPLOAD" >
	</form>
    </div>

</body>
</html>
