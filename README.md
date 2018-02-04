# Lazy Image Plugin

The **Lazy Image** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It defers loading your page's images until they are scrolled into view. This is a quick way to boost your site's performance and improve the user experience. It also works with `srcset` multiple resolution images for lazy loading images on a responsive site.

## Installation

Installing the Lazy Image plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install lazy-image

This will install the Lazy Image plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/lazy-image`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `lazy-image`. You can find these files on [GitHub](https://github.com/unsaturated/grav-plugin-lazy-image) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/lazy-image
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin you should copy the default file `user/plugins/lazy-image/lazy-image.yaml` to `user/config/plugins/lazy-image.yaml` and leave the original unmodifed.

Here is the default configuration and an explanation of available options:

```yaml
# Enables and disables the plugin
enabled: true

# The CSS class that will identify images for lazy loading
lazy_img_class: 'img-fluid'
```

If you're annotating the image links with markdown in your editor, then you need to enable _Markdown extra_ in your system configuration—`system.yaml`.

## Usage

**Identify the images** you'd like to lazy load. The images in the main content of your page are good candidates.

**Add CSS** or re-use a class that's styling your images. For example, your updated markdown might look like this:

```
![](./picture.jpg "A nice picture") {.figure-img .img-fluid}
```

**Update the plugin configuration** so the class is specified but does _not_ have the prefixed period (e.g. `img-fluid` and _not_ `.img-fluid`).

## Credits

 * The frontend logic uses the JavaScript library [jquery_lazyload](https://github.com/tuupola/jquery_lazyload). It uses the [IntersectionObserver](https://developer.mozilla.org/en-US/docs/Web/API/IntersectionObserver) to determine when to load images that are scolling into view. 
 * The [DiDOM](https://github.com/Imangazaliev/DiDOM) parser is used for finding `img` elements in the DOM. When compared to other parsers, it's [quite fast](https://github.com/Imangazaliev/DiDOM/wiki/Comparison-with-other-parsers-(1.0)).
