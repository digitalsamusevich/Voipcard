<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-center" ><?php echo $column_name_seo_template; ?></td>
        <td class="text-center" ><?php echo $column_description_seo_template; ?></td>
        <td class="text-center"><?php echo $column_action_seo_template; ?></td>
      </tr>
    </thead>
    <tbody>
    <?php if ($seo_title) { ?>
      <?php foreach ($seo_title as $result) { ?>
      <tr id="seo_template<?php echo $result['seo_id']; ?>">
        <td class="text-center"><input type="text" name="name_seo_template" value="<?php echo $result['name_seo_template']; ?>" class="form-control"></td>
        <td class="text-center">
			<?php foreach ($languages as $language) { ?>
				<div class="input-group"><span class="input-group-addon"><?php if($VERSION < 2.2){?><img src="admin/view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><?php } else { ?><img src="admin/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /><?php } ?></span>
					<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($result['data_seo_title'][$language['language_id']]) ? $result['data_seo_title'][$language['language_id']]['meta_title'] : ''; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
				</div>
			<?php } ?>
		</td>
		 <td class="text-center">
			<button type="button" data-toggle="tooltip" onclick="saveSeoTitleNew('<?php echo $result['seo_id'];?>')" title="<?php echo $button_seo_save;?>" class="btn btn-primary-editor"><i class="fa fa-save" aria-hidden="true"></i></button>
			<button type="button" data-toggle="tooltip" onclick="deleteSeoTitle('<?php echo $result['seo_id'];?>')" title="<?php echo $button_seo_delete;?>" class="btn btn-danger-editor"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
			<?php if($result['selected_id'] =='1') { ?>	
				<button type="button" onclick="applySeoTitle('<?php echo $result['seo_id'];?>')" data-toggle="tooltip" title="<?php echo $button_seo_used;?>" class="btn btn-success-editor"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
			<?php } else { ?>
				<button type="button" onclick="applySeoTitle('<?php echo $result['seo_id'];?>')" data-toggle="tooltip" title="<?php echo $button_seo_use;?>" class="btn btn-default-editor"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>
			<?php } ?>
		 </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
        <tr>
          <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>