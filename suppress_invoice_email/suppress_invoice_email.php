<?php
/* WHMCS Suppress Email Notification Addon with GNU/GPL Licence
 * Abale - https://abale.com.br
 *
 * https://github.com/khigashi/WHMCS-suppress-invoice-email-addon
 *
 * Developed at Abale by Marcio Dias A.K.A khigashi  (https://abale.com.br)
 * Licence: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
 * */
 
if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

function suppress_invoice_email_config($var) {
	
	
  
    $configarray = array(
        "name" => "Suppress Invoice Email Notification",
        "description" => "WHMCS Suppress Invoice Email Notifications addon. You can see details from: https://github.com/khigashi/WHMCS-suppress-invoice-email-addon",
        "version" => "1.0.0",
        "author" => "Marcio Dias",
		"language" => "english",

    );
	
    return $configarray;
}

function suppress_invoice_email_activate() {

	mysql_query("CREATE TABLE IF NOT EXISTS `addon_suppress_invoice_email_settings` (`id` int(11) NOT NULL AUTO_INCREMENT,`suppressemail` int(11) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

	mysql_query("INSERT INTO `addon_suppress_invoice_email_settings` (`suppressemail`) VALUES (0);");

    return array('status'=>'success','description'=>'Suppress Email succesfully activated');

}

function suppress_invoice_email_deactivate() {

    mysql_query("DROP TABLE `addon_suppress_invoice_email_settings`");

    return array('status'=>'success','description'=>'Suppress Email succesfully deactivated');
	
}

function suppress_invoice_email_output($vars){
	
	require_once("suppress_invoice_email_class.php");
		
	$LANG = $vars['_lang'];
    $tab = $_GET['tab'];
	
    $class = new SuppressInvoiceEmail();

    if ($_POST['suppressemail']) {
    	
        $update = array(
            'suppressemail' => $_POST['suppressemail'],
        );
        update_query("addon_suppress_invoice_email_settings", $update, "");
		
    }

    $settings = $class->getSettings();

    $where = array(
        "fieldtype" => array("sqltype" => "LIKE", "value" => "tickbox"),
    );
	
    $result = select_query("tblcustomfields", "id,fieldname", $where);
    $suppress_invoice_email_field = '';
	
    while ($data = mysql_fetch_array($result)) {
    	
        if ($data['id'] == $settings['suppressemail']) {
        	
            $selected = 'selected="selected"';
            
        } else {
        	
            $selected = "";
			
        }
		
        $suppress_invoice_email_field .= '<option value="' . $data['id'] . '" ' . $selected . '>' . $data['fieldname'] . '</option>';
		
    }

    echo '
    <form action="" method="post">
    <input type="hidden" name="action" value="save" />

            <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
                <tbody>
                    <tr>
                        <td class="fieldlabel">'.$LANG['suppressemail'].'</td>
                        <td class="fieldarea">
                            <select name="suppressemail" class="form-control input-inline input-200">
                                ' . $suppress_invoice_email_field . '
                            </select>
                        </td>
                    </tr>

                </tbody>
            </table>
        
        <p align="center"><input type="submit" value="'.$LANG['save'].'" class="button" /></p>
    </form>
    ';

    echo '<div style="text-align: center;margin-top:50px;"><b>By Marcio Dias - <a href="https://github.com/khigashi/WHMCS-suppress-invoice-email-addon" target="_blank">github.com/khigashi/WHMCS-suppress-invoice-email-addon</a></b></div>';

}