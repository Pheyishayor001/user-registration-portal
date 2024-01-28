<!-- PHP code injected into HTML this doesn't need the curly braces. -->
<?php 
 if (!isset($errors)) {
    $errors = array(); 
 }
?> 
 

<?php if ($errors > 0) : ?>
    <!-- conditionally rendering the css class -->
    <div  class="<?php echo  $errors ? "error" : ' '; ?>">
        <?php foreach($errors as $error) : ?>
            
            <p><?php echo $error; ?></p>

        <?php endforeach ?>
    </div>
<?php endif ?>
    

