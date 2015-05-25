<?php echo wp_head(); ?>
<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tblUserLoot").DataTable({ "iDisplayLength": 15, "order": [[3, "desc"]] });
        $(".btn-del").button({ icons: { primary: "ui-icon-closethick" }, text: false });
        $(".btn-edit").button({ icons: { primary: "ui-icon-pencil" }, text: false });
	});
</script>

<div id="content" style="width: 940px; margin: 0 auto;" ng-app="WRO">
	<div id="divUserLoot" ng-controller="RaidLootCtrl">
		<h1>Loot Log</h1>
		<?php include(plugin_dir_path( __FILE__ ) . "./views/_Loot.php"); ?>
	</div>
		
<script type="text/javascript">
    function RefreshLootLinks() {
        setTimeout(function () {
            $WowheadPower.refreshLinks();
        }, 25);
    }
</script>
<?php echo wp_footer(); ?>