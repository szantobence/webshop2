<?php
  
if($a == 'login') {
  
  echo '
    <form class="form-horizontal" action="index.php?a=loginproc" method="post" style="margin: auto; width: 250px">
    <fieldset>
      <div class="form-group ';
      if (isset($error)) {
        echo 'has-error';
        }
      echo '">
      <label for="inputEmail" class="col-lg-3 control-label">Email</label>
        <div class="col-lg-10">
          <input type="text" class="form-control" name="email" id="inputEmail" placeholder="Email">
        </div>
      </div>
      <div class="form-group';
       if (isset($error)) {
        echo ' has-error';
        }
       echo '">
       <label for="inputPassword" class="col-lg-3 control-label">Password</label>
        <div class="col-lg-10">
          <input type="password" class="form-control" name="pass" id="inputPassword" placeholder="Password">
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-10 col-lg-offset-2">
          <input type="submit" name="submit" class="btn btn-primary">
        </div>
      </div>
    </fieldset>
  </form>
  ';

} //Kicsit meg csinositani kell ! :)
echo '<div style="text-align: center">
 <a href="index.php?a=reg">Registrate now !</a>
</div><br><br>';

?>

