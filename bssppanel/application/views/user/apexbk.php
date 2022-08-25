
<div class="container">

<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">Mobile</th>
      <th scope="col">Message_id</th>
      <th scope="col">Message</th>
       <th scope="col">Status</th>
        <th scope="col">Submit Date</th>
      <th scope="col">Delivered Date</th>
    </tr>
  </thead>
  <tbody>
      <?php 
      if(isset($data) && $data){
          foreach ($data as $full_data){
              ?>
        <tr>
      <th scope="col"><?php echo  $full_data['mobile'];?></th>
      <th scope="col"><?php echo  $full_data['message_id'];?></th>
        <th scope="col"><?php echo  $full_data['message'];?></th>
          <th scope="col"><?php echo  $full_data['status'];?></th>
            <th scope="col"><?php echo  $full_data['submit_date'];?></th>
              <th scope="col"><?php echo  $full_data['done_date'];?></th>
    </tr>
      <?php 
          }
          
      }
      ?>
    <tr>
      
    </tr>
   
</table>

</div>