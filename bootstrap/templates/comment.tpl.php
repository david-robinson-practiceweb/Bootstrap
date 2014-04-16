<div class="comment row<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">
  <div class="col-sm-3 col-md-2 align-r">
    <?php print $picture ?><br>
    <strong><?php print $author; ?></strong><br>
    <small><?php print $date; ?></small>
  </div>
  
  <div class="col-sm-9 col-md-10">
    <h3>
      <?php print $title ?>
      <?php if ($comment->new) print ' <span class="label label-default">' . drupal_ucfirst($new) . '</span>'; ?>
    </h3>
    <div class="content">
    <?php print $content ?>
    <?php if ($signature): ?>
      <div class="clear-block">
        <div>—</div>
        <?php print $signature ?>
      </div>
    <?php endif; ?>
    </div>
  </div>
    
</div>
<?php if ($links): ?>
    <div class="links clearfix"><?php print $links ?></div>
  <?php endif; ?>

<hr> 