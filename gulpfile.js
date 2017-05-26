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
	'resources/assets/scss/*'
]
var css = 'public/assets/css/';

// JS Paths
var js_source = [
	'resources/assets/js/Globals.js',
	'resources/assets/js/Toggle.js',
	'resources/assets/js/LocaleSwitch.js',
	'resources/assets/js/GeoCoder.js',
	'resources/assets/js/StateMap.js',
	'resources/assets/js/FederalHouseDistrictMap.js',
	'resources/assets/js/Bootstrap.js'
];
var js_compiled = 'public/assets/js/';

/**
* Complie the front-end styles and output
*/
gulp.task('sass', function(){
	return gulp.src(scss)
		.pipe(sass({sourceComments: 'map', sourceMap: 'sass', style: 'compact'}))
		.pipe(autoprefix('last 15 version'))
		// .pipe(minifycss({keepBreaks: false}))
		.pipe(gulp.dest(css))
		.pipe(livereload())
		.pipe(notify('Legislator finder styles compiled & compressed.'));
});


/**
* Concatenate and uglify scripts
*/
gulp.task('js', function(){
	return gulp.src(js_source)
		.pipe(concat('scripts.min.js'))
		.pipe(gulp.dest(js_compiled))
		// .pipe(uglify())
		.pipe(gulp.dest(js_compiled))
		.pipe(notify('Legislator finder scripts compiles & compressed.'));
});

/**
* Watch Task
*/
gulp.task('watch', function(){
	livereload.listen();
	gulp.watch(['resources/assets/scss/*']).on('change', livereload.changed);
	gulp.watch(scss, ['sass']);
	gulp.watch(js_source, ['js']);
});

/**
* Default
*/
gulp.task('default', ['sass', 'js', 'watch']);