/**
* Load a State District Map
*/
var StateDistrictMap = function(token, chamber, state, district_number, container)
{
	var plugin = this;
	var $ = jQuery;	

	plugin.token = token;
	plugin.chamber = chamber;
	plugin.state = state;
	plugin.district_number = district_number;
	plugin.container = container;

	plugin.boundary_data = null;

	/**
	* Get the boundary data
	*/
	plugin.getData = function(){
		$.ajax({
			url : state_district_boundaries,
			type : 'POST',
			data : {
				_token : plugin.token,
				chamber : plugin.chamber,
				state : plugin.state,
				district_number : plugin.district_number
			},
			success : function(data){
				if ( data.status === 'success' ){
					plugin.boundary_data = data;
					plugin.loadMap();
					return;
				}
				$(container[0]).hide();
			},
			error : function(data){
				$(container[0]).hide();
			}
		});
	}

	/**
	* Load the Map
	*/
	plugin.loadMap = function()
	{
		var map = new google.maps.Map(plugin.container[0],{
			center: new google.maps.LatLng(plugin.boundary_data.center_lat, plugin.boundary_data.center_lng),
			mapTypeID: google.maps.MapTypeId.ROADMAP,
			zoom: 9,
			mapTypeControl: false,
			scaleControl: true,
			styles : map_styles
		});

		// Format the coordinates into polygon coordinates
		var coordinates = [];
		for ( var i = 0; i < plugin.boundary_data.coordinates.length; i++ ){
			var point = plugin.boundary_data.coordinates[i];
			point = {
				lat: point[1],
				lng: point[0]
			}
			coordinates.push(point);
		}
			
		// create the polygon using the coordinates
		var districtpoly = new google.maps.Polygon({
			paths: coordinates,
			strokeWeight: 0,
			fillColor: map_polygon_fill,
			fillOpacity: '0.8'
		});
		districtpoly.setMap(map);
			
		// Set the zoom to fit the polygon in the map area
		google.maps.Polygon.prototype.getBounds = function() {
			var bounds = new google.maps.LatLngBounds();
			var paths = this.getPaths();
			var path;        
			for (var i = 0; i < paths.getLength(); i++) {
				path = paths.getAt(i);
				for (var ii = 0; ii < path.getLength(); ii++) {
					bounds.extend(path.getAt(ii));
				}
			}
			return bounds;
		}
		map.fitBounds(districtpoly.getBounds()); 
		$(plugin.container[0]).removeClass('loading');
	}

	return plugin.getData();
}