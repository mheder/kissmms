<?php

############################################################################
#
# Copyright [2020] [Mihály Héder]
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#
############################################################################

function start_table($headings){
    echo "\n<table class=\"bigtable\">\n";
    echo "<tr>";
    foreach($headings as $heading_val) {
        echo "<th>";
        echo auxi_lang($heading_val); //it does echo the stuff you need
        echo "</th>"; 
    }    
    echo "</tr>\n";
}

function make_table_row($data) {
    echo "<tr>";
    foreach($data as $td) {
        echo "<td>$td</td>";
    }
    echo "</tr>\n";
}

function end_table() {
    echo "</table>\n";
}

function make_audit_log_screen($data) {
    start_table(array("actor_cuid","target_cuid","action","data","timestamp"));
    foreach ($data as $d) {
        make_table_row($d);
    }
    end_table();  
}

function make_manscreen($data) {
    start_table(array("attribute","value","source","assurance","updated_at","extrainfo"));
    foreach ($data as $d) {
        
        make_table_row(array(auxi_lang($d[0]),$d[1],$d[2],$d[3],$d[4],$d[5]));
    }
    end_table();
}

function make_regscreen($data,$cuid) {
?>
    <div id="centerbox">
        <div class="important_box"><h2><?php echo auxi_lang("your_new_account"); ?></h2></div>
        <form action="reg_save.php" method="post" 
        onsubmit="if(document.getElementById('agree').checked) { return true; } else { alert('<?php echo auxi_lang("need_agree_policy"); ?>'); return false; }">
        <table class="regtable">
        <tr><th><?php 
        echo auxi_lang("attribute_name");
        ?></th><th><?php echo auxi_lang("attribute_value");?></th></tr>
        <tr><td><?php echo auxi_lang("attribute_cuid"); ?></td><td><?php echo $cuid; ?></td></tr>
<?php
foreach($data as $key => $attrcomplex) {
?>
    <tr>
        <td><?php 
        if ($attrcomplex[0]["required"] == "Y") {
            echo "&nbsp;*&nbsp;";
        }                
        echo auxi_lang("attribute_" . $key); 
        ?></td>
        <td><?php
        if (empty($attrcomplex[1]) and $attrcomplex[0]["customizable"] == "Y") {
            echo "<input name='attribute_$key' type='text' value=''>";
        }
        foreach($attrcomplex[1] as $val) { 
            if ($attrcomplex[0]["displayed"] == "Y") {
                if ($attrcomplex[0]["customizable"] == "Y" and $attrcomplex[0]["multival"] == "N") {
                    echo "<input name='attribute_$key' type='text' value='$val'>";
                    echo "<br>"; 
                } else if ($attrcomplex[0]["customizable"] == "N") {
                    echo $val;
                    echo "<br>";  
                }
            } else {
                echo "<input name='attribute_$key' type='hidden' value='$val'>";
            }
        } 
        ?></td>
    </tr>
<?php 
}
?>
        </table>
        <div class="important_box">
            <input type="checkbox" name="aup_checkbox" value="check" id="agree" /><?php echo auxi_lang("agree_policy"); ?><br>
            <input id="submitbutton" type="submit" name="submit" value="submit" />
        </div>
        </form>
    </div>
<?php
}

function make_error_message($message) {
    ?>
        <div class="error_message"><?php echo $message; ?></div>
    <?php
    }

function make_info_message($message) {
    ?>
        <div class="info_message"><?php echo $message; ?></div>
    <?php
    }    

function make_important_box($message) {
    ?>
    <div class="important_box"><h2><?php echo $message ?></h2></div>
    <?php
}

function make_text($message) {
    ?>
    <p><?php echo $message ?></p>
    <?php
}

function make_acc_link_form() {
?>
    <div class="important_box">
    <form method="get" action="doforceauthn.php"> 
                <input type="submit" value="<?php echo auxi_lang("cont_account_linking"); ?>" /> 
    </form>
    </div>
<?php
}

function make_attr_edit_form($data) {
    $ilabel = base64_encode($data[0]['name']);
    ?>
    <div class="important_box">
        <form method="POST" action="attr_edit_save.php">
            <label for="<?php echo $ilabel; ?>"><b><?php echo auxi_lang("attribute_".$data[0]['name']); ?></b></label>
            <input type="input" name="<?php echo $ilabel;?>" value="<?php echo $data[0]['value']; ?>"/> <br><br>
            <input type="submit" value="<?php echo auxi_lang("save_attr_value"); ?>" /> 
        </form>
    </div>
    <?php
    }
?>