<?php 
/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>
<div class="row">
  <div class="col-xs-12">
    <h2><?php print $fields['title']->content; ?></h2>
    <?php if($row->comment_stars_avg): ?>
      <div class="review-stars">
        <?php print _japaresreview_get_review_stars($row->comment_stars_avg); ?>
      </div>
    <?php endif; ?>
  </div>
  <!-- /.col-xs-12 -->
  <div class="col-xs-12 col-md-4">
    <a href="<?php print url('node/' . $row->nid); ?>">
      <div class="img-thumb res-thumb-container" style="background-image: url('<?php print file_create_url($row->comment_photo_uri); ?>');">
      </div>
    </a>
  </div>
  <!-- /.col-xs-4 -->
  <div class="col-xs-12 col-md-8">
    <p>Budget: <?php print $fields['field_budget']->content; ?></p>
    <p>Phone: <?php print $fields['field_phone']->content; ?></p>
    <div><?php print $fields['field_address']->content; ?></div>
    <div><?php print $fields['body']->content; ?></div>
    
  </div>
  <!-- /.col-xs-8 -->
</div>
  <!-- /.row -->