/**
* Load a State Map
*/
var StateMap = function(state, container)
{
	var plugin = this;
	var $ = jQuery;

	plugin.state = state;
	plugin.container = container;

	plugin.centerLat = null;
	plugin.centerLng = null;

	/**
	* Get the Center latitude and longitude for the state
	*/
	plugin.fetchCenter = function()
	{
		$.ajax({
			url : state_centers_json,
			dataType : "json",
			success: function(data){
				for ( var i = 0; i < data.length; i++ ){
					if ( data[i].state !== plugin.state) continue;
					plugin.centerLat = data[i].latitude;
					plugin.centerLng = data[i].longitude;
				}
				plugin.drawStateMap();
			}
		});
	}
		
		
	plugin.drawStateMap = function()
	{
		var state = new google.maps.LatLng(plugin.centerLat, plugin.centerLng);
		map = new google.maps.Map(plugin.container[0], {
		  center: state,
		  zoom: 6,
		  mapTypeId: 'roadmap',
		  styles : map_styles
		});

		layer = new google.maps.FusionTablesLayer({
			styles: [{
				polygonOptions : {
					fillColor: "#1bbc9b",
					strokeColor: "#1bbc9b"
				}
			}],
			suppressInfoWindows: true
		});
		
		// var StateName = "{{ $location->state }}";
		
		layer.setQuery({
			select:'geometry',
			from:"17aT9Ud-YnGiXdXEJUyycH2ocUqreOeKGbzCkUw",
			where:"'id' = '" + plugin.state + "'"
		});
		
		layer.setMap(map);
		$(plugin.container[0]).removeClass('loading');
	}

	return plugin.fetchCenter();
	
}