<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$listall = '';
$colspan = 9;

wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_style("wp-jquery-ui-dialog");

$options = get_option(G5_OPTION_KEY);
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <?php _e('Created board items', 'gnupress');?> <strong><?php echo number_format($total_count) ?></strong> <?php _e('item', 'gnupress');?>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>">
<input type="hidden" name="paged" value="<?php echo intval( $paged ); ?>">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="bo_table"<?php echo g5_get_selected($sfl, "bo_subject", true); ?>>TABLE</option>
    <option value="bo_subject"<?php echo g5_get_selected($sfl, "bo_subject"); ?>><?php echo _e('Subject', 'gnupress');?></option>
</select>
<label for="stx" class="sound_only"><?php _e('Search word', 'gnupress');?><strong class="sound_only"> <?php _e('required', 'gnupress');?></strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" value="<?php _e('Search', 'gnupress');?>" class="btn_submit button">

</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
    <a href="<?php echo add_query_arg( array('page'=>'g5_board_form') );?>" id="bo_add" class="button"><?php _e('New board add', 'gnupress');?></a>
</div>
<?php } ?>

<form name="fboardlist" id="fboardlist" action="<?php echo g5_form_action_url(admin_url("admin.php?page=g5_board_list"));?>" onsubmit="return fboardlist_submit(this);" method="post">
<?php wp_nonce_field( 'bbs_list_admin' ); ?>
<input type="hidden" name="g5_admin_post" value="bbs_list_update" />
<input type="hidden" name="sst" value="<?php echo esc_attr( $sst ); ?>">
<input type="hidden" name="sod" value="<?php echo esc_attr( $sod ); ?>">
<input type="hidden" name="sfl" value="<?php echo esc_attr( $sfl ); ?>">
<input type="hidden" name="stx" value="<?php echo esc_attr( $stx ); ?>">
<input type="hidden" name="page" value="<?php echo esc_attr( $page ); ?>">
<input type="hidden" name="paged" value="<?php echo intval( $paged ); ?>">

<div class="bootstrap" id="no-more-tables">
    <table class="table-bordered table-striped table-condensed">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">게시판 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="g5_check_all(this.form)">
        </th>
        <th scope="col"><?php echo g5_subject_sort_link('bo_table') ?>TABLE</a></th>
        <th scope="col"><?php echo g5_subject_sort_link('bo_skin', '', 'desc') ?><?php _e('Skin', 'gnupress');?></a></th>
        <th scope="col"><?php echo g5_subject_sort_link('bo_subject') ?><?php _e('Subject', 'gnupress');?></a></th>
        <th scope="col"><?php _e('Read P', 'gnupress');?><span class="sound_only"><?php _e('Point', 'gnupress');?></span></th>
        <th scope="col"><?php _e('Write P', 'gnupress');?><span class="sound_only"><?php _e('Point', 'gnupress');?></span></th>
        <th scope="col"><?php _e('Comment P', 'gnupress');?><span class="sound_only"><?php _e('Point', 'gnupress');?></span></th>
        <th scope="col"><?php _e('Download P', 'gnupress');?><span class="sound_only"><?php _e('Point', 'gnupress');?></span></th>
        <th scope="col"><?php _e('Manage', 'gnupress');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach($rows as $row){
        $modify_url = add_query_arg( array('page'=>'g5_board_form', 'w'=>'u', 'bo_table'=>$row['bo_table'] ) ); //수정 버튼 url
        $one_update = '<a href="'.$modify_url.'" class="button">'.__('Modify', 'gnupress').'</a>';
        $one_copy = '<a href="./board_copy.php?bo_table='.$row['bo_table'].'" class="button board_copy" data-bo_table="'.$row['bo_table'].'" data-bo_subject="'.$row['bo_subject'].'" title="'.__('Copy board', 'gnupress').'">'.__('Copy', 'gnupress').'</a>';
        $bbs_direct_url = '';
        
        if( $g5_get_page_id = g5_get_page_id(G5_NAME."-".$row['bo_table']) ){     //게시판이 적용된 페이지가 존재한다면
            $bbs_direct_url = add_query_arg( array( 'bo_table'=>$row['bo_table'] ), get_permalink($g5_get_page_id) );
        }
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo g5_get_text($row['bo_subject']); ?></label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td>
            <input type="hidden" name="board_table[<?php echo $i ?>]" value="<?php echo $row['bo_table']; ?>">
            <?php if( $bbs_direct_url ){ ?>
                <a href="<?php echo $bbs_direct_url; ?>">
            <?php } ?>
            <?php echo $row['bo_table'] ?>
            <?php if( $bbs_direct_url ){ ?>
                </a>
            <?php } ?>
        </td>
        <td>
            <label for="bo_skin_<?php echo $i; ?>" class="sound_only"><?php _e('Skin', 'gnupress');?></label>
            <?php echo g5_get_skin_select('board', 'bo_skin_'.$i, "bo_skin[$i]", $row['bo_skin']); ?>
        </td>
        <td>
            <label for="bo_subject_<?php echo $i; ?>" class="sound_only"><?php _e('Board subject', 'gnupress');?><strong class="sound_only"> <?php _e('required', 'gnupress');?></strong></label>
            <input type="text" name="bo_subject[<?php echo $i ?>]" value="<?php echo g5_get_text($row['bo_subject']) ?>" id="bo_subject_<?php echo $i ?>" required class="required frm_input bo_subject full_input" size="10">
        </td>
        <td class="td_numsmall">
            <label for="bo_read_point_<?php echo $i; ?>" class="sound_only"><?php _e('Read Point', 'gnupress');?></label>
            <input type="text" name="bo_read_point[<?php echo $i ?>]" value="<?php echo $row['bo_read_point'] ?>" id="bo_read_point_<?php echo $i; ?>" class="frm_input" size="2">
        </td>
        <td class="td_numsmall">
            <label for="bo_write_point_<?php echo $i; ?>" class="sound_only"><?php _e('Write a Point', 'gnupress');?></label>
            <input type="text" name="bo_write_point[<?php echo $i ?>]" value="<?php echo $row['bo_write_point'] ?>" id="bo_write_point_<?php echo $i; ?>" class="frm_input" size="2">
        </td>
        <td class="td_numsmall">
            <label for="bo_comment_point_<?php echo $i; ?>" class="sound_only"><?php _e('Comments Point', 'gnupress');?></label>
            <input type="text" name="bo_comment_point[<?php echo $i ?>]" value="<?php echo $row['bo_comment_point'] ?>" id="bo_comment_point_<?php echo $i; ?>" class="frm_input" size="2">
        </td>
        <td class="td_numsmall">
            <label for="bo_download_point_<?php echo $i; ?>" class="sound_only"><?php _e('Download Point', 'gnupress');?></label>
            <input type="text" name="bo_download_point[<?php echo $i ?>]" value="<?php echo $row['bo_download_point'] ?>" id="bo_download_point_<?php echo $i; ?>" class="frm_input" size="2">
        </td>

        <td class="td_mngsmall">
            <?php echo $one_update ?>
            <?php echo $one_copy ?>
        </td>
    </tr>
    <?php
    $i++;
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">'.__('Empty data', 'gnupress').'</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="<?php _e('choose-Modify', 'gnupress');?>" onclick="document.pressed=this.value" class="button">
    <?php if ($is_admin == 'super') { ?>
    <input type="submit" name="act_button" value="<?php _e('choose-Delete', 'gnupress');?>" onclick="document.pressed=this.value" class="button">
    <?php } ?>
</div>

</form>

<?php echo g5_get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $paged, $total_page, add_query_arg( array('paged'=>false) ), '', 'paged'); ?>

