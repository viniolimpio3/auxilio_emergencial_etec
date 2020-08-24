<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro Auxílio Emergencial Estudantil</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    </head>
    <script>    
        //RETIRAR QUERIES da url QUANDO DER UM REFRESH
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        }
    </script>


    <body>
        <?php
            $acceptedInputs = array(
                'student_name' => 'Nome',
                'student_city' => 'Cidade',
                'student_state' => 'Estado',
                'student_school' => 'Escola',
                'student_rm' => 'RM'
            );
            $input_values = array();
            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){
                foreach($acceptedInputs as $key => $input){
                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        echo 'err';
                        $err =  "O campo $input é obrigatório!";
                        header("location: index.php?err=$err");
                        break;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }
                }         

                require('./database/connection.php');
                try{

                    $query = "INSERT INTO aux_em (student_name, student_city, student_state, student_school, student_rm)
                    values(?, ?, ?, ?, ?)";

                    $command = $connection->prepare($query);

                    $command->bindParam(1,$input_values['student_name']);
                    $command->bindParam(2,$input_values['student_city']);
                    $command->bindParam(3,$input_values['student_state']);
                    $command->bindParam(4,$input_values['student_school']);
                    $command->bindParam(5,$input_values['student_rm']);

                    if($command->execute() and $command->rowCount() > 0){
                        $successMessage = "Parabéns " . $input_values['student_name'] . ", você foi cadastrado com sucesso.";
                        header("location: index.php?success=$successMessage");
                    }else{
                        throw new PDOException('Não foi possível cadastrar o usuário. Tente novamente mais tarde.');
                    }

                }catch(PDOException $e){
                    echo ("Error: {$e->getMessage()}");
                }       
            }else{
        ?>


        

            <div class="container mt-5">
                <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="index.php?send=yes" method="POST" >
                        <label for="student_name">Nome:</label>
                        <input type="text" name="student_name" id="student_name" class="form-control">

                        <label for="student_city">Cidade:</label>
                        <input type="text" name="student_city" id="student_city" class="form-control">

                        <label for="student_state">Estado</label>
                        <input type="text" name="student_state" id="student_state" class="form-control">

                        <label for="student_school">Escola:</label>
                        <input type="text" name="student_school" id="student_school" class="form-control">

                        <label for="student_rm">RM:</label>
                        <input type="text" name="student_rm" maxlength="6" id="student_rm" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Cadastrar</button>

                    </form>
                    
                    <?php if(isset($_GET['err'])): ?>
                        <div class="mt-4 alert-danger alert">
                            <?=$_GET['err']?>
                        </div>
                    <?php endif ?>

                    <?php if(isset($_GET['success'])): ?>
                        <div class="mt-4 alert-success alert">
                            <?=$_GET['success']?>
                        </div>
                    <?php endif ?>
            
                </div>
            </div>
        <?php }//fim else!! ?>

    </body>
</html>