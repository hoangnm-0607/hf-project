<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;

class OpeningHoursDto
{

    public const WEEKDAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function __construct() {
        // patch + serializer workaround
        $this->weekday = 'monday';
        $this->opened = false;
    }

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "type" = "string",
     *              "example" = "monday",
     *              "description" = "Weekday"
     *         }
     *     }
     * )
     */
    public string $weekday;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "type" = "boolean",
     *              "example" = true,
     *              "description" = "Opened this day"
     *         }
     *     }
     * )
     */
    public bool $opened;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Öffnungzeit",
     *               "type" = "array",
     *               "items" = {
     *                   "type" = "object",
     *                   "properties" = {
     *                       "from" = {
     *                           "type" = "string",
     *                           "example" = "08:00",
     *                           "description" = "Opening time from e.g. 08:00"
     *                       },
     *                       "to" = {
     *                           "type" = "string",
     *                           "example" = "11:00",
     *                           "description" = "Opening time from e.g. 11:00"
     *                       }
     *                   }
     *               }
     *         }
     *     }
     * )
     */
    public array $times = [];
}
