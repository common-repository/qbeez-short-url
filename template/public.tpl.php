<div style="margin-top:2em;">
  <?php if ($opt['Display'] == 'Y'): ?>
    <?php echo __('QBeez for this page:') ?>
    <a href="<?php echo $shortUrl ?>"><?php echo get_post_meta($post->ID, 'QBeezShortURL', true); ?></a>
  <?php endif ?>
</div>