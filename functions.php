<?php 

function montarTabuleiro($y){
	$teste = 0;

	//criação da tabela que representa o tabuleiro
	echo '<table class="table table-bordered table-hover table-stripped " style="text-align:center; font-size: 30px">';
	
	//criação das linhas do tabuleiro
	for ($iLinha = 0; $iLinha<8; $iLinha++){
		echo '<tr>';
		
		($iLinha % 2 == 0) ? $teste = 0 : $teste = 1;
		
		//criação das colunas do tabuleiro
		for ($iColuna = 0; $iColuna<8; $iColuna++){

			//se a soma dos indices for ímpar, o brackground da posição da coluna vai ser escura
			if ($teste == 1 ){
				echo '<td style="background-color:#aaa">';

				echo '<div style="font-size:10px; font-weight:bold">'.$iLinha . '-' . $iColuna . '</div>';
				$teste = 0;
			}
			//caso contrário, se a soma der par, o background da coluna vai ser clara
			else {
				$teste = 1;
				echo '<td style="background-color:#fff">';
			}

			//switch para mostrar as peças, se for '0' ou 'D' mostra a peça preta
			// se for 'X' ou 'C' mostra a peça branca
			//caso contrário só mostra o espaço sem peça
			switch ($y[$iLinha][$iColuna]) {
				case '0':
				case 'D':
				  echo "<img width='40' src='resources/images/black.png'>";
				  break;
				case 'X':
				case 'C':
				  echo "<img width='40' src='resources/images/white.png'>";
				  break;
				default:
				echo <<< FRASE
                <div style="height:50px">
                <div style="width:50px">
                </div>
                FRASE;
			  }			  

			echo '</td>';
		}
		echo '</tr>';
	}
	echo <<< FRASE
    </table>
    <div class="row">
    </div>
    FRASE;
}

function fazerMovimento($y, $origemLinha, $origemColuna, $destinoLinha, $destinoColuna){
	//movimento normal
	$y[$destinoLinha][$destinoColuna] = $y[$origemLinha][$origemColuna];
	$y[$origemLinha][$origemColuna] = '-';
	

	//movimento pra capturar a peça do adversario
	if ($destinoColuna - $origemColuna == 2 || $destinoColuna - $origemColuna == -2){

		$ColunaCapturada = (($destinoColuna - $origemColuna)/2) + $origemColuna;
		$LinhaCapturada  = (($destinoLinha - $origemLinha)/2) + $origemLinha;
		$y[$LinhaCapturada][$ColunaCapturada] = '-';

	}
	return $y;

}

function botMovimentar($y){
	
	//esse primeiro for procura se há uma peça do jogador a
	//frente da peça do bot, para que o bot possa capturar
	for ($i = 0; $i < 8; $i++ ){
		for ($j = 0; $j < 8; $j++){
			if ($y[$i][$j] == '0'){
				if ($j + 2 < 7){	
					if($y[$i + 1][$j + 1] == 'X' && $y[$i + 2][$j +2] == '-'){
						return fazerMovimento($y, $i, $j, $i+2, $j+2);
					}
			
				}
				else if($j - 2>0){
					if($y[$i + 1][$j - 1] == 'X' && $y[$i + 2][$j -2] == '-'){
						return fazerMovimento($y, $i, $j, $i+2, $j-2);

					}
				}
			}
		}
	}

	//esse segundo for verifica se há algum campo livre a 
	//frente da peça do bot para poder movimentar
	for ($i = 0; $i < 8; $i++ ){
		for ($j = 0; $j < 8; $j++){
			if ($y[$i][$j] == '0'){
				if ($j + 1 < 7 && $j - 1 > 0){					
					if($y[$i + 1][$j + 1] == '-'){
						return fazerMovimento($y, $i, $j, $i+1, $j+1);
					}
					else if($y[$i + 1][$j - 1] == '-'){
						return fazerMovimento($y, $i, $j, $i+1, $j-1);

					}

				}
			}
		}
	}
	
}

