<?php

$defaultTimeZone='America/Bogota';
if(date_default_timezone_get()!=$defaultTimeZone) date_default_timezone_set($defaultTimeZone);
include_once ($_SERVER['DOCUMENT_ROOT']."/includes/conexion.php");
require ($_SERVER['DOCUMENT_ROOT']."/includes/submit_rating.php");

function _date($format="r", $timestamp=false, $timezone=false)
{
    $userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT+5');
    $gmtTimezone = new DateTimeZone('GMT+5');
    $myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
    $offset = $userTimezone->getOffset($myDateTime);
    return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
}
//likes

require_once ($_SERVER['DOCUMENT_ROOT']."/includes/Post.class.php");
$post = new Post();
// Get posts data

$posts = $post->getRows();

$id = new Post();
// Get posts data

$id = $id->getRows();
//endslikes

function url($text)
{
	$text= html_entity_decode($text);
	$text = "".$text;
	$text = preg_replace('/(https{0,1}:\/\/[\w\-\.\/#?&=]*)/', '<a href="$1" target="_blank">($1)</a>',$text);
	return $text;
}

$sql = "SELECT * FROM avatrade WHERE lang='$lang' ORDER BY review_id DESC";
$sentence_a =$pdo-> prepare($sql);
$sentence_a-> execute();

$res = $sentence_a ->fetchAll();

$per_page= 5;

$totals = $sentence_a->rowcount();

$pages = $totals /$per_page;
$pages = ceil($pages);

$start=($_GET['page_reviews']-1)*$per_page;



if(!$_GET){
	header('location:index.php?page_reviews=1');
	}

if($totals >= 1){
	
if($_GET['page_reviews']>$pages){
	header('location:index.php?page_reviews=1');
}
if($_GET['page_reviews']<=0){
	header('location:index.php?page_reviews=1');
}
 }


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'Just now';
}

$sql_rws= "SELECT * FROM avatrade WHERE lang='$lang' ORDER BY review_id DESC LIMIT :iniciar, :narticulos";
$sentence_rws = $pdo->prepare($sql_rws);
$sentence_rws->bindParam(':iniciar',$start, PDO::PARAM_INT);
$sentence_rws->bindParam(':narticulos',$per_page, PDO::PARAM_INT);
$sentence_rws->execute();
sleep(1);
$reviews =$sentence_rws->fetchAll();


