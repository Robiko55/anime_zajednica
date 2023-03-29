<?php 



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



function create_user(){

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $first_name = escape($_POST['first_name']);
        $last_name = escape($_POST['first_name']);
        $username = escape($_POST['username']);
        $email = escape($_POST['email']);
        $password = escape($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users(first_name, last_name,username, profile_image, email, password)";
        $sql .= "VALUES('$first_name','$last_name','$username','uploads/default.jpg','$email','$password')";
    
    
        confirm(query($sql)); // izvrsavam query i potvrdjejemo da l je uspesno izvrsen 
        set_message("Uspesno ste se registrovali! Molimo vas da se ulogujete!"); // postavljanje poruke
        display_message();
    
    }
}