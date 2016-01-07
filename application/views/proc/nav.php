<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url(); ?>/transaction/index">DebbieTC</a> 
          <a class="navbar-brand" href="<?php if(isset($_SESSION['transaction_id'])) { echo site_url('/proc/processing/cover/' . $_SESSION['transaction_id']); } else { echo "#";} ?>" ><strong style="color: red;"><?php echo $transaction_name; ?></strong></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo site_url(); ?>/proc/processing/checklist/<?php if(isset($_SESSION['transaction_id'])) { echo $_SESSION['transaction_id']; } ?>">Checklist <span class="sr-only">(current)</span></a></li>
        <li><a href="<?php echo site_url(); ?>/proc/processing/contacts/<?php if(isset($_SESSION['transaction_id'])) { echo $_SESSION['transaction_id']; } ?>">Contacts</a></li>
        <li><a href="<?php echo site_url(); ?>/proc/processing/dates/<?php if(isset($_SESSION['transaction_id'])) { echo $_SESSION['transaction_id']; } ?>">Dates</a></li>
        <li><a href="<?php echo site_url(); ?>/proc/processing/reminders/<?php if(isset($_SESSION['transaction_id'])) { echo $_SESSION['transaction_id']; } ?>">Reminders</a></li>
        
      </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input id = "item_search" type="text" class="form-control" placeholder="Search">
        </div>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo site_url(); ?>/transaction/home">Home</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>