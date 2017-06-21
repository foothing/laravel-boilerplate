var gulp = require('gulp'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	uglify = require('gulp-uglify'),
	copy = require('gulp-copy'),
	ngAnnotate = require('gulp-ng-annotate'),
	server = require('karma').Server,
	merge = require('merge-stream');

gulp.task('css', function() {
	// @TODO concat w/ fix references.
	// https://stackoverflow.com/questions/44635984/concat-and-minify-assets-from-3rd-party-libraries

	var styles = ['node_modules/bootstrap/dist/css/bootstrap.min.css'];

	var css =
		gulp.src(styles)
		.pipe(concat('libs.min.css'))
		.pipe(gulp.dest('public/assets/css'));
	var assets =
		gulp.src(['node_modules/bootstrap/dist/fonts/*'])
		.pipe(gulp.dest('public/assets/fonts'));

	return merge(css, assets);
});

gulp.task('js', function() {
	return gulp.src([
			'node_modules/angular/angular.min.js',
			'node_modules/angular-ui-router/release/angular-ui-router.min.js',
			'node_modules/angular-ui-bootstrap/dist/ui-bootstrap.js',
			'node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js'
		])
		.pipe(concat('libs.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('public/assets/js'));
});

gulp.task('app', function() {
	return gulp.src('public/src/**/*.js')
		.pipe(concat('main.js'))
		.pipe(rename({ suffix: '.min' }))
		.pipe(ngAnnotate())
		.pipe(uglify())
		.pipe(gulp.dest('public/assets/js'));
});

gulp.task('default', function(){
	gulp.start('js', 'app', 'css');
});

gulp.task('watch', function(){
	gulp.watch('public/src/**/*.js', ['app']);

	gulp.watch('public/tests/**/*.js', ['test']);
});

gulp.task('test', function (done) {
	new server({
		configFile: __dirname + '/karma.conf.js',
		singleRun: true
	}, done).start();
});
