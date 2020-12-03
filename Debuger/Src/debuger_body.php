
  <link rel="stylesheet" href="<?php echo $deb_path;?>/debuger.css">
  <div class='debuger'>
    <form>
      <input autocomplete="off" name='debuger'>
    </form>
    <div id='debuger_out'>
      <?php include_once __PLUGINPATH__.'Debuger/src/debuger_out.txt';?>
    </div>
  </div>
