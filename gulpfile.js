var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var notify = require('gulp-notify');
var minifycss = require('gulp-minify-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

// Style Paths
var scss = [
	'resources/scss/*'
]
var css = 'public/assets/css/';

// JS Paths
var js_source = [
	'resources/js/Globals.js',
	'resources/js/Toggle.js',
	'resources/js/GeoCoder.js',
	'resources/js/StateMap.js',
	'resources/js/FederalHouseDistrictMap.js',
	'resources/js/StateDistrictMap.js',
	'resources/js/Bootstrap.js'
];
var js_compiled = 'public/assets/js/';

/**
* Compile the front-end styles and output
*/
var styles = function(){
	return gulp.src(scss)
		.pipe(sass({sourceComments: 'map', sourceMap: 'sass', style: 'compact'}))
		.pipe(autoprefix('last 5 version'))
		.pipe(minifycss({keepBreaks: false}))
		.pipe(gulp.dest(css))
		.pipe(livereload())
		.pipe(notify('Legislator finder styles compiled & compressed.'));
}

/**
* Concatenate and minify scripts
*/
var scripts = function(){
	return gulp.src(js_source)
		.pipe(concat('scripts.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(js_compiled));
};

/**
* Watch Task
*/
gulp.task('watch', function(){
	livereload.listen();
	gulp.watch(scss, gulp.series(styles));
	gulp.watch(js_source, gulp.series(scripts));
});

/**
* Default
*/
gulp.task('default', gulp.series(styles, scripts, 'watch'));