<?php
/**
 * Add external css files.
 */
drupal_add_css('http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,300italic|Yanone+Kaffeesatz:400,700', array('type' => 'external'));
drupal_add_css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css', array('type' => 'external'));
drupal_add_css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array('type' => 'external'));


/**
 * Add external js files.
 */
drupal_add_js('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', 'external');


/**
 * Implements hook_theme().
 */
function japaresreview_theme(&$existing, $type, $theme, $path) {
  // Add hook for block_user_login
  $hooks['user_login_block'] = array(
    'template' => 'templates/user_login_block',
    'render element' => 'form',
    );
  return $hooks;
}

/**
 * Implements hook_preprocess_block_user_login().
 */
function japaresreview_preprocess_user_login_block(&$variables) {
  $variables['rendered'] = drupal_render_children($variables['form']);
}


/**
 * Implements hook_page_alter().
 */
function japaresreview_page_alter(&$variables) {
  // If front page.
  if ((arg(0) === 'node') && (!arg(1))) {
    unset($variables['content']['system_main']);
  }
  // Check if it is a node
  if (arg(0) == 'node' && is_numeric(arg(1))) {
    $nid = arg(1);
    $content_type = $variables['content']['system_main']['nodes'][$nid]['#bundle'];
    switch ($content_type) {
      case 'restaurant' : {
        $comments = $variables['content']['system_main']['nodes'][$nid]['comments']['comments'];
        // Copy all main contents to featured_first region.
        $variables['featured_first'] = $variables['content'];
        // Delete all main contents from content region.
        unset($variables['content']['system_main']['nodes'][$nid]);
        // Copy comment contents from featured_first region.
        $variables['content']['system_main']['nodes'][$nid]['comments'] = $variables['featured_first']['system_main']['nodes'][$nid]['comments'];
        // Delete comment contents from featured_first region.
        unset($variables['featured_first']['system_main']['nodes'][$nid]['comments']);

        // Collect comment images
        foreach ($comments as $key => $comment) {
          if (!is_numeric($key)) {
           continue; 
         } elseif (!isset($comment['field_photos'])) {
          continue;
        };
        $variables['featured_first']['system_main']['nodes'][$nid]['comment_images'][] = $comment['field_photos'];
      }
      } // Restaurant node
    }
  }
}

/**
 * Implements hook_views_pre_render(&$view)
 */
function japaresreview_views_pre_render(&$view) {
  $view_name = $view->name;
  switch ($view_name) {
    case 'popular_restaurants' : {
      $results = $view->result;
      // Get review star average for each restaurant.
      $review_star_avg_floats = array();
      foreach ($results as $result_key=>$result) {
        $review_star_nums = _japaresreview_get_review_star_num($result->nid);
        if (!$review_star_nums) { continue; }
        $review_star_avg_floats[$result_key] = array_sum($review_star_nums) / count($review_star_nums);
      }
      // Reorder by star_nums
      arsort($review_star_avg_floats);
      // Loop $results foreach by checking nid.
      $new_results = array();
      foreach ($review_star_avg_floats as $result_key=>$star_avg) {
        // Add average star number to $view->resut object.
        $results[$result_key]->comment_stars_avg = round($star_avg);

        // Prepare results with new order.
        $new_results[] = $results[$result_key];
      }

      // If it is block, only 4 rows is stored.
      if ($view->current_display === 'block') {
        array_splice($new_results, 4);
      }
      // Replace $view->result.
      $view->result = $new_results;

      // Unset $results to save memory
      unset($results);

    } // 'popular_restaurant'.
  }
}

/**
 * Implements hook_preprocess_node().
 */

