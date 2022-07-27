<?php


namespace App\DataProvider\Helper;


use Pimcore\Model\Asset;

class AssetHelper
{
    /**
     * @param Asset|null  $asset
     * @param string|null $thumbnail
     *
     * @return string|null
     */
    public function getAssetUrl(?Asset $asset, ?string $thumbnail = null): ?string
    {
        $path = null;
        if($asset) {
            if($asset instanceof Asset\Image) {
                if(!$thumbnail) {
                    $path =  $asset->getFrontendFullPath();
                } else {
                    $path  = $this->convertToWebPath($asset->getThumbnail($thumbnail)->getPathReference());
                }
            } else if ($asset instanceof Asset\Video) {
                $path = $asset->getThumbnail($thumbnail) ?? $asset->getFullPath();
            }
        }
        return $path;
    }

    /**
     * Extracted from Pimcore\Model\Asset\Thumbnail\ImageThumbnailTrait
     * without the check, if request is a frontend request
     */
    protected function convertToWebPath(array $pathReference): ?string
    {
        $type = $pathReference['type'] ?? null;
        $path = $pathReference['src'] ?? null;

        if ($type === 'data-uri') {
            return $path;
        } elseif ($type === 'deferred') {
            $prefix = \Pimcore::getContainer()->getParameter('pimcore.config')['assets']['frontend_prefixes']['thumbnail_deferred'];
            $path = $prefix . urlencode_ignore_slash($path);
        } elseif ($type === 'thumbnail') {
            $prefix = \Pimcore::getContainer()->getParameter('pimcore.config')['assets']['frontend_prefixes']['thumbnail'];
            $path = $prefix . urlencode_ignore_slash($path);
        } else {
            $path = urlencode_ignore_slash($path);
        }

        return $path;
    }

}
