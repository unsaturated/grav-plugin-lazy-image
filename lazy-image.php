<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use DiDom\Document;

/**
 * Class LazyImagePlugin parses content for images and updates
 * the attributes to support lazy loading. The frontend logic is
 * provided by the https://github.com/tuupola/jquery_lazyload
 * project.
 * 
 * @package Grav\Plugin
 */
class LazyImagePlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        include __DIR__.'/vendor/autoload.php';

        // Enable the main event we are interested in
        $this->enable([
            'onPageContentProcessed' => ['onPageContentProcessed', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
        ]);
    }

    /**
     * Add the lazyload JS and add supporting code to initialize it.
     */
    public function onTwigSiteVariables()
    {
        $assets = $this->grav['assets'];

        $assets->addJs('plugin://lazy-image/vendor/lazyload.min.js');

        // Get the class used to identify lazy-load images
        $class_for_images = $this->grav['config']->get('plugins.lazy-image.lazy_img_class');

        if($this->isValidClass($class_for_images))
        {
            $script  = "// Grav Lazy Image Plugin - START\n";
            $script .= "document.onreadystatechange = function() {\n";
            $script .= "  if(document.readyState == \"complete\") {\n";
            $script .= "    let lazyLoadImages = document.querySelectorAll(\".".$class_for_images."\");\n";
            $script .= "    lazyload(lazyLoadImages);\n";
            $script .= "  }\n";
            $script .= "};\n";
            $script .= "// Grav Lazy Image Plugin - END\n";

            $assets->addInlineJs($script, $group="bottom");
        }
    }

    /**
     * Process the content and let the cache serve it again.
     *
     * @param Event $e
     */
    public function onPageContentProcessed(Event $e)
    {
        $page = $e['page'];
        $content = $this->updateImages($page->content());
        $page->setRawContent($content);
    }

    /**
     * Determines validity of user-specified CSS class.
     * 
     * @param String $c
     */
    protected function isValidClass($c = "") 
    {
        $pattern = '/^[a-zA-Z0-9_\-]+$/';
        return preg_match($pattern, $c);
    }

    protected function updateImages($content)
    {
        if(!is_string($content) || trim($content) === '')
        {
            return;
        }

        $class_for_images = $this->grav['config']->get('plugins.lazy-image.lazy_img_class');

        if($this->isValidClass($class_for_images))
        {
            $document = new Document($content, false, 'UTF-8', 'html');
            if (count($images = $document->find("img.".$class_for_images)) > 0) {
                foreach ($images as $image) {
                    if(!$image->getAttribute("data-src")) {
                        $img_src = $image->getAttribute("src");
                        $img_srset = $image->getAttribute("srcset");
                        $image->setAttribute("data-src", $img_src);
                        $image->setAttribute("data-srcset", $img_srset);
                        $image->removeAttribute("src");
                        $image->removeAttribute("srcset");
                    }
                }
                return $document->html();
            }
        }

        return $content;
    }
}
