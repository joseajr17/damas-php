<?php 

//importacao das funções
require_once "functions.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo de Damas</title>
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
            <h2>Controles de Movimentação</h2>
            <form method="POST" class="block-options">
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Origem Linha" name="origemLinha" />
                    <input class="form-control" type="text" placeholder="Origem Coluna" name="origemColuna" />
                    <input class="form-control" type="text" placeholder="Destino Linha" name="destinoLinha" />
                    <input class="form-control" type="text" placeholder="Destino Coluna" name="destinoColuna" />
                </div>
                <input class="btn btn-light" type="submit" value="Movimentar">
            </form>
        </div>

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

        //se a variavel global session nao tiver sido iniciada, inicializa ela
        //apontando para a matriz criada anteriormente
        //após isso, executa a função MontarTabuleiro, passando a SESSION tabuleiro
        //como parametro. Se ela já tiver sido iniciada, apenas  executa a função 
        //MontarTabuleiro, passando a SESSION tabuleiro como parametro

        if(!isset($_SESSION['tabuleiro'])){
            $_SESSION['tabuleiro'] = $y;
            MontarTabuleiro($_SESSION['tabuleiro'] );
        }else{
            MontarTabuleiro($_SESSION['tabuleiro']);
        }

        //

        

        

        ?>

    </div>

</body>

</html>