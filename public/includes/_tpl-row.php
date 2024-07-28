<?php if( isset($row) ): ?>
<tr>
    <td><?= isset($row->city) ? $row->city : '--' ?></td> 
    <td><?= isset($row->date) ? $row->date->format('d.m.Y H:i') : '--' ?></td> 
    <td><?= isset($row->temp) ? ( number_format($row->temp,1,',') . '°' ) : '--' ?></td> 
    <td><?= isset($row->desc) ? $row->desc : '--' ?></td> 
</tr>
<?php endif; ?>