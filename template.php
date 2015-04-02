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
        // Calculate average number of stars for the restaurant.
      foreach ($comments as $key => $comment) {
        if ((!isset($comment['field_stars'])) || (!is_numeric($key))) {
          continue;
        }
        $variables['featured_first']['system_main']['nodes'][$nid]['comment_stars'][] = $comment['field_stars']['#items'][0]['value'];
      }
      } // Restaurant node
    }
  }
}

/**
 * Implements template_preprocess_node().
 */
function japaresreview_preprocess_node(&$variables) {
  if ($variables['nid'] === arg(1)) {
    if (isset($variables['content']['comment_stars'])) {
      $comment_stars = $variables['content']['comment_stars'];
      $variables['content']['comment_stars_avg'] = round(array_sum($comment_stars) / count($comment_stars));  
    }
  }
  if ($variables['view_mode'] === 'teaser') {
    $variables['content']['comment_photo_uri'] = array(
      'type' => 'markup',
      '#markup' => _japaresreview_get_comment_image($variables['nid']),
      );
  }
  if ($variables['type'] === 'restaurant') {
    $res_geocode = array(
      'lat' => $variables['field_address'][0]['latitude'],
      'lng' => $variables['field_address'][0]['longitude'],
      );
    drupal_add_js(array('resGeocode' => $res_geocode), 'setting');
    drupal_add_js('http://maps.googleapis.com/maps/api/js?key=AIzaSyBMAAIRyYmd0OPAn4-2rRwgABrbpwQ9UdI&sensor=false', 'external');
    drupal_add_js(drupal_get_path('theme', 'japaresreview') . '/assets/js/script.js', 'file');
  }
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
  $comments = comment_node_page_additions($node)['comments'];
  $default_image = 'http://japaresreview.dd:8083/sites/japaresreview.dd/files/logo.png';
  foreach ($comments as $cid => $comment) {
    if (isset($comment['field_photos'])) {
      return $comment['field_photos']['#items'][0]['uri'];
    }
  }
  return $default_image;
}

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
