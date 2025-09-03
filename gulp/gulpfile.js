const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sassGlob = require('gulp-sass-glob-use-forward');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const imagemin = require('gulp-imagemin');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const browserSync = require('browser-sync').create();
const pngquant = require('imagemin-pngquant');

function styles() {
  return src('../src/scss/style.scss')
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') }))
    .pipe(sourcemaps.init())
    .pipe(sassGlob())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    // .pipe(cleanCSS())  //cssの圧縮を行う
    .pipe(sourcemaps.write('.'))
    .pipe(dest('../assets/css'))
    .pipe(browserSync.stream());
}

function images() {
  return src('../src/images/**/*')
    .pipe(imagemin([
      imagemin.mozjpeg({ quality: 80 }),
      imagemin.svgo({ plugins: [{ removeViewBox: false }] }),
      pngquant({ quality: [0.65, 0.8] })
    ]))
    .pipe(dest('../assets/images'));
}

function javascript() { return src('../src/js/**/*')
    .pipe(dest('../assets/js'));
}

const browserSyncOption = {
  proxy: "http://XXXXX.local/",// ローカルにある「Site Domain」に合わせる
  // baseDir: '../assets'//gulpfile.jsの格納場所からの相対パスを記述する必要あり
  notify: false,// ブラウザ更新時に出てくる通知を非表示にする
  // open: "external",// ローカルIPアドレスでサーバを立ち上げる
}

function watchFiles() {
  browserSync.init(browserSyncOption);
  // browserSync.init({
  //   // server: {
  //   //   baseDir: '../assets'//gulpfile.jsの格納場所からの相対パスを記述する必要あり
  //   // }
  // });



  watch('../src/scss/**/*.scss', styles);
  watch('../src/images/**/*', images);
  watch('../src/js/**/*.js', javascript);
  watch('../**/*.php').on('change', browserSync.reload);
}

exports.styles = styles;
exports.images = images;
exports.javascript = javascript;
exports.watch = watchFiles;
exports.default = parallel(styles, images, javascript, watchFiles);
