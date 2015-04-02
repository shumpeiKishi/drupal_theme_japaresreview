<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
<header class="header header-main">
  <div class="container-fluid topbar">
    <div class="container">
      <div class="row">
        <div class="col-xs-4">
          <?php print render($page['topbar_first']); ?>
        </div>
        <!-- /.col-xs-4 -->
        <div class="col-xs-4">
          <?php print render($page['topbar_second']); ?>
        </div>
        <!-- /.col-xs-4 -->
        <div class="col-xs-4">
          <?php print render($page['topbar_third']); ?>
        </div>
        <!-- /.col-xs-4 -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.container-fluid .topbar -->
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-xs-3">
         <?php if ($logo): ?>
           <div class="logo">
             <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
            </a>
          </div>
          <!-- /.logo -->
        <?php endif; ?>            
      </div>
      <!-- /.col-xs-3 -->
      <div class="col-xs-5">
        <?php print render($page['header_first']); ?>
      </div>
      <!-- /.col-xs-5 -->
      <div class="col-xs-4">
        <?php print render($page['header_second']); ?>
      </div>
      <!-- /.col-xs-4 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</div>
<!-- /.container-fluid -->

<nav class="navbar navbar-default">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php if ($main_menu): ?>
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'inline', 'clearfix', 'nav', 'navbar-nav')))); ?>
      <?php endif; ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</header>

<div class="container-fluid featured">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <?php print render($page['featured_first']); ?>
      </div>
      <!-- /.col-xs-12 -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</div>
<?php print $messages; ?>

<!-- /.container-fluid featured -->
<div class="container-fluid">
  <div class="container">
    <div class="row">
      <div class="col-xs-2">
        <?php print render($page['sidebar_first']); ?>
      </div>
      <!-- /.col-xs-2 -->
      <div class="col-xs-10">
        <div class="row">
          <div class="col-xs-12">
            <?php print render($page['featured_second']); ?>    
          </div>
          <!-- /.col-xs-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-xs-9">
            <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>
            <?php print render($page['help']); ?>
            <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
            <?php print render($page['content']); ?>
          </div>
          <!-- /.col-xs-8 -->
          <div class="col-xs-3">
            <?php print render($page['sidebar_second']); ?>
          </div>
          <!-- /.col-xs-2 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.col-xs-10 -->

    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->
</div>
<!-- /.container-fluid -->

<footer class="footer main-footer">
  <div class="container-fluid">
    <div class="container">
      <div class="row">
        <div class="col-xs-4">
          <?php print render($page['footer_firstcolumn']); ?>
        </div>
        <!-- /.col-xs-4 -->
        <div class="col-xs-4">
          <?php print render($page['footer_secondcolumn']); ?>
        </div>
        <!-- /.col-xs-4 -->
        <div class="col-xs-4">
          <?php print render($page['footer_thirdcolumn']); ?>
        </div>
        <!-- /.col-xs-4 -->
      </div>
      <div class="col-xs-12">
        <?php print render($page['footer_bottom']); ?>
      </div>
      <!-- /.col-xs-12 -->
      <!-- /.row -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.container-fluid -->
</footer>
<!-- /.footer main-footer -->

</div></div> <!-- /#page, /#page-wrapper -->
