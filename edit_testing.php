<html><head>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
$('#tbl').find('.save, .cancel').hide();
$('#tbl').on('click', '.edit', function(){
    $('#tbl').find('.save, .cancel').hide();
    $('#tbl').find('.edit').show();
    $('*').prop('contenteditable', false)
    $(this).hide().siblings('.save, .cancel').show();
      currentTD = $(this).closest('td').siblings()
      $.each(currentTD, function () {
      		$(this).attr("initialval", $(this).text())
          $(this).prop('contenteditable', true)
      });
});

$('#tbl').on('click', '.save', function() {
    var $btn = $(this);
    $('#tbl').find('.save, .cancel').hide();
    $btn.hide().siblings('.edit').show();
    currentTD = $(this).closest('td').siblings()
    $.each(currentTD, function () {
        $(this).prop('contenteditable', false)
    });
});

$('#tbl').on('click', '.cancel', function() {
    var $btn = $(this);
    $('#tbl').find('.save, .cancel').hide();
    $btn.hide().siblings('.edit').show();
    currentTD = $(this).closest('td').siblings()
    $.each(currentTD, function () {
        $(this).text($(this).attr("initialval"));
        $(this).prop('contenteditable', false)
    });
});
});
</script>
</head>
<body>
<table id="tbl" class="table">
  <tr>
    <td class="field1s">field1x</td>
    <td class="field2s">field2x</td>
    <td class="field3s">field3x</td>
    <td class="field4s">field4x</td>
    <td><button type="button" id="edit" class="edit btn btn-success">Edit</button>
    <button type="button" id="edit" style="display:none" class="save btn btn-primary">Save</button>
    <button type="button" id="edit" style="display:none" class="cancel btn btn-danger">Cancel</button></td>
  </tr>

    <tr>
    <td class="field1s">field1x</td>
    <td class="field2s">field2x</td>
    <td class="field3s">field3x</td>
    <td class="field4s">field4x</td>
    <td><button type="button" id="edit" class="edit btn btn-success">Edit</button>
    <button type="button" id="edit" style="display:none" class="save btn btn-primary">Save</button>
    <button type="button" id="edit" style="display:none" class="cancel btn btn-danger">Cancel</button></td>
  </tr>

    <tr>
    <td class="field1s">field1x</td>
    <td class="field2s">field2x</td>
    <td class="field3s">field3x</td>
    <td class="field4s">field4x</td>
    <td>
    <button type="button" id="edit" class="edit btn btn-success">Edit</button>
    <button type="button" id="edit" style="display:none" class="save btn btn-primary" >Save</button>
    <button type="button" id="edit" style="display:none" class="cancel btn btn-danger" >Cancel</button>
    </td>
  </tr>
</table>
</body>
</html>
