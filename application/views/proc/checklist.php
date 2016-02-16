
<div class="container">
  <div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
          <!-- <input type="button" value="Save" id="save"> -->
          <button id="save" type="button" class="btn btn-default btn-lg">
            <span title = "Click to Save" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
          </button>
           &nbsp;Form List
          <a  class = "pull-right" id = "add_item" href="#"><span title = "Click to Add Form" class="glyphicon glyphicon-plus" aria-hidden="true"></span></a> </div>

      <?php echo validation_errors(); ?>
    <!-- Table -->
    <table class="table">
      <tr id = "item" style="display: none;">
        <td colspan="7">
            <!-- add new form  -->
            <?php echo validation_errors(); ?>
            <?php $form_attributes = array('id'=>'add_form'); ?>
            <?php echo form_open('proc/processing/checklist/'.$_SESSION['transaction_id'].'/add_form', $form_attributes); ?>

              <div class="form-group">
                  <label for="description">Description</label>
                  <textarea name="body" class="form-control" id="description" placeholder="description" title="Enter Description"></textarea>
              </div>

              <!-- show item search results -->
                <ul id =  "search_item_results">
                </ul>

              <div class="form-group">
                  <label for="short_name">Short Name</label>
                  <input name="heading" type="text" class="form-control" id="short_name" placeholder="Short Name">
              </div>


              <div class="form-group">
                  <label for="days">Form Due <input name = "days" size = "10" type = "text" title = "Enter the number of days from the specified date that the form is due"> Days From:</label>
                  <select form ="add_form" name = "date_type" class="form-control">
                    <?php foreach ($date_types as $date_type): ?> 
                      <option value = "<?php echo  $date_type['id']; ?>"><?php echo $date_type['date']; ?></option>
                    <?php endforeach; ?>
                  </select>
              </div>
              <input type= "hidden"  value= "1" name = "queue">
              <!-- <input form="add_form" name="test 1 " type="hidden" value="test"> -->
              <div class="form-group">
                  <label for="parties">Select All Signers</label>
                  <select form ="add_form" multiple name = "parties[]" class="form-control">
                    <?php foreach ($parties as $party): ?> 
                      <option value ="<?php echo $party['id'];?>"><?php echo $party['name']; ?></option>  
                    <?php endforeach; ?>
                  </select>
              </div>

              <!-- set item type to 1 reminders -->
              <input name= "item_type" type = "hidden" value= "2" /> 
              <input name= "tc_id" type = "hidden" value= "<?php echo $_SESSION['user_id']; ?>" /> 
              <input name= "transaction_id" type = "hidden" value= "<?php echo $_SESSION['transaction_id']; ?> " /> 

              <!-- form id -->

              <input id="item_id" name= "item_id" type = "hidden" value="1" /> 

              <!-- SUBMIT    -->
              <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </td>
      </tr>
      <tr>
        <th>Status</th>
        <th>All Signed</th>
        <th>All Buyer Signed</th>
        <th>All Seller Signed</th>
        <th>Abbr</th>
        <th>Description</th>
        <th>Notes</th>
      </tr>

      <!-- list the items -->

      <!-- add new form  -->
      <?php echo validation_errors(); ?>
      <?php $form_attributes = array('id'=>'update_checklist_status'); ?>
      <?php echo form_open('proc/processing/checklist/'.$_SESSION['transaction_id'].'/update_checklist_status', $form_attributes); ?>
      <!-- Table row and data -->
      <?php foreach ($checklist_items as $checklist_item): ?>
      <div>
        <tr>
          <td class="complete"><?php if($checklist_item['all_signed'] == 0){ echo "Incomplete"; }else{echo "Complete"; } ?><br />
            <a href="<?php echo 'del_item/' . $checklist_item['id']; ?>"><span title = "Click to Delete this item" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a> 
            <a class ="show_signers" title="Show All Signers" href="#"><span title = "Click to show individual signers" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></a>
          </td>
          <td><input type="checkbox" name="all_signed[]" value="<?php echo $checklist_item['id']; ?>"></td>
          <td><input type="checkbox" name="all_buyer_signed[]" value="<?php echo $checklist_item['id']; ?>" <?php //if($checklist_item['all_buyer_complete'] == 1) {echo "checked"; } ?>></td>
          <td><input type="checkbox" name="all_seller_signed[]" value="<?php echo $checklist_item['id']; ?>" <?php //if($checklist_item['all_seller_complete'] == 1) {echo "checked"; } ?>></td>
          <td class="search_name"><?php echo $checklist_item['heading']; ?></td>
          <td class="search_name"><?php echo $checklist_item['body']; ?></td>
          <?php $name = "notes[".$checklist_item['id']."]"; ?>
          <td><textarea name= "<?php echo $name; ?>" ><?php echo $checklist_item['notes']; ?></textarea></td>
        </tr>
          <tr style = "display: none; background-color: rgba(58, 48, 38, .5); color: #fff; " class = "signers">
          <!-- list individual parites -->
            <td></td>
            <td colspan= "5">
              <ul>
            <?php foreach ($checklist_item['parties'] as $party): ?>
            <li>
              <input type="checkbox" name="transaction_item_parties_signed[]" value="<?php echo $party['id']; ?>" <?php if($party['complete'] == 1){ echo "checked";} ?>>
              <?php echo $party['party']; ?>,&nbsp;<strong><?php echo $party['first_name']; ?>&nbsp;<?php echo $party['last_name']; ?></strong>
            </li>
            <?php endforeach; ?>
            </ul>
            </td>
          </tr>
            
      </div>
      <?php endforeach; ?>
    </table>
    </div>
</div>
