<?php

$lang="es";
//submit_rating.php
include($_SERVER['DOCUMENT_ROOT']."/includes/conexion.php");



if(isset($_POST["rating_data"]))
{
	 $var = $_POST['user_email'];
	 $emailfor = strtolower($var);
	 

	$data = array(
	    ':lang'             =>  $lang,
		':user_name'		=>	$_POST["user_name"],
		':user_email'		=>  $emailfor,
		':user_rating'		=>	$_POST["rating_data"],
		':user_review'		=>	$_POST["user_review"],
		':user_avatar'		=>	$_POST["user_avatar"],
		);



$query = "
	INSERT INTO avatrade
	(lang, user_name, user_email, user_rating, user_review, user_avatar, datetime) 
	VALUES (:lang, :user_name, :user_email, :user_rating, :user_review, :user_avatar, NOW())
	";

	$statement = $pdo->prepare($query);

	$statement->execute($data);

	echo "Your Review & Rating Successfully Submitted";

}

if(isset($_POST["action"]))
{
	$average_rating = 0;
	$total_review = 0;
	$five_star_review = 0;
	$four_star_review = 0;
	$three_star_review = 0;
	$two_star_review = 0;
	$one_star_review = 0;
	$total_user_rating = 0;
	$review_content = array();



 
	$query = "
	SELECT * FROM avatrade 
	ORDER BY review_id DESC 
	";

	$result = $pdo->query($query, PDO::FETCH_ASSOC);

	foreach($result as $row)
	{
		$review_content[] = array(
			'user_name'		=>	$row["user_name"],
			'user_review'	=>	$row["user_review"],
			'rating'		=>	$row["user_rating"],
			'user_avatar'   => $row["user_avatar"],
			
		);

		if($row["user_rating"] == '5')
		{
			$five_star_review++;
		}

		if($row["user_rating"] == '4')
		{
			$four_star_review++;
		}

		if($row["user_rating"] == '3')
		{
			$three_star_review++;
		}

		if($row["user_rating"] == '2')
		{
			$two_star_review++;
		}

		if($row["user_rating"] == '1')
		{
			$one_star_review++;
		}

		$total_review++;

		$total_user_rating = $total_user_rating + $row["user_rating"];

	}
   
   if ($total_user_rating == 0 && $total_review == 0)
   {
	    $average_rating= '0';
	    
	   }else{
	        $average_rating = $total_user_rating / $total_review;
	        
	    }

   
  	$output = array(
		'average_rating'	=>	number_format($average_rating, 1),
		'total_review'		=>	$total_review,
		'five_star_review'	=>	$five_star_review,
		'four_star_review'	=>	$four_star_review,
		'three_star_review'	=>	$three_star_review,
		'two_star_review'	=>	$two_star_review,
		'one_star_review'	=>	$one_star_review,
		'review_data'		=>	$review_content,
		
	
	);

	echo json_encode($output);

}

?>