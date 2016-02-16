<section id="login" class="container">
    <h1 id="greeting">Welcome to On-It Transactions</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
      Login
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Login</h4>
          </div>
          <div class="modal-body">
             <?php echo form_open('welcome/check_login'); ?>

              <span style="color:white;"><?php echo validation_errors(); ?></span>
              
              <div class="form-group">
                <?php 
                  // set params for input
                  $data = array(
                        'class'=>'form-control',
                        'placeholder'=>'Email',
                        'name'=>'email',
                        'type'=>'email');
                        ?>
                <?php echo form_input($data);
                //should echo <input type="text" placeholder="Email" class="form-control">
                ?>
              </div>
              <div class="form-group">
                <?php
                  $data = array (
                      'placeholder'=>'Password',
                      'class' => 'form-control',
                      'name'=>'password');
                  echo form_password($data); 
                //<input type="password" placeholder="Password" class="form-control">
                ?>
              </div>
              <button type="submit" name="submit" class="btn btn-success">Sign in</button>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- modal -->
    
</section> <!-- container -->
