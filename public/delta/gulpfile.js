var gulp = require('gulp');
var gulpLoadPlugins = require('gulp-load-plugins');
var $ = gulpLoadPlugins();
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var changed = require('gulp-changed');
var del = require('del');
var reload = browserSync.reload;
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var cache = require('gulp-cached');
var gulpif = require('gulp-if');
var useref = require('gulp-useref');
var htmlmin = require('gulp-htmlmin');
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var gulpCopy = require('gulp-copy');
var imagemin = require('gulp-imagemin');
var runSequence = require('run-sequence')

gulp.task('serve', ['clean', 'sass'], function() {

    browserSync.init({
        server: "./"
    });

    gulp.watch("./styles/**/*.scss", ['sass']);
    gulp.watch("./**/*.html").on('change', reload);
    //gulp.watch("./css/**/*.css").on('change', reload);
});

gulp.task('sass', function() {
    return gulp.src('styles/**/*.scss')
		.pipe(cache('styles'))
        .pipe(changed('css/'))
        .pipe($.plumber())
        .pipe($.sass.sync({
            outputStyle: 'compressed', //expanded || compressed
            precision: 10,
            includePaths: ['styles']
        }).on('error', $.sass.logError))
        .pipe(postcss([autoprefixer({
            //browsers: ['last 2 versions']
        })]))
        .pipe(gulp.dest('css/'))
        .pipe(reload({
            stream: true
        }));
});

gulp.task('clean', del.bind(null, ['.tmp', 'dist']));

gulp.task('default', ['clean', 'serve']);

gulp.task('useref', ['useref-index', 'useref-all']);

gulp.task('useref-index', function() {
    return gulp.src(['index.html'])
        .pipe(useref({
            searchPath: './'
        }))
        .pipe(gulpif('*.html', htmlmin({
            collapseWhitespace: true
        })))
        //.pipe(gulpif('*.css', cleanCSS()))
        .pipe(gulp.dest('dist/'));
});

gulp.task('useref-all', function() {
    return gulp.src(['**/*.html', '!index.html', '!node_modules/**/*.html'])
        .pipe(useref({
            searchPath: './',
						noAssets: true
        }))
        .pipe(gulpif('*.html', htmlmin({
            collapseWhitespace: true
        })))
        //.pipe(gulpif('*.css', cleanCSS()))
        .pipe(gulp.dest('dist/'));
});

gulp.task('minify', ['minify-js', 'minify-css']);

gulp.task('minify-js', function() {
    return gulp.src('dist/js/*.js')
        .pipe(uglify({
					mangle: false
				}))
        .pipe(gulp.dest('dist/js/'));
});

gulp.task('minify-css', function() {
    return gulp.src('dist/css/vendor.css')
        .pipe(cleanCSS())
        .pipe(gulp.dest('dist/css/'));
});

//gulp.task('copy-1', function() {
//    gulp.src(['bower_components/**/*'])
//        .pipe(gulp.dest('dist/bower_components/'));
//});
//gulp.task('copy-2', function() {
//    gulp.src(['components/**/*'])
//        .pipe(gulp.dest('dist/components/'));
//});
//gulp.task('copy-6', function() {
//    gulp.src(['bower_components/flag-icon-css/flags'])
//        .pipe(gulp.dest('dist/flags'));
//});

gulp.task('copy', ['copy-1', 'copy-2', 'copy-3']);

gulp.task('copy-1', function() {
    gulp.src(['assets/**/*'])
        .pipe(gulp.dest('dist/assets/'));
});
gulp.task('copy-2', function() {
    gulp.src(['fonts/**/*'])
        .pipe(gulp.dest('dist/fonts/'));
});
gulp.task('copy-3', function() {
    gulp.src(['favicon.ico'])
        .pipe(gulp.dest('dist/'));
});

gulp.task('imagemin', function() {
    gulp.src(['dist/assets/**/*'])
        .pipe(imagemin())
        .pipe(gulp.dest('dist/assets/'));
});

gulp.task('build', function() {
    runSequence('clean', 'sass', 'useref-index', 'useref-all', 'minify-js', 'minify-css', 'copy-1', 'copy-2', 'copy-3');
});

