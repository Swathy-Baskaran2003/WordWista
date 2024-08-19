<?php include('server.php')?>
<?php 

  
  include "gc_config.php";
  
  if(isset($_GET["code"])){
    $token=$client->fetchAccessTokenWithAuthCode($_GET["code"]);
    $client->setAccessToken($token["access_token"]);
    
    $obj=new Google_Service_Oauth2($client);
    $data=$obj->userinfo->get();
    
    if(!empty($data->email)&&!empty($data->name)){
      
      //if you want to register user details, place mysql insert query here
      session_start();
      $_SESSION["email"]=$data->email;
      $_SESSION["name"]=$data->name;
      $_SESSION['loggedin'] = true;

      header("location:index.php");
    }
  }

?>
<!DOCTYPE html>
<html>
  <head>  
    <title>Login With Google Account in PHP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />

</head>
<body>
<section>

 <div class="container">
  <div id="scene">
    <div class="layer" data-depth-x="-0.5" data-depth-y="-0.25"><img src="moon (1).png"></div>
    <div class="layer" data-depth-x="0.15"><img src="mountains02.png"> </div>
    <div class="layer" data-depth-x="0.25"><img src="mountains01.png"> </div>
    <div class="layer" data-depth-x="-0.25"><img src="road.png"> </div>
  </div>
</div>
    <form method="post" action="login1.php">
        <?php include('errors.php'); ?>

         <div class="login">
        <h2>Log In</h2>
        <div class="inputBox">
        <input type="text" placeholder="Username" name="username">
        </div>
        <div class="inputBox">
        <input type="password" placeholder="Password" name="password">
        </div>
        <div class="inputBox" >
        <input type="Submit" id="btn"  value="Login" name="login_user" ></button>
        </div>
        <div class="or" >
            <p style="margin-left:200px;">or</p>
        </div>
    </form>
    <div >
        <a  href= "<?php echo  $client->createAuthUrl(); ?>" ><img style="height:90px; margin-top:-20px; margin-left:30px;" src="./assets/loginLogo.png" /></a>
    </div>
    <div class="group">
    <p>Not yet a member?</p>
    <a href="register.php">Create Account</a>
    </div>
    
    
</div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/3.1.0/parallax.min.js" 
integrity="sha512-/6TZODGjYL7M8qb7P6SflJB/nTGE79ed1RfJk3dfm/Ib6JwCT4+tOfrrseEHhxkIhwG8jCl+io6eaiWLS/UX1w==" 
crossorigin="anonymous" referrerpolicy="no-referrer">
</script> 

<script>
  let scene = document.getElementById('scene');
  let parallax = new Parallax(scene);
  </script>
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="script.js"></script>
</body>
</html>