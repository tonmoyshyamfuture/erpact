
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $title; ?></title>
                <link href="<?php echo $this->config->item('base_url'); ?>assets/developer.css" rel="stylesheet" type="text/css" />
                <script src="<?php echo $this->config->item('base_url'); ?>assets/front/js/jQuery 1.10.2.min.js" type="text/jscript"></script>

    <body>
        <div id="wrapper">
            <div class="container">
                <?php echo $content;?>
            </div>
        </div>
                                    
        <script src="<?php echo $this->config->item('base_url'); ?>assets/developer.js" rel="stylesheet" type="text/jscript"></script>

        <?php
        $errormessage = $this->session->flashdata('errormessage');
        $successmessage = $this->session->flashdata('successmessage');
        if (!empty($errormessage) or ! empty($successmessage)) {
            ?>
            <div class="overlay"></div>
            <?php
        }
        ?>

        <?php
        if ($errormessage) {
            ?>
            <div class="errormessage">
                <span><i class="fa fa-warning"></i></span>
                <div><?php echo $errormessage; ?></div>
                <span class="closebutn"><i class="fa fa-close"></i></span>
            </div>
            <?php
        }
        if ($successmessage) {
            ?>
            <div class="successmessage">
                <span><i class="fa fa-check-circle"></i></span>
                <div><?php echo $successmessage; ?></div>
                <span class="closebutn"><i class="fa fa-close"></i></span>
            </div>
            <?php
        }
        ?>
    </body>
</html>