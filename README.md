# Kirby 2 Plugin: Sitemap

sitemap.xml for Kirby 2 websites.

> For **Kirby 3** you can use this SEO Kit: [Kirby 3 SEO Kit](https://github.com/ThePoddi/kirby3-seokit) 


## Installation

### Kirby CLI
`kirby plugin:install thepoddi/kirby-sitemap`

### Git
Include this repository as a submodule `git submodule add https://github.com/thepoddi/kirby-sitemap.git site/plugins/kirby-sitemap` or copy it manually to `/site/plugins/`. *Attention: Plugin directory must named like the plugin file (kirby-sitemap).*


## Usage
This plugin sets a sitemap to `/sitemap.xml` as a kirby route. There is no actual file generated.


## Config

The sitemap can be configured via Kirby’s config file `/site/config/config.php`.

### Ignore Pages
Ignore pages by URI - example: 'blog/my-article'. (array) *Default: error*
```
c::set( 'sitemap.ignore.pages', array('error') );
```

Ignore pages by intended templates. (array) *Default: error*
```
c::set( 'sitemap.ignore.templates', array('error') );
```

Ignore invisible pages. (boolean) *Default: true*
```
c::set( 'sitemap.ignore.invisible', true );
```

### Prioritize Pages
Set high priority pages by uid. (array) *Default: home*
```
c::set( 'sitemap.important.pages', array('home') );
```

Set high priority pages by intended template. (array) *Default: home*
```
c::set( 'sitemap.important.templates', array('home') );
```

### Include Image Sitemap
Include image tags in sitemap [Google Image Sitemaps](https://support.google.com/webmasters/answer/178636). (boolean) *Default: true*
```
c::set( 'sitemap.include.images', true );
```

## Authors

**Patrick Schumacher** - [GitHub](https://github.com/thepoddi) [Website](https://www.thepoddi.com)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
