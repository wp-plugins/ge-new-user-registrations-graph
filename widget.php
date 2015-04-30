<?php

// Just display the graph

$days = self::get_dashboard_widget_option(self::wid, 'daystodisplay',7);

?>

<div id='GESUW' style='width:100%'></div>
<script>
document.writeln("<img src='<?php echo plugins_url("graph.php",__FILE__); ?>?days=<?php echo $days; ?>&w=" + document.getElementById('GESUW').offsetWidth + "'>");
</script>
