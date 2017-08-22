<div class="box">
    <div class="box-header well">
        <h2><i class="icon-chevron-right"></i> <?php echo $lang->trans('ogniter.popular_searches')?></h2>
    </div>
    <div class="box-content clearfix">
        <table class="table table-condensed table-striped">
            <tr><th>Name</th><th>Searches</th>
            <?php foreach($popular as $k){ ?>
            <tr><td><?php echo e(substr($k->text,0,18))?><?php if(strlen($k->text)>18){ echo '...'; }?></td><td><?php echo $k->repeated?></td></tr>
            <?php } ?>
        </table>
    </div>
</div>
