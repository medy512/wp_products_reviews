<table style="width: 100%;">
    <tr valign="top">
        <th class="metabox_label_column" style="width: 20%;">
            <label for="meta_a">Ratings</label>
        </th>
        <td style="width: 80%;">
            <?php
                $stars = array('1 Star' => 1, '2 Stars' => 2, '3 Stars' => 3, '4 Stars' => 4, '5 Stars' => 5);
                $options = '<option value="">Select Rating</option>';
                $value = get_post_meta($post->ID, 'ratings', true);
                foreach($stars as $key => $val){
                    $selected = ($val == $value)?'selected':'';
                    $options .= '<option value="' . $val . '" ' . $selected . '>' . $key . '</option>';
                }
            ?>
            <select name="ratings" style="width: 100%;" id="ratings"> <?php echo $options; ?> </select>
        </td>
    </tr>
</table>
