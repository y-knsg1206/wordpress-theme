<?php

/**
 * CSSとJavaScriptの読み込み
 */
function my_script_init()
{
  // Googleフォント読み込み
  wp_enqueue_style('google-fonts-ibm-plex-sans-jp', 'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+JP:wght@100;200;300;400;500;600;700&display=swap', array(), null);
  wp_enqueue_style('google-fonts-lato', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap', array(), null);

  // CSS読み込み
  wp_enqueue_style('my-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_theme_file_path('assets/css/style.css')),  'all');

  // JavaScript読み込み
  wp_enqueue_script('my-script', get_template_directory_uri() . '/assets/js/script.js', array(), filemtime(get_theme_file_path('assets/js/script.js')), true);
}
add_action('wp_enqueue_scripts', 'my_script_init');

/**
 * Googleフォントのpreconnect設定
 */
function add_google_fonts_preconnect() {
  echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'add_google_fonts_preconnect', 1);
/**
 * セキュリティー対策
 */

/**
 * wordpressバージョン情報の削除
 */
  remove_action('wp_head', 'wp_generator');

/**
 * 投稿者一覧ページを自動で生成されないようにする
 */
add_filter('author_rewrite_rules', '__return_empty_array');
function disable_author_archive() {
  if( preg_match( '#/author/.+#', $_SERVER['REQUEST_URI'] ) ){
    wp_redirect( esc_url( home_url( '/404.php' ) ) );
    exit;
  }
}
add_action('init', 'disable_author_archive');

/**
 * /?author=1 などでアクセスしたらリダイレクトさせる
 */
if (!is_admin()) {
  if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
  add_filter('redirect_canonical', 'my_shapespace_check_enum', 10, 2);
}
function my_shapespace_check_enum($redirect, $request)
{
  if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
  else return $redirect;
}

/**
 * pタグとbrタグの自動挿入を解除
 */
add_action('init', 'disable_output');

function disable_output()
{
  remove_filter('the_content', 'wpautop');  // 本文欄
  // remove_filter('the_title', 'wpautop');  // タイトル蘭
  // remove_filter('comment_text', 'wpautop');  // コメント欄
  // remove_filter('the_excerpt', 'wpautop');  // 抜粋欄
}

/*
 * テンプレートパスを返す
 */
function temp_path()
{
  echo esc_url(get_template_directory_uri());
}
/* assetsパスを返す */
function assets_path()
{
  echo esc_url(get_template_directory_uri() . '/assets');
}
/* 画像パスを返す */
function img_path()
{
  echo esc_url(get_template_directory_uri() . '/assets/img');
}
/* mediaフォルダへのURL */
function uploads_path()
{
  echo esc_url(wp_upload_dir()['baseurl']);
}

/*
/* ホームURLのパスを返す
 */
function page_path($page = "")
{
  $page = $page . '/';
  echo esc_url(home_url($page));
}

?>