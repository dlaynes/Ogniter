<div class="box above-me">
    <div class="box-header well">
        <h3><i class="icon-chevron-right icon-white"></i> {{ $lang->trans('ogniter.og_home_domain_list') }}</h3>
    </div>
    <div class="box-content clearfix center">
        <?php
        $servers_l = $lang->trans('ogniter.og_servers');
        foreach($countries as $dom){ ?>
        <p class="server-{{ $dom->language }}"><a href="<?php echo $dom->language?>" title="<?php echo $dom->num_servers?>
            <?php echo $servers_l?>"><i class="flag flag-<?php echo $dom->flag?>"></i>
                <?php echo $dom->domain?></a></p>
        <?php } ?>
    </div>
</div>