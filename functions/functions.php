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

function validate_user_login(){
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if(empty($email)){
            $errors[] = "Email ne moze biti prazan!";
        }

        if(empty($password)){
            $errors[] = "Password ne moze biti prazan!";
        
    }

    if(empty($errors)){
        if(user_login($email, $password)){
            redirect("index.php");
        } else {
            $errors[] = "Vas email ili password nisu uneti ispravno!";
        }
    }

    if(!empty($errors)){
        foreach($errors as $error){
            echo '<div class="alert">' . $error . '</div>';
        }
    }
}
}

function user_login($email, $password){

    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = query($query);

    if($result->num_rows > 0){
        $data = $result->fetch_assoc(); // uzmi jedan red iz baze i pretvori u asocijativni niz i dodeli promenljivoj $data

        if(password_verify($password, $data['password'])){

            $_SESSION['email'] = $email;
            return true;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }


}


function get_user($id = NULL){
    if($id != NULL){

        $query = "SELECT * FROM users WHERE id=" .$id;
        $result = query($query);

        if($result->num_rows>0){
            return $result->fetch_assoc();
        } else{
            return "Korisnik nije pronadjen!";
        }
    } else {
        $query = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "'";
        $result = query($query);

        if($result->num_rows>0){
            return $result->fetch_assoc();
        } else{
            return "Korisnik nije pronadjen!";
        }
    }
}

function user_profile_image_upload()
{
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $target_dir = "uploads/";
        $user = get_user();
        $user_id = $user['id'];
        $target_file = $target_dir . $user_id . "." .pathinfo(basename($_FILES["profile_image_file"]["name"]), PATHINFO_EXTENSION);;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $error = "";

        $check = getimagesize($_FILES["profile_image_file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "Izabrani fajl nije slika.";
            $uploadOk = 0;
        }

        if ($_FILES["profile_image_file"]["size"] > 5000000) {
            $error = "Slika je prevelika!";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Morate iabrati sliku u formatu JPG, JPEG, PNG ili GIF.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            set_message('Gleska prilikom postavljanja slike'. $error);
        } else {
            $sql = "UPDATE users SET profile_image='$target_file' WHERE id=$user_id";
            confirm(query($sql));
            set_message('Profila slika uspesno postavljena!');

            if (!move_uploaded_file($_FILES["profile_image_file"]["tmp_name"], $target_file)) {
                set_message('Greska prilikom postavljanja slike! '. $error);
            }
        }

        redirect('profile.php');
    }
}

function user_restrictions(){
    if(!isset($_SESSION['email'])){
        redirect("login.php");
    }
}

function login_check_pages(){
    if(isset($_SESSION['email']))
    redirect("index.php");
}

function create_post(){
    $errors  = [];
    if($_SERVER['REQUEST_METHOD']== 'POST'){
        $post_content = clean($_POST['post_content']);

        if(strlen($post_content)>200){

            $errors[] = "Vasa objava mora imati manje od 200 karaktera!";

        }

        if(!empty($errors)){
        
            foreach($errrors as $error){
                echo '<div> class="alert">'. $error . '</div>';
            }

        } else {
            $post_content = filter_var($post_content, FILTER_SANITIZE_STRING);
            $post_content = escape($post_content);

            $user = get_user();
            $user_id = $user['id'];

            $sql = "INSERT INTO posts(user_id,content,likes)";
            $sql .= "VALUES('$user_id','$post_content',0)";

            confirm(query($sql));
            set_message('Dodali ste objavu!');
            redirect('index.php');
        }

        
    }
}