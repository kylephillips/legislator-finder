/**
* Load a Federal House District Map
*/
var FederalHouseDistrictMap = function(district, state, container)
{
	var plugin = this;
	var $ = jQuery;

	plugin.district = district;
	plugin.state = state;
	plugin.container = container;
	plugin.fipsCode = null;

	/**
	* Get the FIPS code for the state
	*/
	plugin.getFipsCode = function()
	{
		$.getJSON(state_to_fips_json, function(data){
			plugin.fipsCode = data[plugin.state];
			plugin.loadGeoJson();
		});
	}
	
	/**
	* Load the Geo JSON, converted from US census data
	* @link https://www.census.gov/geo/maps-data/data/cbf/cbf_cds.html
	* @link http://trac.osgeo.org/gdal/wiki/DownloadingGdalBinaries
	* @link https://medium.com/@govtrack/creating-congressional-district-maps-with-mapbox-4888e4b79cf
	*/
	plugin.loadGeoJson = function()
	{
		$.getJSON(federal_congressional_districts_geojson, function(data){
			var layers = data.features;
			$.each(layers, function(key, val){
				if ( val.properties.STATEFP === plugin.fipsCode && val.properties.CD115FP === plugin.district ) plugin.initializeMap(val);
			});
		});
	}

	/**
	* Initialize the Map
	*/
	plugin.initializeMap = function(json)
	{
		var map;
		map = new google.maps.Map(plugin.container[0], {
			zoom: 4,
			center: {lat: 35.1825687, lng: -100.0268953},
			mapTypeId: 'roadmap',
		  	styles : map_styles
		});
		var geoJson = {
			type: 'FeatureCollection',
			features : [
				json
			]
		}

		map.data.addGeoJson(geoJson);
		map.data.setStyle({
			fillColor: map_polygon_fill,
			strokeWeight: 0
		});

		// Zoom and fit the map to the congressional boundary
		var bounds = new google.maps.LatLngBounds(); 
		map.data.forEach(function(feature){
			feature.getGeometry().forEachLatLng(function(latlng){
				bounds.extend(latlng);
			});
		});
		map.fitBounds(bounds);
		
		$(plugin.container[0]).removeClass('loading');
	}

	return plugin.getFipsCode();
}