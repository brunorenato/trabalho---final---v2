<?php

ob_start();

require __DIR__."/Oauth2/vendor/autoload.php";

echo '<html>';
echo '<head>';
  echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
  echo '<title>Trabalho Final</title>';
  echo "<meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>";
  echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>';
  echo ' <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />';
  echo ' <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
  echo ' <script type="text/javascript" src="js/ajax.js"></script>';
  echo ' <script type="text/javascript" src="js/scripts.js"></script>';
  echo ' <link rel="stylesheet" type="text/css" href="css/style.css" />';   
echo '</head>';

if(empty($_SESSION["userlogin"])) {
    echo "<br />";
    echo "<h1>TRABALHO FINAL</h1>";
    echo " <br />";
    /**
     *  AUTH GITHUB
     */
    $github = new \League\OAuth2\Client\Provider\Github([
        "clientId" => GITHUB["app_id"],
        "clientSecret" => GITHUB["app_secret"],
        "redirectUri" => GITHUB["app_redirect"],
        "graphApiVersion" => GITHUB["app_version"],
    ]);
    $authUrl = $github->getAuthorizationUrl([
        "scope" => ["email"]
    ]);
    $error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_STRIPPED);
    if($error){
        echo "<h4>Você precisa autorizar para continuar</h4>";
    }
    $code = filter_input(INPUT_GET, "code", FILTER_SANITIZE_STRIPPED);
    if($code){
        $token = $github->getAccessToken( "authorization_code", [
            "code" => $code
        ]);
        $_SESSION["userlogin"] = $github->getResourceOwner($token);
        header( "Refresh: 0");
    }	
	
   	echo '<div><p id="bola"></p><br><br><hr><br><br>';
	echo "<div align='center'><a title='GH login' href='{$authUrl}'><button><img src='Oauth2/botao_github.png' width='100px' height='100px'/></button></a></div>";	
	echo'<br><br><hr><h3>Para continuar faça a autenticação Oauth2 do GitHub<h3></div>';
	
} else {	

 echo '<body>'; 
	echo '  <br />';
	echo '  <h1>TRABALHO FINAL</h1>';
	echo '  <br />';
	echo '  <div>';
		echo '<h2>Bem-Vindo '.$_SESSION['user_first_name'].' </h2>';
		echo'<form id="meuForm" action="http://localhost:8001/produtos/add" onsubmit="return validarForm()" method="POST">';
			echo'<h3>Formulário de Cadastro:</h3><br>';
				echo'Produto: <input type="text" placeholder="nome ou marca..." name="produto" id="txtProduto"><br>';
				echo'Preço: <input type="number" pattern="[0-9]+([,\.][0-9]+)?" min="0" step="any" placeholder="ex: 10, 5,5 ou 7.8" name="preco" id="txtPreco"><br>';
			echo'<input type="submit" value="Salvar"><br>';
		echo'</form>';
			echo' <a href="http://localhost:8001/produtos" target="_blank" id="tabela2">Listar Produtos API REST</a><br>';
			echo'<h4>Exibir tabela de Produtos com AJAX</h4><br>';
			echo'<button class="btn1">Ocultar/Mostrar</button>';
		echo'<div id="trocar"><br>';
			echo'<table border="1" width="500">';
					echo'<thead>';
						echo'<tr>';
							echo'<th>ID</th>';
							echo'<th>Produto</th>';
							echo'<th>Preço</th>';
						echo'</tr>';
					echo'</thead>';
				echo'<tbody id="tabela">';
				echo'</tbody>';
			echo'</table>';
		echo'</div>';
		echo "<br><br><a title='sair' href='?off=true'>Sair</a>";
		
		$off = filter_input( INPUT_GET, "off", FILTER_VALIDATE_BOOLEAN );
		if($off){
			unset($_SESSION["userlogin"]);
			header( "Refresh: 0");
		}
		
	echo'</div>';
 echo'</body>';
echo'</html>';
}

ob_end_flush();