<script>
$(function () {

    $('.debuger').on('submit', function (e) {
    
      e.preventDefault();
    
      $.ajax({
        type: 'post',
        url: '<?php echo $deb_path.'debuger_engine.php';?>',
        data: $('form').serialize(),
        success: function () {
            $("#debuger_out").load(location.href + " #debuger_out");
            window.history.back();
        }
      });
    
    });
    
    });
</script>    