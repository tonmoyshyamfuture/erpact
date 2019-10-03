<?php foreach ($companylist as $company): ?>
    <li>
        <a href="#" data-url="<?php echo ACCOUNT_URL; ?>admin/checklogin" data-act="<?php echo $company->id . '_' . $this->session->userdata('userid'); ?>"  class="switch_company"> <?php echo $company->company_name; ?>&nbsp;(<?php echo ($company->company_alias != "") ? $company->company_alias . " -" : ''; ?>&nbsp;<?php echo ($company->company_code != "") ? $company->company_code : ''; ?>)</a>
    </li>
<?php endforeach; ?>

    <script>
        $(document).ready(function(){
                $(".switch_company").on("click", function() {
                    var obj = $(this);
                    var value = obj.data('act');
                    var url = obj.data('url');
                    console.log(value);
                    console.log(url);  
                    $.ajax({
                        url: url,
                        dataType: 'jsonp',
                        jsonp: "accountcallback",
                        data: {value: value},
                        success: function(data) {
                            if (data.res == 'success') {
                                Command: toastr["success"](data.message);
                                window.location.href = data.url;
                            } else {
                                Command: toastr["error"](data.message);
                            }
                        }
                    });
                });
            });
    </script>