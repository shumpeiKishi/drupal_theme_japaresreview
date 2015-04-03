<?php
/**
 * Add css files.
 */
drupal_add_css('http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,300italic|Yanone+Kaffeesatz:400,700', array('type' => 'external'));
drupal_add_css('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css', array('type' => 'external'));
drupal_add_css('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array('type' => 'external'));

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
        // $comments = $variables['content']['system_main']['nodes'][$nid]['comments']['comments'];
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
 * Implements template_preprocess_node().
 */
// function japaresreview_preprocess_node(&$variables) {
//   // If node page.
//   if ($variables['nid'] === arg(1)) {
//     $variables['content']['comment_stars_avg'] = _japaresreview_get_review_star_avg($variables['nid']);
//   }

//   // If teaser.
//   if ($variables['view_mode'] === 'teaser') {
//     $variables['content']['comment_photo_uri'] = array(
//       'type' => 'markup',
//       '#markup' => _japaresreview_get_comment_image($variables['nid']),
//       );
//   }

//   // If restaurant page.
//   if (($variables['type'] === 'restaurant') && ($variables['page'] == TRUE)) {

//     // Add js for Google map api 
//     if (!isset($variables['field_address'][0])) { return ;}
//     $res_geocode = array(
//       'lat' => $variables['field_address'][0]['latitude'],
//       'lng' => $variables['field_address'][0]['longitude'],
//       );
//     drupal_add_js(array('resGeocode' => $res_geocode), 'setting');
//     drupal_add_js('http://maps.googleapis.com/maps/api/js?key=AIzaSyBMAAIRyYmd0OPAn4-2rRwgABrbpwQ9UdI&sensor=false', 'external');
//     drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/script.js', 'file');
//   }
// }

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
        if (!isset($variables['field_address'][0])) { return ;}
        // Add geocode to Drupal.setting.
        $res_geocode = array(
          'lat' => $variables['field_address'][0]['latitude'],
          'lng' => $variables['field_address'][0]['longitude'],
          );
        drupal_add_js(array('resGeocode' => $res_geocode), 'setting');
        // Googlemap API js file
        drupal_add_js('http://maps.googleapis.com/maps/api/js?key=AIzaSyBMAAIRyYmd0OPAn4-2rRwgABrbpwQ9UdI&sensor=false', 'external');
        drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/script.js', 'file');
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
 * 
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
    } // Case 'views_view_fields__restaurant_thumbs'
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
 * 
 */

function _japaresreview_get_review_star_num ($nid) {
 $node = node_load($nid);
 $comments = comment_node_page_additions($node);
 $star_nums = array();
 foreach ($comments['comments'] as $cid => $comment) {
  if (!is_numeric($cid) || (!isset($comment['field_stars']))) { continue; }
  $star_nums[$cid] = $comment['field_stars']['#items'][0]['value'];
}
return $star_nums;
}

function _japaresreview_get_review_star_avg ($nid) {
  $stars = _japaresreview_get_review_star_num($nid);
  $stars_avg = intval(round(array_sum($stars) / count($stars)));
  return $stars_avg;
} 

/**
 * 
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
