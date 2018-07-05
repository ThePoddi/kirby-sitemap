<?php
/** KIRBY PLUGIN: Sitemap
 * -------------------------------------------------------------------
 * Plugin Name: Sitemap
 * Description: sitemap.xml for Kirby Websites.
 * @version    1.0.0
 * @author     Patrick Schumacher <hello@thepoddi.com>
 * @link       https://github.com/ThePoddi/kirby-sitemap
 * @license    MIT
 */

// get configs
$ignorePages            = c::get( 'sitemap.ignore.pages', array('error') );
$ignoreTemplates        = c::get( 'sitemap.ignore.templates', array('error') );
$ignoreInvisible        = c::get( 'sitemap.ignore.invisible', true );
$importantPages         = c::get( 'sitemap.important.pages', array('home') );
$importantTemplates     = c::get( 'sitemap.important.templates', array('home') );


// SITEMAP.xml
kirby()->routes(
  array(

    array(
      'pattern' => 'sitemap.xml',
      'method'  => 'GET',
      'action'  => function() use ( $ignorePages, $ignoreTemplates, $ignoreInvisible, $importantPages, $importantTemplates ) {

        // xml doctype
        $sitemap  = '<?xml version="1.0" encoding="utf-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // loop all pages
        foreach( site()->index() as $p ) :

          // ignore pages defined in config
          if( in_array( $p->uri(), $ignorePages ) ) continue;

          // ignore templates defined in config
          if( in_array( $p->intendedTemplate(), $ignoreTemplates ) ) continue;

          // ignore invisible pages
          if( $ignoreInvisible === true && $p->isInvisible() ) continue;

          $sitemap .= '<url>';
          $sitemap .= '<loc>' . $p->url() . '</loc>';

          // set multilanguage canonicals
          if ( site()->languages()->count() > 0 ) :
            foreach( site()->languages() as $language ):
              $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $language->code() . '" href="' . $p->url($language->code()) . '" />';
            endforeach;
          endif;

          $sitemap .= '<lastmod>' . date( 'c', $p->modified() ) . '</lastmod>';
          $sitemap .= '<priority>' . ( ( $p->isHomePage() || in_array( $p->uri(), $importantPages ) || in_array( $p->intendedTemplate(), $importantTemplates ) ) ? 1 : number_format( 0.5/$p->depth(), 1 ) ) . '</priority>';
          $sitemap .= '</url>';

        endforeach;

        $sitemap .= '</urlset>';
        return new Response( $sitemap, 'xml' );
      }
    )
  )
);