<script>
function fboardlist_submit(f)
{
    if (!g5_is_checked("chk[]")) {
        alert(document.pressed+" <?php _e('to be select least one item', 'gnupress');?>");
        return false;
    }

    if(document.pressed == "<?php _e('choose-Delete', 'gnupress');?>") {
        if(!confirm("<?php _e('Are you sure you want to delete the selected data?', 'gnupress');?>")) {
            return false;
        }
    }

    return true;
}
jQuery(document).ready(function($) {
    var $c_box = $("#g5_board_copy_el").dialog({
        'dialogClass' : 'wp-dialog',
        'modal' : false,
        'autoOpen' : false,
        'closeOnEscape' : true,
        'minWidth' : 310,
        'width' : 440,
        'buttons' : [
            {
            'text' : 'Close',
            'class' : 'button-primary',
            'click' : function() {
                            $(this).dialog('close');
                        }
            }
        ]
    });
    
    $(".board_copy").on("click", function(e){
        e.preventDefault();
        var title = $(this).attr("title"),
            bo_table = $(this).attr("data-bo_table"),
            bo_subject = $(this).attr("data-bo_subject");

        $("#g5_board_copy_el").find(".bo_table_key").text( bo_table ).end().find("input[name='bo_table']").val( bo_table ).end().find("input[name='target_subject']").val( "[<?php echo _e('carbon copy', 'gnupress');?>] "+bo_subject );
        $("span.ui-dialog-title").text(title);
        ($c_box.dialog("isOpen") == false) ? $c_box.dialog("open") : $c_box.dialog("close");
    });

});
</script>

<div id="g5_board_copy_el" style="display:none">

    <form action="<?php echo g5_form_action_url($form_action_url);?>" onsubmit="return fboardcopy_check(this);" method="post">
    <?php wp_nonce_field( 'bbs-adm-copy' ); ?>
    <input type="hidden" name="bo_table" value="">
    <input type="hidden" name="g5_admin_post" value="bbs_copy" />
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?></caption>
        <tbody>
        <tr>
            <th scope="col"><?php _e('Original table name', 'gnupress');?></th>
            <td><span class="bo_table_key"></span></td>
        </tr>
        <tr>
            <th scope="col"><label for="target_table"><?php _e('Copy the table name', 'gnupress');?><strong class="sound_only"><?php _e('required', 'gnupress');?></strong></label></th>
            <td><input type="text" name="target_table" id="target_table" required class="required alnum_ frm_input" maxlength="20"><br /><?php _e('Allowed characters Alphabetic and Number and underbar( no whitespace )', 'gnupress'); ?></td>
        </tr>
        <tr>
            <th scope="col"><label for="target_subject"><?php _e('Board Subject', 'gnupress');?><strong class="sound_only"><?php _e('required', 'gnupress');?></strong></label></th>
            <td><input type="text" name="target_subject" value="" id="target_subject" required class="required frm_input" maxlength="120"></td>
        </tr>
        <tr>
            <th scope="col"><?php _e('Copy Type', 'gnupress');  //복사유형?></th>
            <td>
                <input type="radio" name="copy_case" value="schema_only" id="copy_case" checked>
                <label for="copy_case"><?php _e('Only Structure', 'gnupress');  //구조만?></label>
                <input type="radio" name="copy_case" value="schema_data_both" id="copy_case2">
                <label for="copy_case2"><?php _e('Structure and Data', 'gnupress');  //구조와 데이터?></label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" class="btn_submit" value="<?php _e('Copy', 'gnupress');?>">
    </div>
    </form>
</div>