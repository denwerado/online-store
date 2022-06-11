//*Установка gulp: npm install gulp --save-dev
const gulp = require('gulp');


//#####Sass - Css#####
//*Работа с Sass  и СSS: npm install sass gulp-sass --save-dev
var sass = require('gulp-sass')(require('sass'));

//*Очищение CSS кода: npm install gulp-clean-css --save-dev
const cleanCSS = require('gulp-clean-css');
//#####-----#####


//#####SVG#####
//*npm install gulp-svgstore --save-dev
const svgstore = require('gulp-svgstore');

//*npm i gulp-svgmin
const svgmin = require('gulp-svgmin');
//#####-----#####


//#####JS#####
//*npm install browserify --save-dev
//http://browserify.org/
const browserify = require('browserify');

//*npm i vinyl-source-stream
//https://www.npmjs.com/package/vinyl-source-stream
const source = require('vinyl-source-stream');

//*npm i vinyl-buffer
//https://www.npmjs.com/package/vinyl-buffer
var buffer = require('vinyl-buffer');

//*npm i gulp-uglify
//https://www.npmjs.com/package/gulp-uglify
var uglify = require('gulp-uglify');
//#####-----#####


//!Преобразование scss
gulp.task('build-scss', ()=>{
    return gulp.src('./assets/_scss/style.sass')
    .pipe(sass().on('error', sass.logError))
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('./assets/css/goodville_css/'));
});

//!Преобразование css
gulp.task('minify-css', ()=>{
    return gulp.src('./assets/plugins/css/*.css')
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('./assets/plugins/css/'));
});

//!Преобразование svg
gulp.task('svgstore', function () {
    return gulp.src('./assets/images/goodville_images/_svg/*.svg')
        .pipe(svgmin())
        .pipe(svgstore({prefix: 'icon-' }))
        .pipe(gulp.dest('./assets/images/goodville_images/'));
});

//!Сборка и перенос js
gulp.task('build-js', ()=>{
    //Взятие файла для сборки
    return browserify('./assets/js/goodville.js',{debug: false})//debug: true Подключение опции ориентировки
      //Перевод документа на старый стандарт 
      /*npm install babelify --save-dev @babel/core @babel/preset-env
      .transform("babelify", {presets: ["@babel/preset-env"], sourceMaps: false})//sourceMaps: true Подключение опции ориентировки*/
      .bundle()
      //Создание файла js        
      .pipe(source('goodville.min.js'))
      .pipe(buffer())
      .pipe(uglify())
      .pipe(gulp.dest("./assets/js/"));
});

//?Билдинг
gulp.task('build', gulp.parallel('build-scss','svgstore','minify-css','build-js'));

gulp.task('watch', ()=>{
    gulp.watch('./assets/_scss/**/*.*', gulp.parallel('build-scss'));
    //gulp.watch('./assets/plugins/css/*.css', gulp.parallel('minify-css'));
    gulp.watch('./assets/images/goodville_images/_svg/*.svg', gulp.parallel('svgstore'));
    gulp.watch('./assets/js/goodville.js', gulp.parallel('build-js'));
});

gulp.task('default', gulp.parallel('watch', 'build'));