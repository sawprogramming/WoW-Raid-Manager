<?php echo wp_head(); ?>
<div id="content" style="width: 940px; margin: 0 auto;" ng-app="WRO">
    <div id="divUserAttnd" ng-controller="AttendanceCtrl">
	    <h1>Attendance</h1>
        <?php include(plugin_dir_path( __FILE__ ) . "./views/_AttndBrkdwn.php"); ?>
    </div>
<?php echo wp_footer(); ?>