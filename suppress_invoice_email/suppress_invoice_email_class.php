<?php
/* WHMCS Suppress Email Notification Addon with GNU/GPL Licence
 * Abale - https://abale.com.br
 *
 * https://github.com/khigashi/WHMCS-suppress-invoice-email-addon
 *
 * Developed at Abale by Marcio Dias A.K.A khigashi  (https://abale.com.br)
 * Licence: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
 * */

class SuppressInvoiceEmail{

    /**
     * @return array
     */

    public function getSettings(){

        return mysql_fetch_array(select_query("addon_suppress_invoice_email_settings", "suppressemail", array()));
		
    }

}
?>