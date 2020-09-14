<?php 
function err($messageTimeout=false){
    ?>
        <?php if(isset($_GET['err'])): ?>
            <div id="e-message" class="mt-4 alert-danger alert fade show" role="alert">
                <?= urldecode($_GET['err'])  ?>
            </div>
            <?php if($messageTimeout and is_numeric($messageTimeout)): ?>
                <script>
                    const m = document.getElementById('e-message')
                    setTimeout(function(){
                        hide([m])
                    }, <?= $messageTimeout ?>)
                </script>
            <?php endif ?>
        <?php endif ?>
    <?php
}
function success($location=false, $messageTimeout=false){
    ?>
        <?php if(isset($_GET['success'])): ?>
            <div class="mt-4 alert-success alert-dismissible alert fade show" role="alert">
                <?= urldecode( $_GET['success']); ?>
            </div>

            <?php if($location): ?>
                <script type="text/javascript">
                    setTimeout(() =>{
                        window.location = "<?= $location ?>";
                    }, <?= isset($messageTimeout) ? $messageTimeout : 4000 ?>)
                </script>
            <?php endif; ?>

        <?php endif ?>
    <?php
}


?>