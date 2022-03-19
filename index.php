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

$lang="es";
//likes

require_once ($_SERVER['DOCUMENT_ROOT']."/includes/Post.class.php");
$post = new Post();
// Get posts data
$posts = $post->getRows();


$sql = 'select * FROM avatrade ORDER BY review_id DESC';
$sentencia =$pdo-> prepare($sql);
$sentencia-> execute();

$resultado = $sentencia ->fetchAll();

$comentarios_x_pagina= 10;

$comentarios_totales = $sentencia->rowcount();
$paginas = $comentarios_totales /$comentarios_x_pagina;
$paginas = ceil($paginas);

$iniciar=($_GET['page_reviews']-1)*$comentarios_x_pagina;

if ($comentarios_totales >0){

	if(!$_GET){
	header('location:index.php?page_reviews=1');

die();
	}
if($_GET['page_reviews']>$paginas || $_GET['page_reviews']<=0){
	header('location:index.php?page_reviews=1');

die();}
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
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

$sql_articulos= "SELECT * FROM avatrade ORDER BY review_id DESC LIMIT :iniciar, :narticulos";
$sentencia_articulos = $pdo->prepare($sql_articulos);
$sentencia_articulos->bindParam(':iniciar',$iniciar, PDO::PARAM_INT);
$sentencia_articulos->bindParam(':narticulos',$comentarios_x_pagina, PDO::PARAM_INT);
$sentencia_articulos->execute();

$resultado_comentarios =$sentencia_articulos->fetchAll();

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
	
 <head>
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
    					<h3><span id="total_review">0</span> Review(s)</h3>
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
						<?php foreach($resultado_comentarios as $comentarios):  ?>
							<div class="container mt-5">
								<div class="row mb-3">
									<div class="col-sm-1">
										<div class="rounded mx-auto d-block"><img class="avatar" src="<?php echo $comentarios['user_avatar']?>" />
											<h6 class="text-center"><?php echo $comentarios['user_name']?></h6> </div>
									</div>
									<div class="col-sm-11">
										<div class="card">
											<div class="card-header">By <b><?php echo $comentarios['user_name']?></b> </div>
											<div class="card-body">
												<div class="us-rate">
													<div class="pdt-rate">
														<div class="pro-rating">
															<div class="clearfix rating marT8 ">
																<div class="rating-stars ">
																	<div class="grey-stars"></div>
																	<div class="filled-stars" style="width:<?= $comentarios['user_rating'] * 20 ?>%"> </div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<br>
												<div class="us-cmt">
													<p>
														<?php echo $comentarios['user_review']?>
													</p>
												</div>
											</div>
											<div class="card-footer">
												<p class="alignright">
													<?php echo time_elapsed_string($comentarios['datetime'])?>
														<p class="alignleft">
															<p class="pull-left">
																<!-- Like button --><span class="fa fa-thumbs-up" onClick="cwRating(<?php echo $comentarios['review_id']; ?>, 1, 'like_count<?php echo $comentarios['review_id']; ?>')"></span>&nbsp;
																<!-- Like counter --><span class="counter" id="like_count<?php echo $comentarios['review_id']; ?>"><?php echo $comentarios['like_num']; ?></span>&nbsp;&nbsp;&nbsp;
																<!-- Dislike button --><span class="fa fa-thumbs-down" onClick="cwRating(<?php echo $comentarios['review_id']; ?>, 0, 'dislike_count<?php echo $comentarios['review_id']; ?>')"></span>&nbsp;
																<!-- Dislike counter --><span class="counter" id="dislike_count<?php echo $comentarios['review_id']; ?>"><?php echo $comentarios['dislike_num']; ?></span> <span  class="fas fa-edit" data-toggle="modal" data-target="#edit_modal"></span> </p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php endforeach ?>
					</section>
					
				<?php
				if (!empty($paginas)) {?>
				<section class="pagination">
					<div class="container mt-5">
						<nav aria-label="">
							<ul class="pagination">
								<li class="page-item <?php echo $_GET['page_reviews']<=1? 'disabled' : ''?>"><a class="page-link" href="index.php?page_reviews=<?php echo ''.$_GET['page_reviews']-1 ?>">Anterior</a></li>
								<?php  for($i=0;$i<$paginas;$i++): ?>
									<li class="page-item <?php echo $_GET['page_reviews']==$i+1 ? 'active' : ''  ?>">
										<a class="page-link" href="index.php?page_reviews=<?php echo $i+1?>">
											<?php echo $i+1?>
										</a>
									</li>
									<?php endfor?>
										<li class="page-item <?php echo $_GET['page_reviews']>= $paginas? 'disabled' : ''?>"><a class="page-link" href="index.php?page_reviews=<?php echo ''.$_GET['page_reviews']+1 ?>">Siguiente</a></li>
							</ul>
						</nav>
					</div>
					<?php } else { ?>
					<div class="container my-5">
						<div class="card text-center">
							<div class="card-header">
								Seems there are no Reviews yet!
							</div>
						<div class="card-body">
						
							<h5 class="card-title">Be the first person on review this item!</h5>
							<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
							<a data-target="#review_modal" data-toggle="modal" name="add_review" id="add_review"  class="btn btn-primary">Review Now</a>
							<div class="nothing-yet"></div>
							</div>
						  <div class="card-footer text-muted">
							
						  </div>
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
				<div class="modal-body">
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
	<style>
	.nothing-yet{
  background-image: url(https://cdn.dribbble.com/users/926537/screenshots/8768655/media/0eb8fcc9f2b8a55c589cfabd6cc89d94.gif);
   height: 400px;
   background-position: center;
 }
	.modal-title{
		text-align:center;
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
	
	.counter {
		color: #333333;
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
	
	.tri .filled-stars:before,
	.tri .grey-stars:before {
		font-size: 20px;
		line-height: 23px;
	}
	
	.rnrn {
		color: #888;
		font-family: "lato";
		font-weight: 700;
		font-size: 1rem;
	}
	
	.rpb {
		width: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	
	.rnpb {
		display: flex;
		width: 100%
	}
	
	.rnpb label:first-child {
		margin-right: 5px;
		margin-top: -2px;
	}
	
	.rnpb label:last-child {
		margin-left: 3px;
		margin-top: -2px;
	}
	
	.rnpb label i {
		color: var(--primary-color)
	}
	
	.ropb {
		height: 10px;
		width: 75%;
		background-color: #f1f1f1;
		position: relative;
		margin-bottom: 10px;
	}
	
	.ripb {
		height: 100%;
		background-color: #f7bf17;
		border: 1px solid #a0a0a0;
	}
	
	.rrb p {
		font-size: 1rem;
		font-weight: 500;
		font-family: raleway;
		margin-bottom: 10px;
	}
	
	.rrb button {
		width: 220px;
		height: 40px;
		background: var(--light-b);
		color: var(--white);
		border: 0;
		outline: none;
		font-size: 1.2rem;
		font-family: "roboto", sans-serif;
		box-shadow: 0px 2px 2px var(--light-b);
		cursor: pointer;
	}
	
	.rrb button:hover {
		opacity: .9;
	}
	
	.bri .filled-stars::before,
	.bri .grey-stars::before {
		font-size: 54px;
	}
	
	.review-bg {
		position: fixed;
		background: rgba(0, 0, 0, .8);
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		z-index: 100;
	}
	
	.review-modal {
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 101;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
	
	.rmp {
		width: 400px;
		height: auto;
		background: var(--white);
		border-radius: 10px;
		animation: scaleUp .7s linear;
		transition: all .7s ease-in-out;
		z-index: 201;
	}
	
	@keyframes scaleUp {
		0% {
			transform: scale(0.2);
		}
		25% {
			transform: scale(.8);
		}
		50% {
			transform: scale(1.2);
		}
		75% {
			transform: scale(0.8);
		}
		100% {
			transform: scale(1);
		}
	}
	
	.rpc {
		text-align: right;
		padding: 6px 15px;
		font-size: 1.5rem;
		color: var(--linear);
	}
	
	.rpc span {
		cursor: pointer;
	}
	
	.rps {
		padding-bottom: 20px;
	}
	
	.rps i {
		font-size: 1.6rem;
		cursor: pointer;
	}
	
	.rptf textarea,
	.rptf input {
		width: 80%;
		outline: none;
		border: 1px solid #ccc;
		border-radius: 5px;
		padding: 7px;
		resize: none;
		min-height: 80px;
		margin-bottom: 10px;
		font-family: "roboto", sans-serif;
		font-size: .9rem;
		font-weight: 100;
		color: var(--t-color);
	}
	
	.rptf input {
		min-height: 10px !important;
	}
	
	.rate-error {
		font-size: 12px;
		color: var(--r-color);
		font-family: "roboto", sans-serif;
		margin-bottom: 5px;
		font-weight: 500;
	}
	
	.rpsb button {
		color: var(--white);
		background: var(--light-b);
		border: 0;
		outline: none;
		width: 80%;
		height: 40px;
		margin-bottom: 20px;
		border-radius: 3px;
		font-family: "roboto", sans-serif;
		cursor: pointer;
	}
	
	.rpsb button:hover {
		opacity: .9;
	}
	
	.bri {
		overflow: hidden;
		height: 100%
	}
	
	.uscm-secs {
		padding: 10px;
		display: flex;
		width: 100%;
		height: 100%;
		border-bottom: 1px solid #f1f1f1;
	}
	
	.us-img {
		width: 13%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	
	.us-img p {
		background: var(--light-b);
		width: 45px;
		height: 45px;
		border-radius: 50%;
		text-align: center;
		line-height: 45px;
		color: var(--white);
		font-size: 1.1rem;
		font-family: "roboto", sans-serif;
		font-weight: 500;
	}
	
	.uscms {
		display: flex;
		flex-direction: column;
		width: 87%;
	}
	
	.bri .filled-stars::before,
	.bri .grey-stars::before {
		font-size: 24px;
	}
	
	.us-cmt p {
		font-size: .9rem;
		padding: 10px 10px 10px 0;
		color: #333;
		font-weight: 500;
		font-family: raleway;
	}
	
	.us-nm p {
		font-size: .8rem;
		font-weight: 500;
		color: #888;
		font-family: "roboto", sans-serif;
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
	}
	
	.pagination {
		text
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
					$('#total_review').text(data.total_review);
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
							for(var star = 1; star <= 5; star++) {
								var class_name = '';
								if(data.review_data[count].rating >= star) {
									class_name = 'text-warning';
								} else {
									class_name = 'star-light';
								}
								html += '<i class="fas fa-star ' + class_name + ' mr-1"></i>';
							}
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
	
	</script>
