<div class="content-wrapper">
    <section class="content-header">
        <h1>Document Upload</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('department/home/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Document Upload</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Upload Document</h3>
                    </div>
                    <div class="box-body">
                        <?php if ($msg = $this->session->flashdata('success')) { ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                <?php echo $msg; ?>
                            </div>                                   
                        <?php } ?>
                        
                        <div class="form-group">
                            <label>Document Upload</label>
                            <div class="input-group">
                                <div class="input document-uploaded">
                                    <input type="file" name="document" attr="document" class="form-control">
                                    <input type="hidden" name="documentFile" id="document">
                                    <span class="help-block">(Only .jpeg .jpg .png .pdf allowed of max size 1 MB)</span>
                                    <span class="help-block doc_error text-red"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('input[type=file]').on('change', function(e){ 
            startUpload(e, this); 
        });
    });
    
    // Ajax File upload
    function startUpload(e, thiss){
        var fname = $(thiss).attr("name");
        var id = $(thiss).attr("attr");
        var base_url = "<?php echo base_url(); ?>";
        e.preventDefault();
        
        var formData = new FormData();
        formData.append(fname, $(thiss)[0].files[0]);        
        formData.append('name', fname);        		
        formData.append("folder", id);
        
        if($(thiss)[0].files[0].size > 1048576) {
            $('.doc_error').html('The file you are attempting to upload is larger than the permitted size.');
            $(thiss).val("");
            $('input[name="'+fname+'"]').val("");
            return false;
        }
        
        $('#'+fname).hide();
        $(thiss).attr("disabled", true);
        
        $.ajax({
            url: "<?php echo base_url('department/DocumentUpload/fileupload'); ?>",
            type: "POST",
            beforeSend: function (xhr) {
                $(thiss).before('<img class="loader" src="<?php echo base_url('assets/images/loading.gif'); ?>" style="width:30px" >');
            },
            complete: function (jqXHR, textStatus) {
                $(".loader").hide();
            },
            data: formData,
            contentType: false,
            cache: false,
            processData: false,            
            success: function(data) {
                $(thiss).attr("disabled", false);
                var res = jQuery.parseJSON(data);
                
                if(res.result.status == '0'){
                    $('.doc_error').html(res.result.msg);
                    $('#'+id).val("");
                    $('input[name="'+fname+'"]').val("");
                    $("#"+fname).hide();
                }
                else {							
                    var path = res.result.path;	
                    var path1 = res.result.path1;
                    $('#'+id).val(path1);
                    $(".doc_error").html('');
                    $(thiss).after('<div id="'+fname+'" class="mt-2"><img width="30" vspace="5" border="0" hspace="5" align="absmiddle" src="'+res.result.icon+'" /><a class="text-primary" style="cursor:pointer;" onclick="remove_file(\''+fname+'\',\''+path+'\',\''+id+'\')" >Remove</a></div>');
                }				              
            }
        });
    } 
    
    function remove_file(fname, path, folder) {
        $("#"+fname).empty();
        $("#"+fname).remove();
        $('input[id="'+fname+'"]').val("");
        $('input[name="'+fname+'"]').val("");
        
        $.ajax({
            url: "<?php echo base_url();?>department/DocumentUpload/removeFile",
            type: "POST",
            data: {
                name: folder,
                path: path
            },
            datatype: "text",
            success: function(data){
                if(data === "success") {
                    $(".doc_error").html('<span class="text-success">File removed successfully.</span>');
                    setTimeout(function() {
                        $(".doc_error").html('');
                    }, 3000);
                }
            }
        });
    }
</script>
