/**
 * Setup : Gulp
 *
 * @since 1.0.0
 * @package cartfront
 * -------------------------------------------------
 */

// Project name, used for build zip.
var project 		= 'cartfront';

// Local Development URL for BrowserSync. Change as-needed.
var url 			= 'https://localhost/wordpress';

// Not truly using this yet, more or less playing right now.
// TO-DO: Place in Dev branch.
var bower 			= './bower_components/';

// Files that you want to package into a zip go here.
var build 			= './buildtheme/';
var buildInclude 	= [
	'**/*.php',
	'**/*.html',
	'**/*.css',
	'**/*.js',
	'**/*.svg',
	'**/*.ttf',
	'**/*.otf',
	'**/*.eot',
	'**/*.woff',
	'**/*.woff2',

	// Include specific files and folders
	'screenshot.png',

	// Exclude files and folders
	'!node_modules/**/*',
	'!assets/bower_components/**/*',
	'!sass/*',
	'!waste/*',
	'!vendor/*',
	'!framework/css/public.css.map'
];

// Load plugins
var gulp     		= require('gulp');

// Asynchronous browser loading on .scss file changes.
var browserSync  	= require('browser-sync');
var reload       	= browserSync.reload;

// Autoprefixing magic.
var autoprefixer 	= require('gulp-autoprefixer');
var minifycss    	= require('gulp-uglifycss');
var filter       	= require('gulp-filter');
var uglify       	= require('gulp-uglify');
var imagemin     	= require('gulp-imagemin');
var newer        	= require('gulp-newer');
var rename       	= require('gulp-rename');
var concat       	= require('gulp-concat');
var notify       	= require('gulp-notify');
// Not combining media queries for now. Can be used later down the line!
// var cmq          = require('gulp-combine-media-queries');
var runSequence  	= require('gulp-run-sequence');
var sass         	= require('gulp-sass');
var plugins      	= require('gulp-load-plugins')({ camelize: true });

// Helps with ignoring files and directories in our run tasks.
var ignore       	= require('gulp-ignore');

// Helps with removing files and directories in our run tasks.
var rimraf       	= require('gulp-rimraf');

// Using to zip up our packaged theme into a tasty zip file that can be installed in WordPress!
var zip          	= require('gulp-zip');

// Helps prevent stream crashing on errors.
var plumber      	= require('gulp-plumber');
var cache        	= require('gulp-cache');
var sourcemaps   	= require('gulp-sourcemaps');


/**
 * Browser Sync
 * -------------------------------------------------
 * Asynchronous browser syncing of assets across multiple devices!! Watches for
 * changes to js, image and php files.
 * -------------------------------------------------
 * Although, I think this is redundant, since we have a watch task that does this
 * already.
 */

gulp.task('browser-sync', function() {
	var files = [
		'**/*.php',
		'**/*.{png,jpg,gif}'
	];

	browserSync.init(files, {
		// Read here http://www.browsersync.io/docs/options/
		proxy: url,

		// port: 8080,

		// Tunnel the Browsersync server through a random Public URL
		// tunnel: true,

		// Attempt to use the URL "http://my-private-site.localtunnel.me"
		// tunnel: "ppress",

		// Inject CSS changes
		injectChanges: true
	});
});


/**
 * Styles
 * -------------------------------------------------
 * Looking at src/sass and compiling the files into Expanded format, Autoprefixing
 * and sending the files to the build folder.
 * -------------------------------------------------
 * Sass output styles:
 * @link https://web-design-weekly.com/2014/06/15/different-sass-output-styles/
 */

