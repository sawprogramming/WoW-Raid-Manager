var gulp       = require('gulp');
var concat     = require('gulp-concat');
var uglify     = require('gulp-uglify');
var ngAnnotate = require('gulp-ng-annotate');
var cleanCSS   = require('gulp-clean-css');
var zip        = require('gulp-zip');
var fs         = require('fs');
var json       = JSON.parse(fs.readFileSync('./package.json'));

// minify angular application into one file
gulp.task('js', function() {
	return gulp.src(['src/scripts/**/app.module.js', 'src/scripts/app/**/*.js', 'src/scripts/**/wrm.js'])
		.pipe(concat('wro.min.js'))
		.pipe(ngAnnotate())
		.pipe(uglify())
		.pipe(gulp.dest('temp/WoWRaidOrganizer/scripts'));
});

// minify css
gulp.task('css', function() {
	return gulp.src(['src/css/*.css'])
		.pipe(concat('wro.min.css'))
		.pipe(cleanCSS())
		.pipe(gulp.dest('temp/WoWRaidOrganizer/css'));
});

// copy the application (minus scripts and css folder)
gulp.task('copy-prod', ['js', 'css'], function() {
	return gulp.src([
		'src/**',
		'!src/css{,/**}',
		'!src/scripts/**/*.js'
	], { dot: true })
	.pipe(gulp.dest('temp/WoWRaidOrganizer'));
});

// copy the application
gulp.task('copy-dev', function() {
	return gulp.src([
		'src/**',
	], { dot: true })
	.pipe(gulp.dest('temp/WoWRaidOrganizer'));
});

// zip to prod
gulp.task('prod', ['copy-prod'], function() {
	return gulp.src('temp/**')
		.pipe(zip('WoWRaidOrganizer-' + json.version + '-prod.zip'))
		.pipe(gulp.dest('dist'));
});

// zip to dev
gulp.task('dev', ['copy-dev'], function() {
	return gulp.src('temp/**')
		.pipe(zip('WoWRaidOrganizer-' + json.version + '-dev.zip'))
		.pipe(gulp.dest('dist'));
});