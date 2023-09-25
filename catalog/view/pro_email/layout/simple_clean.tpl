<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$width = $theme['width'] ? $theme['width'] : '580';
$width_unit = $theme['width_unit'] ? $theme['width_unit'] : 'px';
if ($width_unit == '%' && $width > 100) { $width = 100; }
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width"/>
<style>#outlook a{padding:0;}body{width:100%!important;min-width:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0;}.ExternalClass{width:100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:100%;}#backgroundTable{margin:0;padding:0;width:100%!important;line-height:100%!important;}img{outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;width:auto;max-width:100%;/*float:left;clear:both;display:block;*/}center{width:100%;min-width:<?php echo $width.$width_unit; ?>;}a img{border:none;}p{margin:0 0 0 10px;}table{border-spacing:0;border-collapse:collapse;}td{word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;border-collapse:collapse!important;}table,tr,td{padding:0;vertical-align:top;}
hr{color:#d9d9d9;background-color:#d9d9d9;height:1px;border:none;}table.body{height:100%;width:100%;}table.container{width:<?php echo $width.$width_unit; ?>;margin:0 auto;text-align:inherit;}table.row{padding:0px;width:100%;position:relative;}table.container table.row{display:block;}td.wrapper{padding:10px 20px 0px 0px;position:relative;}table.columns,table.column{margin:0 auto;}table.columns td,table.column td{padding:0px 0px 10px;}table.columns td.sub-columns,table.column td.sub-columns,table.columns td.sub-column,table.column td.sub-column{padding-right:10px;}td.sub-column,td.sub-columns{min-width:0px;}table.row td.last,table.container td.last{padding-right:0px;}table.one{width:30px;}table.two{width:80px;}table.three{width:130px;}table.four{width:180px;}table.five{width:230px;}table.six{width:280px;}
table.seven{width:330px;}table.eight{width:380px;}table.nine{width:430px;}table.ten{width:480px;}table.eleven{width:530px;}table.twelve{width:<?php echo $width.$width_unit; ?>;}table.one center{min-width:30px;}table.two center{min-width:80px;}table.three center{min-width:130px;}table.four center{min-width:180px;}table.five center{min-width:230px;}table.six center{min-width:280px;}table.seven center{min-width:330px;}table.eight center{min-width:380px;}table.nine center{min-width:430px;}table.ten center{min-width:480px;}table.eleven center{min-width:530px;}table.twelve center{min-width:<?php echo $width.$width_unit; ?>;}table.one .panel center{min-width:10px;}table.two .panel center{min-width:60px;}table.three .panel center{min-width:110px;}table.four .panel center{min-width:160px;}table.five .panel center{min-width:210px;}table.six .panel center{min-width:260px;}
table.seven .panel center{min-width:310px;}table.eight .panel center{min-width:360px;}table.nine .panel center{min-width:410px;}table.ten .panel center{min-width:460px;}table.eleven .panel center{min-width:510px;}table.twelve .panel center{min-width:560px;}.body .columns td.one,.body .column td.one{width:8.333333%;}.body .columns td.two,.body .column td.two{width:16.666666%;}.body .columns td.three,.body .column td.three{width:25%;}.body .columns td.four,.body .column td.four{width:33.333333%;}.body .columns td.five,.body .column td.five{width:41.666666%;}.body .columns td.six,.body .column td.six{width:50%;}.body .columns td.seven,.body .column td.seven{width:58.333333%;}.body .columns td.eight,.body .column td.eight{width:66.666666%;}.body .columns td.nine,.body .column td.nine{width:75%;}.body .columns td.ten,.body .column td.ten{width:83.333333%;}
.body .columns td.eleven,.body .column td.eleven{width:91.666666%;}.body .columns td.twelve,.body .column td.twelve{width:100%;}td.offset-by-one{padding-left:50px;}td.offset-by-two{padding-left:100px;}td.offset-by-three{padding-left:150px;}td.offset-by-four{padding-left:200px;}td.offset-by-five{padding-left:250px;}td.offset-by-six{padding-left:300px;}td.offset-by-seven{padding-left:350px;}td.offset-by-eight{padding-left:400px;}td.offset-by-nine{padding-left:450px;}td.offset-by-ten{padding-left:500px;}td.offset-by-eleven{padding-left:550px;}td.expander{visibility:hidden;width:0px;padding:0!important;}table.columns .text-pad,table.column .text-pad{padding-left:10px;padding-right:10px;}table.columns .left-text-pad,table.columns .text-pad-left,table.column .left-text-pad,table.column .text-pad-left{padding-left:10px;}
table.columns .right-text-pad,table.columns .text-pad-right,table.column .right-text-pad,table.column .text-pad-right{padding-right:10px;}.block-grid{width:100%;max-width:<?php echo $width.$width_unit; ?>;}.block-grid td{display:inline-block;padding:10px;}.two-up td{width:270px;}.three-up td{width:173px;}.four-up td{width:125px;}.five-up td{width:96px;}.six-up td{width:76px;}.seven-up td{width:62px;}.eight-up td{width:52px;}table.center,td.center{text-align:center;}h1.center,h2.center,h3.center,h4.center,h5.center,h6.center,p.center{text-align:center;}span.center{display:block;width:100%;text-align:center;}img.center{margin:0 auto;float:none;}.show-for-small,.hide-for-desktop{display:none;}
body{color:<?php echo $color['text'] ? $color['text'] : '#444'; ?>} body,table.body,h1,h2,h3,h4,h5,h6,p,td{font-family:"Helvetica","Arial",sans-serif;font-weight:normal;padding:0;margin:0;line-height:1.3;}h1,h2,h3,h4,h5,h6{word-break:normal;}h1{font-size:40px;}h2{font-size:36px;}h3{font-size:32px;}h4{font-size:28px;}h5{font-size:24px;}h6{font-size:20px;}body,table.body,p,td{font-size:14px;line-height:23px;}p.lead,p.lede,p.leed{font-size:18px;line-height:21px;}p{margin-bottom:10px;}small{font-size:10px;}a{color:#2ba6cb;text-decoration:none;}a:hover{color:#2795b6!important;}a:active{color:#2795b6!important;}a:visited{color:#2ba6cb!important;}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{color:#2ba6cb;}h1 a:active,h2 a:active,h3 a:active,h4 a:active,h5 a:active,h6 a:active{color:#2ba6cb!important;}
h1 a:visited,h2 a:visited,h3 a:visited,h4 a:visited,h5 a:visited,h6 a:visited{color:#2ba6cb!important;}.panel{background:#f2f2f2;border:1px solid #d9d9d9;padding:10px!important;}.sub-grid table{width:100%;}.sub-grid td.sub-columns{padding-bottom:0;}
table.secondary td{background:#e9e9e9;border-color:#d0d0d0;color:#555;}table.secondary td a{color:#555;}table.secondary:hover td{background:#d0d0d0!important;color:#555;}table.secondary:hover td a,table.secondary td a:visited,table.secondary:active td a{color:#555!important;}table.success td{background:#5da423;border-color:#457a1a;}table.success:hover td{background:#457a1a!important;}table.alert td{background:#c60f13;border-color:#970b0e;}table.alert:hover td{background:#970b0e!important;}table.radius td{-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;}table.round td{-webkit-border-radius:500px;-moz-border-radius:500px;border-radius:500px;}body.outlook p{display:inline!important;}@media only screen and (max-width: <?php echo ($width_unit == 'px') ? ($width+20).$width_unit : $width.$width_unit; ?>) {table[class="body"] img{width:auto!important;height:auto!important;}
table[class="body"] center{min-width:0!important;}table[class="body"] .container{width:95%!important;}table[class="body"] .row{width:100%!important;display:block!important;}table[class="body"] .wrapper{display:block!important;padding-right:0!important;}table[class="body"] .columns,table[class="body"] .column{table-layout:fixed!important;float:none!important;width:100%!important;padding-right:0px!important;padding-left:0px!important;display:block!important;}table[class="body"] .wrapper.first .columns,table[class="body"] .wrapper.first .column{display:table!important;}table[class="body"] table.columns td,table[class="body"] table.column td{width:100%!important;}table[class="body"] .columns td.one,table[class="body"] .column td.one{width:8.333333%!important;}table[class="body"] .columns td.two,table[class="body"] .column td.two{width:16.666666%!important;}
table[class="body"] .columns td.three,table[class="body"] .column td.three{width:25%!important;}table[class="body"] .columns td.four,table[class="body"] .column td.four{width:33.333333%!important;}table[class="body"] .columns td.five,table[class="body"] .column td.five{width:41.666666%!important;}table[class="body"] .columns td.six,table[class="body"] .column td.six{width:50%!important;}table[class="body"] .columns td.seven,table[class="body"] .column td.seven{width:58.333333%!important;}table[class="body"] .columns td.eight,table[class="body"] .column td.eight{width:66.666666%!important;}table[class="body"] .columns td.nine,table[class="body"] .column td.nine{width:75%!important;}table[class="body"] .columns td.ten,table[class="body"] .column td.ten{width:83.333333%!important;}table[class="body"] .columns td.eleven,table[class="body"] .column td.eleven{width:91.666666%!important;}
table[class="body"] .columns td.twelve,table[class="body"] .column td.twelve{width:100%!important;}table[class="body"] td.offset-by-one,table[class="body"] td.offset-by-two,table[class="body"] td.offset-by-three,table[class="body"] td.offset-by-four,table[class="body"] td.offset-by-five,table[class="body"] td.offset-by-six,table[class="body"] td.offset-by-seven,table[class="body"] td.offset-by-eight,table[class="body"] td.offset-by-nine,table[class="body"] td.offset-by-ten,table[class="body"] td.offset-by-eleven{padding-left:0!important;}table[class="body"] table.columns td.expander{width:1px!important;}table[class="body"] .right-text-pad,table[class="body"] .text-pad-right{padding-left:10px!important;}table[class="body"] .left-text-pad,table[class="body"] .text-pad-left{padding-right:10px!important;}table[class="body"] .hide-for-small,table[class="body"] .show-for-desktop{display:none!important;}
table[class="body"] .show-for-small,table[class="body"] .hide-for-desktop{display:inherit!important;}}table,tr,td{vertical-align:middle;}table.columns td,table.column td{padding:0;}td.wrapper{padding-top:0;}@media only screen and (max-width: <?php echo ($width_unit == 'px') ? ($width+20).$width_unit : $width.$width_unit; ?>) {table[class="body"] .row tr,table[class="body"] .row tbody{display:block;}}</style>
<style>
body,.body{background-color:<?php echo $color['bg_page']; ?>; <?php echo $theme['bg_page'] ? 'background-image: url('.$img_path.$theme['bg_page'].') '.$theme['bg_page_repeat'] . ';' : ''; ?> line-height:24px; direction:<?php echo $direction; ?>;}
.main{-webkit-border-radius:8px 8px 6px 6px; -moz-border-radius:8px 8px 6px 6px; border-radius:8px 8px 6px 6px; background-color:<?php echo $color['bg_body']; ?>; <?php echo $theme['bg_body'] ? 'background-image:url('.$img_path.$theme['bg_body'].') '.$theme['bg_body_repeat'].';' : ''; ?>;}
.header{color:<?php echo $color['text_head']; ?>; background-color:<?php echo $color['bg_header']; ?>; <?php echo $theme['bg_header'] ? 'background-image:url('.$img_path.$theme['bg_header'].') '.$theme['bg_header_repeat'].';' : ''; ?>; -webkit-border-radius:6px 6px 0 0; -moz-border-radius:6px 6px 0 0; border-radius:6px 6px 0 0; padding:40px 0; text-align:center;}
.header a img{color:<?php echo $color['text_head']; ?>; font-size:30px;}
.header a{color:<?php echo $color['link_head']; ?>;}
.button{display:inline-block;width:auto!important;text-align:center;background-color:<?php echo $color['btn']; ?>;color:<?php echo $color['btn_text']; ?>;padding:8px 30px;-webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;}
a{color:<?php echo $color['link']; ?>;}
span.content{display:block; font-size:12px;-moz-hyphens:none;-webkit-hyphens:none;hyphens:none;}
span.footer p{margin-bottom:0;}
span.footer{display:block; font-size:12px; padding:10px 15px; border-radius: 0 0 6px 6px;
color:<?php echo $color['text_foot']; ?>; background-color:<?php echo $color['bg_footer']; ?>; <?php echo $theme['bg_footer'] ? 'background-image:url('.$img_path.$theme['bg_footer'].') '.$theme['bg_footer_repeat'].';' : ''; ?>;}
.rtl{text-align:right}
.rtl .right, .ltr .left, .ltr .content{text-align:left}
.ltr .right, .rtl .left, .rtl .content{text-align:right}
.contain{width:<?php echo $width.$width_unit; ?>;margin:0 auto;text-align:inherit;}
.top{color:<?php echo $color['text_top']; ?>; background-color:<?php echo $color['bg_top']; ?>; <?php echo $theme['bg_top'] ? 'background-image:url('.$img_path.$theme['bg_top'].') '.$theme['bg_top_repeat'].';' : ''; ?>;}
.top a{color:<?php echo $color['link_top']; ?>;}
.bottom{color:<?php echo $color['text_bottom']; ?>; background-color:<?php echo $color['bg_bottom']; ?>; <?php echo $theme['bg_bottom'] ? 'background-image:url('.$img_path.$theme['bg_bottom'].') '.$theme['bg_bottom_repeat'].';' : ''; ?>;}
.bottom a{color:<?php echo $color['link_bottom']; ?>;}
</style>
</head>
<body>
  <table class="body" <?php echo $theme['bg_page'] ? 'background="'.$img_path.$theme['bg_page'].'"' : ''; ?>>
  <tr>
    <td class="page center" align="center" valign="top">
      <center>
      
        <table class="spacer"><tr><td height="40"></td></tr></table>
        
        <?php if (!empty($top)) { ?>
        <table class="container top">
          <tr><td><span class=""><?php echo $top; ?></span></td></tr>
        </table>
        <?php } ?>
        
        <table class="container main <?php echo $direction; ?>" <?php echo $theme['bg_body'] ? 'background="'.$img_path.$theme['bg_body'].'"' : ''; ?>>
        <tr>
          <td class="header center" <?php echo $theme['bg_header'] ? 'background="'.$img_path.$theme['bg_header'].'"' : ''; ?>>
            <center><span><a href="<?php echo $store_url; ?>"><img class="center" src="<?php echo $theme['logo']; ?>" alt="<?php echo $store_name; ?>" /></a></span></center>
          </td>
        </tr>
        <tr>
          <td style="padding: 10px 15px;"><span class="content"><?php echo $main_content; ?></span></td>
        </tr>
        <?php if (!empty($footer)) { ?>
        <tr>
          <td><span class="footer"><?php echo $footer; ?></span></td>
        </tr>
        <?php } ?>
        </table>
        
        <?php if (!empty($bottom)) { ?>
        <table class="spacer"><tr><td height="10"></td></tr></table>
        <table class="container bottom">
          <tr><td><span><?php echo $bottom; ?></span></td></tr>
        </table>
        <?php } ?>
        
        <table class="spacer"><tr><td height="40"></td></tr></table>
      </center>
    </td>
  </tr>
  </table>
</body>
</html>