gulp.task('styles', function() {
 	gulp.src('./sass/**/*.scss')
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass({
			errLogToConsole: true,
	
			//outputStyle: 'compressed',
			outputStyle: 'compact',
			// outputStyle: 'nested',
			// outputStyle: 'expanded',
			precision: 10
		}))
		.pipe(sourcemaps.write({includeContent: false}))
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(autoprefixer('last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
		.pipe(sourcemaps.write('.'))
		.pipe(plumber.stop())
		.pipe(gulp.dest('./framework/css'))
		// Filtering stream to only css files.
		.pipe(filter('framework/css/*.css'))
		// Combines media queries.
		// .pipe(cmq())
		// Inject Styles when style file is created.
		.pipe(reload({stream: true}))
		.pipe(rename({suffix: '.min'}))
		.pipe(minifycss({
			maxLineLen: 80
		}))
		.pipe(gulp.dest('./framework/css/'))
		// Inject Styles when min style file is created.
		.pipe(reload({stream: true}))
		.pipe(notify({ message: 'Styles task complete', onLast: true }))
});


/**
 * Scripts: Vendors
 * -------------------------------------------------
 * Look at src/js and concatenate those files, send them to assets/js where we
 * then minimize the concatenated file.
 */

gulp.task('vendorsJs', function() {
	return 	gulp.src('./framework/js/vendors/*.js')
				.pipe(concat('vendors.js'))
				.pipe(gulp.dest('./framework/js/'))
				.pipe(rename( {
					basename: "vendors",
					suffix: '.min'
				}))
				.pipe(uglify())
				.pipe(gulp.dest('./framework/js/'))
				.pipe(notify({ message: 'Vendor scripts task complete', onLast: true }));
});


/**
 * Scripts: Custom
 * -------------------------------------------------
 * Look at src/js and concatenate those files, send them to assets/js where we
 * then minimize the concatenated file.
 */

gulp.task('scriptsJs', function() {
	return  gulp.src('./framework/js/src/*.js')
				.pipe(concat('public.js'))
				.pipe(gulp.dest('./framework/js/'))
				.pipe(rename( {
					basename: "public",
					suffix: '.min'
				}))
				.pipe(uglify())
				.pipe(gulp.dest('./framework/js/'))
				.pipe(notify({ message: 'Custom scripts task complete', onLast: true }));
});


/**
 * Images
 * -------------------------------------------------
 * Look at src/images, optimize the images and send them to the appropriate place.
 */

gulp.task('images', function() {

// Add the newer pipe to pass through newer images only
return 	gulp.src(['./framework/img/raw/*.{png,jpg,gif}'])
			.pipe(newer('./framework/img/'))
			.pipe(rimraf({ force: true }))
			.pipe(imagemin({ optimizationLevel: 7, progressive: true, interlaced: true }))
			.pipe(gulp.dest('./framework/img/'))
			.pipe( notify( { message: 'Images task complete', onLast: true } ) );
});


/**
 * Clean gulp cache
 * -------------------------------------------------
 */

gulp.task('clear', function() {
	cache.clearAll();
});


/**
 * Clean tasks for zip
 * -------------------------------------------------
 * Being a little overzealous, but we're cleaning out the build folder, 
 * codekit-cache directory and annoying DS_Store files and Also clearing out
 * unoptimized image files in zip as those will have been moved and optimized.
 */

gulp.task('cleanup', function() {
	return 	gulp.src(['.git', '**/.sass-cache','**/.DS_Store'], { read: false }) // much faster
 				.pipe(ignore('node_modules/**'))
		 		.pipe(rimraf({ force: true }))
				.pipe(notify({ message: 'Clean task complete', onLast: true }));
});

gulp.task('cleanupFinal', function() {
 	return 	gulp.src(['.git','**/.sass-cache','**/.DS_Store'], { read: false }) // much faster
		 		.pipe(rimraf({ force: true }))
		 		.pipe(notify({ message: 'Clean task complete', onLast: true }));
});


/**
 * Build task that moves essential theme files for production-ready sites
 * -------------------------------------------------
 * buildFiles copies all the files in buildInclude to build folder - check
 * variable values at the top buildImages copies all the images from img folder
 * in assets while ignoring images inside raw folder if any.
 */

gulp.task('buildFiles', function() {
  	return 	gulp.src(buildInclude)
 		 		.pipe(gulp.dest(build))
 		 		.pipe(notify({ message: 'Copy from buildFiles complete', onLast: true }));
});


/**
 * Images
 * -------------------------------------------------
 * Look at src/images, optimize the images and send them to the appropriate place.
 */

gulp.task('buildImages', function() {
	return 	gulp.src(['framework/img/**/*', '!framework/img/raw/**'])
		 		.pipe(gulp.dest(build+'framework/img/'))
		 		.pipe(plugins.notify({ message: 'Images copied to buildTheme folder', onLast: true }));
});


/**
 * Zipping build directory for distribution
 * -------------------------------------------------
 * Taking the build folder, which has been cleaned, containing optimized files
 * and zipping it up to send out as an installable theme.
 */

gulp.task('buildZip', function () {
 	// return 	gulp.src([build+'/**/', './.jshintrc','./.bowerrc','./.gitignore' ])
 	return 	gulp.src(build + '/**/')
		 		.pipe(zip(project + '.zip'))
		 		.pipe(gulp.dest('./'))
		 		.pipe(notify({ message: 'Zip task complete', onLast: true }));
});


/**
 * Tasks
 * -------------------------------------------------
 * Gulp Default Task
 * -------------------------------------------------
 * Compiles styles, fires-up browser sync, watches js and php files. Note browser
 * sync task watches php files.
 */

// Package Distributable Theme
gulp.task('build', function(cb) {
	runSequence('styles', 'scriptsJs', 'buildFiles', 'buildImages', cb);
});


// Watch Task
gulp.task('default', ['styles', 'scriptsJs', 'images', 'browser-sync'], function() {
	gulp.watch('./framework/img/raw/*.{gif,jpg,}', ['images']);
	gulp.watch('./sass/**/*.scss', ['styles']);
	gulp.watch('./framework/js/*.js', ['scriptsJs', browserSync.reload]);
});