<div class="wrapper2">    
    <form role="form" action="<?php echo base_url('emailtemplate/admin/emailtemplateupdate'); ?>/<?php echo!empty($emailtemplate[0]->id) ? urlencode(base64_encode($emailtemplate[0]->id)) : ''; ?>" method="post" name="emailtemplate" id="emailtemplate">
        <section class="content-header">
            <div class="row">
                <div class="col-xs-6">
                    <h1> <i class="fa fa-envelope" aria-hidden="true"></i> Notifications / Edit</h1>  
                </div>
                <div class="col-xs-6"> 
                    <div class="pull-right">
                    <input class="btn btn-default" type="button" onclick="window.location.href = '<?php echo base_url('admin/email-template'); ?>';" value="Discard"/>
                    <input class="btn btn-primary" type="submit" value="Save"/>
                    </div>
                </div> 
            </div>      
        </section>       
        <section>
        <?php
        $this->breadcrumbs->push('Home', '/admin/dashboard');
        $this->breadcrumbs->push('Settings', '#');
        $this->breadcrumbs->push('Email Notifications', '/admin/email-template');
          $this->breadcrumbs->push('Update', '/admin/edit-email-template');
        $this->breadcrumbs->show();
        ?>
    </section>
        <!-- Main content -->
        <section class="content">
            <div class="boxx">
                <div class="header">Template - <?php if (!empty($emailtemplate[0]->name)) { ?> <?php echo $emailtemplate[0]->name; ?> <?php } ?></div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label>Template Name</label>
                            <input type="text" class="form-control" name="name" <?php if (!empty($emailtemplate[0]->name)) { ?> value="<?php echo $emailtemplate[0]->name; ?>" <?php } ?> placeholder="Enter Template Name"/>

                        </div>

                        <!-- text input -->
                        <div class="form-group">
                            <label>From Address</label>
                            <input type="text" class="form-control" name="email_from" <?php if (!empty($emailtemplate[0]->email_from)) { ?> value="<?php echo $emailtemplate[0]->email_from; ?>" <?php } ?> placeholder="Enter Email From"/>
                        </div>
                        <!-- text input -->
                        <div class="form-group">
                            <label>CC</label>
                            <span class="text-danger" style="padding:5px;">(If you have multiple cc then separated using comma.)</span>
                            <input type="text" class="form-control" name="email_cc" <?php if (!empty($emailtemplate[0]->email_cc)) { ?> value="<?php echo $emailtemplate[0]->email_cc; ?>" <?php } ?> placeholder="Enter Email CC"/>
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="email_subject" <?php if (!empty($emailtemplate[0]->email_subject)) { ?> value="<?php echo $emailtemplate[0]->email_subject; ?>" <?php } ?> placeholder="Enter Email Subject"/>
                        </div>

                        <?php
                            $ary_variables=explode(",",$emailtemplate[0]->variables);
                        ?>
                        <div style=" margin-left: 10px;float: right; margin-top: -14px;"> <span style="line-height: 30px;margin-right: 10px;font-weight: bold;">Variable:</span> <select name="countryid" id="countryid" class="form-control" style="float:right;width:180px;" onchange="insertIntoCkeditor(this.value)">
                            
                            <option value="">Select Variable</option>
                            <?php
                            if(!empty($ary_variables))
                            {
                                foreach($ary_variables as $valvariables)
                                {
                                    $valvariables=trim($valvariables);
                                ?>
                                    <option value="<?php echo $valvariables; ?>"><?php echo $valvariables; ?></option>
                                <?php
                                }
                            }
                            ?>
                            </select></div>

                        <div class="form-group">
                            <label>Email Template</label> <?php if (!empty(trim($emailtemplate[0]->variables))) { ?>  <span style=" margin-left: 10px;">( Plase don't change the following values: <?php echo $emailtemplate[0]->variables; ?> ) </span> <?php } ?>                           


                            <textarea class="form-control ckeditor" id="ckeditor" name="content" placeholder="Enter Content"><?php if (!empty($emailtemplate[0]->content)) {
    echo html_entity_decode($emailtemplate[0]->content, ENT_QUOTES, 'utf-8');
} ?></textarea>
                            <!--<p><?php // echo form_error('content','<div class="error">', '</div>');  ?></p>-->
                        </div>
                    </div>
                        </div>
                </div>
                <div class="footer">
                    <div class="footer-button">   
                        <input class="btn btn-primary" type="submit" value="Save"/>  
                        <input class="btn btn-default" type="button" onclick="window.location.href = '<?php echo base_url('admin/emailtemplate'); ?>';" value="Discard"/>                     
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </form>
</div>
<script>

    function insertIntoCkeditor(str)
    {
        CKEDITOR.instances['ckeditor'].insertText(str);
    }

    
</script>
<style>
    .error
    {
        color : #ff0000;
    }
</style>
