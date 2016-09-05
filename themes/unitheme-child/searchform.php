<form action="<?php  echo home_url(); ?>/" method="get">
    <fieldset>
        <input type="text" name="s" id="s" data-placeholder="<?php echo __('Buscar...', TEMPNAME); ?>" value="<?php echo __('Buscar...', TEMPNAME); ?>" />
        <span class="icon-close"></span>
        <input type="submit" id="searchsubmit" value="<?php echo __('Buscar', TEMPNAME); ?>" />
        <span class="icon-search2"></span>
        <input type="hidden" name="post_type" value="product" />
    </fieldset>
</form>