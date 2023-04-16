<?php 

//importacao das funções
require_once "functions.php";

//Inicia uma nova sessão
session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Jogo De Damas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="resources/css/styles.css">
</head>

<body class="container background">

    <div class="row">

        <!-- Menu de movimentação das peças -->
        <!-- Aqui o usuário escolhe a linha e coluna de origem e também
        a linha e coluna de destino-->
        <div class="col-md-2 controllers">
            <h2>Controles</h2>
            <form method="POST" class="block-options">
                <h4>Movimentar</h4>
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Origem Linha" name="origemLinha" />
                    <input class="form-control" type="text" placeholder="Origem Coluna" name="origemColuna" />
                    <input class="form-control" type="text" placeholder="Destino Linha" name="destinoLinha" />
                    <input class="form-control" type="text" placeholder="Destino Coluna" name="destinoColuna" />
                </div>
                <input class="btn btn-light" type="submit" value="Movimentar!">
            </form>
        </div>
        <!--Fim do menu de movimentação das peças-->

        <div class="col-md-8">

            <?php
			
			//criação da matriz que representa o tabuleiro
			$linha1 = ['-','0','-','0','-','0','-','0'];
			$linha2 = ['0','-','0','-','0','-','0','-'];
			$linha3 = ['-','0','-','0','-','0','-','0'];
			$linha4 = ['-','-','-','-','-','-','-','-'];
			$linha5 = ['-','-','-','-','-','-','-','-'];
			$linha6 = ['X','-','X','-','X','-','X','-'];
			$linha7 = ['-','X','-','X','-','X','-','X'];
			$linha8 = ['X','-','X','-','X','-','X','-'];

			$y = [$linha1, $linha2, $linha3, $linha4, $linha5, $linha6, $linha7, $linha8];

			//ESSE IF PRECISA ESTAR ANTES DE TODOS OS OUTROS, SENÃO DÁ ERRO
			//Objetivo: Reiniciar a partida
			//Funcionamento: se o campo 'action' tiver sido iniciado
			//através do método POST e se o campo for igual a resetGame
			//executa esse if, no qual a sessao atual é destruida e é 
			//criada uma nova. Além disso, também muda o turno atual 
			//para o jogador e referencia o tabuleiro
			if(isset($_POST['action']) && $_POST['action'] == "ResetGame"){
				session_destroy();
				session_start();
				$_SESSION["turn"] = 'X';
				$_SESSION['tabuleiro'] = $y;
			}

			//Funcionamento: Se os campos 'origemLinha', 'origemColuna', 'destinoLinha' e
			//'destinoColuna' tiverem sidos iniciados(preenchidos) executa esse if.
			if(isset($_POST['origemLinha']) &&
			isset($_POST['origemColuna'])  &&
			isset($_POST['destinoLinha'])  &&
			isset($_POST['destinoColuna']) ){
				$origemLinha = $_POST['origemLinha'];
				$origemColuna = $_POST['origemColuna'];
				$destinoLinha = $_POST['destinoLinha'];
				$destinoColuna = $_POST['destinoColuna'];

				if(empty($origemLinha) || empty($origemColuna) || empty($destinoLinha) || empty($destinoColuna)){
					echo '<div class="alert alert-danger">Você esqueceu de preencher algum campo. Por favor, preencha todos os campos!</div>';
					// Ou você pode redirecionar o usuário para outra página, exibir uma mensagem de erro em um alerta, etc.
				}else{
					$y = $_SESSION["tabuleiro"];
				
				//Funcionamento: se as posições escolhidas forem válidas excuta esse if.
				if(validarMovimento($y, $origemLinha, $origemColuna, $destinoLinha, $destinoColuna)){
					//Funcionamento: se o turno atual for do jogador passa o turno para o bot
					// caso contrário o turno atual é o do jogador.
					if($_SESSION['turn'] == 'X')
					{
						$_SESSION['turn'] = "0";
					}
					else
					{
						$_SESSION['turn'] = 'X';
					}
					
					//Funcionamento: O jogador realizar o movimento, após isso
					//atualiza a matriz com o movimento que o jogador fez e por
					//fim o bot faz o movimento.
					$_SESSION["tabuleiro"] = fazerMovimento($y, $origemLinha, $origemColuna, $destinoLinha, $destinoColuna);

					$y = $_SESSION["tabuleiro"];

					$_SESSION["tabuleiro"] = botMovimentar($y);

					// Verificar se o jogador X venceu
					if (verificarVencedor($y, '0')) {
						echo <<< FRASE
						<h1>O jogador X ganhou!</h1>
						<form method='POST'>
						<input type='hidden' name='action' value='ResetGame'>
						<input class='btn btn-light' type='submit' value='Reiniciar jogo'>
						</form>
						</div>
						FRASE;
						exit;
					}

					// Verificar se o jogador 0 venceu
					if (verificarVencedor($y, 'X')) {
						echo <<< FRASE
						<h1>O bot ganhou!</h1>
						<form method='POST'>
						<input type='hidden' name='action' value='ResetGame'>
						<input class='btn btn-light' type='submit' value='Reiniciar jogo'>
						</form>
						</div>
						FRASE;
						exit;
					}
					
					//Objetivo: Exibir alerta de quem é o turno atual
					//Funcionamento: se o turno atual for do jogador, exibe na
					//tela um alerta com a msg 'Turno: Jogador', caso contrário
					//exibe 'Turno: Bot'.
					$_SESSION["turn"] = 'X';
					if ($_SESSION['turn'] == 'X') {
					echo "<div class='alert alert-info'> Turno: Brancas </div>";
					}
					else{
					echo "<div class='alert alert-info'> Turno: Pretas</div>";
					}

				}
				//Funcionamento: se as posições escolhidas não forem
				//válidas executa esse else.
				else{
					echo '<div class="alert alert-danger">Esse movimento não é válido</div>';
				}
				
				//Objetivo: verificar se após cada turno, algum dos players fez dama
				//Funcionamento: O primeiro for verifica se o jogador fez dama e o
				//segundo for verifica se o bot fez dama
				for($j = 0; $j < 8; $j++){
					if ($y[0][$j] == 'X'){
						$_SESSION['tabuleiro'][0][$j] = 'C';
					}
				}
				for($j = 0; $j < 8; $j++){
					if ($y[7][$j] == '0'){
						$_SESSION['tabuleiro'][0][$j] = 'D';
					}
				}
				}

				

			}
			
			//Objetivo: Montar o tabuleiro na tela
			//Funcionamento: se a sessao chamada 'tabuleiro' não
			//tiver sido iniciada, inicializa ela apontando para
			//a matriz criada anteriormente e  após isso, executa
			//a função MontarTabuleiro, passando a sessão 'tabuleiro'
			//como parametro. Se ela já tiver sido iniciada, apenas  executa a função 
			//MontarTabuleiro, passando a 'sessão' tabuleiro como parâmetro
			if(!isset($_SESSION['tabuleiro'])){
				$_SESSION['tabuleiro'] = $y;
				MontarTabuleiro($_SESSION['tabuleiro'] );
			}else{
				MontarTabuleiro($_SESSION['tabuleiro']);
			}

			?>
        </div>

        <!--Menu para reiniciar a partida-->
        <div class="col-md-2 options">
            <h2>Opções</h2>
            <form method="POST" class="block-options">
                <input type="hidden" name='action' value="ResetGame" />
                <input class="btn btn-light" type="submit" value="Reiniciar o Jogo!">
            </form>
        </div>
        <!--Fim do menu para reiniciar a partida-->

    </div>
</body>

</html>