function japaresreview_preprocess_node(&$variables) {
  $node_type = $variables['type'];
  $view_mode = $variables['view_mode'];

  switch ($node_type) {
    case 'restaurant' : {
      // For any view mode.

      // Add variable['comment_star_avg'] as average of star gained.
      $variables['content']['comment_stars_avg'] = _japaresreview_get_review_star_avg($variables['nid']);

      // For page view
      if ($view_mode === 'full') {
        // Add js for Google map api 
        if (isset($variables['field_address'][0])) { 
          // Add geocode to Drupal.setting.
          $res_geocode = array(
            'lat' => $variables['field_address'][0]['latitude'],
            'lng' => $variables['field_address'][0]['longitude'],
            );
          drupal_add_js(array('resGeocode' => $res_geocode), 'setting');
          // Googlemap API js file
          drupal_add_js('http://maps.googleapis.com/maps/api/js?key=AIzaSyBMAAIRyYmd0OPAn4-2rRwgABrbpwQ9UdI&sensor=false', 'external');
          drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/restaurant-map.js', 'file');
        }

        // Add review star script.
        drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/review-stars.js', 'file');
        drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/switch-main-image.js', 'file');

      }

      // For teaser.
      if ($view_mode === 'teaser') {
        // Add variable['comment_photo_uri'] as thumbnail image uri.
        $variables['content']['comment_photo_uri'] = array(
          'type' => 'markup',
          '#markup' => _japaresreview_get_comment_image($variables['nid']),
          );
      }
    }
  }
}

/**
 * Implements template_preprocess_comment().
 */
function japaresreview_preprocess_comment(&$variables) {
  unset($variables['content']['links']['comment']['#links']['comment-reply']);
}

/**
 * Implements template_preprocess_views_view_fields().
 */
function japaresreview_preprocess_views_view_fields(&$variables) {
  switch($variables['view']->name) {
    case 'restaurant_thumbs' : {
      $variables['row']->comment_photo_uri = _japaresreview_get_comment_image($variables['row']->nid);
      break;
    } // Case: 'views_view_fields__restaurant_thumbs'.
    case 'popular_restaurants' : {
      $variables['row']->comment_photo_uri = _japaresreview_get_comment_image($variables['row']->nid);
      break;
    } // Case: 'popular_restaurants'.
    case 'new_reviews' : {
      $user = user_load($variables['row']->comment_uid);
      if (isset($user->picture->uri)) {
        // Get user picture url for new_review view.
        $user_photo_url = file_create_url($user->picture->uri);
        $variables['row']->comment_user_picture_url = $user_photo_url;
      } else {
        $variables['row']->comment_user_picture_url = 'http://japaresreview.dd:8083/sites/japaresreview.dd/files/logo.png';
      }
      break;
    } // Case: 'new_review'.
  } // switch
}

/**
 * Get first comment image for a restaurant.
 * @param  $nid node_id
 * @return string comment images uri.
 */
function _japaresreview_get_comment_image ($nid) {
  $node = node_load($nid);
  $comments = comment_node_page_additions($node);
  $default_image = 'http://japaresreview.dd:8083/sites/japaresreview.dd/files/logo.png';
  
  if (isset($comments['comments'])) {
    foreach ($comments['comments'] as $cid => $comment) {
      if (isset($comment['field_photos'])) {
        return $comment['field_photos']['#items'][0]['uri'];
      }
    }  
  }
  return $default_image;
}

/**
 * Get an array of review star numbers a restaurant got.
 * @param  $nid Node id of restaurant page.
 * @return  array 
 */

function _japaresreview_get_review_star_num ($nid) {
 $node = node_load($nid);
 $comments = comment_node_page_additions($node);
 $star_nums = array();
 if (!isset($comments['comments'])) { return ;}
 foreach ($comments['comments'] as $cid => $comment) {
  if (!is_numeric($cid) || (!isset($comment['field_stars']))) { continue; }
  $star_nums[$cid] = $comment['field_stars']['#items'][0]['value'];
}
return $star_nums;
}

/**
 * Get average number of stars a restaurant get.
 * @param $nid Node id of restaurant page.
 * @return integer
 */

function _japaresreview_get_review_star_avg ($nid) {
  $stars = _japaresreview_get_review_star_num($nid);
  if(count($stars) == 0) {
    return false;
  }
  $stars_avg = intval(round(array_sum($stars) / count($stars)));
  return $stars_avg;
} 

/**
 * Output the stars to in templates
 * @param   $star_num integier between 1 - 5
 * @return  string HTML output of review stars
 */

function _japaresreview_get_review_stars ($star_num) {
  $output = '';
  for ($i = 0; $i < 5; $i ++) {
    if ($i < $star_num) {
      $star_type = 'fa-star';
    } else {
      $star_type = 'fa-star-o';
    }
    $output .= '<i class="fa ' . $star_type . '"></i>';
  }
  return $output;
}
