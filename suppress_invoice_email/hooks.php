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


function suppress_invoice_email($args){
					
	$haystack = array(
	'Third Invoice Overdue Notice',
	'Second Invoice Overdue Notice',
	'Invoice Refund Confirmation',
	'Invoice Payment Reminder',
	'Invoice Payment Confirmation',
	'Invoice Created',
	'First Invoice Overdue Notice',
	);
	
	if(in_array($args['messagename'], $haystack)) {
		
		require_once("suppress_invoice_email_class.php");
	
	    $class = new SuppressInvoiceEmail();
	
	    $settings = $class->getSettings();
		
					
			if($settings['suppressemail'] AND $args['relid']){
				
			    $userSql = "";
				$userSql .= "SELECT ";
				$userSql .= "a.id AS id, ";
				$userSql .= "b.id AS userid ";
				$userSql .= "FROM ((tblinvoices a JOIN tblclients b ON((b.id = a.userid))) JOIN tblcustomfieldsvalues c ON((c.relid = a.userid))) ";
				$userSql .= "WHERE ((a.id = '".$args['relid']."') AND (c.fieldid = '".$settings['suppressemail']."') AND (c.value = 'on')) ";
				$userSql .= "LIMIT 1";
			
			    $result = mysql_query($userSql);
			    $num_rows = mysql_num_rows($result);
			    
			    if($num_rows == 1){
			    	
					list($invoice_id, $customer_id) = mysql_fetch_array($result);
					
			        $merge_fields['abortsend'] = true;
					
					logActivity("Email Suppressed - ".$args['messagename'], $customer_id);

			    }
	
	    }
	
	}

	return $merge_fields;

}

add_hook('EmailPreSend', 1, "suppress_invoice_email");
?>