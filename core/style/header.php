<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo auxi_lang("pagetitle_".$GLOBALS['pagename']); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['baseurl'] . "/" . $GLOBALS['css'];?>">
    <?php if (isset($GLOBALS['customcss'])) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['baseurl'] . "/" . $GLOBALS['customcss'];?>">
    <?php } ?>
<body>
    <div id="main">
        <div id="sidebar">
            <div id="sidebar-content">
                <div id="site-title">
                    <a href="#"><?php echo auxi_lang("bannertitle_".$GLOBALS['pagename']); ?></a>
                </div>
                <?php

                    foreach($menuitems as $menuitem) {
                        ?>
                            <div class="menuitem">
                                <?php if($menuitem != $GLOBALS['pagename']) { ?>
                                <a href="<?php echo $GLOBALS['baseurl'] . "/" . $menuitem; ?>">
                                <?php echo auxi_lang("menuitem_" . $menuitem); ?></a>
                                <?php } else { ?>
                                <b><?php echo auxi_lang("menuitem_" . $menuitem); ?></b>
                                <?php } ?>
                            </div>
                        <?php
                    }

                ?>
                <div id="aboutbox">
                    <span id="about-title"><?php echo auxi_lang("bannerabouttitle"); ?></span>
                    <span id="about-text"><?php echo auxi_lang("bannerabout"); ?></span>
                    <img id="logo" alt="logo" src="<?php echo $GLOBALS['baseurl'] . "/" . $GLOBALS['left_logo']; ?>"/>
                </div>
            </div>
        </div>
        <div id="leftbar">
            <div id="headbox">
                <img id="headbox-img" src="<?php echo $GLOBALS['baseurl'] . "/" . $GLOBALS['head_logo']; ?>" />
            </div>
            <div id="content">