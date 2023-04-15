<?php 

function montarTabuleiro($y){
	echo '<table class="table table-bordered table-hover table-stripped " style="text-align:center; font-size: 30px">';
	$teste = 0;
	
	for ($iLinha = 0; $iLinha<8; $iLinha++){
		echo '<tr>';
		
		($iLinha % 2 == 0) ? $teste = 0 : $teste = 1;
		
		for ($iColuna = 0; $iColuna<8; $iColuna++){

			if ($teste == 1 ){
				echo '<td style="background-color:#aaa">';

				echo '<div style="font-size:10px; font-weight:bold">'.$iLinha . '-' . $iColuna . '</div>';
				$teste = 0;
			}

			else {
				$teste = 1;
				echo '<td style="background-color:#fff">';
			}

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

function fazerMovimento(){

}

function validarMovimento(){

}

function botMovimentar(){
	
}

?>