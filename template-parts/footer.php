</div><!-- #main -->
</div><!-- #page -->

	<!-- <footer id="footer">
		<div class="container">
			<ul>
				<li>
					<img src="../images/perkemi.png" alt="" style="width:200px;">
				</li>
			</ul>
		</div>
		<div style="width:100%;text-align:center;color: #333;margin:0auto;">
			<span style="margin:0 auto;text-align:center;vertical-align:middle;"><br> &copy; Copyright - Unsada University</span>
		</div>
		<br>
	</footer> -->
	
	<script>
		// $(function () {
		// 	$('#table').bootstrapTable({
				
		// 	});
		// });

		// (function ($) {
		// 	'use strict';

		// 	var initResizable = function (that) {
		// 		//Deletes the plugin to re-create it
		// 		that.$el.colResizable({disable: true});

		// 		//Creates the plugin
		// 		that.$el.colResizable({
		// 			liveDrag: that.options.liveDrag,
		// 			fixed: that.options.fixed,
		// 			headerOnly: that.options.headerOnly,
		// 			minWidth: that.options.minWidth,
		// 			hoverCursor: that.options.hoverCursor,
		// 			dragCursor: that.options.dragCursor,
		// 			onResize: that.onResize,
		// 			onDrag: that.options.onResizableDrag
		// 		});
		// 	};

		// 	$.extend($.fn.bootstrapTable.defaults, {
		// 		resizable: false,
		// 		liveDrag: false,
		// 		fixed: true,
		// 		headerOnly: false,
		// 		minWidth: 15,
		// 		hoverCursor: 'e-resize',
		// 		dragCursor: 'e-resize',
		// 		onResizableResize: function (e) {
		// 			return false;
		// 		},
		// 		onResizableDrag: function (e) {
		// 			return false;
		// 		}
		// 	});

		// 	var BootstrapTable = $.fn.bootstrapTable.Constructor,
		// 		_toggleView = BootstrapTable.prototype.toggleView,
		// 		_resetView = BootstrapTable.prototype.resetView;

		// 	BootstrapTable.prototype.toggleView = function () {
		// 		_toggleView.apply(this, Array.prototype.slice.apply(arguments));

		// 		if (this.options.resizable && this.options.cardView) {
		// 			//Deletes the plugin
		// 			$(this.$el).colResizable({disable: true});
		// 		}
		// 	};

		// 	BootstrapTable.prototype.resetView = function () {
		// 		var that = this;

		// 		_resetView.apply(this, Array.prototype.slice.apply(arguments));

		// 		if (this.options.resizable) {
		// 			// because in fitHeader function, we use setTimeout(func, 100);
		// 			setTimeout(function () {
		// 				initResizable(that);
		// 			}, 100);
		// 		}
		// 	};

		// 	BootstrapTable.prototype.onResize = function (e) {
		// 		var that = $(e.currentTarget);
		// 		that.bootstrapTable('resetView');
		// 		that.data('bootstrap.table').options.onResizableResize.apply(e);
		// 	}
		// })(jQuery);
	</script>
</body>
</html>
<?php
if(isset($pdo)) {
	// Tutup Koneksi
	$pdo = null;
}
?>