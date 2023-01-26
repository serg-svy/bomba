<?php
/**
 * Created by PhpStorm.
 * User: Root
 * Date: 14.11.2018
 * Time: 13:01
 */
$lang = strtolower($lang);
$name = 'name_' . $lang;
?>
<div style="background-color:transparent;">
    <div style="Margin: 0 auto;min-width: 320px;max-width: 620px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid mixed-two-up ">
        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 620px;"><tr class="layout-full-width" style="background-color:#FFFFFF;"><![endif]-->

            <!--[if (mso)|(IE)]><td align="center" width="413" style=" width:413px; padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:0px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num8" style="display: table-cell;vertical-align: top;min-width: 320px;max-width: 408px;">
                <div style="background-color: transparent; width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!--><div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:15px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->


                        <div class="">
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                            <div style="color:#000000;font-family:'Lato', Tahoma, Verdana, Segoe, sans-serif;line-height:120%; padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px;">
                                <div style="font-size:12px;line-height:14px;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;color:#000000;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><span style="color: rgb(0, 0, 0); font-size: 14px; line-height: 16px;"><a style="text-decoration: none; color: rgb(0, 0, 0);" href="<?= rtrim($url, '/') . '/' . $lang . '/product/' . $id ?>" target="_blank"><?= $quantity ?> x <?= $$name ?></a></span></p></div>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                        </div>

                        <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                </div>
            </div>
            <!--[if (mso)|(IE)]></td><td align="center" width="207" style=" width:207px; padding-right: 0px; padding-left: 0px; padding-top:15px; padding-bottom:0px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num4" style="display: table-cell;vertical-align: top;max-width: 320px;min-width: 204px;">
                <div style="background-color: transparent; width: 100% !important;">
                    <!--[if (!mso)&(!IE)]><!--><div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:15px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;"><!--<![endif]-->

                        <div class="">
                            <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->
                            <div style="color:#000000;font-family:'Lato', Tahoma, Verdana, Segoe, sans-serif;line-height:120%; padding-right: 20px; padding-left: 20px; padding-top: 10px; padding-bottom: 10px;">
                                <div style="font-size:12px;line-height:14px;font-family:Lato, Tahoma, Verdana, Segoe, sans-serif;color:#000000;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px"><?= number_format(($price * $quantity), 2, '.', ' '); ?> MDL</p></div>
                            </div>
                            <!--[if mso]></td></tr></table><![endif]-->
                        </div>

                        <!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
                </div>
            </div>
            <!--[if (mso)|(IE)]></td></tr></table></td></tr></table><![endif]-->
        </div>
    </div>
</div>
