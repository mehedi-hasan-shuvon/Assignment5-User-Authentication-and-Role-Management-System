<?php

session_start();

$users = json_decode( file_get_contents( 'users.json' ), true );

//redirect to registration.php if  user is not logged in or not in the users.json
if(!isset( $_SESSION['email'] ) || !$users[$_SESSION['email']] ) {
	header( 'Location: registration.php' );
}

if(isset($_POST['logout'])) {
	session_destroy();
	header( 'Location: login.php' );

}




function getUserDetails($users,$email) {

	$userDetails =  $users[$email];
	return $userDetails;	
}

$userDetails = getUserDetails($users,$_SESSION['email']);


function getRoleList(){
	$roleFile = 'roles.json';
	$roles = file_exists( $roleFile ) ? json_decode( file_get_contents( $roleFile ), true ) : [];
	return $roles;
}

$roleList = getRoleList();

function saveRoles( $roles, $file ) {

	file_put_contents( $file, json_encode( $roles, JSON_PRETTY_PRINT ) );
	
}

function saveUsers( $users, $file ) {

    file_put_contents( $file, json_encode( $users, JSON_PRETTY_PRINT ) );

}


//add role in role list
if(isset($_POST['addrole'])) {
	$role = $_POST['rolename'];
	$level = $_POST['level'];

	//check if current user has role level 3 or not
	$roles = getRoleList();

	if($roles[$userDetails['role']]['level'] < 3 ) {
		$errorMsg = "User does not have permission to add role";
		header('Location: dashboard.php');	
	}else{
		$roles[$role] = [
			'level' => $level,
		];

		$roleFile = 'roles.json';
		saveRoles( $roles, $roleFile );

	}

}


?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>


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

		.button-section{
			display: flex;
   			justify-content: flex-end;
    		gap: 10px;
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
				<h5 class="text-uppercase text-center">Welcome  <?php echo $userDetails['username']; ?>  !!</h5>
				<p class="text-center text-info ">
					<em>
					<?php 

					if($roleList[$userDetails['role']]['level'] > 2){

						echo "*You have level 3 permission, you can view & edit user roles";
					} else if($roleList[$userDetails['role']]['level'] > 1){
						echo "*You have level 2 permission, you can view users but cannot edit user roles";
					} else{ 
						echo "*You have level 1 permission, you can view users only without roles";
					} 
					?>
					</em>
				</p>

				<div class="button-section">
					<div > 
						<!-- logout button in the same page -->
						<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
							<button type="submit" name="logout" class="btn btn-danger" value = "logout">Logout</button>
						</form>
					</div>

					<?php  if($roleList[$userDetails['role']]['level'] > 2){ ?>
					<div>
						<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2" onclick="getRoles()" >
								Edit Roles
						</button>
					</div>
					<?php } ?>


				</div>


				<table class="table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Name</th>
							<th scope="col">Email</th>
							<?php if($userDetails['role'] == 'admin' || $userDetails['role'] == 'manager') { ?>
							<th scope="col">Role</th>
							<th scope="col">Edit</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$counter = 1;
						 foreach($users as $email => $user) {
						?>
						<tr>
							<th scope="row"><?php echo $counter++; ?></th>
							<td><?php echo $user['username']; ?></td>
							<td><?php echo  $email  ?></td>
							<?php if($userDetails['role'] == 'admin' || $userDetails['role'] == 'manager') { ?>
							<td><?php echo $user['role']; ?></td>
							<td> 
							<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="editUser('<?php echo $email; ?>')" 
							<?php

							if($roleList[$userDetails['role']]['level'] <3){
								echo "disabled";
							}
							?>
							>
							Edit 
							</button>
							</td>
							<?php } ?>
						</tr>
						<?php
					 } ?>
					</tbody>
				</table>	
				</div>
				
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>
	</section>



	<!-- modal section  -->

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<p>User Name: <span id='Uname'></span></p>
			<p>User Email: <span id='Uemail'></span></p>
			<p>User Role: <span id='Urole'></span></p>


		<p>Change Role to: </p>

		<form  method="POST" action = "ajax.php"> 
		<select class="form-select" aria-label="Default select example" name="selectedRole">
			<?php
			foreach ($roleList as  $role => $value) {			
					echo "<option value='{$role}' >{$role} - Level: {$value['level']}</option>";
			}
			?>
		</select>

		<br>

		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" name="editRole" value="editRole">Update Role</button>

		</form>	

		</div>

		</div>
	</div>
	</div>


	 <!-- role section modal  -->

	 <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<h1 class="modal-title fs-5" id="exampleModalLabel2">List of roles</h1>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">

		<table id="roleTable" class="table">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Role Name</th>
				<th scope="col">Permission Level</th>
			</tr>
		</thead>
			

		<tbody>


		</tbody>			 

		</table>



		<h5 class="text-center">Add new role</h5>
		
		<form id="myForm" method="POST" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class="form-outline-sm mb-4">
			<label class="form-label" for="rolename">Role Name</label>
			<input type="text" id="rolename" class="form-control form-control-sm"  name="rolename"/>
					
			</div>

			<div class="form-outline mb-4">
					
			<label class="form-label" for="level">Permission Level</label>
			<select name="level" id="level" class="form-select form-select-sm" aria-label="Small select example">
				<option value="1" selected>One</option>
				<option value="2">Two</option>
				<option value="3">Three</option>
			</select>

			</div>


		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" name="addrole" value="addrole">Add Role</button>

		</form>

		</div>

	</div>
	</div>



	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script>
	function editUser(selectedUserEmail) {



		//make a ajax_request to get the user details
		$.ajax({
        url: 'ajax.php',
        method: 'GET',
        data: { email: selectedUserEmail },
        success: function (data) {
            // Update the modal or form fields with the retrieved data
			console.log(data);
			$('#Uname').html(data.username);
			$('#Uemail').html(selectedUserEmail);
			$('#Urole').html(data.role);
        },
        error: function (xhr, status, error) {
            console.error('AJAX request failed:', error);
        }
    });

	}

	function getRoles() {
		
		$.ajax({
        url: 'ajax.php',
        method: 'GET',
        data: { action: 'getRoles' }, // Specify the action
        success: function (data) {
            console.log(data);
			if (Object.keys(data).length > 0) {
				console.log("hello");
                var tbody = $('#roleTable tbody');

                // Clear the existing rows from the table
                tbody.empty();

				// Loop through the roles and add them to the table
				let index = 0;
				for (var role in data) {
					var tr = $('<tr>');
					console.log(role);
					tr.append('<td>' + index + '</td>');
					tr.append('<td>' + role + '</td>');
					tr.append('<td>' + data[role]['level'] + '</td>');
					tbody.append(tr);
					index++;
				}
              
            }

        },
        error: function (xhr, status, error) {
            console.error('AJAX request failed:', error);
        }
    });
	}



	</script>



<script>
    $(document).ready(function () {
        $("#myForm").submit(function (event) {
            var roleNameInput = $("#rolename");

            if (roleNameInput.val().trim() === "") {
                // Input is empty, prevent form submission
                event.preventDefault();
                alert("Role Name cannot be empty");
            }
        });
    });
</script>
	
</body>
</html>