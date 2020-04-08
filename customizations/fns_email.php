<?php

function core_send_email($to, $subject, $message, $html = false) {
    $tmpfname = tempnam("/var/www/html/dev/tmp", "KISS") . ".html";
    file_put_contents($tmpfname, $to . "<br/><br/>" . $subject . "<br/><br/>" . $message);
    core_log_debug("TMP EMAIL saved to:" . $tmpfname);
}

?>