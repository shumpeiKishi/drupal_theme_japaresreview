<?php

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup themeable
 */
?>
<?php if ($page): ?>
  <div class="row">
    <div class="col-xs-12 col-md-7">
      <div class="row">
        <div class="col-xs-12">
          <h1><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h1>
          <?php if((isset($content['comment_stars_avg'])) && $content['comment_stars_avg']): ?>
            <div class="review-stars">
              <?php print _japaresreview_get_review_stars($content['comment_stars_avg']); ?>
            </div>
          <?php endif; ?>
        </div>
        <!-- /.col-xs-12 -->
        <div class="col-xs-12 col-md-5">
          <?php
          hide($content['body']);
          hide($content['field_budget']);
          hide($content['field_menu']);
          print render($content['field_phone']);
          print render($content['field_address']);
          ?>
          <?php if($content['field_address']): ?>
            <div id="res-map" class="res-map" style="height: 240px;"></div>
          <?php endif; ?>
        </div>
        <!-- /.col-xs-5 -->
        <div class="col-xs-12 col-md-7">
          <?php print render($content['body']); ?>
          <?php print render($content['field_budget']); ?>
          <?php if (isset($content['field_menu']['#items'])): ?>
            <?php foreach($content['field_menu']['#items'] as $index=>$menus): ?>
              <a href="<?php print file_create_url($menus['uri']) ?>" class="fancybox" rel="res-menus"><?php if ($index === 0) { print 'See menu';} ?></a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <!-- /.col-xs-7 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.col-xs-7 -->
    <div class="col-xs-12 col-md-5">
      <div class="row">
        <div class="col-xs-12 col-md-8">
          <?php if (isset($content['comment_images'][0]['#items'][0]['uri'])): ?>
            <a id="fancybox-comment-image-main" href="<?php print file_create_url($content['comment_images'][0]['#items'][0]['uri']); ?>"><img id="comment-image-main" class="img-responsive comment-image-main" src="<?php print file_create_url($content['comment_images'][0]['#items'][0]['uri']); ?>" alt=""></a>
          <?php endif; ?>

        </div>
        <!-- /.col-xs-8 -->
        <div class="col-xs-12 col-md-4">
          <?php if (isset($content['comment_images']) && $content['comment_images']):  ?>
            <div id="comment-image-thumb-up" class="comment-image-thumb-up">
              <i class="fa fa-caret-up"></i>
            </div>
            <div id="comment-image-thumbs-wrapper" class="comment-image-thumbs-wrapper">
              <div id="comment-image-thumbs" class="comment-image-thumbs">
                <?php foreach ($content['comment_images'] as $images): ?>
                  <?php foreach ($images['#items'] as $image): ?>
                    <div class="res-thumb-container" style="background-image: url('<?php print file_create_url($image['uri']); ?>')"></div>  
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </div>
            </div>
            <div id="comment-image-thumb-down" class="comment-image-thumb-down">
              <i class="fa fa-caret-down"></i>
            </div>            
          <?php endif; ?>
        </div>
        <!-- /.col-xs-4 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.col-xs-5 -->
  </div>
  <!-- /.row -->

<?php elseif ($teaser): ?>
  <div class="row">
    <div class="col-xs-12">
      <h2><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php if($content['comment_stars_avg']): ?>
        <div class="review-stars">
          <?php print _japaresreview_get_review_stars($content['comment_stars_avg']); ?>
        </div>
      <?php endif; ?>
    </div>
    <!-- /.col-xs-12 -->
    <div class="col-xs-12 col-md-4">
      <a href="<?php print $node_url; ?>">
        <div class="img-thumb res-thumb-container" style="background-image: url('<?php print file_create_url($content['comment_photo_uri']['#markup']); ?>');">
        </div>
      </a>
    </div>
    <!-- /.col-xs-4 -->
    <div class="col-xs-12 col-md-8">
      <?php print render($content['field_budget']); ?>
      <?php print render($content['field_phone']); ?>
      <?php print render($content['field_address']); ?>
      <?php print render($content['body']); ?>
    </div>
    <!-- /.col-xs-8 -->
  </div>
  <!-- /.row -->
<?php endif; ?>