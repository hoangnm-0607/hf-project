<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\VPP\Events\EventInputDto;
use App\Dto\VPP\Events\EventOutputDto;
use App\Dto\VPP\Events\SeriesOutputDto;
use App\Dto\VPP\Events\EventListOutputDto;
use App\Dto\VPP\Events\BookingDto;
use App\Dto\VPP\Events\EventCancelInputDto;
use JetBrains\PhpStorm\Pure;
use Pimcore\Model\DataObject\SingleEvent as DataObjectSingleEvent;
use App\Dto\VPP\Events\EventAddInputDto;
use App\Controller\PatchEventController;


/**
 * @ApiResource(
 *  attributes={},
 *  shortName="Partner Courses",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *      "skip_null_values" = false,
 *  },
 *  formats={"json"},
 *  collectionOperations={
 *     "post" = {
 *          "input"=EventAddInputDto::class,
 *          "output"=SeriesOutputDto::class,
 *          "path"="/partners/{publicId}/courses/{courseId}/events",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "openapi_context"={
 *              "summary"="Creates a collection of events for a given course resource independent from the publish Flag",
 *              "description"="Creates a collection of events for a given course resource independent from the publish Flag",
 *              "parameters"={
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the related course",
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
 *     },
 *     "get_events"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/courses/events",
 *          "output"=EventListOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves the collection of not archived events",
 *              "description"="Retrieves the collection of not archived events",
 *              "parameters"={
 *                 {
 *                     "name": "course_id",
 *                     "type": "integer",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Id of the related course. If not provided all non archived events are returned",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "limit",
 *                     "type": "integer",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Limit the results to the given amount",
 *                     "example": "10",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
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
 *                {
 *                      "name": "order[date]",
 *                      "description": "Order by date",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[course_name]",
 *                      "description": "Order by course name",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[course_type]",
 *                      "description": "Order by course type",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[time]",
 *                      "description": "Order by time",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[published]",
 *                      "description": "Order by published state",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[duration]",
 *                      "description": "Order by duration",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[bookings]",
 *                      "description": "Order by number of bookings",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[stream]",
 *                      "description": "Order by streamlink",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[course_name]",
 *                      "description": "The course name to filter by",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[course_type]",
 *                      "description": "The course type to filter by",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[published]",
 *                      "description": "Event is published",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[not_published]",
 *                      "description": "Event is not published",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[canceled]",
 *                      "description": "Event is canceled",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[date_from]",
 *                      "description": "Event date Y-m-d",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[date_to]",
 *                      "description": "Event date Y-m-d",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[time_from]",
 *                      "description": "Event time H-i",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[time_to]",
 *                      "description": "Event time H-i",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[duration_from]",
 *                      "description": "Event duration",
 *                      "type": "integer",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[duration_to]",
 *                      "description": "Event duration",
 *                      "type": "integer",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[bookings]",
 *                      "description": "Event has bookings",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[no_bookings]",
 *                      "description": "Event has no bookings",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[stream]",
 *                      "description": "Event has a stream link",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[no_stream]",
 *                      "description": "Event has not stream link",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *     "get_events_archive"={
 *          "method"="GET",
 *          "path"="/partners/{publicId}/courses/events-archive",
 *          "output"=EventListOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves the collection of archived events",
 *              "description"="Retrieves the collection of archived events",
 *              "parameters"={
 *                 {
 *                     "name": "course_id",
 *                     "type": "integer",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Id of the related course. If not provided all non archived events are returned",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "limit",
 *                     "type": "integer",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Limit the results to the given amount",
 *                     "example": "10",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
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
 *                {
 *                      "name": "order[date]",
 *                      "description": "Order by date",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[course_name]",
 *                      "description": "Order by course name",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[course_type]",
 *                      "description": "Order by course type",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[time]",
 *                      "description": "Order by time",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[published]",
 *                      "description": "Order by published state",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[duration]",
 *                      "description": "Order by duration",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[bookings]",
 *                      "description": "Order by number of bookings",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "order[stream]",
 *                      "description": "Order by streamlink",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "example": "desc",
 *                      "schema" = {
 *                          "enum": {"asc", "desc"},
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[course_name]",
 *                      "description": "The course name to filter by",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[course_type]",
 *                      "description": "The course type to filter by",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[published]",
 *                      "description": "Event is published",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[not_published]",
 *                      "description": "Event is not published",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[canceled]",
 *                      "description": "Event is canceled",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[date_from]",
 *                      "description": "Event date Y-m-d",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[date_to]",
 *                      "description": "Event date Y-m-d",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[time_from]",
 *                      "description": "Event time H-i",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[time_to]",
 *                      "description": "Event time H-i",
 *                      "type": "string",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[duration_from]",
 *                      "description": "Event duration",
 *                      "type": "integer",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[duration_to]",
 *                      "description": "Event duration",
 *                      "type": "integer",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[bookings]",
 *                      "description": "Event has bookings",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[no_bookings]",
 *                      "description": "Event has no bookings",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[stream]",
 *                      "description": "Event has a stream link",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *                 {
 *                      "name": "filter[no_stream]",
 *                      "description": "Event has not stream link",
 *                      "type": "boolean",
 *                      "in": "query",
 *                      "required": false,
 *                      "schema" = {
 *                          "type"="boolean"
 *                      }
 *                 },
 *              },
 *          },
 *      },
 *     "update"={
 *          "method"="PATCH",
 *          "input"=EventInputDto::class,
 *          "output"=EventOutputDto::class,
 *          "path"="/partners/{publicId}/courses/events",
 *          "controller" = PatchEventController::class,
 *          "requirements" = {
 *              "courseId" = "\d+",
 *          },
 *          "openapi_context"={
 *              "summary"="Updates a collection of events for a given course resource. Each event in the collection requires a valid event-id",
 *              "description"="Updates a collection of events for a given course resource. Each event in the collection requires a valid event-id",
 *               "parameters"={
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
 *  },
 *  itemOperations={
 *     "get_event"={
 *          "method"="GET",
 *          "output"=EventOutputDto::class,
 *          "path"="/partners/{publicId}/courses/{courseId}/events/{eventId}",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *              "eventId" = "\d+",
 *          },
 *          "openapi_context"={
 *              "shortName"="",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "eventId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the event to return",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to delete",
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
 *     "get_bookings_download"={
 *          "method"="GET",
 *          "output"=BookingDto::class,
 *          "formats"={"json", "csv"={"text/csv"}},
 *          "path"="/partners/{publicId}/courses/{courseId}/events/{eventId}/bookings",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *              "eventId" = "\d+",
 *          },
 *          "openapi_context"={
 *              "summary"="Retrieves a collection of bookings for a given event. Canceled bookings are excluded",
 *              "description"="Retrieves a collection of bookings for a given event. Canceled bookings are excluded",
 *              "shortName"="",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "eventId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the event to return",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to delete",
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
 *     "patch_cancel_event"={
 *          "method"="PATCH",
 *          "input"=EventCancelInputDto::class,
 *          "output"=false,
 *          "formats"={"json", "csv"={"text/csv"}},
 *          "path"="/partners/{publicId}/courses/{courseId}/events/{eventId}/cancel",
 *          "requirements" = {
 *              "courseId" = "\d+",
 *              "eventId" = "\d+",
 *          },
 *          "openapi_context"={
 *              "summary"="Canceles the given event with all realted bookings",
 *              "description"="Canceles the given event with all realted bookings",
 *              "shortName"="",
 *              "tags"={"Partner Courses"},
 *              "parameters"={
 *                 {
 *                     "name": "eventId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the event to return",
 *                     "example": "123",
 *                     "schema" = {
 *                          "type"="integer"
 *                      }
 *                 },
 *                 {
 *                     "name": "courseId",
 *                     "type": "integer",
 *                     "in": "path",
 *                     "required": true,
 *                     "description": "Id of the course to delete",
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
 *  }
 * )
 */
final class SingleEvent extends DataObjectSingleEvent
{
    /**
     * @ApiProperty(identifier=true)
     */
    #[Pure] public function getEventId(): ?int
    {
        return $this->getId();
    }
}
