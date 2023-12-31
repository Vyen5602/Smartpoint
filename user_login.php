<?php

include 'components/connect.php';

session_start();

class User {
   private $id;
   private $email;
   private $password;
   private $conn;

   public function __construct($conn) {
      $this->conn = $conn;
   }

   public function login($email, $password) {
      $this->email = filter_var($email, FILTER_SANITIZE_STRING);
      $this->password = filter_var(sha1($password), FILTER_SANITIZE_STRING);

      $select_user = $this->conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
      $select_user->execute([$this->email, $this->password]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $this->id = $row['id'];
         $_SESSION['user_id'] = $this->id;
         header('location:home.php');
      }else{
         return 'incorrect username or password!';
      }
   }
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $user = new User($conn);
   $message[] = $user->login($_POST['email'], $_POST['pass']);
}

?>



<html>
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
      <p>Don't have an account?</p>
      <a href="user_register.php" class="option-btn">Register Now</a>
   </form>

</section>



<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>