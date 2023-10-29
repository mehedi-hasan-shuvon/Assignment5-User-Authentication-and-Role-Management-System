<?php

session_start();

$users = json_decode( file_get_contents( 'users.json' ), true );

if ( isset( $_POST['login'] ) ) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	if ( empty( $email ) ) {

        $errorMsg = "Email is required";
    } else if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {

        $errorMsg = "Invalid email format";
    }
    if ( empty( $password ) ) {

        $errorMsg = "Password is required";
    } else if ( strlen( $password ) < 3 ) {

        $errorMsg = "Password must be at least 3 characters";
    } else {

		if ( !isset( $users[$email] ) ) {

			$errorMsg = "Email not found";
		} else {
			if($users[$email]['password'] == md5($password)){ 
				$_SESSION['email'] = $email;
				header( 'Location: dashboard.php' );
			}else{
				$errorMsg = "Password didn't matched";
			}
		}

	}

}



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


	<style>
		.gradient-custom-3 {
			/* fallback for old browsers */
			background: #84fab0;

			/* Chrome 10-25, Safari 5.1-6 */
			background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));

			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
			background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
		}
		.gradient-custom-4 {
			/* fallback for old browsers */
			background: #84fab0;

			/* Chrome 10-25, Safari 5.1-6 */
			background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));

			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
			background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
		}
	</style>
</head>
<body>


		

	<section class=" bg-image vh-100"
	style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
	<div class="mask d-flex align-items-center h-100 gradient-custom-3">
		<div class="container h-100">
		<div class="row d-flex justify-content-center align-items-center h-100">
			<div class="col-12 col-md-9 col-lg-7 col-xl-6">
			<div class="card my-5" style="border-radius: 15px;" >
				<div class="card-body p-5">
				<h3 class=" text-center">Crew Project - (Role management)</h3>
				<h5 class="text-uppercase text-center mb-5">Login to your account</h5>

				<form method="POST" action = "<?php echo $_SERVER['PHP_SELF']; ?>">


					<div class="form-outline mb-4">
					<input type="email" id="email" class="form-control form-control-lg" name ="email"/>
					<label class="form-label" for="email">Your Email</label>
					</div>

					<div class="form-outline mb-4">
					<input type="password" id="password" class="form-control form-control-lg" name="password" />
					<label class="form-label" for="password">Password</label>
					</div>


					<?php if ( isset( $errorMsg ) && !empty( $errorMsg ) && $errorMsg != "" ) : ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $errorMsg; ?>
					</div>
					<?php endif; ?>

					<div class="d-flex justify-content-center">
						<button type="submit"
							class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="login" value="login">Login</button>

					</div>

					<p class="text-center text-muted mt-5 mb-0">Don't have an account? <a href="registration.php"
						class="fw-bold text-body" ><u>Registration here</u></a></p>

					<p>** For Test: Admin email: 'admin@gmail.com' & password: 'admin123' **</p>

				</form>

				</div>
			</div>
			</div>
		</div>
		</div>
	</div>
	</section>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>


</body>
</html>