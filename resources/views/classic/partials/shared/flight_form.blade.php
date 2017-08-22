<form class="form-inline" id="flight_times_form" method="post" action="#">
    <div class="row-fluid">
        <div class="span6">
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th colspan="2"><?php echo $lang->trans('ogniter.start')?> <span id="from"></span></td>
                    <th colspan="2"><?php echo $lang->trans('ogniter.destination')?> <span id="to"></span></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $lang->trans('ogniter.og_galaxy')?></td>
                    <td><input class="input-mini input-sm" type="text" name="from_galaxy" id="from_galaxy" value="<?php echo $from[0]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                    <td><?php echo $lang->trans('ogniter.og_galaxy')?></td>
                    <td><input class="input-mini input-sm" type="text" name="to_galaxy" id="to_galaxy" value="<?php echo $to[0]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                </tr>
                <tr>
                    <td><?php echo $lang->trans('ogniter.og_system')?></td>
                    <td><input class="input-mini input-sm" type="text" name="from_system" id="from_system" value="<?php echo $from[1]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[3]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 3, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                    <td><?php echo $lang->trans('ogniter.og_system')?></td>
                    <td><input class="input-mini input-sm" type="text" name="to_system" id="to_system" value="<?php echo $to[1]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[3]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 3, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                </tr>
                <tr>
                    <td><?php echo $lang->trans('ogniter.og_position')?></td>
                    <td><input class="input-mini input-sm" type="text" name="from_position" id="from_position" value="<?php echo $from[2]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                    <td><?php echo $lang->trans('ogniter.og_position')?></td>
                    <td><input class="input-mini input-sm" type="text" name="to_position" id="to_position" value="<?php echo $to[2]?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                </tr>
                <tr><td colspan="4"></td></tr>
                <tr><td colspan="1"><?php echo $lang->trans('ogniter.begin_hour')?> </td>
                    <td colspan="3">
                        <input type="text" name="start_date" id="start_date" value="" placeholder="yyyy/mm/dd hh:mm:ss"
                               data-validation-engine="validate[required]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-prompt-position="bottomLeft" class="input-sm input-medium"/></td></tr>
                </tbody>
            </table>
        </div>
        <div class="span6">
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th colspan="2"><?php echo $lang->trans('ogniter.motors')?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $lang->trans('ogniter.combustion_drive_tech')?></td>
                    <td><input data-resource-id="115" class="input-mini input-sm" type="text" name="combustion_drive_tech" id="combustion_drive_tech" value=""
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                </tr>
                <tr>
                    <td><?php echo $lang->trans('ogniter.impulse_drive_tech')?></td>
                    <td><input  data-resource-id="116" class="input-mini input-sm" type="text" name="impulse_drive_tech" id="impulse_drive_tech" value=""
                                data-validation-engine="validate[custom[integer],maxSize[2]]"
                                data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                                data-prompt-position="bottomLeft" /></td>

                </tr>
                <tr>
                    <td><?php echo $lang->trans('ogniter.hyperspace_drive_tech')?></td>
                    <td><input data-resource-id="117" class="input-mini input-sm" type="text" name="hyperspacial_drive_tech" id="hyperspacial_drive_tech" value=""
                               data-validation-engine="validate[custom[integer],maxSize[2]]"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" /></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <hr />
    <div class="row-fluid">
        <div class="span6">
            <table class="table table-condensed table-striped" style="font-size: 11px">
                <tr style="font-size: 12px"><th colspan="2"><?php echo $lang->trans('ogniter.fleet_ships')?>: </th><th>Capacity</th></tr>
                <?php
                foreach($fleet as $ship){
                if($pricelist[$ship]['motor'] < 0) continue;
                ?>
                <tr><td><?php echo $lang->trans('ogniter.'.$resource[$ship])?></td>
                    <td><input class="input-mini input-sm resource-fleet"
                               data-resource-id="<?php echo $ship?>"
                               data-capacity="<?php echo $pricelist[$ship]['capacity']?>"
                               data-speed="<?php echo $pricelist[$ship]['speed']?>"
                               data-motor="<?php echo $pricelist[$ship]['motor']?>"
                               data-consumption="<?php echo $pricelist[$ship]['consumption']?>"
                               <?php
                               if(isset($pricelist[$ship]['motor2'])){
                               list($motor2,$level) = each($pricelist[$ship]['motor2']);
                               ?>
                               data-motor2-level="<?php echo $level?>"
                               data-motor2="<?php echo $motor2?>"
                               data-speed2="<?php echo $pricelist[$ship]['speed2']?>"
                               data-consumption2="<?php echo $pricelist[$ship]['consumption2']?>"
                               <?php } ?>
                               <?php
                               if(isset($pricelist[$ship]['motor3'])){
                               list($motor3,$level) = each($pricelist[$ship]['motor3']);
                               ?>
                               data-motor3-level="<?php echo $level?>"
                               data-motor3="<?php echo $motor3?>"
                               data-speed3="<?php echo $pricelist[$ship]['speed3']?>"
                               data-consumption3="<?php echo $pricelist[$ship]['consumption3']?>"
                               <?php } ?>
                               data-validation-engine="validate[custom[integer]]"
                               data-errormessage="<?php echo $lang->trans('ogniter.integer_only')?>"
                               data-prompt-position="bottomLeft"
                               type="text" name="resource_<?php echo $ship?>"
                               id="resource_<?php echo $ship?>" value="" /></td>
                    <td><span id="capacity_<?php echo $ship?>">0</span></td>
                </tr>
                <?php }	?>
                <tr><th><?php echo $lang->trans('ogniter.og_total')?></th><td><span id="total_ships">0</span></td><td><span id="total_capacity">0</span></td></tr>
            </table>
        </div>
        <div class="span6">
            <table class="table table-condensed table-striped">
                <tr>
                    <td><?php echo $lang->trans('ogniter.uni_speed')?></td>
                    <td>
                        <input type="text" class="input-mini input-sm" name="uni_speed" id="uni_speed" value="<?php echo (isset($server['speed_fleet'])?((int) $server['speed_fleet']):'1')?>"
                               data-validation-engine="validate[required,custom[integer],maxSize[2]]"
                               data-errormessage-value-missing="<?php echo $lang->trans('ogniter.required_field')?>"
                               data-errormessage="<?php echo str_replace('%s%', 2, $integer_n_digits)?>"
                               data-prompt-position="bottomLeft" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $lang->trans('ogniter.fleet_speed')?></td>
                    <td>
                        <select id="fleet_speed" class="input-small input-sm">
                            <option value="10">100%</option>
                            <option value="9">90%</option>
                            <option value="8">80%</option>
                            <option value="7">70%</option>
                            <option value="6">60%</option>
                            <option value="5">50%</option>
                            <option value="4">40%</option>
                            <option value="3">30%</option>
                            <option value="2">20%</option>
                            <option value="1">10%</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><a href="javascript:;" id="calc_times" class="btn btn-primary"><?php echo $lang->trans('ogniter.calc_times')?></a></td>
                </tr>
            </table>
            <hr />
            <table class="table table-condensed table-striped">
                <tr><td><?php echo $lang->trans('ogniter.result_time')?>:</td><td><span id="duration_desc" class="text-success">0s</span></td></tr>
                <tr><td>Distance:</td><td><span id="distance_desc">0</span></td></tr>
                <tr><td><?php echo $lang->trans('ogniter.begin_hour')?>:</td><td><span id="start_time_desc" class="text-alert">0s</span></td></tr>
                <tr><td><?php echo $lang->trans('ogniter.arriving_time')?>: &nbsp;</td><td><span id="arriving_time_desc" class="text-alert">0s</span></td></tr>
                <tr><td><?php echo $lang->trans('ogniter.end_hour')?>: &nbsp;</td><td><span id="end_time_desc" class="text-success">0s</span></td></tr>
                <tr><td><?php echo $lang->trans('ogniter.deuterium'),' (',$lang->trans('ogniter.og_total'),')'?>: &nbsp;</td><td><span id="deuterium_usage_desc" class="yellow">0</span></td></tr>
            </table>
        </div>
    </div>
</form>