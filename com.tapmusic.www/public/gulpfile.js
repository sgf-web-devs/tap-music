var gulp = require('gulp'),
	shell = require('gulp-shell'),
	livereload = require('gulp-livereload'),
	watch = require('gulp-watch'),
	plumber = require('gulp-plumber'),
    browserify = require('browserify'),
    source = require('vinyl-source-stream');

gulp.task('css', function() {
    gulp.src('./sass/main.scss')
    .pipe(shell([
      'sassc <%= file.path %> css/main.css -t nested -m'
    ])).on('error', function(){
        console.log('turn down for what!');
    })
    .pipe(livereload());
});

gulp.task('php-reload', function() {
    gulp.src('**/*.php')
    .pipe(livereload());
});


gulp.task('js', function(){
    //return browserify('./js/index')
    //    .bundle().
    //    on('error', function(){
    //        console.log('js error');
    //        return true;
    //    })
    //    .pipe(source('js/app.js'))
    //    .pipe(gulp.dest('.'));
    gulp.src('./js/index.js')
        .pipe(livereload());
});



// Deault
gulp.task('default', function(){
    gulp.watch('sass/**/*.scss', ['css']);
    gulp.watch('js/**/*.js', ['js']);
    gulp.watch('../app/views/**/*.php').on('change', function(file) {
        gulp.src(file.path)
        .pipe(livereload());
    });
});
