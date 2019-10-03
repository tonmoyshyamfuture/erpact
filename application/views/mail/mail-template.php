<!DOCTYPE html>
<html>
    <head>
        <title><?php echo getsitename();?></title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
     
    </head>
    <body style="background-color:#F5F5F5;">
        <div style="width: 600px; margin: 0 auto;">
            
            
            <table width="100%" border="0" style="background-color: #ffffff; border: 1px solid #e3e3e3;">
                <tr>
                    <td style="float:left;width:382px;background:url({siteurl}assets/images/text_bg.png) no-repeat top left;height:120px;font:bold 40px/50px arial;color:#ffffff;padding: 20px 0 0 30px;">TCR Member</td>
                    <td><a href="{siteurl}" target="_blank"><img src="{logo}" alt="logo" style="width:100%"/></a></td>
                </tr>
            </table>

            <table width="100%" border="0">
                <tr>
                    <td style=" font: bold 18px/25px arial; color: #f7921e; padding: 10px 0px;">Date - <span style="font: bold 18px/25px arial; color: #444444;">{date}</span></td>
                </tr>
            </table>
           
            
            
            <table width="100%" border="0" style="background-color: #ffffff; border: 2px solid #e4e5e4; padding: 15px;">
                <tr>
                    <td style="font:bold 20px/30px Tahoma; color:#08a0c1 ;padding: 0;">{heading}</td>
                </tr>
                <tr>
                    <td>
                        <p style="font:normal 13px/19px arial; color:#797A89 ;padding: 0;">
                          {message}
                        </p>

                      
                    </td>
                </tr>
            </table>
            
            
            
           
            <table width="100%" border="0" style="background-color: #F7921E; height: 30px; margin-top: 15px;">
                <tr><td><p style="font:normal 12px/10px arial; color: #ffffff; text-align: center;"> Copyright &copy; TCR Member {year}.All Rights Reserved. Terms of Service.</p></td></tr>   
            </table>
            
        </div>
    </body>
</html>