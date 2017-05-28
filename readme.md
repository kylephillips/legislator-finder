## Legislator Finder

This is a simple web tool that combines data from Google's Civic Information API and Open State's API into a resource for looking up legislators based on a U.S. address. The geocoding and mapping is built using the Google Maps Javascript API, with the geocoding and places libraries enabled.

Congressional district boundaries are sourced from [The United States Census Bureau](https://www.census.gov/geo/maps-data/data/cbf/cbf_cds.html), and converted to geoJSON using the ogr2ogr tool included in the [GDAL command tool library](http://www.gdal.org/ogr2ogr.html). The current congressional district data is for the 115th congress.

The site is built in Laravel.

[Try it out at legislatorfinder.com](https://legislatorfinder.com)