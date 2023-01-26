"use strict";

let path = {
    dist: {
        js: 'dist/js/',
        jsmain: 'dist/js',
        css: 'dist/css/',
        img: 'dist/img/',
        fonts: 'dist/fonts/',
        slick: 'dist/slick/',
        adminCss: 'dist/css/admin/',
        adminJs: 'dist/js/admin/'
    },
    app: {
        js: 'app/js/*.js',
        jsmain: 'app/js/main.js',
        style: 'app/css/style.css',
        css: 'app/css/*.css',
        img: 'app/img/**/*.*',
        fonts: 'app/fonts/**/*.*',
        slick: 'app/slick/**/*.*',
        adminCss: 'app/css/admin/**/*.*',
        adminJs: 'app/js/admin/**/*.*'
    },
    watch: {
        js: 'app/js/*.js',
        jsmain: 'app/js/main.js',
        style: 'app/css/style.css',
        css: 'app/css/*.css',
        img: 'app/img/**/*.*',
        fonts: 'app/fonts/**/*.*',
        slick: 'app/slick/**/*.*',
        adminCss: 'app/css/admin/**/*.*',
        adminJs: 'app/js/admin/**/*.*',
    },
    clean: './dist/'
};

let gulp = require("gulp"),
    plumber = require('gulp-plumber'), // модуль для отслеживания ошибок
    rigger = require('gulp-rigger'), // модуль для импорта содержимого одного файла в другой
    sourcemaps = require('gulp-sourcemaps'), // модуль для генерации карты исходных файлов
    autoprefixer = require('autoprefixer'), // модуль для автоматической установки автопрефиксов
    cleanCSS = require('gulp-clean-css'), // плагин для минимизации CSS
    uglify = require('gulp-uglify'), // модуль для минимизации JavaScript
    cache = require('gulp-cache'), // модуль для кэширования
    imagemin = require('gulp-imagemin'), // плагин для сжатия PNG, JPEG, GIF и SVG изображений
    jpegRecompress = require('imagemin-jpeg-recompress'), // плагин для сжатия jpeg
    pngquant = require('imagemin-pngquant'), // плагин для сжатия png
    del = require('del'), // плагин для удаления файлов и каталогов
    postcss = require('gulp-postcss');

const rollup = require('gulp-better-rollup');
const babel = require('rollup-plugin-babel');
const resolve = require('rollup-plugin-node-resolve');
const commonjs = require('rollup-plugin-commonjs');

// сбор стилей
gulp.task('style:build', function(done) {
    gulp.src(path.app.style) // получим style.css
        .pipe(plumber()) // для отслеживания ошибок
        //.pipe(sourcemaps.init()) // инициализируем sourcemap
        .pipe(postcss([autoprefixer({ overrideBrowserslist: ["last 2 version", "> 2%"] })]))
        .pipe(cleanCSS({
            level: 2
        }, (details) => {
            console.log(`${details.name}: ${details.stats.originalSize}`);
            console.log(`${details.name}: ${details.stats.minifiedSize}`);
        })) // минимизируем CSS
        //.pipe(sourcemaps.write('./')) // записываем sourcemap
        .pipe(gulp.dest(path.dist.css)); // выгружаем в dist
    done();
});

gulp.task('css:build', function(done) {
    gulp.src([path.app.css, '!app/css/style.css'])
        .pipe(gulp.dest(path.dist.css)); // Переносим скрипты в продакшен
    done();
});

// сбор js
gulp.task('jsmain:build', function(done) {
    gulp.src(path.app.jsmain)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(rollup({ plugins: [babel(), resolve(), commonjs()] }, 'umd'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(path.dist.jsmain));
    done();
});

gulp.task('js:build', function(done) {
    gulp.src([path.app.js, '!app/js/main.js'])
        .pipe(gulp.dest(path.dist.js)); // Переносим скрипты в продакшен
    done();
});

// перенос шрифтов
gulp.task('fonts:build', function(done) {
    gulp.src(path.app.fonts)
        .pipe(gulp.dest(path.dist.fonts));
    done();
});

// перенос slick
gulp.task('slick:build', function(done) {
    gulp.src(path.app.slick)
        .pipe(gulp.dest(path.dist.slick));
    done();
});

// перенос plugins
gulp.task('plugins:build', function(done) {
    gulp.src(path.app.adminCss)
        .pipe(gulp.dest(path.dist.adminCss));
    gulp.src(path.app.adminJs)
        .pipe(gulp.dest(path.dist.adminJs));
    done();
});

// обработка картинок
gulp.task('image:build', function(done) {
    gulp.src(path.app.img) // путь с исходниками картинок
        // .pipe(cache(imagemin([ // сжатие изображений
        //     imagemin.gifsicle({ interlaced: true }),
        //     jpegRecompress({
        //         progressive: true,
        //         max: 90,
        //         min: 80
        //     }),
        //     pngquant(),
        //     imagemin.svgo({ plugins: [{ removeViewBox: false }] })
        // ])))
        .pipe(gulp.dest(path.dist.img)); // выгрузка готовых файлов
    done();
});

// удаление каталога dist
gulp.task('clean:build', function(done) {
    del.sync(path.clean);
    done();
});

// очистка кэша
gulp.task('cache:clear', function(done) {
    cache.clearAll();
    done();
});

// сборка
gulp.task('build', gulp.series('clean:build', 'style:build', 'css:build', 'js:build', 'jsmain:build', 'fonts:build', 'slick:build', 'plugins:build', 'image:build', function(done) {
    done();
}));

gulp.task('demo', gulp.series('style:build', 'css:build', 'js:build', 'jsmain:build', function(done) {
    done();
}));

// запуск задач при изменении файлов

gulp.task('watch', function() {
    gulp.watch(path.watch.css, gulp.series('css:build'));
    gulp.watch(path.watch.style, gulp.series('style:build'));
    gulp.watch(path.watch.js, gulp.series('js:build'));
    gulp.watch(path.watch.img, gulp.series('image:build'));
    gulp.watch(path.watch.fonts, gulp.series('fonts:build'));
    gulp.watch(path.watch.slick, gulp.series('slick:build'));
    gulp.watch(path.watch.jsmain, gulp.series('jsmain:build'));
});

// задача по умолчанию
gulp.task('default', gulp.series('clean:build', 'build', gulp.parallel('watch')));
