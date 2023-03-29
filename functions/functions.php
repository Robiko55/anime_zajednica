<?php 

function clean($string){
    return htmlentities($string);
}

function redirect($location){
    header("location: {$location}");
    exit();
}

function set_message($message){
    if(!empty($message)){
        $_SESSION['message'] = $message;
    }

    else{
        $message = "";
    }

}

function display_message(){
    if(isset($_SESSION['message'])){
        echo $_SESSION['message']; // ispis poruke
        unset($_SESSION['message']);
    }
}


function email_exists($email){

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $query = "SELECT id FROM users WHERE email='$email'";
    $result = query($query);

    if($result->num_rows > 0){ // ako je broj redova iz baze veci od 0 znaci da ima mejl koji se poklapa

        return true;

    }
    else{
    return false;
    }


}

function username_exists($user){
    
    $user = filter_var($user, FILTER_SANITIZE_STRING);
    $query = "SELECT id FROM users WHERE username='$user'";
    $result = query($query);

    if($result->num_rows > 0){ // ako je broj redova iz baze veci od 0 znaci da ima mejl koji se poklapa

        return true;

    }
    else{
    return false;
    }
}

function validate_user_registration(){
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if(strlen($first_name)<3){
            $errors[] = "Vase ime ne moze biti krace od 3 karaktera!";
        }
        if(strlen($last_name)<3){
            $errors[] = "Vase prezime ne moze biti krace od 3 karaktera!";
        }
        if(strlen($username)<3){
            $errors[] = "Vas username ne moze biti kraci od 3 karaktera!";
        }
        if(strlen($username)>15){
            $errors[] = "Vas username ne moze biti duzi od 15 karaktera!";
        }
        if(email_exists($email)){
            $errors[] = "Postoji korisnik sa unetim emailom, unesite novi!";
        }
        if(username_exists($username)){
            $errors[] = "Postoji korisnik sa tim username-om, unestite novi!";
        }
        if(strlen($password)<8){
            $errors[] = "Password more biti duzi od 8 karktera!";

        }
        if($password != $confirm_password){
            $errors[] = "Password nije potvrdjen ispravno!";

        }
        if(!empty($errors)){
            foreach($errors as $error){
                echo "<div class='alert'>". $error , "</div>";
            } 
        } else {
                create_user($first_name, $last_name, $username, $email, $password);
            }
    }
}

function create_user($first_name, $last_name, $username, $email, $password){

    
        $first_name = escape($first_name);
        $last_name = escape($last_name);
        $username = escape($username);
        $email = escape($email);
        $password = escape($password);
        $password = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users(first_name, last_name,username, profile_image, email, password)";
        $sql .= "VALUES('$first_name','$last_name','$username','uploads/default.jpg','$email','$password')";
    
    
        confirm(query($sql)); // izvrsavam query i potvrdjejemo da l je uspesno izvrsen 
        
        set_message("Uspesno ste se registrovali! Molimo vas da se ulogujete!"); // postavljanje poruke
        redirect("login.php");
      
    
    
}