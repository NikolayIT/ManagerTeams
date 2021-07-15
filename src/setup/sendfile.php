<?php
/*
Hack Name: PHP Send File By Email
Hack URI: http://planetozh.com/blog/my-projects/php-send-file-by-email-sendmail-attachment/
Description: Fetch a file and send it as an email attachment
Version: 1.0
Author: Ozh
Author URI: http://planetOzh.com
*/

/***********************************
 * CONFIGURE THE SCRIPT
 * Edit here to suit your needs
 ***********************************/

$sendfile['email_from'] = "SendMeFiles <noreply@yourblog.com>";
    // Who the email is from

$sendfile['email_subject'] = "File : %f (%s bytes)";
    // The Subject of the email (%f will be file name, %s size)

$sendfile['email_message'] = "File name : %f\nSize: %s bytes\nDownloaded from : %l\n\n%t";
    // Message along with attachment (%f: file name, %s: size, %l: original location, %t: free text)

$sendfile['email_to'] = "you@yourblog.com";
    // Default value of recipient's email when nothing specified

$sendfile['file_dir'] = "/var/www/managerteams.com";
    // Directory in which sent files will be mirrored first. Create this directory and chmod it 777 (make it writeable)

$sendfile['file_delete'] = 1;
    // Default behaviour of the script once the file has been sent by email. Delete it (1) or keep it on server (0)

$sendfile['file_log'] = "sendfile.log";
    // Log file in which dates and URL of files sent are stored


/***********************************
 * END CONFIG
 * Do not modify below, unless you know
 * what you are doing
 ***********************************/

$sendfile['remote'] = stripslashes(init('remote'));
$sendfile['local'] = stripslashes(init('local'));
$sendfile['deletefile'] = init('deletefile');
$sendfile['dest'] = init('dest');
$sendfile['text'] = stripslashes(init('text'));
$sendfile['log'] = stripslashes(init('log'));
$sendfile['action'] = init('action');
$sendfile['bintext'] = init('bintext');

html();

if (!$sendfile['remote'] and $sendfile['action']) {
    print '<p class="error"><b>ERROR</b> : You did not specify any file to send ! Try again !</p>';
    $sendfile['action'] = '';
}

switch ($sendfile['action']) {
    case 'mirror':
        mirrorfile($sendfile['remote']);
        break;
    case 'send':
        sendfile($sendfile['local']);
    default:
        printform();
}

function init($in='') {
    return @$_GET[$in]?@$_GET[$in]:@$_POST[$in];
}

function logsend() {
    global $sendfile;
    
    $log=fopen($sendfile['file_log'],"a");
    $stamp = date("Y/m/d G:i:s");
    $msg = "$stamp :\n\tFile ${sendfile['remote']}\n\tSent to ${sendfile['dest']}\n\n";
    fputs($log,$msg);
    fclose($log);
}

function sendfile($input) {

    global $sendfile;

    /* PREPARE VARIABLES */
    if (!$sendfile['dest']) $sendfile['dest'] = $sendfile['email_to'];
    if ($sendfile['log']) print $sendfile['log'];

    print "<p class=\"info\">Sending file to <b>" .  $sendfile['dest'] . "</b> ...</p>";

    $sendfile['email_subject'] = str_replace ('%f',basename($input),$sendfile['email_subject']);
    $sendfile['email_subject'] = str_replace ('%s',filesize($input),$sendfile['email_subject']);

    $sendfile['email_message'] = str_replace ('%f',basename($input),$sendfile['email_message']);
    $sendfile['email_message'] = str_replace ('%s',filesize($input),$sendfile['email_message']);
    $sendfile['email_message'] = str_replace ('%l',$sendfile['remote'],$sendfile['email_message']);
    $sendfile['email_message'] = str_replace ('%t',$sendfile['text'],$sendfile['email_message']);

    
    /* PREPARE MAIL HEADERS */
    $headers = "From: ".$sendfile['email_from'];
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers .= "\nMIME-Version: 1.0\n" .
                "Content-Type: multipart/mixed;\n" .
                " boundary=\"{$mime_boundary}\"";

    $email_message = "This is a multi-part message in MIME format.\n\n" .
                    "--{$mime_boundary}\n" .
                   "Content-Type:text/plain; charset=\"iso-8859-1\"\n" .
                   "Content-Transfer-Encoding: 7bit\n\n" .
            @$sendfile['email_message'] . "\n\n" .
            "-- \nFile sent with PHP Send File By Email\nhttp://frenchfragfactory.net/ozh/my-projects/php-send-file-by-email-sendmail-attachment/\n" .
            "(c) Ozh 2005 - http://planetozh.com\n\n" ;

    /* PREPARE ATTACHMENT */
    $fileatt = basename($input) ;
    $fileatt_type = "application/octet-stream";

    $file = fopen($input,"r${sendfile['bintext']}");
    $data = fread($file,filesize($input));
    fclose($file);

    $data = chunk_split(base64_encode($data));

    $email_message .= "--{$mime_boundary}\n" .
        "Content-Type: {$fileatt};\n" .
        " name=\"{$fileatt}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data . "\n\n" .
        "--{$mime_boundary}--\n";

    /* SEND FILE */
    $ok = @mail($sendfile['dest'], $sendfile['email_subject'], $email_message, $headers);

    if($ok) {
        echo '<p class="info">File successfully sent!</p>';
        logsend();
    } else {
        echo('<p class="error"><b>ERROR</b> : could not send email.</p>');
    }

    if($sendfile['deletefile'] and ($input != __FILE__) ) {
        $ok=unlink($input);
        if ($ok==FALSE) {
            echo '<p class="error"><b>ERROR</b> : could not delete file from server.</p>';
        } else {
            echo '<p class="info">File successfully deleted.</p>';
        }
    }
}

