<?php


namespace App\Dto\VPP\Partners;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="Studio"
 * )
 */
final class ServicePackageDto
{

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Service Package name",
     *              "example"="Online-Kurse",
     *              "type"="string",
     *              "enum"={
     *                  "Exklusiv-Kurse",
     *                  "Family & Friends",
     *                  "Fitness (Basic)",
     *                  "Klettern, Tennis, Wellness etc. (Best)",
     *                  "Online-Kurse",
     *                  "Schwimmen (Pro)",
     *                  "Twogether Trainingsprogramm"
     *              }
     *         }
     *     },
     * )
     * @Assert\Choice({
     *     "Exklusiv-Kurse",
     *     "Family & Friends",
     *     "Fitness (Basic)",
     *     "Klettern, Tennis, Wellness etc. (Best)",
     *     "Online-Kurse",
     *     "Schwimmen (Pro)",
     *     "Twogether Trainingsprogramm"
     * })
     */
    public string $servicePackageName;
}
