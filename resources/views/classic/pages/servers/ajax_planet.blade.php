<div>
    <div style="width: 500px; margin: 0 auto">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-bordered
				table-condensed table-hover text-center">
                    <tr>
                        <th colspan="2"><h4><span class="text-info">
					<?php
                                    if($planet->type==1){
                                        $desc = $lang->trans('ogniter.og_planet');
                                        $label = '<i class="icon-globe"></i>';
                                    } else {
                                        $label = '<i class="icon-adjust"></i>';
                                        $desc = $lang->trans('ogniter.og_moon');
                                    }
                                    echo $desc?>
                                    : <?php echo $planet->name.' '.$label?></span></h4></th>
                    </tr>
                    <tr>
                        <td><?php echo $lang->trans('ogniter.og_location')?></td>
                        <td><?php echo $planet->galaxy.':'.$planet->system.':'.$planet->position?></td>
                    </tr>
                    <?php if($planet->size) { ?>
                    <tr>
                        <td>Size</td>
                        <td><?php echo $planet->size?>km</td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php if(count($changes)) { ?>
            <div class="col-md-6">
                <table class="table table-striped table-bordered
				table-condensed table-hover text-center">
                    <thead>
                    <tr>
                        <th>Change</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($changes as $change){ ?>
                    <tr class="text-left">
                        <td><strong><?php echo $change['change']?></strong></td>
                        <td><?php echo $change['from']?></td>
                        <td><?php echo $change['to']?></td>
                        <td><?php echo $change['date']?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
        <?php if($planet->type==2) { ?>
        <div class="row">
        </div>
        <?php } ?>
    </div>
</div>