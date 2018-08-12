<?php
/** KIRBY PLUGIN: Sitemap
 * -------------------------------------------------------------------
 * Plugin Name: Sitemap
 * Description: sitemap.xml for Kirby Websites.
 * @version    1.1.2
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
$includeImages          = c::get( 'sitemap.include.images', true );


// SITEMAP.xml
kirby()->routes(
  array(

    array(
      'pattern' => 'sitemap.xml',
      'method'  => 'GET',
      'action'  => function() use ( $ignorePages, $ignoreTemplates, $ignoreInvisible, $importantPages, $importantTemplates, $includeImages ) {

        // get languages
        $languages = site()->languages();

        // xml doctype
        $sitemap  = '<?xml version="1.0" encoding="utf-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" ' . ( r( $includeImages === true, 'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' ) ) . '>';

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
          if ( $languages && $languages->count() > 0 ) :
            foreach( $languages as $language ):
              $sitemap .= '<xhtml:link rel="alternate" hreflang="' . $language->code() . '" href="' . $p->url($language->code()) . '" />';
            endforeach;
          endif;

          // set image tags
          if ( $p->hasImages() && $includeImages === true ) :
            foreach( $p->images()->limit(1000) as $image ):
              $sitemap .= '<image:image>';
                $sitemap .= '<image:loc>' . $image->url() . '</image:loc>';
                $sitemap .= r( $image->image_caption()->isNotEmpty(), '<image:caption>' . $image->image_caption()->xml() . '</image:caption>' );
                $sitemap .= r( $image->image_title()->isNotEmpty(), '<image:title>' . $image->image_title()->xml() . '</image:title>' );
                $sitemap .= r( $image->image_geo_location()->isNotEmpty(), '<image:geo_location>' . $image->image_geo_location()->xml() . '</image:geo_location>' );
                $sitemap .= r( $image->image_licence()->isNotEmpty(), '<image:licence>' . $image->image_licence()->xml() . '</image:licence>' );
              $sitemap .= '</image:image>';
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
