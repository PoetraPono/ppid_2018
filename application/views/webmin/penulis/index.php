<div class="box-body">
   <div class="box">
      <div class="box-header">         
         <h3 class="box-title"><b>Data Penulis</b></h3>
      </div>
      <!-- /.box-header -->  
      <div class="box-body">
        <ul class="nav nav-pills" style="margin-bottom: 10px">
            <li class="active"><button type="button" onclick="showModal()" class="btn btn-primary">Add Data</button>  </li>
         </ul>
         <table id="data_place" width="100%" class="table table-bordered table-striped">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>-</th>
               </tr>
            </thead>
            <tbody>               
            </tbody>
         </table>
      </div>
      <!-- /.box-body -->
   </div>
</div>

<!-- Modal -->
    <div id="formModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div>
          <div class="modal-body">
            <div id="error_code" style="color: red">
                
            </div>
            
            <form id="form" class="form-horizontal" method="post" >
              
              <div class="form-group">
                <label class="control-label col-sm-2" for="nama">Nama:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="biografi">Biografi:</label>
                <div class="col-sm-10">
                  <textarea id="biografi" name="biografi" rows="4" cols="80"></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-sm-2" for="photo">Photo:</label>
                <div class="col-sm-10">
                  <input type="file" class="form-control" name="photo" id="photo">
                  <input type="hidden" class="form-control" name="image" id="image">
                </div>
              </div>

              <div class="form-group">
                  <label class="control-label col-sm-2" for="status">Status:</label>
                  <div class="col-sm-10"> 
                    <div class="radio-inline">
                       <label><input type="radio" value="1" name="status">Redaktur</label>
                    </div>

                    <div class="radio-inline">
                       <label><input type="radio" value="2" name="status">Reporter</label>
                    </div>
                    
                    <div class="radio-inline">
                       <label><input type="radio" value="0" name="status">Non Aktif</label>
                    </div>
                  </div>
              </div>            

              <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10">
                  <input type="hidden" name="id" id="id">
                  <a id="submit-form" class="btn btn-primary">Submit</a>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

<script>
   		var table; 
        $(document).ready(function() {
            table = $('#data_place').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo site_url('webmin/penulis/data/ajax')?>",
                    "type": "POST"
                },

                 columns: [
                    { data: "0" },
                    { data: "1" },
                    { data: "2" },
                    { data: "3" },
                    { data: "4" },
                ],
                "columnDefs": [
                { 
                    "targets": [ 0 ],
                    "orderable": false,
                },
                ],    });
                
        } );

        $("#submit-form").click(function() {
            var form = $('#form');
            var data = new FormData($("#form")[0]);
             $.ajax( {
                type: "POST",
                dataType: "json",
                url: form.attr( 'action' ),
                processData: false,
                contentType: false,
                data: data,
                success: function(data){
                    if(data.error === true)
                    {
                        $("#error_code").html(data.message).show().delay(1000).fadeOut();
                    } else {
                        $("#formModal").modal('hide');
                        swal('Succes!', data.message, 'success').catch(swal.noop);
                        table.ajax.reload();
                    }
                }
            } );
        });

        function showModal(type = 'add', id = 0)
        {   
            if(type == 'add')
            {
                $(".modal-title").html("Add Data Writer");
                $('#form').attr('action', '<?php echo site_url('webmin/penulis/store')?>');
                $("#biografi").val("");
                $("#nama").val("");                      
                $("#email").val("");
                $("#image").val("");
                $("#photo").val("");
                $("#id").val("");
                $("#formModal").modal('show');
            } else {
                $(".loader").show();
                $.ajax({ 
                    url: '<?php echo site_url('webmin/penulis/edit')?>/'+id,
                    data: {
                        'id' : id,
                        'single' : true
                    },
                    type: 'get',
                    dataType: 'json',
                    success: function(data)
                    {
                        $(".loader").hide();
                        $(".modal-title").html("Edit Data Writer");
                        $("#nama").val(data.FULLNAME);
                        $("#email").val(data.EMAIL);
                        $("#biografi").val(data.BIOGRAFI);
                        $("#image").val(data.IMAGE);
                        $("#photo").val("");
                        $("#id").val(data.ID);
                        $("#role").val(data.ROLE);
                        if(data.STATUS == "1")
                          $('input:radio[name=status][value=1]').click();
                        else if(data.STATUS == "2")
                          $('input:radio[name=status][value=2]').click();
                        else 
                          $('input:radio[name=status][value=0]').click();

                        $('#form').attr('action', '<?php echo site_url('webmin/penulis/put')?>');
                        $("#formModal").modal('show');
                    }
                });

            }
            
        }

        CKEDITOR.replace('biografi');
</script>