?>
<!DOCTYPE HTML>
<html>
<head>
   <link rel="stylesheet" type="text/css" href="/css/style.css" /> 
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.10.0/js/md5.js"></script>
   <link rel="shortcut icon" href="/img/favicon.ico">
   <script src="/js/shorten.js"></script>	
 </head>
 <body>

 <div class="container">
 <div class="card" id="result">
    		<div class="card-header">AWESOME TITLE</div>
    		<div class="card-body">
    			<div class="row">
    				<div class="col-sm-4 text-center">
    					<h1 class="text-warning mt-4 mb-4">
    						<b><span id="average_rating">0.0</span> / 5</b>
    					</h1>
    					<div class="mb-3">
    						<i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
	    				</div>
    					<h3><span id="total_review"><?php if($totals==0){$numerate="No"; echo $numerate;} else { echo $totals;} ?></span> <?php if($totals ==1){$review="Review"; }else{ $review="Reviews";} echo $review  ?></h3>
    				</div>
    				<div class="col-sm-4">
    					<p>
                            <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>

                            <div class="progress-label-right">(<span id="total_five_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                            </div>
                        </p>
    					<p>
                            <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_four_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_three_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_two_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                            </div>               
                        </p>
    					<p>
                            <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                            
                            <div class="progress-label-right">(<span id="total_one_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                            </div>               
                        </p>
    				</div>
    				<div class="col-sm-4 text-center">
    					<h3 class="mt-4 mb-3">Write Review Here</h3>
    					<button type="button" name="add_review" id="add_review" class="btn btn-primary">Review</button>
    				</div>
    			</div>
    		</div>
    	</div>
    	<div class="mt-5" id="review_content"></div>
    </div></div>
   <section class="reviews">
	<?php foreach($reviews as $rws):  ?>
		<div class="container mt-5">
			<div class="row mb-3">
				<div class="col-sm-1"> <img class="avatar" src="<?php echo $rws['user_avatar']?>" alt="Avatar de <?php echo $rws['user_name']?> en Gravatar.com" />
					<h6 class="text-center"><?php echo $rws['user_name']?></h6> </div>
				<div class="col-sm-11">
					<div class="card">
						<div class="card-header">By <b><?php echo $rws['user_name']?></b> </div>
						<div class="card-body">
							<div class="rating-stars ">
								<div class="grey-stars"></div>
								<div class="filled-stars" style="width:<?= $rws['user_rating'] * 20 ?>%"> </div>
							</div>
							<br>
							<div class="comment-small">
								<?php echo url($rws['user_review'])?>
							</div>
						</div>
						<div class="card-footer">
							<p class="alignright">
								<?php echo time_elapsed_string($rws['datetime'])?>
									<p class="alignleft">
										<p class="pull-left">
											<!-- Like button --><span class="fa fa-thumbs-up" onClick="cwRating(<?php echo $rws['review_id']; ?>, 1, 'like_count<?php echo $rws['review_id']; ?>')"></span>&nbsp;
											<!-- Like counter --><span class="counter" id="like_count<?php echo $rws['review_id']; ?>"><?php echo $rws['like_num']; ?></span>&nbsp;&nbsp;&nbsp;
											<!-- Dislike button --><span class="fa fa-thumbs-down" onClick="cwRating(<?php echo $rws['review_id']; ?>, 0, 'dislike_count<?php echo $rws['review_id']; ?>')"></span>&nbsp;
											<!-- Dislike counter --><span class="counter" id="dislike_count<?php echo $rws['review_id']; ?>"><?php echo $rws['dislike_num']; ?></span>
											<!--Edit---><span name="edit_review" value="edit_review" id="<?php echo $rws['review_id']?>" class="verify_data" data-toggle="modal" data-target="#verify_modal"><i class="fas fa-edit edit_data"></i></span> </p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach ?>
</section>
<?php
				if (!empty($pages)) {?>
	<div class="row clearfix">
		<div class="col-sm-12 text-center"> <span class="loading" style="display: none;"><img src="/img/more.gif" alt="cargando"></span> </div>
	</div>
	<section class="pagination">
		<div class="container mt-5">
			<nav data-pagination>
				<ul class="pagination ">
					<li class="page-item prev<?php echo $_GET['page_reviews']<=1? 'disabled' : ''?>"><a class="page-link" href="index.php?page_reviews=<?php echo ''.$_GET['page_reviews']-1 ?>">Anterior</a></li>
					<?php  for($i=0;$i<$pages;$i++): ?>
						<li class="page-item <?php echo $_GET['page_reviews']==$i+1 ? 'active' : ''  ?>">
							<a class="page-link" href="index.php?page_reviews=<?php echo $i+1?>">
								<?php echo $i+1?>
							</a>
						</li>
						<?php endfor?>
							<?php ?>
								<li class="page-item next<?php echo $_GET['page_reviews']>= $pages? 'disabled' : ''?>"><a class="page-link" href="index.php?page_reviews=<?php echo ''.$_GET['page_reviews']+1 ?>">Siguiente</a></li>
				</ul>
			</nav>
		</div>
		<?php } else { ?>
			<div class="container my-5">
				<div class="card text-center">
					<div class="card-header"> Seems there are no Reviews yet! </div>
					<div class="card-body">
						<h5 class="card-title">Be the first person on review this item!</h5>
						<p class="card-text">With supporting text below as a natural lead-in to additional content.</p> <a data-target="#review_modal" data-toggle="modal" name="add_review" id="add_review" class="btn btn-primary">Review Now</a>
						<div class="nothing-yet"></div>
					</div>
					<div class="card-footer text-muted"> </div>
				</div>
			</div>
			<?php } ?>
	</section>
	<div></div>
	</body>

	</html>
	<div id="review_modal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Submit Review</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body"> <img id="avatar" src="https://s.gravatar.com/avatar/4960b5c8e92e02c3cc04280e7b0b259a?s=80" />
					<h4 class="text-center mt-2 mb-4">
	        		<i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
	        	</h4>
					<div class="form-group">
						<input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Your Name" required /> </div>
					<div class="form-group">
						<input type="text" name="user_email" id="user_email" class="form-control" placeholder="your-mail@domain.com" required /> </div>
					<div class="form-group">
						<textarea name="user_review" id="user_review" class="form-control" placeholder="Type Review Here" required></textarea>
					</div>
					<div class="form-group text-center mt-4">
						<button type="button" class="btn btn-primary" id="save_review">Submit</button>
					</div>
					<label for="email">Enter a valid email, it's used to show your avatar and allows you to edit your rating in the future.</label>
				</div>
			</div>
		</div>
	</div>
	<!-- Edit Modal -->
	<div id="edit_modal" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">	Edit your Review</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body"> <img id="avatar" src="https://s.gravatar.com/avatar/4960b5c8e92e02c3cc04280e7b0b259a?s=80" />
					<h4 class="text-center mt-2 mb-4">
	        		<i class="fas fa-star star-light edit_star mr-1" id="edit_star_1" data-rating="1"></i>
                    <i class="fas fa-star star-light edit_star mr-1" id="edit_star_2" data-rating="2"></i>
                    <i class="fas fa-star star-light edit_star mr-1" id="edit_star_3" data-rating="3"></i>
                    <i class="fas fa-star star-light edit_star mr-1" id="edit_star_4" data-rating="4"></i>
                    <i class="fas fa-star star-light edit_star mr-1" id="edit_star_5" data-rating="5"></i>
	        	</h4>
					<div class="form-group">
						<input type="text" name="user_name" id="u_name" class="form-control" placeholder="Enter Your Name" required /> </div>
					<div class="form-group">
						<input type="text" name="user_email" id="u_email" class="form-control" placeholder="your-mail@domain.com" required /> </div>
					<div class="form-group">
						<textarea name="user_review" id="u_review" class="form-control" placeholder="Type Review Here" required></textarea>
					</div>
					<div class="form-group text-center mt-4">
						<button type="button" class="btn btn-primary" id="update_review">Submit</button>
					</div>
					<label for="email">Enter a valid email, it's used to show your avatar and allows you to edit your rating in the future.</label>
				</div>
			</div>
		</div>
	</div>
	<!--VALIDATION ------>
	<div class="modal fade" id="verify_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Edit your Review</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body">
					<label for="email">Before you can edit, we need to verify if was you who wrote the review, please enter your email and press verify</label>
					<div class="form-group">
						<input type="text" name="user_mail" id="user-email_input" class="form-control" placeholder="your-mail@domain.com" required /> </div><span id="fb"></span> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="" onClick="edit_coment(<?php echo $rws['review_id']; ?>)" name="validate" id="validate" class="btn btn-primary">Verify</button>
				</div>
			</div>
		</div>
	</div>
	<style>
	.modal img {
		vertical-align: middle;
		width: 90px;
		height: 90px;
		border-radius: 50%;
		border: 2px solid #1fb864;
		margin-left: 190px;
		margin-right: 190px;
	}
	
	.nothing-yet {
		background-image: url(<?php echo $fondos[array_rand($fondos)]?>);
		height: 400px;
		background-position: center;
	}
	
	.modal-title {
		text-align: center;
	}
	
	.pull-left {
		color: #11703b;
	}
	
	.glyphicon-thumbs-up:hover {
		color: #008000;
		cursor: pointer;
	}
	
	.glyphicon-thumbs-down:hover {
		color: #E10000;
		cursor: pointer;
	}
	
	.rating-stars {
		position: relative;
		vertical-align: baseline;
		color: #b9b9b9;
		line-height: 10px;
		float: left;
	}
	
	.grey-stars {
		height: 100%;
	}
	
	.filled-stars {
		position: absolute;
		left: 0;
		top: 0;
		height: 100%;
		overflow: hidden;
		color: #f7bf17;
	}
	
	.filled-stars:before,
	.grey-stars:before {
		content: "\2605\2605\2605\2605\2605";
		font-size: 19px;
		letter-spacing: 0px;
		line-height: 18px;
	}
	
	a {
		color: #0254EB
	}
	
	a:visited {
		color: #0254EB
	}
	
	a.morelink {
		text-decoration: none;
		outline: none;
	}
	
	.morecontent span {
		display: none;
	}
	
	.progress-label-left {
		float: left;
		margin-right: 0.5em;
		line-height: 1em;
	}
	
	.progress-label-right {
		float: right;
		margin-left: 0.3em;
		line-height: 1em;
	}
	
	.star-light {
		color: #e9ecef;
	}
	
	.alignleft {
		float: left;
	}
	
	.alignright {
		float: right;
	}
	
	.avatar {
		vertical-align: middle;
		width: 90px;
		height: 90px;
		border-radius: 50%;
		border: 2px solid #1fb864;
		margin-top: 45px;
		margin-bottom: 25px;
	}
	
	#one_star_progress {
		background-color: #F70E0A!important;
	}
	
	#two_star_progress {
		background-color: #F70E0A!important;
	}
	
	#three_star_progress {
		background-color: #ffc107!important;
	}
	
	#four_star_progress {
		background-color: #ffc107!important;
	}
	
	#five_star_progress {
		background-color: #1AB608!important;
	}
	
	ul.pagination li a {
		border-radius: 45px;
	}
	
	ul.pagination li a prev {}
	
	ul.pagination li a next {}
	
	ul.pagination li a.active {
		border-radius: 45px;
	}
	</style>
	<script>
	var gravatar_image_url;
	$('#user_email').on('change', function() {
		// Obtener correo electrónico
		email = $(this).val();
		console.log("user_email:", email);
		// Actualiza la variable con la URL obtenida
		gravatar_image_url = get_gravatar_image_url(email, 200);
		$('#user_avatar').html(gravatar_image_url);
		$('#image').attr('src', gravatar_image_url);
		document.getElementById("avatar").src = gravatar_image_url;
	});

	function get_gravatar_image_url(email, size, default_image, allowed_rating, force_default) {
		email = typeof email !== 'undefined' ? email : 'john.doe@example.com';
		size = (size >= 1 && size <= 2048) ? size : 80;
		default_image = typeof default_image !== 'undefined' ? default_image : 'mm';
		allowed_rating = typeof allowed_rating !== 'undefined' ? allowed_rating : 'g';
		force_default = force_default === true ? 'y' : 'n';
		return("https://secure.gravatar.com/avatar/" + md5(email.toLowerCase().trim()) + "?size=" + size + "&default=" + encodeURIComponent(default_image) + "&rating=" + allowed_rating + (force_default === 'y' ? "&forcedefault=" + force_default : ''));
	}
	$(document).ready(function() {
		var rating_data = 0;
		$('#add_review').click(function() {
			$('#review_modal').modal('show');
		});
		$(document).on('mouseenter', '.submit_star', function() {
			var rating = $(this).data('rating');
			reset_background();
			for(var count = 1; count <= rating; count++) {
				$('#submit_star_' + count).addClass('text-warning');
			}
		});

		function reset_background() {
			for(var count = 1; count <= 5; count++) {
				$('#submit_star_' + count).addClass('star-light');
				$('#submit_star_' + count).removeClass('text-warning');
			}
		}
		$(document).on('mouseleave', '.submit_star', function() {
			reset_background();
			for(var count = 1; count <= rating_data; count++) {
				$('#submit_star_' + count).removeClass('star-light');
				$('#submit_star_' + count).addClass('text-warning');
			}
		});
		$(document).on('click', '.submit_star', function() {
			rating_data = $(this).data('rating');
		});
		$('#save_review').click(function() {
			var user_name = $('#user_name').val();
			var user_review = $('#user_review').val();
			var user_email = $('#user_email').val();
			var user_avatar = gravatar_image_url;
			if(user_name == '') {
				alert("Please Set an User Name");
				return false;
			} else if(user_review == '') {
				alert("Please writte your review");
				return false;
			} else if(user_email == '') {
				alert("Please writte your valid email");
				return false;
			} else if(rating_data == '') {
				alert("Please set a score");
				return false;
			} else {
				$.ajax({
					url: "/includes/submit_rating.php",
					method: "POST",
					data: {
						rating_data: rating_data,
						user_name: user_name,
						user_review: user_review,
						user_email: user_email,
						user_avatar: user_avatar
					},
					success: function(data) {
						$('#review_modal').fadeOut();
						window.location.reload();
						load_rating_data();
						alert(data);
					}
				})
			}
		});
		load_rating_data();

		function load_rating_data() {
			$.ajax({
				url: "/includes/submit_rating.php",
				method: "POST",
				data: {
					action: 'load_data'
				},
				dataType: "JSON",
				success: function(data) {
					$('#average_rating').text(data.average_rating);
					//$('#total_review').text(data.total_review);
					var count_star = 0;
					$('.main_star').each(function() {
						count_star++;
						if(Math.ceil(data.average_rating) >= count_star) {
							$(this).addClass('text-warning');
							$(this).addClass('star-light');
						}
					});
					$('#total_five_star_review').text(data.five_star_review);
					$('#total_four_star_review').text(data.four_star_review);
					$('#total_three_star_review').text(data.three_star_review);
					$('#total_two_star_review').text(data.two_star_review);
					$('#total_one_star_review').text(data.one_star_review);
					$('#five_star_progress').css('width', (data.five_star_review / data.total_review) * 100 + '%');
					$('#four_star_progress').css('width', (data.four_star_review / data.total_review) * 100 + '%');
					$('#three_star_progress').css('width', (data.three_star_review / data.total_review) * 100 + '%');
					$('#two_star_progress').css('width', (data.two_star_review / data.total_review) * 100 + '%');
					$('#one_star_progress').css('width', (data.one_star_review / data.total_review) * 100 + '%');
					if(data.review_data.length > 0) {
						let html = '';
						for(let count = 0; count < data.review_data.length; count++) {
							html += '<div class="row mb-3">';
							html += '<div class="col-sm-1"><div class="rounded mx-auto d-block"><img  class="avatar" src="' + data.review_data[count].user_avatar + '"  width="90px" height="90px" alt="Gravatar de ' + data.review_data[count].user_name + '"<h3 class="text-center">' + data.review_data[count].user_name + '</h3></div></div>';
							html += '<div class="col-sm-11">';
							html += '<div class="card">';
							html += '<div class="card-header"><b>' + data.review_data[count].user_name + '</b></div>';
							html += '<div class="card-body">';

							function user_rating() {
								for(let count = 0; count < data.review_data.length; count++) {
									for(var star = 1; star <= 5; star++) {
										var class_name = '';
										if(data.review_data[count].rating >= star) {
											class_name = 'text-warning';
										} else {
											class_name = 'star-light';
										}
										html += '<i class="fas fa-star ' + class_name + ' mr-1"></i>';
									}
									document.getElementById('user_rating').innerHTML = user_rating()
								}
							};
							html += '<br />';
							html += data.review_data[count].user_review;
							html += '</div>';
							html += '<div class="card-footer text-right">On ' + data.review_data[count].datetime + '</div>';
							html += '</div>';
							html += '</div>';
							html += '</div>';
						}
						//$('#review_content').html(html);
					}
				}
			})
		}
	});

	function cwRating(id, type, target) {
		$.ajax({
			type: 'POST',
			url: '/includes/rating.php',
			data: 'id=' + id + '&type=' + type,
			success: function(msg) {
				if(msg == 'err') {
					alert('Some problem occured, please try again.');
				} else {
					$('#' + target).html(msg);
				}
			}
		});
	}
	$(document).ready(function() {
		$(".comment").shorten();
		$(".comment-small").shorten({
			showChars: 200
		});
	});
	</script>
