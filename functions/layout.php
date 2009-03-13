<?
if (!function_exists('box_start') ) {
     function box_start() {
        echo '<div id="box"><!--- box border --><div id="lb"><div id="rb"><div id="bb"><div id="blc"><div id="brc"><div id="tb"><div id="tlc"><div id="trc"><div id="boxcontent">';
     }
}
if (!function_exists('box_end') ) {
     function box_end() {
        echo '</div><!--- end of box border --></div></div></div></div></div></div></div></div></div>';
     }
}
if (!function_exists('box_button') ) {
     function box_button($name,$image,$url,$description) {
?><div style="width:50%;height:80px;display:inline-table">
        <div class="boxbutton" id="<?=$name?>" >
            <a  href="<?=$url;?>" onclick="this.blur();new Effect.Pulsate('<?=$name?>',{ pulses: 1, duration: 0.5 });setTimeout('this.location=\'/<?=$url?>\'',1000);return false;">
                <img src="/images/64x64/<?=$image?>.png" align="left" />
                <b><?=$name?></b><br /><?=$description?>
            </a>
        </font>
        </div>
    </div><?
    }
}
?>
