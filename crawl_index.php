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
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
           <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

           <!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script-->
           <script type="text/javascript" src="cron/jquery-cron.js"></script>
           <link type="text/css" href="cron/jquery-cron.css" rel="stylesheet" />


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
                      <div id="new_update"></div>
                </div>
           </div>
      </body>
 </html>
 <script>

 function extractHostname(url) {
    var hostname;
        //find & remove protocol (http, ftp, etc.) and get hostname
    if (url.indexOf("://") > -1) {
        hostname = url.split('/')[2];
    }
    else {
        hostname = url.split('/')[0];
    }
    //find & remove port number
    hostname = hostname.split(':')[0];
    //find & remove "?"
    hostname = hostname.split('?')[0];
    return hostname;
}

function extractRootDomain(url) {
    var domain = extractHostname(url),
        splitArr = domain.split('.'),
        arrLen = splitArr.length;

    //extracting the root domain here
    //if there is a subdomain
    if (arrLen == 2){
        domain = splitArr[0];
    }
    if (arrLen > 2) {
        domain = splitArr[arrLen - 2];
        //check to see if it's using a country code (i.e. ".me.uk")
        if (splitArr[arrLen - 1].length == 2 && splitArr[arrLen - 1].length == 2) {
            //this is using a Country Code (ccTLD)
            domain = splitArr[arrLen - 3];
        }
    }
    return domain;
}
var cron_field="";
 $(document).ready(function(){
      function fetch_data()
      {
           $.ajax({
                url:"import.php",
                method:"POST",
                success:function(data){
                     $('#live_data').html(data);
                     $('#myTable').DataTable({
                       //"scrollY":"350px",
                       //"scrollCollapse": true,
                       "pageLength": 5,
                       "iDisplayLength": 5,
                       "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                       "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
                       {
                        if ( aData[17] == 1 )
                        {
                        $('td', nRow).css('background-color', 'LightGray');
                        }
                        else if ( aData[17] == 0 )
                        {
                        $('td', nRow).css('background-color', 'White');
                        }
                        else
                        {
                        $('td', nRow).css('background-color', 'Blue');
                        }
                      },
                      'columnDefs': [
                        { 'orderData':[17], 'targets': [0] },
                        {
                          //'visible': false,
                          //'searchable': false,
                          'targets': [17]
                        },
                      ]
                     });
                     $('#insertexample1').cron({
                         initial: "* * * * *",
                         onChange: function() {
                             $('#insertCronvalue').text($(this).cron("value"));
                         }
                     });
                     window.cron_field=$('#editexample1').cron({initial: "* * * * *"});

                }

           });

      }
      fetch_data();
      // $(document).on('click', '.url', function(){
      //   window.open($(this).text(),'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no');return false;
      // });
      $(document).on('click', '#btn_add', function(){
        $('#insertModal').modal('show');
        if($('#insertCronvalue').text()=="")
        {
        $('#insertexample1').cron({
            initial: "* * * * *",
            onChange: function() {
                $('#insertCronvalue').text($(this).cron("value"));
            }
        });
        window.cron_field=$('#editexample1').cron({initial: "* * * * *"});
      }
        });
        $(document).on('click','#insertresults',function () {
          var checked = $(this).is(':checked');
          if(checked)
          {$("#emailrow").show();
          } else {
          $("#emailrow").hide();
          }
      });
      $(document).on('click','#editresults',function () {
      if($('#editresults').prop('checked') == true){
          $("#editemailrow").show();
      } else {
          $("#editemailrow").hide();
      }
    });
    $(document).on('change','#insertalert',function () {
     if($('#insertalert').val()=="Anychange")
        {$('#depthrow').hide();
    } else {
        $('#depthrow').show();
    }
   });
   $(document).on('change','#editalert',function () {
    if($('#editalert').val()=="Anychange")
       {$('#editdepthrow').hide();
   } else {
       $('#editdepthrow').show();
   }
  });

        $(document).on('click', '.insertProperties', function(){
           var url = $('#inserturl').val();
           var Email = $('#insertemail').val();
           var keyword1 = $('#insertkword1').val();
           var keyword2 = $('#insertkword2').val();
           var keyword3 = $('#insertkword3').val();
           var keyword4 = $('#insertkword4').val();
           var logic1=$('#insertselect1').val();
           var logic2=$('#insertselect2').val();
           var logic3=$('#insertselect3').val();
           var insertalert=$('#insertalert').val();
           var results='';
           var Cronvalue=$('#insertCronvalue').text();
           var sendemail="no";
           var domain=extractRootDomain(url);
           if(insertalert=="Anychange")
            {var Depth=1;}
           else
            {var Depth = $('#insertdepth').val();}
           if($('#insertresults').prop('checked') == true){
             sendemail="yes";
           }
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
           if(sendemail=="yes"&&Email=="")
           {
             alert("Please provide emailid");
             return false;
           }
           if(insertalert=="Keyword")
           {
             if(keyword1==""||keyword2==""||keyword3==""||keyword4=="")
               {alert("Please enter keywords");
               return false;}
           }
           $('#insertModal').modal('hide');
           $.ajax({
                url:"valid_url.php",
                method:"POST",
                data:{url:url,domain:domain},
                dataType:"text",
                success:function(data)
                {
                var result = $.trim(data);
                if(result === 'valid'){
           $.ajax({
                url:"insert.php",
                method:"POST",
                data:{url:url,Depth:Depth,Email:Email,keyword1:keyword1,keyword2:keyword2,keyword3:keyword3,keyword4:keyword4,logic1:logic1,logic2:logic2,logic3:logic3,insertalert:insertalert,results:results,Cronvalue:Cronvalue,sendemail:sendemail,domain:domain},
                dataType:"text",
                success:function(data)
                {
                     alert(data);
                     fetch_data();
                }
           });
         }
         if(result === 'duplicate'){
                        WRN_PROFILE_ADD = "Click OK to add a duplicate monitor?";
                        var check = confirm(WRN_PROFILE_ADD);
                        if(check == true){
                            $.ajax({
                                url:"insert.php",
                                method:"POST",
                                data:{url:url,Depth:Depth,Email:Email,keyword1:keyword1,keyword2:keyword2,keyword3:keyword3,keyword4:keyword4,logic1:logic1,logic2:logic2,logic3:logic3,insertalert:insertalert,results:results,Cronvalue:Cronvalue,sendemail:sendemail,domain:domain},
                                dataType:"text",
                                success:function(data)
                                {
                                     alert(data);
                                     fetch_data();
                                }
                            });
                        }else{
                            alert('Duplicate monitor not added.');
                        }
                    }
                    if(result === 'invalid'){
                        alert('URL entered was invalid');
                        fetch_data();
                    }
                }
            });
      });
      $(document).on('click', '.Results', function(){
        var tr = $(this).parents("tr");
        var id=tr.find("td").eq(1).text();
        tr.css("background-color","LightGray");
        var read=1;
        $.ajax({
             url:"read_edit.php",
             method:"POST",
             data:{id:id,read:read},
             dataType:"text",
             success:function(data){
                  fetch_data();
             }
        })
      });
      $(document).on('click', '#btn_mark', function(){
        var allVals = [];
        $(".sub_chk:checked").each(function() {
        allVals.push($(this).attr('data-id15'));
        });
        if(allVals.length <=0)
          {
            alert("Please select row.");
          }
          else {
        var join_selected_values = allVals.join(",");
        var read=1;
        $.ajax({
             url:"read_edit.php",
             method:"POST",
             data:{read:read,id:join_selected_values},
             dataType:"text",
             success:function(data){
                  fetch_data();
             }
        })
        }
      });
      $(document).on('change','#editexample1',function(){$('#editCronvalue').text($(this).cron("value"));});
      $(document).on('click', '.btn_properties', function(){
        var tr = $(this).parents("tr");
        var id=tr.find("td").eq(1).text();
        var url=tr.find("td").eq(3).text();
        var depth=tr.find("td").eq(4).text();
        var Alert=tr.find("td").eq(6).text();
        var Cronvalue = tr.find("td").eq(7).text();
        var Results = tr.find("td").eq(8).text();
        var Email = tr.find("td").eq(9).text();
        var keyword1 = tr.find("td").eq(10).text();
        var keyword2 = tr.find("td").eq(11).text();
        var keyword3 = tr.find("td").eq(12).text();
        var keyword4 = tr.find("td").eq(13).text();
        var logic1 = tr.find("td").eq(14).text();
        var logic2 = tr.find("td").eq(15).text();
        var logic3 = tr.find("td").eq(16).text();
        var sendemail=tr.find("td").eq(18).text();
        cron_field.cron("value",Cronvalue);
        $('#editid').val(id);
        $('#editurl').val(url);
        $('#editdepth').val(depth);
        $('#editalert').val(Alert);
        $('#editCronvalue').text(Cronvalue);
        if(Alert=="Anychange")
        {$('#editdepthrow').hide();}
        else {
          $('#editdepthrow').show();
        }
        if(sendemail=="yes")
        {$('#editresults').prop('checked',true);}
        else
        {$('#editresults').prop('checked',false);}
        if($('#editresults').prop('checked') == true){
            $("#editemailrow").show();
        } else {
            $("#editemailrow").hide();
        }
        $('#editemail').val(Email);
        $('#kword1').val(keyword1);
        $('#kword2').val(keyword2);
        $('#kword3').val(keyword3);
        $('#kword4').val(keyword4);
        $('#select1').val(logic1);
        $('#select2').val(logic2);
        $('#select3').val(logic3);
       });
      $(document).on('click', '.saveProperties', function(){
        $('#propertiesModal').modal('hide');
        var editid=$('#editid').val();
        var editurl=$('#editurl').val();
        var editalert=$('#editalert').val();
        var editemail=$('#editemail').val();
        var editCronvalue=$('#editCronvalue').text();
        var editemail=$('#editemail').val();
        var editkeyword1=$('#kword1').val();
        var editkeyword2=$('#kword2').val();
        var editkeyword3=$('#kword3').val();
        var editkeyword4=$('#kword4').val();
        var editlogic1=$('#select1').val();
        var editlogic2=$('#select2').val();
        var editlogic3=$('#select3').val();
        var editsendemail="no";
        if(editalert=="Anychange")
        {
          var editdepth=1;
        }
        else {
          var editdepth=$('#editdepth').val();
        }
        if($('#editresults').prop('checked') == true){
          editsendemail="yes";
        }
        else if($('#editresults').prop('checked') == false){
          editsendemail="no";
          editemail="";
        }
        if(editsendemail=="yes"&&editemail=="")
        {
          alert("Please provide emailid");
          return false;
        }
        if(editalert=="Keyword")
        {
          if(editkeyword1==""||editkeyword2==""||editkeyword3==""||editkeyword4=="")
           {alert("Please enter keywords");
            return false;}
        }
        $.ajax({
             url:"edit.php",
             method:"POST",
             data:{editid:editid, editurl:editurl, editdepth:editdepth,editemail:editemail,editsendemail:editsendemail,editalert:editalert,editCronvalue:editCronvalue,editemail:editemail,editkeyword1:editkeyword1,editkeyword2:editkeyword2,editkeyword3:editkeyword3,editkeyword4:editkeyword4,editlogic1:editlogic1,editlogic2:editlogic2,editlogic3:editlogic3},
             dataType:"text",
             success:function(data){
                  alert(data);
                  fetch_data();
             }
        })
      });
      $(document).on('click', '.btn_delete', function(){
        var allVals = [];
        $(".sub_chk:checked").each(function() {
        allVals.push($(this).attr('data-id15'));
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
      $(document).on('click', '.startcrawl', function(){
        var allVals = [];
        $(".sub_chk:checked").each(function() {
        allVals.push($(this).attr('data-id15'));
        });
        if(allVals.length <=0)
          {
            alert("Please select row.");
          }
        else {
        for (i = 0; i < allVals.length; i++) {
        //$("td[data-id3='"+allVals[i]+"']").text("PENDING");
        var url = $("td[data-id1='"+allVals[i]+"']").text();
        var Depth =$("td[data-id2='"+allVals[i]+"']").text();
        var Alert=$("td[data-id4='"+allVals[i]+"']").text();
        var Cronvalue = $("td[data-id5='"+allVals[i]+"']").text();
        var Results = $("td[data-id6='"+allVals[i]+"']").text();
        var kword1 = $("td[data-id8='"+allVals[i]+"']").text();
        var kword2 = $("td[data-id9='"+allVals[i]+"']").text();
        var kword3 = $("td[data-id10='"+allVals[i]+"']").text();
        var kword4 = $("td[data-id11='"+allVals[i]+"']").text();
        var select1 = $("td[data-id12='"+allVals[i]+"']").text();
        var select2 = $("td[data-id13='"+allVals[i]+"']").text();
        var select3 = $("td[data-id14='"+allVals[i]+"']").text();
        var sendemail = $("td[data-id18='"+allVals[i]+"']").text();
        var email = $("td[data-id19='"+allVals[i]+"']").text();
        //$("td[data-id3='"+allVals[i]+"']").html('<img src="http://preloaders.net/preloaders/287/Filling%20broken%20ring.gif"> loading...');
        //$.ajaxStart(function() {$("td[data-id3='"+allVals[i]+"']").text("PENDING")});
        var indexvalue=allVals[i];
       $.ajax({
          url:"startcrawl.php",
          indexValue:indexvalue,
          link:url,depth:Depth,
           type: "POST",
          data:{url:url,
                Depth:Depth,
                Alert:Alert,
                Cronvalue:Cronvalue,
                Results:Results,
                keyword1:kword1,
                keyword2:kword2,
                keyword3:kword3,
                keyword4:kword4,
                  oper1 : select1,
                  oper2 : select2,
                  oper3 : select3,
                  email:email,
                sendemail:sendemail},
          dataType:"text",
          //async:false,
          beforeSend: function () {$("td[data-id3='"+allVals[i]+"']").text("pending");},
          success:function(response)
          {   //alert(response);
              //$('#new_update').html(response);
              var index_value=this.indexValue;
              //setTimeout(function () {$("td[data-id3="+index_value+"]").text("pending");}, 20);
              //$("td[data-id3="+index_value+"]").text("pending");
              if(response=="no new content added")
              {$("#data-id6"+index_value).text("No change");}
              else if(response=="no new content added for searched keywords")
              {
                $("#data-id6"+index_value).text("No new results found for specified keywords");
              }
              else if(response.indexOf("The following page has changed!") !== -1){
                $("#data-id6"+index_value).text("New results");
              }
              else if(response.indexOf("childurl not Inserted") !== -1){
                $("#data-id6"+index_value).text("Error Occured");
              }
              else if(response=="page not found"){
                $("#data-id6"+index_value).text("Error occured (404)");
              }
              else{
                $("#data-id6"+index_value).text("Error occured (Time out)");
              }
              var data1=this.link;var data2=this.depth;var data3=response;
              setTimeout(function () {$("td[data-id3="+index_value+"]").text("complete");}, 1000);
              $.ajax({
                   url:"updatestatus.php",
                   method:"POST",
                   data:{url:data1,depth:data2,response:data3},
                   success:function(data){

                    setTimeout(function () {fetch_data();},2000);
                   }});
               //setTimeout(function () {fetch_data();},2000);
               //fetch_data();
          }
     })
   }
 }
   });
   // Add event listener for opening and closing details
   $(document).on('click', 'td.details-control', function () {
     var table = $('#myTable').DataTable();
       var tr = $(this).closest('tr');
       var row = table.row( tr );
       var id=tr.find("td").eq(1).text();
       var outputdata="";
       $.ajax({
          url:"crawl_index_new.php",
          method:"POST",
          dataType:"json",
          data:{urlid:id},
          success:function(response)
          {
              for(var i = 0, len = response.length; i < len; i++) {
               //outputdata+="<tr><td><a href='' onclick=window.open('"+response[i].childurl+"','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no');return false;>" + response[i].childurl + "</a></td></tr>";
               outputdata+="<tr><td><a href="+response[i].childurl+" target='_blank'>" + response[i].childurl + "</a></td></tr>";
              }
         },
         async: false
      })
       if ( row.child.isShown() ) {
             // This row is already open - close it
             row.child.hide();
             tr.removeClass('shown');
         }
         else {
            // Open this row
            row.child(outputdata).show();
            tr.addClass('shown');
        }
    } );
 });
 </script>
 <?php ob_end_flush();
  ?>
