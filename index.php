<?php

require_once('config.php');
require_once('marquee.php');

if (isset($_REQUEST['marquee_text']))
{
    $text = $_REQUEST['marquee_text'];

    $fbml = '<fb:fbml version="1.1">' .
                '<fb:subtitle>' .
                    '<fb:action href="http://www.facebook.com/apps/application.php?api_key=291292077554369">Home</fb:action>' .
                    '<fb:action href="http://apps.facebook.com/clicktocall">Click2Call</fb:action>' .
                '</fb:subtitle>' .
                '<a href="http://apps.facebook.com/clicktocall">' .
                get_marquee_code($text) .
                '</a>' .
            '</fb:fbml>';

    $facebook->api_client->profile_setFBML($fbml, $user);

    $database = new Database();
    $database->query("update fb_marquee set value='" . $database->quote($text) . "' where uid = $user");
}
else
{
    $database = new Database();
    $result = $database->query("select * from fb_marquee where uid = $user");
    if (sizeof($result) == 0)
    {
        $text = "Hello, world!";
        $result = $database->query("insert into fb_marquee (uid, value) values ($user, '" . $database->quote($text) . "')");
    }
    else
    {
        $text = $result[0]->value;
    }
}

?>

<div style="margin: 10px;">

<fb:dashboard>
  <fb:create-button href="invite.php">Invite your friends!</fb:create-button>
</fb:dashboard>
<br />

<div style="width: 50%">
Add a scrolling LED marquee to your profile to let your friends know what's going on. Announce it!
</div>

<br />
<br />

<div style="width: 100%; margin-left: 20%; margin-right: 20%;">
<span style="text-align: center;"><b>Preview</b></span><br />
<?php
    echo get_marquee_code($text);
?>
</div>

<br />
Enter the text you want shown in the marquee. <br />
Enter each message in its own line; they will be separated appropriately.<br />
<br />
<form action="" method="get">
    <textarea name="marquee_text" cols="50" rows="7"><?php echo $text; ?></textarea><br />
    <input name="submit" type="submit" value="Save">
</form>

</div>