<?box_start();?>
                <?
                if ($config_values['DISABLE_MESSAGE_UPLOAD'] == "NO") {

                    ?>
                    
                        <A HREF="uploadmessage.php?type=audio" class="btn btn-primary "><i class="glyphicon glyphicon-upload"></i> Upload New Audio Message</A>
                    
                    <? if ($config_values['disable_all_types'] != "YES") {

                        ?>
                        
                            <A HREF="uploadmessage.php?type=fax" class="btn btn-primary "><i class="glyphicon glyphicon-upload"></i> Upload New Fax Message</A>
                        
                        <?
                    }
                    ?>
                    <?
                }
                ?>
                
                    <A HREF="messages.php" class="btn btn-primary "><i class="glyphicon glyphicon-list"></i> View All Messages</A>
                
<?box_end();?>