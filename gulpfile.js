var gulp = require('gulp'),
    del = require('del'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify'),
	minify = require('gulp-minify-css'),
	copy = require('gulp-copy'),
	ngAnnotate = require('gulp-ng-annotate'),
	server = require('karma').Server,
	merge = require('merge-stream'),
	sass = require('gulp-sass'),
	embedTemplaets = require('gulp-angular-embed-templates');

var config = require('./resources/src/config/config.json');

gulp.task('clean', function(){
    for (var i in config.output) {
        console.log("Cleaning " + config.output[i]);
        del(config.output[i] + "/*");
    }
});

//
//
//  CSS
//
//

gulp.task('css-vendor', function() {
    return gulp.src(config.css.vendor).pipe(concat('libs.min.css')).pipe(gulp.dest(config.output.css));
});

gulp.task('css-theme-default', function() {
    return gulp.src(config.css.theme.default).pipe(concat('theme.min.css')).pipe(gulp.dest(config.output.css));
});

gulp.task('css-theme-horizontal', function() {
    return gulp.src(config.css.theme.horizontal).pipe(concat('theme-topnav.min.css')).pipe(gulp.dest(config.output.css));
});

gulp.task('css', function() {
    gulp.start('css-vendor', 'css-theme-default', 'css-theme-horizontal');
});

//
//
//  SASS
//
//

gulp.task('sass', function(){
    gulp.src('resources/src/sass/*')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('app.min.css'))
        .pipe(minify())
        .pipe(gulp.dest('public/assets/css'))
});

//
//
//  JS
//
//

gulp.task('js-vendor', function() {
    return gulp.src(config.js.vendor).pipe(concat('libs.min.js')).pipe(gulp.dest(config.output.js));
});

gulp.task('js-theme-default', function() {
    return gulp.src(config.js.theme.default).pipe(concat('theme.min.js')).pipe(gulp.dest(config.output.js));
});

gulp.task('js-theme-topnav', function() {
    return gulp.src(config.js.theme.horizontal).pipe(concat('theme-topnav.min.js')).pipe(gulp.dest(config.output.js));
});

gulp.task('js', function() {
    gulp.start('js-vendor', 'js-theme-default', 'js-theme-topnav');
});

//
//
//  BUNDLES
//
//

gulp.task('app', function() {
	return gulp.src(config.js.app)
		.pipe(embedTemplaets())
		.pipe(concat('main.min.js'))
		//.pipe(rename({ suffix: '.min' }))
		.pipe(ngAnnotate())
		.pipe(uglify())
		.pipe(gulp.dest(config.output.js));
});

gulp.task('locale', function(){
    return gulp.src(config.locale).pipe(gulp.dest(config.output.js));
});

gulp.task('develop', function(){
    gulp.start('clean', 'js', 'css', 'sass', 'app', 'locale', 'watch');
});

gulp.task('watch', function(){
	gulp.watch('resources/src/sass/*', ['sass']);

	gulp.watch([
        'resources/src/js/app/**/*.js',
        'resources/src/js/app/*.js',
        'resources/src/js/app/**/*.html',
        'resources/src/locales/*'
    ], ['app']);

	gulp.watch('resources/js/tests/**/*.js', ['test']);
});

gulp.task('test', function (done) {
	new server({
		configFile: __dirname + '/karma.conf.js',
		singleRun: true
	}, done).start();
});
