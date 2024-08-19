<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
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
 
  

	
  <form method="post" action="register.php">
  	<?php include('errors.php'); ?>
	  <div class="register">
  	<h2>Register</h2>
  
	  <div class="inputBox">
  	  
  	  <input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div class="inputBox">
  	  
  	  <input type="email" placeholder="Email"name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="inputBox">
  	 
  	  <input type="password" placeholder="Password"name="password_1">
  	</div>
  	<div class="inputBox">
  	  
  	  <input type="password"placeholder="Confirm Password" name="password_2">
  	</div>
  	<div class="inputBox">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
	<div class="group">
  	<p>Already a member?</p> <a href="login.php">Sign in</a>
  	
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
    </form>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="script.js"></script>
	
</body>
</html>