function validarMovimento($y, $origemLinha, $origemColuna, $destinoLinha, $destinoColuna){

		if (

		//movimento simples: direita e frente
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha][$destinoColuna] == '-'
		&& $destinoLinha == $origemLinha - 1 
		&& $destinoColuna == $origemColuna + 1  
		
		|| 
		
		//movimento simples: esquerda e frente 
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha][$destinoColuna] == '-'
		&& $destinoLinha == $origemLinha - 1
		&& $destinoColuna == $origemColuna - 1)
		
	{
		return true;
	}

	//movimento de captura: direita e frente
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha+1][$destinoColuna-1] == '0'
		&& $destinoLinha == $origemLinha-2
		&& $destinoColuna == $origemColuna + 2){

		$y[$destinoLinha+1][$destinoColuna-1] = '-';
		return true;

	}

	//movimento de captura: esquerda e frente
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha+1][$destinoColuna+1] == '0'
		&& $destinoLinha == $origemLinha-2
		&& $destinoColuna == $origemColuna - 2
		){

		$y[$destinoLinha+1][$destinoColuna+1] = '-';
		return true;

	}

	//movimento de captura: direita e trás
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha-1][$destinoColuna-1] == '0'
		&& $destinoLinha == $origemLinha+2
		&& $destinoColuna == $origemColuna+2
		){

		$y[$destinoLinha-1][$destinoColuna-1] = '-';
		return true;

	}

	//movimento de captura: esquerda e trás
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha-1][$destinoColuna+1] == '0'
		&& $destinoLinha == $origemLinha+2
		&& $destinoColuna == $origemColuna - 2
		){

		$y[$destinoLinha-1][$destinoColuna+1] = '-';
		return true;

	}
	////////////////----BOT----///////////////////////
	
	//movimento de captura: direita e frente
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha-1][$destinoColuna+1] == 'X'
		&& $destinoLinha == $origemLinha + 2
		&& $destinoColuna == $origemColuna - 2
		){

		$y[$destinoLinha-1][$destinoColuna+1] = '-';
		return true;

	}

	//movimento de captura: esquerda e frente
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha-1][$destinoColuna-1] == 'X'
		&& $destinoLinha == $origemLinha + 2
		&& $destinoColuna == $origemColuna + 2
		){


		$y[$destinoLinha-1][$destinoColuna-1] = '-';
		return true;

	}
	
	//movimento de captura: direita e trás
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha+1][$destinoColuna-1] == 'X'
		&& $destinoLinha == $origemLinha - 2
		&& $destinoColuna == $origemColuna + 2
		){

		$y[$destinoLinha+1][$destinoColuna-1] = '-';
		return true;

	}

	//movimento de captura: esquerda e trás
	else if(
		$y[$origemLinha][$origemColuna] == $_SESSION['turn']
		&& $y[$destinoLinha+1][$destinoColuna+1] == 'X'
		&& $destinoLinha == $origemLinha - 2
		&& $destinoColuna == $origemColuna - 2
		){

		$y[$destinoLinha+1][$destinoColuna+1] = '-';
		return true;

	}

	/////

	//comentei esse echo pq acho que serve pra nada
	//echo($y[$origemLinha][$origemColuna]);

	//if para validar o movimento das damas
	if($y[$origemLinha][$origemColuna] == 'D' || $y[$origemLinha][$origemColuna] == 'C'){
		if (abs($origemLinha - $destinoLinha) - abs($origemColuna - $destinoColuna) == 0){ 
			if($_SESSION['turn'] == 'X'){
				if($y[$origemLinha][$origemColuna] == 'C'){
					return true;
				}
			}
			else if($_SESSION['turn'] == '0'){
				if($y[$origemLinha][$origemColuna] == 'D'){
					return true;
				}
			}
		}
	}

	// if($origemLinha == "" || $origemLinha = null || !isset($origemLinha)){
	// 		return false;
	// }
	else
	{
		return false;
	}
	
}

function verificarVencedor($tabuleiro, $jogador)
{
    foreach ($tabuleiro as $linha) {
        foreach ($linha as $peca) {
            if ($peca == $jogador) {
                return false; // O jogador ainda possui peças, o jogo não tem vencedor
            }
        }
    }
    return true; // O jogador não possui mais peças, o jogo tem um vencedor
}

?>