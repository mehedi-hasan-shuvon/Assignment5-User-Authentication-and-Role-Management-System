<?php

session_start();

$users = json_decode( file_get_contents( 'users.json' ), true );

function getRoleList() {
    $roleFile = 'roles.json';
    $roles = file_exists( $roleFile ) ? json_decode( file_get_contents( $roleFile ), true ) : [];
    return $roles;
}

function getUserDetails( $email ) {
    // Example: Fetch user details from the $users array
    $users = json_decode( file_get_contents( 'users.json' ), true );
    return isset( $users[$email] ) ? $users[$email] : null;
}

function saveUsers( $users, $file ) {

    file_put_contents( $file, json_encode( $users, JSON_PRETTY_PRINT ) );

}

// for get roles
if (isset( $_GET['action'] ) && $_GET['action'] === 'getRoles' ) {
    $roleList = getRoleList();
	$emptyArr=[];

    if ( $roleList ) {
        header( 'Content-Type: application/json' );
        echo json_encode( $roleList );
    } else {
		echo json_encode( $emptyArr );
    }
}

//for get user details
if ( isset( $_GET['email'] ) ) {
    $email = $_GET['email'];

    $userDetails = getUserDetails( $email );

    $_SESSION['selectedUserEmail'] = $email;

    if ( $userDetails ) {
        // Return user details as JSON
        header( 'Content-Type: application/json' );
        echo json_encode( $userDetails );
    } else {
        // Handle the case when user details are not found
        http_response_code( 404 ); // Not Found
        echo json_encode( array( 'error' => 'User not found' ) );
    }

} 

//for update role
if ( isset( $_POST['editRole'] ) ) {
    $role = $_POST['selectedRole'];
    $email = $_SESSION['selectedUserEmail'];
    $users[$email]['role'] = $role;
    $usersFile = 'users.json';
    saveUsers( $users, $usersFile );
    header( 'Location: dashboard.php' );
} 



?>