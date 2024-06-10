<html>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Space - Products</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form method="post" action="signUpConfirmation.php">
			<h1>Créer un compte</h1>
			<input type="text" name="username" placeholder="Nom" required />
			<input type="email" name="email" placeholder="Adresse mail" required />
			<input type="password" name="password" placeholder="Mot de passe" required />
			<button>S'inscrire</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="loginConfirmation.php" method="post">
			<h1>Connexion</h1>
			<input type="email" name="email" placeholder="adresse mail" required />
			<input type="password" name="password" placeholder="Mot de passe" required />
			<a href="#">Mot de passe oublié?</a>
			<button>Se connecter</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Vous êtes de retour?</h1>
				<p>Connecter vous pour acceder a votre compte</p>
				<button class="ghost" id="signIn">Se connecter</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Vous souhaitez rejoindre Angel Space?</h1>
				<p>Entrez vos informations de connexion pour créer un compte</p>
				<button class="ghost" id="signUp">S'inscrire</button>
			</div>
		</div>
	</div>
</div>
<script src="js/login.js"></script>
</body>
</html>