/**
* Global JS Vars
*/
var map_styles = [
  {
    "stylers": [
      { "visibility": "off" }
    ]
  },{
    "featureType": "landscape",
    "stylers": [
      { "visibility": "on" },
      { "color": "#151543" }
    ]
  },{
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "simplified" },
      { "color": "#1bad90" }
    ]
  },{
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
      { "visibility": "on" },
      { "color": "#ffffff" }
    ]
  },{
    "featureType": "road.highway",
    "elementType": "labels.text.stroke",
    "stylers": [
      { "visibility": "on" },
      { "color": "#151540" }
    ]
  },{
    "featureType": "administrative.province",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "on" },
      { "color": "#232254" }
    ]
  },{
    "featureType": "administrative.locality",
    "elementType": "labels.text.fill",
    "stylers": [
      { "visibility": "on" },
      { "color": "#3696da" }
    ]
  },{
    "featureType": "administrative.locality",
    "elementType": "labels.text.stroke",
    "stylers": [
      { "visibility": "simplified" },
      { "color": "#151543" }
    ]
  },{
    "featureType": "road.arterial",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "simplified" },
      { "color": "#27275e" }
    ]
  },{
    "featureType": "water",
    "stylers": [
      { "visibility": "on" },
      { "color": "#0e0e33" }
    ]
  },{
    "featureType": "administrative.neighborhood",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "on" }
    ]
  }
];

var state_centers_json = '/assets/js/state-centers.json';
var federal_congressional_districts_geojson = '/assets/js/congressional-districts-115.geojson';
var state_to_fips_json = '/assets/js/state-to-fips.json';