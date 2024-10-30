<?php
$location = $options_page; // Form Action URI

//Default values form
$data = array(
				'id' => '0',
				'user'=>'', 
				'columns'=>'3', 
				'featured'=>'',
				'imgfeatured'=>'',
				'bypage'=>'12'
			);
			
if (!get_option('options_mlInt')) {
	add_option('options_mlInt',array());
}				
$options_mlInt = get_option('options_mlInt');

?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>	
  <h2><?php _e('MercadoLibre Integration Options', 'mercadolibre_integration') ?></h2>
<?php						

require_once('mercadolibre-integration_options_table.php');

function tt_render_list(){
global $options_mlInt;
	tt_render_list_page($options_mlInt);
}

if ('edit' == $_GET['action']) {
	for ($i=0; $i < count($options_mlInt); $i++) {

			if ($_POST['stage'] ==  'process')
			{				
				if ($options_mlInt[$i]['id'] == $_POST['mli_id'])
				{			
					$options_mlInt[$i]['user'] = $_POST['mli_user'];
					$options_mlInt[$i]['columns'] = $_POST['mli_columns'];
					$options_mlInt[$i]['featured'] = $_POST['mli_featured'];
					$options_mlInt[$i]['imgfeatured'] = $_POST['mli_imgfeatured'];
					$options_mlInt[$i]['bypage'] = intval($_POST['mli_bypage']);					
					update_option('options_mlInt', $options_mlInt);	
					tt_render_list();
					break;
				}
			}
			else
			{
				if ($options_mlInt[$i]['id'] == $_GET['id']){
					tt_render_form($options_mlInt[$i]);
					break;
					}
			}			
			
		}
}
if ('delete' == $_GET['action']) {
	for ($i=0; $i < count($options_mlInt); $i++) {
		if ($options_mlInt[$i]['id'] == $_GET['id']){
			
			unset($options_mlInt[$i]);
			$options_mlInt = array_values($options_mlInt);
			update_option('options_mlInt', $options_mlInt);
			tt_render_list();

		}		
	}
}
if ('new' == $_GET['action']) {
		if ($_POST['stage'] ==  'process')
		{				
			$arr = array(
					'id' => $_POST['mli_id'],
					'user'=>$_POST['mli_user'], 
					'columns'=>$_POST['mli_columns'], 
					'featured'=>$_POST['mli_featured'],
					'imgfeatured'=>$_POST['mli_imgfeatured'],
					'bypage'=>intval($_POST['mli_bypage'])
				);				
			array_push($options_mlInt, $arr);			
			update_option('options_mlInt', $options_mlInt);	
			tt_render_list();
		}
		else
		{
			tt_render_form($data);
		}
}



if (!isset($_GET["action"])) 
{
	tt_render_list();
}
?>	

<?php
function tt_render_form($data){
global $location;
?>
  <form name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>&amp;updated=true">
	<input type="hidden" name="stage" value="process" />	
    <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
        <tr valign="baseline">
        <th scope="row"><?php _e('Name', 'mercadolibre_integration') ?></th> 
        <td>
		<?php 
		if ($data['id'] != '0') {
			echo("\n<input type=\"text\" name=\"mli_id\" readonly value=\"".$data['id']."\" />\n");
		} else { 
			echo("\n<input type=\"text\" name=\"mli_id\" value=\"\" />\n");
		}
		?>
        </td>
        </tr>
        <tr valign="baseline">
        <th scope="row"><?php _e('User', 'mercadolibre_integration') ?></th> 
        <td>
        <?php
			echo("\n<input type=\"text\" name=\"mli_user\" value=\"".$data['user']."\" />\n");
        ?>
        </td>
        </tr>
        <tr valign="baseline">
        <th scope="row"><?php _e('Columns', 'mercadolibre_integration') ?></th> 
        <td>
        <?php
			echo("\n<select name=\"mli_columns\">\n");
			for ($i=1; $i<= 5; $i++)
			{
				echo("\n<option value=".$i);
				if ($i == $data['columns'] )
					echo(" selected=\"selected\"");
				echo(">".$i."</option>\n");
			}
			echo("\n</select>\n");
		?>	
        </td>
        </tr>
        <tr valign="baseline">
        <th scope="row"><?php _e('Articles per page', 'mercadolibre_integration') ?></th> 
        <td>
        <?php
			echo("\n<input type=\"text\" size=\"2\" name=\"mli_bypage\" value=\"".$data['bypage']."\" />\n");
        ?>
        </td>
        </tr>		
        <tr valign="baseline">
        <th scope="row"><?php _e('Featured articles', 'mercadolibre_integration') ?></th> 
        <td>
        <?php
			echo("\n<input type=\"text\" name=\"mli_featured\" style=\"width:80%;\" value=\"".$data['featured']."\" />\n");
        ?>
		<p><small><?php _e('Note: Publication numbers of the items must be separated by commas. (Eg 4587894.4545157)', 'mercadolibre_integration') ?></small></p>
        </td>
        </tr>
        <tr valign="baseline">
        <th scope="row"><?php _e('Image of featured articles', 'mercadolibre_integration') ?></th> 
        <td>
        <?php
			echo("\n<input type=\"text\" name=\"mli_imgfeatured\" style=\"width:60%;\" value=\"".$data['imgfeatured']."\" />\n");
        ?>
		<p><small><?php _e('Note: Path of the image that is displayed with the featured articles', 'mercadolibre_integration') ?></small></p>
        </td>
        </tr>		
     </table>

    <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'mercadolibre_integration') ?> &#187;" />
	  <input type="button" name="Submit" class="button-secondary" value="<?php _e('Cancel', 'mercadolibre_integration') ?>" onclick="location.href='<?php echo $location ?>'" />
    </p>
  </form>
<?php
}
?>
</div>	