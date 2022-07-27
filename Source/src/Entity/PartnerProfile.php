<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\VPP\Assets\AssetsVppOutputDto;
use App\Dto\DatedPartnerProfileDto;
use App\Dto\PartnerProfileDto;
use App\Dto\PartnerProfileVppInputDto;
use App\Dto\PartnerProfileVppOutputDto;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Dto\VPP\Assets\AssetDto;
use App\Dto\VPP\Assets\VideoDto;
use App\Dto\VPP\Assets\GalleryUpdateDto;
use App\Dto\VPP\Partners\PartnerNameDto;
use App\Dto\VPP\Partners\ServicePackageDto;
use App\Dto\VPP\Dashboard\DashboardStatsDto;
use Pimcore\Model\DataObject\PartnerProfile as DataObjectPartnerProfile;

/**
 * @ApiResource(
 *  attributes={},
 *  shortName="Partner",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *      "skip_null_values" = false,
 *  },
 *  output=DatedPartnerProfileDto::class,
 *  formats={"json","xml"},
 *  collectionOperations={
 *     "get"={
 *         "openapi_context"={
 *             "parameters"={
 *                 {
 *                     "name": "lastUpdateTimestamp",
 *                     "description": "If a timestamp is provided, the output delivers only the modified objects since ",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "1523131853",
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 }
 *             },
 *             "security": {}
 *         }
 *     },
 *     "get_names"={
 *          "method"="GET",
 *          "output"=PartnerNameDto::class,
 *          "path"="/partners-names",
 *          "openapi_context"={
 *              "summary"="Gets a list of all partner names which belong to the authorized cognito user",
 *              "description"="Gets a list of all partner names which belong to the authorized cognito user",
 *          },
 *     },
 *  },
 *  itemOperations={
 *      "update"={
 *          "method"="PATCH",
 *          "path"="/partners/{publicId}",
 *          "input"=PartnerProfileVppInputDto::class,
 *          "output"=PartnerProfileVppOutputDto::class,
 *      },
 *     "put_service_package"={
 *          "method"="PUT",
 *          "input"=ServicePackageDto::class,
 *          "output"=false,
 *          "path"="/partners/{publicId}/service_packages",
 *          "openapi_context"={
 *              "summary"="Activates the given service package.",
 *              "description"="Activates the given service package.",
 *          },
 *      },
 *     "put_asset_logo"={
 *          "method"="PUT",
 *          "input"=AssetUploadDto::class,
 *          "output"=AssetDto::class,
 *          "path"="/partners/{publicId}/assets/logo",
 *          "openapi_context"={
 *              "summary"="Assigns an uploaded file to pimcore.",
 *              "description"="Assigns an uploaded file to pimcore.",
 *          },
 *      },
 *     "patch_asset_logo"={
 *          "method"="PATCH",
 *          "input"=AssetDto::class,
 *          "output"=AssetsVppOutputDto::class,
 *          "path"="/partners/{publicId}/assets/logo",
 *          "openapi_context"={
 *              "summary"="Updates the logo resource.",
 *              "description"="Updates the logo resource.",
 *          },
 *      },
 *     "patch_asset_studiovideo"={
 *          "method"="PATCH",
 *          "input"=VideoDto::class,
 *          "output"=AssetsVppOutputDto::class,
 *          "path"="/partners/{publicId}/assets/studiovideo",
 *          "openapi_context"={
 *              "summary"="Updates the studio video resource.",
 *              "description"="Updates the studio video resource.",
 *          },
 *      },
 *     "put_asset_studiovideo"={
 *          "method"="PUT",
 *          "input"=AssetUploadDto::class,
 *          "output"=AssetDto::class,
 *          "path"="/partners/{publicId}/assets/studiovideo",
 *          "openapi_context"={
 *              "summary"="Assigns an uploaded file to pimcore.",
 *              "description"="Assigns an uploaded file to pimcore.",
 *          },
 *      },
 *     "put_asset_gallery"={
 *          "method"="PUT",
 *          "input"=AssetUploadDto::class,
 *          "output"=AssetDto::class,
 *          "path"="/partners/{publicId}/assets/gallery",
 *          "openapi_context"={
 *              "summary"="Assigns an uploaded file to pimcore",
 *              "description"="Assigns an uploaded file to pimcore",
 *          },
 *      },
 *     "delete_asset"={
 *          "method"="DELETE",
 *          "path"="/partners/{publicId}/assets/{assetId}",
 *          "output"=AssetsVppOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Deletes the given asset resource.",
 *              "description"="Deletes the given asset resource.",
 *              "parameters"={
 *                 {
 *                     "name": "assetId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the asset to update",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "publicId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Public Id of the related partner",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *
 *     "update_gallery"={
 *          "method"="PATCH",
 *          "input"=GalleryUpdateDto::class,
 *          "output"=AssetsVppOutputDto::class,
 *          "path"="/partners/{publicId}/assets/gallery",
 *          "openapi_context"={
 *              "summary"="Updates the gallery to the given assets.",
 *              "description"="Updates the gallery to the given assets.",
 *          },
 *      },
 *
 *     "get"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}",
 *          "output"=PartnerProfileVppOutputDto::class,
 *      },
 *
 *     "get_details"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/details",
 *          "output"=PartnerProfileDto::class,
 *          "openapi_context"={
 *              "summary"="Get a partner with all public available informations.",
 *              "description"="Get a partner with all public available informations.",
 *               "parameters"={
 *                 {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 }
 *             },
 *              "security": {}
 *          },
 *      },
 *
 *     "get_assets"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/assets",
 *          "output"=AssetsVppOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves all assets of the related partner",
 *              "description"="Retrieves all assets of the related partner",
 *          },
 *      },
 *      "get_dashboard_stats"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/dashboard/stats",
 *          "output"=DashboardStatsDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves dashboard related statistics of the partner",
 *              "description"="Retrieves dashboard related statistics of the partner",
 *               "parameters"={
 *                  {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter, used for search filter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *              },
 *          },
 *      }
 *  }
 * )
 */

final class PartnerProfile extends DataObjectPartnerProfile implements LastUsedAssertIdInterface
{
    use LastUsedAssertIdTrait;

    /**
     * @ApiProperty(identifier=true)
     */
    private ?string $publicId;
}