function mirrorfile($url) {

    global $sendfile;

    if (!$sendfile['local']) $sendfile['local'] = basename($url);

    $log = "<p class=\"info\">Mirroring file <b>$url</b> ...</p>";

    print $log;

    $msg = '';

    // open source file
    $hfici=@fopen($url,"r${sendfile['bintext']}");

    // woks fine ?
    if($hfici==FALSE){
        // No :/
        $msg="<b>ERROR</b> : couldn't read source file <b>$url</b>.";
    } else {
        // Yes : create target file
        $hfico=@fopen($sendfile['file_dir'] . '/' . $sendfile['local'],"w${sendfile['bintext']}");
        // works fine ?
        if($hfico==FALSE){
            // No :/
            $msg="<b>ERROR</b> : couldn't locally write file";
        }
        else {
            // Yes : read remote and write local
            while($buf=fread($hfici,1024)){
                fwrite($hfico, $buf);
            }
            fclose($hfici);
            fclose($hfico);
        }
    }
    if ($msg) {
        print "<p class='error'>$msg</p>";
        printform();
    } else {
        $log2 = "<p class='info'>File locally mirrored !</p><p class='info'>Preparing mail and attachment ...</p>";
        print $log2;
        print '<form method="post" action="'. basename(__FILE__) . '" name="form_send">';
        print '<input type="hidden" name="remote" value="' . $sendfile['remote'] . '">';
        print '<input type="hidden" name="local" value="' . $sendfile['file_dir'] . '/' . $sendfile['local'] . '">';
        print '<input type="hidden" name="deletefile" value="' . $sendfile['deletefile'] . '">';
        print '<input type="hidden" name="dest" value="' . $sendfile['dest'] . '">';
        print '<input type="hidden" name="text" value="' . $sendfile['text'] . '">';
        print '<input type="hidden" name="bintext" value="' . $sendfile['bintext'] . '">';
        print '<input type="hidden" name="log" value="' . htmlentities($log . $log2) . '">';
        print '<input type="hidden" name="action" value="send">';
        //print '<input type="submit">';
        print '</form>';
        print <<<JS
        <script>
        document.form_send.submit();
        </script>
JS;
    }
}

function printform() {

    global $sendfile;

    if ($sendfile['file_delete']) {
        $checked_del = 'checked';
        $checked_keep = '';
    } else {
        $checked_del = '';
        $checked_keep = 'checked';
    }

    $_this = basename(__FILE__);

    print <<<HTML
    </div>
    <div id="box">
    <fieldset id="form"><legend><a href="$_this">Send a File</a></legend>
    <form method="post" name="form_mirror">
    <p title="URL of a file. Can be a full url e.g. 'http://host.com/file.exe', or a relative url from your own server, e.g. 'file.zip' or 'somedir/archive.rar'"><span class="label">URL of file to send</span><input class="wide" type="text" name="remote"></p>
    <p title="Optionnal. Do you want to rename the locally mirrored file ?"><span class="label">Rename this file</span><input class="wide" type="text" name="local"></p>
    <p title="Is it a text file (.txt, .html ...) or a binary file (an archive, a .exe, an image, ....) ?"><span class="label">Binary or Text file</span><input type="radio" name="bintext" value="b" id="bintext_b" checked><label for="bintext_b">Binary</label> &mdash; <input type="radio" name="bintext" value="t" id="bintext_t"><label for="bintext_t">Text</label></p>
    <p title="After the file has been sended (successfully or not), do you want to delete the local copy ?"><span class="label">Delete locally mirrored file</span><input type="radio" name="deletefile" value="1" id="radio_del" $checked_del ><label for="radio_del">Yes</label> &mdash; <input type="radio" name="deletefile" value="0" id="radio_keep" $checked_keep ><label for="radio_keep">No</label></p>
    <p title="Enter recipient's email address"><span class="label">Send file to</span><input class="wide"  type="text" name="dest"></p>
    <p title="Optionnal. Enter here any message you'd like to send along with the file"><span class="label">Additionnal message</span><textarea name="text"></textarea></p>
    <p><span class="label">&nbsp; </span><input class="wide"  type="submit" value="Send"></p>
    <input type="hidden" name="action" value="mirror">
    </form>
    </fieldset>
        <p id="ozh"><a href="http://frenchfragfactory.net/ozh/my-projects/php-send-file-by-email-sendmail-attachment/">PHP Send File By Email</a> by <a href="http://planetOzh.com/">Ozh</a></p>
    </div>
HTML;
}

function html() {
    print <<<HTML
<html>
<head>
<title>PHP Send File</title>
</head>
<body>
<style>
body {background:#EFDFCD;text-align:center;color:#111;font-family:verdana,arial;}
a,a:visited {color:brown;text-decoration:none}
a:hover{text-decoration:underline;}
#box{margin:0 auto;margin-top:1em;padding:0.5em;width:580px;border:2px solid brown;text-align:justify}
#log{margin:0 auto;margin-top:1em;padding:0.5em;width:580px;}
#form {padding:0.5em;color:brown}
#ozh {text-align:right;margin:0.2em 0;font-size:0.8em}
p{font-family:verdana,arial}
p.error{color:red;border:2px solid white;kwidth:580px;padding:0.2em;margin:0.1em 0}
p.info{color:green;border:2px solid silver;kwidth:580px;padding:0.2em;margin:0.1em 0}
.kklabel{width=200px;text-align:right;margin-right:1em;background:white;}
.label{display:block;float:left;text-align:right;margin-right:1em;width:250px;clear:left;}
input,textarea,label{color:brown;font-family:verdana,arial;font-size:1em;}
input.wide,textarea{kwidth=50%;border:1px solid brown}
</style>
<div id="log">
HTML;
}
?>
