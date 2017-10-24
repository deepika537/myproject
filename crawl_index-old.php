<?php
#http://www.webslesson.info/2016/02/live-table-add-edit-delete-using-ajax-jquery-in-php-mysql.html
ob_start();
session_start();
 require_once 'dbconnect.php';
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }
 // select loggedin users detail
 $res=mysqli_query($conn,"SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 ?>
 <div>
 <ul style="list-style-type: none;float:right;padding-right:50px;padding-top:10px;">
   <li style="color:#216288"><span class="glyphicon glyphicon-user"></span> <b><?php echo $userRow['userName']; ?>&nbsp;&nbsp;</b></li>
   <li><a href="Logout.php?logout">Logout</a></li>
 </ul>
 </div>
<html>
      <head>
           <title>Web Crawler</title>
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
           <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
           <style>
           table.fixed {table-layout:fixed; width:1300px;}/*Setting the table width is important!*/
           table.fixed td {overflow:hidden;}/*Hide text outside the cell.*/
           td.details-control {
           background: url('resources/details_open.png') no-repeat center center;
           cursor: pointer;
           }
           tr.shown td.details-control {
           background: url('resources/details_close.png') no-repeat center center;
           }
           </style>
      </head>
      <body>
           <div class="container" style="margin-left:200px;">
                <br />
                <br />
                <br />
                <div class="table-responsive" style="width:fit-content;">
                     <h3 align="center">Saved search Criteria</h3><br />
                     <div id="live_data"></div>
                     <div id="show-diff"></div>
                </div>
           </div>
      </body>
 </html>
 <script>
 function format ( d ) {
     // `d` is the original data object for the row
     return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
         '<tr>'+
             '<td>Full name:</td>'+
             '<td>'+d.name+'</td>'+
         '</tr>'+
         '<tr>'+
             '<td>Extension number:</td>'+
             '<td>'+d.extn+'</td>'+
         '</tr>'+
         '<tr>'+
             '<td>Extra info:</td>'+
             '<td>And any further details here (images etc)...</td>'+
         '</tr>'+
     '</table>';
 }
 $(document).ready(function(){
      function fetch_data()
      {
           $.ajax({
                url:"import.php",
                method:"POST",
                success:function(data){
                     $('#live_data').html(data);
                     $('#myTable').dataTable({
                       "pageLength": 5,
                       "iDisplayLength": 5,
                       "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                     });
                }
           });

      }

      fetch_data();
      //$('#myTable').DataTable();
      // Add event listener for opening and closing details
    $('#myTable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );
      $(document).on('click', '#btn_add', function(){
           var url = $('#url').text();
           var Depth = $('#Depth').text();
           var Email = $('#Email').text();
           var AnyKeyword = $('#AnyKeyword').text();
           var AllKeywords = $('#AllKeywords').text();
           var NoKeywords = $('#NoKeywords').text();
           var ExactKeywords = $('#ExactKeywords').text();
           if(url == '')
           {
                alert("Enter url");
                return false;
           }
           if(Depth == '')
           {
                alert("Enter Depth");
                return false;
           }
           if(Email == '')
           {
                alert("Enter Email");
                return false;
           }
           /*if(Keywords == '')
           {
                alert("Enter Keywords");
                return false;
           }*/
           $.ajax({
                url:"insert.php",
                method:"POST",
                data:{url:url,Depth:Depth,Email:Email,AnyKeyword:AnyKeyword,AllKeywords:AllKeywords,NoKeywords:NoKeywords,ExactKeywords:ExactKeywords},
                dataType:"text",
                success:function(data)
                {
                     alert(data);
                     fetch_data();
                }
           })
      });
      $(document).on('click', '#edit-info', function(){
       var id=$(".sub_chk:checked").parents("tr");
       id.find("td").eq(2).prop('contenteditable', true);
       id.find("td").eq(3).prop('contenteditable', true);
       id.find("td").eq(4).prop('contenteditable', true);
       id.find("td").eq(5).prop('contenteditable', true);
       id.find("td").eq(6).prop('contenteditable', true);
       id.find("td").eq(7).prop('contenteditable', true);
       id.find("td").eq(8).prop('contenteditable', true);
        });
      function edit_data(id, text, column_name)
      {
           $.ajax({
                url:"edit.php",
                method:"POST",
                data:{id:id, text:text, column_name:column_name},
                dataType:"text",
                success:function(data){
                     alert(data);
                     //fetch_data();
                }
           });
      }
      $(document).on('blur', '.url', function(){
           var id = $(this).data("id1");
           var url = $(this).text();
           edit_data(id, url, "url");
           var id1=$(this).parents("tr");
           id1.find("td").eq(2).prop('contenteditable', false);
      });
      $(document).on('blur', '.Depth', function(){
           var id = $(this).data("id2");
           var Depth = $(this).text();
           edit_data(id,Depth, "Depth");
           var id2=$(this).parents("tr");
           id2.find("td").eq(3).prop('contenteditable', false);
      });
      $(document).on('blur', '.Email', function(){
           var id = $(this).data("id3");
           var Email = $(this).text();
           edit_data(id, Email, "Email");
           var id3=$(this).parents("tr");
           id3.find("td").eq(4).prop('contenteditable', false);
      });
      $(document).on('blur', '.AnyKeyword', function(){
           var id = $(this).data("id4");
           var AnyKeyword = $(this).text();
           edit_data(id,AnyKeyword, "ANYKEYWORD");
           var id4=$(this).parents("tr");
           id4.find("td").eq(5).prop('contenteditable', false);
      });
      $(document).on('blur', '.AllKeywords', function(){
           var id = $(this).data("id9");
           var AllKeywords = $(this).text();
           edit_data(id,AllKeywords, "ALLKEYWORDS");
           var id9=$(this).parents("tr");
           id9.find("td").eq(6).prop('contenteditable', false);
      });
      $(document).on('blur', '.NoKeywords', function(){
           var id = $(this).data("id10");
           var NoKeywords = $(this).text();
           edit_data(id,NoKeywords, "NOKEYWORDS");
           var id10=$(this).parents("tr");
           id10.find("td").eq(7).prop('contenteditable', false);
      });
      $(document).on('blur', '.ExactKeywords', function(){
           var id = $(this).data("id11");
           var ExactKeywords = $(this).text();
           edit_data(id,ExactKeywords, "EXACTKEYWORDS");
           var id11=$(this).parents("tr");
           id11.find("td").eq(8).prop('contenteditable', false);
      });
      $(document).on('click', '.btn_delete', function(){
        var allVals = [];
        $(".sub_chk:checked").each(function() {
        allVals.push($(this).attr('data-id8'));
        });
        if(allVals.length <=0)
          {
            alert("Please select row.");
          }
          else {
         WRN_PROFILE_DELETE = "Are you sure you want to delete this row?";
         var check = confirm(WRN_PROFILE_DELETE);
         if(check == true){
         //for server side
         var join_selected_values = allVals.join(",");
         $.ajax({
            type: "POST",
            url: "delete.php",
            cache:false,
            data: 'ids='+join_selected_values,
            success: function(response)
            {
              alert(response);
              fetch_data();
            }
          });
        }
      }
  });
      $(document).on('click', '#selectall', function(){
        $(':checkbox').prop('checked', this.checked);
      });
      $(document).on('click', '.btn-primary', function(){
        //var id=$(this).parents("tr");
        var id=$(".sub_chk:checked").parents("tr");
        var url = id.find("td").eq(2).text();
        var Depth =id.find("td").eq(3).text();
        var Email = id.find("td").eq(4).text();
        var AnyKeyword =id.find("td").eq(5).text();
        var AllKeywords =id.find("td").eq(6).text();
        var NoKeywords =id.find("td").eq(7).text();
        var ExactKeywords =id.find("td").eq(8).text();
        $.ajax({
             url:"startcrawl.php",
             method:"POST",
             data:{url:url,Depth:Depth,Email:Email,AnyKeyword:AnyKeyword,AllKeywords:AllKeywords,NoKeywords:NoKeywords,ExactKeywords:ExactKeywords},
             dataType:"text",
             success:function(data)
             {
                  //alert(data);
                  fetch_data();
                  $('#show-diff').html(data);
             }
        })
   });
 });
 </script>
 <?php ob_end_flush();
  ?>
