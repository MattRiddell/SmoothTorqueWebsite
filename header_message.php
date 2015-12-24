<?box_start();?>
                <?
                if ($config_values['DISABLE_MESSAGE_UPLOAD'] == "NO") {

                    ?>
                    
                        <A HREF="uploadmessage.php?type=audio" class="btn btn-default navbar-btn"><img src="images/sound_add.png" border="0" align="left">Upload New Audio Message</A>
                    
                    <? if ($config_values['disable_all_types'] != "YES") {

                        ?>
                        
                            <A HREF="uploadmessage.php?type=fax" class="btn btn-default navbar-btn"><img src="images/page_add.png" border="0" align="left">Upload New Fax Message</A>
                        
                        <?
                    }
                    ?>
                    <?
                }
                ?>
                
                    <A HREF="messages.php" class="btn btn-default navbar-btn"><img src="images/sound.png" border="0" align="left">View All Messages</A>
                
<?box_end();?>