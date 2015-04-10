<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
?>
<div class="row">
  <div class="col-xs-2">
    <img class="user-picture img-responsive" src="<?php print $row->comment_user_picture_url; ?>" alt="User Profile Image">
  </div>
  <div class="col-xs-10">
    <h4 class="title title-new-review">
      <?php print $row->comment_subject; ?>
    </h4>
    <div class="review-stars">
      <?php print _japaresreview_get_review_stars($row->field_field_stars[0]['raw']['value']); ?>
    </div>
    <div class="res-review-body">
      <?php print $row->field_comment_body[0]['rendered']['#markup']; ?>
    </div>
    <?php if(isset($row->field_field_photos)): ?>
      <div class="res-review-photos field-name-field-photos">
        <?php foreach ($row->field_field_photos as $index=>$photos): ?>
          <div class="field-item">
            <a href="<?php print file_create_url($photos['rendered']['#item']['uri']) ?>">
             <img class="review-photo review-photo-new-reviews" src="<?php print file_create_url($photos['rendered']['#item']['uri']) ?>" alt="">   
           </a>
           
         </div>
       <?php endforeach; ?>
     <?php endif; ?>
     <div class="submitted">
       Submitted by <?php print $row->comment_name; ?> on <?php print format_date($row->comment_created); ?> for <?php print $row->node_comment_title; ?>.
     </div>
   </div>
 </div>
</div>