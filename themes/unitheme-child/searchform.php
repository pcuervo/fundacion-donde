<form class="[  ]" action="<?php  echo home_url(); ?>/" method="get">
    <fieldset>
        <?php echo do_shortcode( '[aws_search_form]' ); ?>
        <span class="icon-close z-index--999"></span>
        <label class="[ hidden ]" for="searchsubmit">Buscar</label>
        <input type="submit" id="searchsubmit" value="<?php echo __('Buscar', TEMPNAME); ?>" />
        <span class="icon-search2"></span>
        <input type="hidden" name="post_type" value="product" />
    </fieldset>
</form>
