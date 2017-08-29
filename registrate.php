<?php

if(filter_input(INPUT_POST, 'submit')) {

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $name = filter_input(INPUT_POST, 'name');
  $Pass = filter_input(INPUT_POST, 'Pass');
  $homeadress = filter_input(INPUT_POST, 'homeadress');
  $phone = filter_input(INPUT_POST, 'phone');
  $account = filter_input(INPUT_POST, 'account');
  $company = filter_input(INPUT_POST, 'company');
  $companyplace = filter_input(INPUT_POST, 'companyplace');
  $CRN = filter_input(INPUT_POST, 'CRN');

  $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
  $sql = mysqli_query($conn, $query);

  if(mysqli_num_rows($sql) == 1) {

    echo 'Mar regisztaltak ezzel az email cimmel !';

  } else {

    $query = "INSERT INTO users (Nev, Artipus, jelszo, email, Varos, telefonszam, szamlaszam, ceg_neve, ceg_cime, cegjegyzekszam) 
              VALUES ('$name',1,'$Pass','$email','$homeadress','$phone','$account','$company','$companyplace','$CRN')";
    mysqli_query($conn, $query);

    echo 'sikeres hozzaadas!';

  }
}  

echo ' <script>
          $(document).ready(function(){
            $(".sth").hide();
            $(\'input[type="checkbox"]\').click(function(){
              if($(this).prop("checked")){
                $(".sth").show(200);
                } else {
                $(".sth").hide(200);
                }
            })
          });
       </script>
';

echo'
<form class="form-horizontal" method="post" action="index.php?a=reg">
  <fieldset>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Name</label>
      <div class="col-lg-4">
        <input type="text" class="form-control" id="inputEmail" placeholder="Name" name="name">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Email</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="Email" name="email">
        </div>
    </div>
    <div class="form-group">
      <label for="inputPassword" class="col-lg-4 control-label">Password</label>
      <div class="col-lg-4">
        <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="Pass">
      </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Home Adress</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="Home Adress" name="homeadress">
        </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">PhoneNumber</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="Phone Number" name="phone">
        </div>
        </div>
        <div class="form-group">
          <label for="inputEmail" class="col-lg-4 control-label">Account Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="inputEmail" placeholder="Account" name="account">
            
            <div class="checkbox">
          <label>
            <input type="checkbox"> Do You Have a Company?
          </label>
        </div>
        </div>
        </div>
        
    ';
echo '
  <div class="sth">
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Company Name</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="Company Name" name="company">
        </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Company Place</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="Company Place" name="companyplace">
        </div>
    </div>
    <div class="form-group">
      <label for="inputEmail" class="col-lg-4 control-label">Company Registration Number</label>
        <div class="col-lg-4">
          <input type="text" class="form-control" id="inputEmail" placeholder="CompanyRegNum" name="CRN">
        </div>
    </div>
   </div> 
';
  echo  '<div class="form-group">
      <div class="col-lg-4 col-lg-offset-4">
        <input type="submit" class="btn btn-primary" name="submit" value="registrate">
      </div>
    </div>
  </fieldset>
</form>
';

?>