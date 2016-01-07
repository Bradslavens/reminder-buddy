
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">TC Slavens</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

              <?php 
                // set params for form
                $data = array(
                      'class'=>'navbar-form navbar-right'
                      );
                      ?>
          <?php echo form_open('welcome/check_login', $data); ?>

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
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
   
