<?php
/*
Template Name: Custom Feed for MailChimp
*/

$numposts = 5;

function yoast_rss_date( $timestamp = null ) {
  $timestamp = ($timestamp==null) ? time() : $timestamp;
  echo date(DATE_RSS, $timestamp);
}

function yoast_rss_text_limit($string, $length, $replacer = '...') { 
  $string = strip_tags($string);
  if(strlen($string) > $length) 
    return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;   
  return $string; 
}

$posts = query_posts('showposts='.$numposts);

$lastpost = $numposts - 1;

header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0"?>';
?><rss version="2.0" xmlns:media="http://search.yahoo.com/mrss">
<channel>
  <title>Posts from <?php echo bloginfo('name'); ?></title>
  <link><?php echo bloginfo('url'); ?>/</link>
  <description>The latest blog posts from <?php echo bloginfo('name'); ?>.</description>
  <language>en-us</language>
  <pubDate><?php yoast_rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></pubDate>
  <lastBuildDate><?php yoast_rss_date( strtotime($ps[$lastpost]->post_date_gmt) ); ?></lastBuildDate>
  <managingEditor><?php echo bloginfo('admin_email'); ?></managingEditor>
<?php foreach ($posts as $post) { ?>
  <item>
    <title><?php echo get_the_title($post->ID); ?></title>
    <link><?php echo get_permalink($post->ID); ?></link>
      <?php if(get_the_post_thumbnail()): 
        $thumb_id = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true); 
      ?>
    <media:content url="<?php echo $thumb_url[0]; ?>" medium="image" />
      <?php endif; ?>
    <description><?php echo '<![CDATA['.yoast_rss_text_limit($post->post_content, 500).'<br><br>'.']]>';  ?></description>
    <pubDate><?php yoast_rss_date( strtotime($post->post_date_gmt) ); ?></pubDate>
    <guid><?php echo get_permalink($post->ID); ?></guid>
  </item>
<?php } ?>
</channel>
</rss>