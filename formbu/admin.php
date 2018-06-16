<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "universalvisions2010@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "20110815-5bd8" );

?>
<?php
/**
 * Copyright (C) : http://www.formmail-maker.com
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|");
    $public_functions = false !== strpos('|phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    $_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'BBE2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHaY6IIkFTBFpZW1gCAhAFmsVaXRtYHQQwVTXIILkvtCoqWFLQ1etikJyH1RdowOGeQytDJhiUxiwuAXTzY6hIYMg/KgIsbgPAA+czaSv+Oy5AAAAAElFTkSuQmCC',
			'4FA0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37poiGOkxhaEURCxFpYAhlmOqAJMYIFGN0dAgIQBJjnSLSwNoQ6CCC5L5p06aGLV0VmTUNyX0BqOrAMDQUKBaKKsYAVheAYgdUDMUtUDFUNw9U+FEPYnEfAFtQzKVnm2b+AAAAAElFTkSuQmCC',
			'7FD6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DGaY6IIu2ijSwNjoEBKCLNQQ6CCCLTYGIobgvamrY0lWRqVlI7mN0AKtDMY+1AaJXBElMBItYQAOmW8Bi6G4eoPCjIsTiPgBVNsxtIG60wwAAAABJRU5ErkJggg==',
			'C612' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRGAMAhFfwo3iPvEDSiIhdOQIhtghsiUxkqMlnoXKDjewfEO1EcIRspf/CZ2DMUeDPN5ymAQGUbJJ8cueMukdQrxxm+rZa3lrJcfyZzbXAr33RQUGd2NxhS9i4J6ZxeXyAP878N88TsAFk7MMw3wSZgAAAAASUVORK5CYII=',
			'74A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMZWhmmMEx1QBZtZZjKEMoQEIAqFsro6Ogggiw2hdGVtSEQJgZxU9TSpUtXRUWFIbmP0UGklbUhYCqyXtYG0VDX0IAGZDEgG6QOxY4AiBiKW6BiqG4eoPCjIsTiPgCRicwewpK1JAAAAABJRU5ErkJggg==',
			'2B9E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxhCGUMDkMREpoi0Mjo6OiCrC2gVaXRtCEQRY2gVaWVFiEHcNG1q2MrMyNAsZPcFiLQyhKDqZXQQaXRAM4+1QaTREU1MpAHTLaGhmG4eqPCjIsTiPgAK3Mmwk0VHWwAAAABJRU5ErkJggg==',
			'0441' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YWhkaHVqRxVgDGKYytDpMRRYTmcIQyjDVIRRZLKCV0ZUhEK4X7KSopUuXrszMWorsvoBWkVZWNDsCWkVDXUMDWtHswOYWDDGom0MDBkH4URFicR8ABKfMLVgs9QYAAAAASUVORK5CYII=',
			'6909' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQximMEx1QBITmcLayhDKEBCAJBbQItLo6OjoIIIs1iDS6NoQCBMDOykyaunS1FVRUWFI7guZwhjo2hAwFUVvKwNQL9AEFDEWoB0OKHZgcws2Nw9U+FERYnEfAI82zIs5lYRgAAAAAElFTkSuQmCC',
			'469B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjCGMIQyhjogi4WwtjI6OjoEIIkxhog0sjYEOoggibFOEWkAiQUguW/atGlhKzMjQ7OQ3BcwRbSVISQQxbzQUJFGBzTzGKaINDpiiGG6BaubByr8qAexuA8AWBjK5DdOJXMAAAAASUVORK5CYII=',
			'3637' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7RAMYQxhDGUNDkMQCprC2sjY6NIggq2wVaQTKoIpNAfKA6gKQ3LcyalrYqqmrVmYhu2+KaCtQXSsDmnlAnVOwiAUwYLjF0QGLm1HEBir8qAixuA8Ao6LMYbq+hWEAAAAASUVORK5CYII=',
			'F2D3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDGUIdkMQCGlhbWRsdHQJQxEQaXUEkihgDWCwAyX2hUauWLl0VtTQLyX1A+SmsCHUwsQBWDPMYHTDFWBsw3SIa6orm5oEKPypCLO4DALY5zwU6wyuFAAAAAElFTkSuQmCC',
			'CEBC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WENEQ1lDGaYGIImJtIo0sDY6BIggiQU0AsUaAh1YkMUaQOocHZDdF7VqatjS0JVZyO5DU4cQA5rHQMAObG7B5uaBCj8qQizuAwDc3cvxh4JZpAAAAABJRU5ErkJggg==',
			'FA7C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA6YGIIkFNDCGAMkAERQx1laGhkAHFhQxkUaHRkcHZPeFRk1bmbV0ZRay+8DqpjA6MKDoFQ11CEAXEwGaxohhh2sDA5pbwGIobh6o8KMixOI+AHT0zUovmSK2AAAAAElFTkSuQmCC',
			'D318' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgNYQximMEx1QBILmCLSyhDCEBCALNbK0OgYwugggirWCtQLUwd2UtTSVWGrpq2amoXkPjR1cPMcpmCYhykGcguaXpCbGUMdUNw8UOFHRYjFfQC3C81/g66NGgAAAABJRU5ErkJggg==',
			'3CFF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWElEQVR4nGNYhQEaGAYTpIn7RAMYQ1lDA0NDkMQCprA2ujYwOqCobBVpwBCbItLAihADO2ll1LRVS0NXhmYhuw9VHdw8bGLodmBzC9jN6HoHKPyoCLG4DwDecMlZltlohwAAAABJRU5ErkJggg==',
			'B99E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUMDkMQCprC2Mjo6OiCrC2gVaXRtCEQVm4IiBnZSaNTSpZmZkaFZSO4LmMIY6BCCpreVodEB3bxWlkZHDDsw3YLNzQMVflSEWNwHAKAZy7fMtoIFAAAAAElFTkSuQmCC',
			'4A28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpI37pjAEMIQyTHVAFgthDGF0dAgIQBJjDGFtZW0IdBBBEmOdItLo0BAAUwd20rRp01ZmrcyamoXkvgCQulYGFPNCQ0VDHaYwopjHAFIXgCnm6ICqFyTmGhqA6uaBCj/qQSzuAwDsw8xN/SCFwAAAAABJRU5ErkJggg==',
			'E790' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNEQx1CGVqRxQIaGBodHR2mOqCJuTYEBASgirWyNgQ6iCC5LzRq1bSVmZFZ05DcB1QXwBACVwcVY3RgaEAXY21gxLBDpIERzS2hIUBdaG4eqPCjIsTiPgCKT80mQifKgwAAAABJRU5ErkJggg==',
			'78E9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHaY6IIu2srayNjAEBKCIiTS6NjA6iCCLTQGpg4tB3BS1Mmxp6KqoMCT3MTqAzZuKrJe1AWQeQwOymAhEDMWOgAZMtwQ0YHHzAIUfFSEW9wEAubnLMbNhcRMAAAAASUVORK5CYII=',
			'919D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjAGMDo6OgQgiQW0sgawNgQ6iKCIMSCLgZ00beqqqJWZkVnTkNzH6gq0IwRVLwNQLwOaeQJAMUY0MZEpDBhuAbokFN3NAxV+VIRY3AcAgjbIYXUXTsUAAAAASUVORK5CYII=',
			'4557' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37poiGsoY6hoYgi4WINLACaREkMUYsYqxTREJYpzI0BCC5b9q0qUuXZmatzEJyX8AUhkaHhoBWZHtDQ8FiU1DdItLo2hAQgCrG2sro6OiAKsYYwhDKiCo2UOFHPYjFfQCdN8ujzMyu6QAAAABJRU5ErkJggg==',
			'FA7A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA1qRxQIaGEOA5FQHFDFWoJqAgAAUMZFGh0ZHBxEk94VGTVuZtXRl1jQk94HVTWGEqYOKiYY6BDCGhqCZ5+iArk6k0bWBsNhAhR8VIRb3AQAbEs2kKS0IfAAAAABJRU5ErkJggg==',
			'0724' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGRoCkMRYAxgaHR0dGpHFRKYwNLo2BLQiiwW0MrQCySkBSO6LWrpq2qqVWVFRSO4DqgtgaGV0QNXL6MAwhTE0BMUO1gagSjS3iADdiCrG6CDSwBoagCI2UOFHRYjFfQAU0My6xQ8YSQAAAABJRU5ErkJggg==',
			'5C9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYQxlCGVqRxQIaWBsdHR2mOqCIiTS4NgQEBCCJBQaINLA2BDqIILkvbNq0VSszI7OmIbuvFagiBK4OIdYQGBqCbAdQzLEBVZ3IFJBbHFHEWANAbmZENW+Awo+KEIv7AJDszC4pq7MtAAAAAElFTkSuQmCC',
			'5913' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMYQximMIQ6IIkFNLC2MoQwOgSgiIk0OoYwNIggiQUGiDQ6TAHJIdwXNm3p0qxpq5ZmIbuvlTEQSR1UjAGsF9m8gFYWDDGRKUC3TEF1C2sAYwhjqAOKmwcq/KgIsbgPAB45zN/8SSVhAAAAAElFTkSuQmCC',
			'2188' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYHR0CAhAEgtoZQ1gbQh0EEHW3cqArA7ipmmrolaFrpqahey+AAYM8xgdGDDMY23AFAOyMfSGhrKGort5oMKPihCL+wBPr8kcni7hMwAAAABJRU5ErkJggg==',
			'566E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGUMDkMQCGlhbGR0dHRhQxEQaWRtQxQIDRBpYGxhhYmAnhU2bFrZ06srQLGT3tYq2sqKZx9Aq0ujaEIhqBxYxkSmYbmENwHTzQIUfFSEW9wEAb7TJ8c/o/F4AAAAASUVORK5CYII=',
			'7FF7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA0NDkEVbRRpYgbQIIbEpELEAZPdFTQ1bGrpqZRaS+xgdwOpake1lbQCLTUEWE4GIBSCLBYDFGB0IiQ1U+FERYnEfAMpkytzcJBpQAAAAAElFTkSuQmCC',
			'C3DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WEOAMJQxNARJTKRVpJW10dEBWV1AI0Oja0MgqlgDQysrQgzspKhVq8KWrooMzUJyH5o6mBimeVjswOYWqJtRxAYq/KgIsbgPAMduyvh6WHsQAAAAAElFTkSuQmCC',
			'BA2F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGUNDkMQCpjCGMDo6OiCrC2hlbWVtCEQVmyLS6IAQAzspNGrayqyVmaFZSO4Dq2tlRDNPNNRhCroYUF0AI4Ydjg6oYqEBIo2uoahuGajwoyLE4j4A/KPLUZc2b7QAAAAASUVORK5CYII=',
			'63B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7WANYQ1hDGaY6IImJTBFpZW10CAhAEgtoYWh0bQh0EEEWa2BAVgd2UmTUqrCloaumZiG5L2QKA6Z5rVjMwyKGzS3Y3DxQ4UdFiMV9ALdrzXYPVAt3AAAAAElFTkSuQmCC',
			'CBB9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WENEQ1hDGaY6IImJtIq0sjY6BAQgiQU0ijS6NgQ6iCCLNYDUOcLEwE6KWjU1bGnoqqgwJPdB1DlMRdMLNA9IYtgRgGIHNrdgc/NAhR8VIRb3AQDprc2pCwbYEwAAAABJRU5ErkJggg==',
			'B240' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgMYQxgaHVqRxQKmsLYytDpMdUAWaxVpBIoEBKCoA+oMdHQQQXJfaNSqpSszM7OmIbkPqG4KayNcHdQ8hgDW0EA0MUYHoIlodrA2MDSiuiU0QDTUAc3NAxV+VIRY3AcAFTjOeINX9d4AAAAASUVORK5CYII=',
			'178B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUMdkMRYHRgaHR0dHQKQxESBYq4NgQ4iKHoZWhkR6sBOWpm1atqq0JWhWUjuA6oLYEQzj9GB0YEVwzzWBkwxkQZ0vaIhQBVobh6o8KMixOI+AC0+yB2mpAo7AAAAAElFTkSuQmCC',
			'C05D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WEMYAlhDHUMdkMREWhlDWBsYHQKQxAIaWVtBYiLIYg0ija5T4WJgJ0WtmrYyNTMzaxqS+0DqHBoCMfRiiIHtQBUDuYXR0RHFLSA3M4Qyorh5oMKPihCL+wDOWMsZxzYxWQAAAABJRU5ErkJggg==',
			'2559' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDHaY6IImJTBFpYG1gCAhAEgtoBYkxOogg624VCWGdCheDuGna1KVLM7OiwpDdF8DQ6NAQMBVZL6MDWKwBWYy1QaTRtSEAxQ6gra2Mjg4obgkNZQxhCGVAcfNAhR8VIRb3AQAtOct3WZOzIwAAAABJRU5ErkJggg==',
			'A20B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB0YQximMIY6IImxBrC2MoQyOgQgiYlMEWl0dHR0EEESC2hlaHRtCISpAzspaumqpUtXRYZmIbkPqG4KK0IdGIaGMgSAxFDNA7oGww7WBnS3BLSKhjqguXmgwo+KEIv7AC0by574vKsGAAAAAElFTkSuQmCC',
			'2B54' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeUlEQVR4nM2QsQ2FMAxEjyIbmH1CQW+k+BdsAFM4hTcANqBhSqBzBCVf4OuezvKTsV1G8aX8xS9wnYJEZcdoIguK7Bkb5VZhnsGO3oyJvd8y/9Zh7Hvvx2TQLvrdKlKO2knyLnre4NJFyaqm9BOpEwQFe+t/D+bGbwfcIM2Rzle5HgAAAABJRU5ErkJggg==',
			'433D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpI37prCGMIYyhjogi4WItLI2OjoEIIkxhjA0OjQEOoggibFOYWhlAKoTQXLftGmrwlZNXZk1Dcl9AajqwDA0FNM8hinYxDDdgtXNAxV+1INY3AcAjPfL0SUJZRUAAAAASUVORK5CYII=',
			'C0F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WEMYAlhDA6Y6IImJtDKGsDYwBAQgiQU0srayNjA6iCCLNYg0uiLUgZ0UtWraytTQVVOzkNyHpg5JDM08LHZgcwvYzQ0MKG4eqPCjIsTiPgCv48u/AETZDgAAAABJRU5ErkJggg==',
			'AD8D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB1EQxhCGUMdkMRYA0RaGR0dHQKQxESmiDS6NgQ6iCCJBbSKNDoC1YkguS9q6bSVWaErs6YhuQ9NHRiGhmI3D4sYhlsCWjHdPFDhR0WIxX0A+frMV4I1O98AAAAASUVORK5CYII=',
			'F0EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDHUMDkMQCGhhDWBsYHRhQxFhbMcVEGl0RYmAnhUZNW5kaujI0C8l9aOrwiGGzA5tbMN08UOFHRYjFfQDGk8pMICIQawAAAABJRU5ErkJggg==',
			'05CD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB1EQxlCHUMdkMRYA0SA4oEOAUhiIlNEGlgbBB1EkMQCWkVCWIEqRZDcF7V06tKlq1ZmTUNyX0ArQ6MrQh1OMaAdQDFUO1gDWFvR3cLowBiC7uaBCj8qQizuAwAyJ8qjGPjhbwAAAABJRU5ErkJggg==',
			'D2B4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM3QMQ6AIAxA0TL0BnqfOrDXxC6chg7cgCuwcEo7UnTUKE0YfiB5KfTLyfCnecUnHA4UyDw0rlhQSV0ri0a7fQONSpUHX2q9NekpDT57V1E3mv4y5l0O1wKhSSZLNotrwqvEyfzV/h6cG98JUoHQRYFmvh8AAAAASUVORK5CYII=',
			'AC07' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQxmmMIaGIImxBrA2OoQyNIggiYlMEWlwdHRAEQtoFWlgbQgAQoT7opZOW7V0VdTKLCT3QdW1ItsbGgoWm8KAZh7QjgBUMZBbGB1QxcBuRhEbqPCjIsTiPgDw3czVw4467QAAAABJRU5ErkJggg==',
			'1A0F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB0YAhimMIaGIImxOjCGMIQCZZDERB1YWxkdHR1Q9Yo0ujYEwsTATlqZNW1l6qrI0Cwk96Gpg4qJhmKKiTQ6YrHDAd0tIUCxKahiAxV+VIRY3AcA2QjHVpSKwlMAAAAASUVORK5CYII=',
			'8B4C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WANEQxgaHaYGIImJTBFpZWh1CBBBEgtoFQGqcnRgQVcX6OiA7L6lUVPDVmZmZiG7D6SOtRGuDm6ea2gghphDIxY7GlHdgs3NAxV+VIRY3AcA1bXMwEyWcOMAAAAASUVORK5CYII=',
			'1146' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAhgaHaY6IImxOjAGMLQ6BAQgiYk6sAYwTHV0EEDXG+jogOy+lVmrolZmZqZmIbkPpI610RHFPLBYaKCDCIZbHLGIobklhDUU3c0DFX5UhFjcBwAvfcd7R0571wAAAABJRU5ErkJggg==',
			'2C35' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQ0EwAElMZApro2ujowOyuoBWkQaHhkAUMQagGEOjo6sDsvumTVu1aurKqChk9wWA1Dk0iCDpZXQA8QJQxFgbIHYgi4k0gNziEIDsvlCwixmmOgyC8KMixOI+AFXEzHj49AoJAAAAAElFTkSuQmCC',
			'A3BD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDGUMdkMRYA0RaWRsdHQKQxESmMDS6NgQ6iCCJBbQygNWJILkvaumqsKWhK7OmIbkPTR0YhoZiNQ+LGKZbAlox3TxQ4UdFiMV9ABmezHEpV1kEAAAAAElFTkSuQmCC',
			'2249' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHaY6IImJTGFtZWh1CAhAEgtoFQGqcnQQQdbdCtQZCBeDuGnaqqUrM7OiwpDdF8AwhRWoG1kvowNDAGtoQAOyGCtItNEBxQ4RoChQDMUtoaGioQ5obh6o8KMixOI+AEBGzD79YNvjAAAAAElFTkSuQmCC',
			'4768' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpI37poiGOoQyTHVAFgthaHR0dAgIQBJjBIq5Njg6iCCJsU5haGVtYICpAztp2rRV05ZOXTU1C8l9AVMYAljRzAsNZXRgbQhEMY9hCmsDpphIAyOaXpAYA7qbByr8qAexuA8A7k7L+m8w8gcAAAAASUVORK5CYII=',
			'7B87' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGUNDkEVbRVoZHR0aRFDFGl0bAlDFpkDUBSC7L2pq2KrQVSuzkNzH6ABW14psL2sD2LwpyGIiELEAZDGgjUC9jg6oYmA3o4gNVPhREWJxHwClpMurYUZHDwAAAABJRU5ErkJggg==',
			'C45D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WEMYWllDHUMdkMREWhmmsjYwOgQgiQU0MoSCxESQxRoYXVmnwsXATopatXTp0szMrGlI7gsAmdgQiKZXFGgnmlgj0C1oYkCdrYyOjihuAbmZIZQRxc0DFX5UhFjcBwC1TMsOXMaPiQAAAABJRU5ErkJggg==',
			'7108' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMZAhimMEx1QBZtZQxgAIoHoIixBjA6OjqIIItNYQhgbQiAqYO4KWpV1NJVUVOzkNzH6ICiDgxZG0BigSjmAdkYdgD1YLgloIE1FMPNAxR+VIRY3AcA0t7JmwvMDj4AAAAASUVORK5CYII=',
			'B6A0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGWY6oAs1irSyOjoEBCAok6kgbUh0EEEyX2hUdPClq6KzJqG5L6AKaKtSOrg5rmGYhFrCECzgxWoNwDFLSA3A8VQ3DxQ4UdFiMV9AGgPzkPqvZEDAAAAAElFTkSuQmCC',
			'EFC8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNEQx1CHaY6IIkFNIg0MDoEBASgibE2CDqIYIgxwNSBnRQaNTVs6apVU7OQ3IemDkmMEYt5mHaguyU0BKgCzc0DFX5UhFjcBwCtMM1XWd3G9AAAAABJRU5ErkJggg==',
			'8798' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQx1CGaY6IImJTGFodHR0CAhAEgtoZWh0bQh0EEFV18raEABTB3bS0qhV01ZmRk3NQnIfUF0AQ0gAmnmMDgxo5gUATWPEsEOkgRHNLawBQBVobh6o8KMixOI+AKq2zHO/1NuPAAAAAElFTkSuQmCC',
			'CA48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WEMYAhgaHaY6IImJtDKGMLQ6BAQgiQU0srYyTHV0EEEWaxBpdAiEqwM7KWrVtJWZmVlTs5DcB1Ln2ohmXoNoqGtoIKp5jUDzGlHtEGkFiaHqZQ0Bi6G4eaDCj4oQi/sAv0DOiQlC5eQAAAAASUVORK5CYII=',
			'4354' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpI37prCGsIY6NAQgi4WItLI2MDQiizGGMDS6NjC0IouxTmFoZZ3KMCUAyX3Tpq0KW5qZFRWF5L4AoDqGhkAHZL2hoQyNDg2BoSEobgHZEYDqlikirYyOaO4DupkhlAFVbKDCj3oQi/sAprTNg3hGaUUAAAAASUVORK5CYII=',
			'931C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WANYQximMEwNQBITmSLSyhDCECCCJBbQytDoGMLowIIq1sowhdEB2X3Tpq4KWzVtZRay+1hdUdRBINA8BzQxAagYsh1gt0xBdQvIzYyhDihuHqjwoyLE4j4AwAzKQsYxCSYAAAAASUVORK5CYII=',
			'6DDA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGVqRxUSmiLSyNjpMdUASC2gRaXRtCAgIQBZrAIkFOogguS8yatrK1FWRWdOQ3BcyBUUdRG8rWCw0BFMMRR3ELY4oYhA3M6KIDVT4URFicR8ARkDNuHPwLLYAAAAASUVORK5CYII=',
			'0421' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YWhlCgRhJjDWAYSqjo8NUZDGRKQyhrA0BochiAa2MriAS2X1RS5cuXbUyaymy+wJaRVoZWlHtCGgVDXWYgiomAuIHYLilFexGNDezhgaEBgyC8KMixOI+AC2iyp/JUc93AAAAAElFTkSuQmCC',
			'732B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkNZQxhCGUMdkEVbRVoZHR0dAlDEGBpdGwIdRJDFpgBFgWIByO6LWhW2amVmaBaS+xgdgOpaGVHMY21gaHSYwohinghILABVLKAB6BYHVL0BDawhrKGBqG4eoPCjIsTiPgD/ksp8NTYZqgAAAABJRU5ErkJggg==',
			'AE04' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMDQEIImxBog0MIQyNCKLiUwRaWB0dGhFFgtoFWlgbQiYEoDkvqilU8OWroqKikJyH0RdoAOy3tBQsFhoCJp5QDsa0O0AugVNDNPNAxV+VIRY3AcA43zN3eN9uecAAAAASUVORK5CYII=',
			'AD30' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQxhDGVqRxVgDRFpZGx2mOiCJiUwRaXRoCAgIQBILaAWKNTo6iCC5L2rptJVZU1dmTUNyH5o6MAwNBZkXiCIGVodpB4ZbAlox3TxQ4UdFiMV9AMBXzoxMvwRXAAAAAElFTkSuQmCC',
			'B030' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgMYAhhDGVqRxQKmMIawNjpMdUAWa2UFqgkICEBRJ9Lo0OjoIILkvtCoaSuzpq7MmobkPjR1UPOAYg2BaGLY7MB0CzY3D1T4URFicR8AravONEaJ0YAAAAAASUVORK5CYII=',
			'CB75' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WENEQ1hDA0MDkMREWkVaGRoCHZDVBTSKNDqgiwFVMjQ6ujoguS9q1dSwVUtXRkUhuQ+sbgrQXFS9jQ4BaGJAOxwdGB1E0NzC2sAQgOw+sJsbGKY6DILwoyLE4j4AiPrMa5972DQAAAAASUVORK5CYII=',
			'4CAC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpI37pjCGMkxhmBqALBbC2ugQyhAggiTGGCLS4Ojo6MCCJMY6RaSBtSHQAdl906ZNW7V0VWQWsvsCUNWBYWgoUCwUVYwBqM4VqI4FRYy10bUhAMUtIDezNgSgunmgwo96EIv7AOxEzEyILUMpAAAAAElFTkSuQmCC',
			'7880' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGVpRRFtZWxkdHaY6oIiJNLo2BAQEIItNAalzdBBBdl/UyrBVoSuzpiG5j9EBRR0YsjaAzAtEERNpwLQjoAHTLQENWNw8QOFHRYjFfQBWmcuthvsm4wAAAABJRU5ErkJggg==',
			'1738' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB1EQx1DGaY6IImxOjA0ujY6BAQgiYkCxRwaAh1EUPQytDIg1IGdtDJr1bRVU1dNzUJyH1BdAAOaeYwOQFEM81gbMMVEGljR3RIi0sCI5uaBCj8qQizuAwD13spRDIW4vAAAAABJRU5ErkJggg==',
			'52A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QMQ6AMAhF6cAN9D506E6TYqKnwaE3UG/g0lNaNxodNSl/e8mHF6A8RqGn/OInySXYnLBhrJhBHEHDhtV737DIsAaNgYzfdJTzLPOyWL8MG94b7OUMjNIyzo5QI1k21GbtsvVDHiUo79TB/z7Mi98FEvXMT/iLbzkAAAAASUVORK5CYII=',
			'6744' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQx0aHRoCkMREpjA0OrQ6NCKLBbQAxaY6tKKINTC0MgQ6TAlAcl9k1KppKzOzoqKQ3BcyhSGAtdHRAUVvK6MDa2hgaAiKGGsDA4ZbRDDEWAMwxQYq/KgIsbgPAA4CzzFJiIsnAAAAAElFTkSuQmCC',
			'33D0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7RANYQ1hDGVqRxQKmiLSyNjpMdUBW2crQ6NoQEBCALDaFoZW1IdBBBMl9K6NWhS1dFZk1Ddl9qOqQzMMmhmoHNrdgc/NAhR8VIRb3AQDmAczMeyEuQgAAAABJRU5ErkJggg==',
			'3E02' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7RANEQxmmMEx1QBILmCLSwBDKEBCArLJVpIHR0dFBBFkMqI61IaBBBMl9K6Omhi1dFQWESO6DqGt0QDMPKNbKgGGHwxQGLG7BdDNjaMggCD8qQizuAwCAX8uWGBKj4QAAAABJRU5ErkJggg==',
			'98B5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGUMDkMREprC2sjY6OiCrC2gVaXRtCEQTA6tzdUBy37SpK8OWhq6MikJyH6srSJ1DgwiyzWDzAlDEBKB2iGC4xSEA2X0QNzNMdRgE4UdFiMV9ANy5zBBh+VniAAAAAElFTkSuQmCC',
			'8B6A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCGVqRxUSmiLQyOjpMdUASC2gVaXRtcAgIQFPH2sDoIILkvqVRU8OWTl2ZNQ3JfWB1jo4wdUjmBYaGYIqhqIO4BVUvxM2MKGIDFX5UhFjcBwAijswLHKUwDAAAAABJRU5ErkJggg==',
			'1F71' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA1qRxVgdRIBkwFRkMVGIWCiqXqBYowNML9hJK7Omhq1aCoRI7gOrm8LQiqE3AFOM0QFTjLUBVUw0BCwWGjAIwo+KEIv7AMbDyUut7jOaAAAAAElFTkSuQmCC',
			'4BFF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpI37poiGsIYGhoYgi4WItLI2MDogq2MMEWl0RRNjnYKiDuykadOmhi0NXRmaheS+gCmY5oWGYprHMAWrGIZesJvRxQYq/KgHsbgPAGXnyR2GuKoeAAAAAElFTkSuQmCC',
			'20DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDGVqRxUSmMIawNjpMdUASC2hlbWVtCAgIQNbdKtLo2hDoIILsvmnTVqauisyahuy+ABR1YMjoABYLDUF2SwPIDlR1Ig0gtziiiIWGgtzMiCI2UOFHRYjFfQByuMtwPXS8BQAAAABJRU5ErkJggg==',
			'AE2D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGUMdkMRYA0QaGB0dHQKQxESmiDSwNgQ6iCCJBbSCeHAxsJOilk4NW7UyM2sakvvA6loZUfSGhgJ5UxgxzQvAFGN0YERxS0CraChraCCKmwcq/KgIsbgPAAJbyqkbUD0EAAAAAElFTkSuQmCC',
			'00DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUNDkMRYAxhDWBsdHZDViUxhbWVtCEQRC2gVaXRFiIGdFLV02srUVZGhWUjuQ1OHUwybHdjcAnUzithAhR8VIRb3AQAc6Mmj+dncrwAAAABJRU5ErkJggg==',
			'7CF4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQ1lDAxoCkEVbWRtdGxgaUcVEGoBirShiU0QaWIFkALL7oqatWhq6KioKyX2MDiB1jA7IelkbwGKhIUhiIg1gO1DcEtAAdguaGNDNaGIDFX5UhFjcBwDheM2csKqz4AAAAABJRU5ErkJggg==',
			'07F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA6Y6IImxBjA0ujYwBAQgiYlMAYkxOoggiQW0MrSyIsTATopaumra0tBVUWFI7gOqC2BtYJiKqpfRgRVkLoodrA1AMRQ7WANEQGIobgHZCDIP2c0DFX5UhFjcBwCJhMq/kHTd3AAAAABJRU5ErkJggg==',
			'15E7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDHUNDkMRYHUQaWIG0CJKYKBYxRgeREJBYAJL7VmZNXbo0FEghuY/RgaHRtYGhFdVesNgUVDERkFgAqhhrKytINbJbQhhDgG5GERuo8KMixOI+AK64yGwGYJQ2AAAAAElFTkSuQmCC',
			'B872' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA6Y6IIkFTGFtBZIBAchirSKNDg2BDiLo6oCiIkjuC41aGbZq6apVUUjuA6ubAlKJZl4AQysDmpijA1Almh2sDQwBGG5uYAwNGQThR0WIxX0A3ITODB5z2CIAAAAASUVORK5CYII=',
			'553B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNEQxmB0AFJLKBBpIG10dEhAE2MoSHQQQRJLDBAJIQBoQ7spLBpU5eumroyNAvZfa0MjQ5o5oHF0MwLaBXBEBOZwtqK7hbWAMYQdDcPVPhREWJxHwDhyMyn21XakwAAAABJRU5ErkJggg==',
			'B64F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgMYQxgaHUNDkMQCprC2MrQ6OiCrC2gVaWSYiiY2RaSBIRAuBnZSaNS0sJWZmaFZSO4LmCLaytqIaZ5raCCGmAO6OpBb0MSgbkYRG6jwoyLE4j4A97HL9fYbd3gAAAAASUVORK5CYII=',
			'0A19' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEx1QBJjDWAMYQhhCAhAEhOZwtrKGMLoIIIkFtAq0ugwBS4GdlLU0mkrs6atigpDch9EHcNUVL2ioUCxBhEUO8DqUOxgDQCLobgFaGOjY6gDipsHKvyoCLG4DwAR2su8gtSofwAAAABJRU5ErkJggg==',
			'C3B0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7WEOAMJShFVlMpFWklbXRYaoDklhAI0Oja0NAQACyWAMDUJ2jgwiS+6JWrQpbGroyaxqS+9DUwcSA5gWiimGxA5tbsLl5oMKPihCL+wCRCM1XykqHDgAAAABJRU5ErkJggg==',
			'5690' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGVqRxQIaWFsZHR2mOqCIiTSyNgQEBCCJBQaINLA2BDqIILkvbNq0sJWZkVnTkN3XKtrKEAJXBxUTaXRoQBULAIo5otkhMgXTLawBmG4eqPCjIsTiPgAXrcwPnwQt/QAAAABJRU5ErkJggg==',
			'38A2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7RAMYQximMEx1QBILmMLayhDKEBCArLJVpNHR0dFBBFkMqI61IaBBBMl9K6NWhi1dFQWESO6DqGt0QDPPNTSglQFdDGg7A5pbgHoD0N3M2hAYGjIIwo+KEIv7APs0zQRRUm3lAAAAAElFTkSuQmCC',
			'A10C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEwNQBJjDWAMYAhlCBBBEhOZAhR1dHRgQRILaGUIYG0IdEB2X9RSEIrMQnYfmjowDA3FFAOpw2YHulsCWllD0d08UOFHRYjFfQARZ8kvgG/YWAAAAABJRU5ErkJggg==',
			'A7E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHUMDkMRYAxgaXYEyyOpEpmCKBbQytLI2MLo6ILkvaumqaUtDV0ZFIbkPqC6AFWQGkt7QUEYHdLEAoGlA8xxQxUSAYgwBAehioQ5THQZB+FERYnEfAL6iy1jQnmudAAAAAElFTkSuQmCC',
			'21E8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYG1gCAhAEgtoZQWKMTqIIOtuZUBWB3HTtFVRS0NXTc1Cdl8AA4Z5jA4MGOaB1KCLiTRg6g0NZQ1Fd/NAhR8VIRb3AQDkLsi9Skhs7wAAAABJRU5ErkJggg==',
			'04CC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YWhlCHaYGIImxBjBMZXQICBBBEhOZwhDK2iDowIIkFtDK6MoKMgHJfVFLgWDVyixk9wW0irQiqYOKiYa6ookB7WhFtwPollZ0t2Bz80CFHxUhFvcBAG5AygbchEebAAAAAElFTkSuQmCC',
			'C3E5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WEOAMNQxNABJTKRVpJW1gdEBWV1AI0OjK7pYAwNInasDkvuiVq0KWxq6MioKyX0QdUBzUfUCzUMTg9qBLAZxC0MAsvsgbnaY6jAIwo+KEIv7AMzsy0SfLBZAAAAAAElFTkSuQmCC',
			'D400' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgMYWhmmADGSWMAUhqkMoQxTHZDFWhlCGR0dAgJQxBhdWRsCHUSQ3Be1FAhWRWZNQ3JfQKtIK5I6qJhoqCuGGEMrhh0gt6G5BZubByr8qAixuA8Ae77NRuUEbVEAAAAASUVORK5CYII=',
			'E50A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMLQiiwU0iDQwhDJMdUATY3R0CAhAFQthbQh0EEFyX2jU1KVLV0VmTUNyH1BPoytCHbJYaAiqeY2Ojo5o6lhbGUIZUcRCQxhDGKagig1U+FERYnEfAPgKzLDsesrpAAAAAElFTkSuQmCC',
			'9303' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WANYQximMIQ6IImJTBFpZQhldAhAEgtoZWh0dHRoEEEVa2VtCGgIQHLftKmrwpauilqaheQ+VlcUdRAINM8VKIJsngAWO7C5BZubByr8qAixuA8AfgHMZAvpOfcAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && PHPFMG_USER == $_POST['Username'] &&
            defined( 'PHPFMG_PW' )   && PHPFMG_PW   == $_POST['Password']
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created aumotically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>